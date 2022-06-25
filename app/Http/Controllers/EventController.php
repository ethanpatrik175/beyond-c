<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Speaker;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class EventController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->section = "Events";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::whereNull('deleted_at')->with(['addedBy', 'updatedBy'])->get();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="checkbox" name="checkbox[]" value="' . $row->id . '">';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('events.show', $row->id) . '" target="_blank" class="text-success"><i title="Show" class="fas fa-eye font-size-18"></i></a>&nbsp;&nbsp;';
                    $btn .= '<a href="' . route('events.edit', $row->id) . '" target="_blank" ><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" data-toggle="tooltip" onClick="deleteRecord(' . $row->id . ')" data-original-title="Delete" class="text-danger"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at)) . '<br /><label class="text-primary">By: ' . $row->addedBy->first_name . ' ' . $row->addedBy->last_name . '</label>';
                })
                ->addColumn('updated_at', function ($row) {
                    if (isset($row->updatedBy)) {
                        return date('d-m-Y', strtotime($row->updated_at)) . '<br /><label class="text-primary">By: ' . $row->updatedBy->first_name . ' ' . $row->updatedBy->last_name . '</label>';
                    }
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_active == 0) {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } else if ($row->is_active == 1) {
                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('start_at', function ($row) {
                    return date('d-m-Y H:i A', strtotime($row->start_at));
                })
                ->addColumn('titles', function ($row) {
                    return $row->title . '<br /><label class="text-primary">' . $row->subtitle . '</label>';
                })
                ->addColumn('image', function ($row) {
                    return '<img src="' . asset('assets/frontend/images/events/' . Str::of($row->image)->replace(' ', '%20')) . '" width="50" height="50" />';
                })
                ->addColumn('ticket_price', function ($row) {
                    $ticket = json_decode($row->metadata, true);
                    $price = '<label class="text-primary">Orig. Price: $' . @$ticket['orig_price'] . '</label><br />';
                    $price .= '<label class="text-primary">Price: $' . @$ticket['price'] . '</label>';
                    return $price;
                })
                ->rawColumns(['action', 'created_at', 'updated_at', 'checkbox', 'image', 'status', 'titles', 'ticket_price'])
                ->make(true);
        }

        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "All " . $this->section;

        return view('backend.events.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['speakers'] = Speaker::whereNull('deleted_at')->where('is_active', 1)->get();
        $data['venues'] = Venue::whereNull('deleted_at')->where('is_active', 1)->get();
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "Add New " . $this->section;

        return view('backend.events.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'sub_title' => 'nullable|string|max:150',
            'slug' => 'required|string|max:254|unique:events,slug',
            'summary' => 'required|string|max:254',
            'image' => 'required|image|max:1024',
            'speaker_id' => 'required|exists:speakers,id',
            'venue_id' => 'required|exists:venues,id',
            'day_number' => 'required|integer',
            'start_at' => 'required',
            'orig_price' => 'required',
            'price' => 'required',
        ]);

        $obj = new Event();
        $obj->added_by = Auth::user()->id;
        $obj->speaker_id = $request->speaker_id;
        $obj->venue_id = $request->venue_id;
        $obj->day_number = $request->day_number;
        $obj->start_at = $request->start_at;
        $obj->title = $request->title;
        $obj->sub_title = $request->sub_title;
        $obj->slug = $request->slug;
        $obj->summary = json_encode($request->summary);
        $obj->description = json_encode($request->description);

        $metadata = [
            'orig_price' => $request->orig_price,
            'price' => $request->price,
        ];

        $obj->metadata = json_encode($metadata);

        $model = "Event";
        $image = $request->file('image');
        if ($image->move('assets/frontend/images/events/', $image->getClientOriginalName())) {

            $obj->image = $image->getClientOriginalName();
            if ($obj->save()) {
                $data['type'] = "success";
                $data['message'] = $model . " Added Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('events.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add " . $model . ", please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('events.create')->withInput()->with($data);
            }
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to upload image, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('events.create')->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['event'] = Event::findOrFail($id);
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "View ".Str::singular($this->section).' Detail';

        return view('backend.events.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        $data['event'] = $event;
        $data['speakers'] = Speaker::whereNull('deleted_at')->where('is_active', 1)->get();
        $data['venues'] = Venue::whereNull('deleted_at')->where('is_active', 1)->get();
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "Edit " . $this->section;

        return view('backend.events.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        $obj = Event::findOrFail($event->id);

        $request->validate([
            'title' => 'required|string|max:150',
            'sub_title' => 'nullable|string|max:150',
            'slug' => 'required|string|max:254|unique:events,slug,' . $event->id,
            'summary' => 'required|string|max:254',
            'image' => 'nullable|image|max:1024',
            'speaker_id' => 'required|exists:speakers,id',
            'venue_id' => 'required|exists:venues,id',
            'day_number' => 'required|integer',
            'start_at' => 'required',
            'orig_price' => 'required',
            'price' => 'required',
        ]);

        $obj->updated_by = Auth::user()->id;
        $obj->speaker_id = $request->speaker_id;
        $obj->venue_id = $request->venue_id;
        $obj->day_number = $request->day_number;
        $obj->start_at = $request->start_at;
        $obj->title = $request->title;
        $obj->sub_title = $request->sub_title;
        $obj->slug = $request->slug;
        $obj->summary = json_encode($request->summary);
        $obj->description = json_encode($request->description);
        $metadata = [
            'orig_price' => $request->orig_price,
            'price' => $request->price,
        ];
        
        $obj->metadata = json_encode($metadata);

        $model = "Event";

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($image->move('assets/frontend/images/events/', $image->getClientOriginalName())) {
                $obj->image = $image->getClientOriginalName();
            }
        }

        $image = $request->file('image');
        if ($obj->save()) {
            $data['type'] = "success";
            $data['message'] = $model . " Updated Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return redirect()->route('events.index')->with($data);
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to update " . $model . ", please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('events.edit', $event->id)->withInput()->with($data);
        }
    }

    public function updateStatus(Request $request)
    {
        $update = Event::where('id', $request->id)->update(['is_active' => $request->status]);
        $modelName = "Event";
        if ($update) {
            $request->status == 1 ? $alertType = 'success' : $alertType = 'info';
            $request->status == 1 ? $message = $modelName.' Activated Successfuly!' : $message = $modelName.' Deactivated Successfuly!';

            $notification = array(
                'message' => $message,
                'type' => $alertType,
                'icon' => 'mdi-check-all'
            );
        } else {
            $notification = array(
                'message' => 'Some Error Occured, Try Again!',
                'type' => 'error',
                'icon'  => 'mdi-block-helper'
            );
        }

        echo json_encode($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        $obj = Event::findOrFail($event->id);
        $model = "Event";
        if($obj->delete())
        {
            $obj->deleted_by = Auth::user()->id;
            $obj->save();

            $data['type'] = "success";
            $data['message'] = $model." Deleted Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return json_encode($data);
        }
        else{
            $data['type'] = "danger";
            $data['message'] = "Failed to Delete ".$model.", please try again.";
            $data['icon'] = 'mdi-block-helper';

            return json_encode($data);
        }
    }

    public function trash(Request $request)
    {
        if($request->ajax())
        {
            $data = Event::onlyTrashed()->with(['deletedBy'])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function($row){
                    return '<input type="checkbox" class="checkbox" name="checkbox[]" value="'.$row->id.'">';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0);" data-toggle="tooltip" onClick="restoreRecord('.$row->id.')" data-original-title="Restore"><i title="Restore" class="fas fa-trash-restore font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('deleted_at', function($row){
                    return date('d-m-Y', strtotime($row->deleted_at)).'<br /><label class="text-primary">By: '.$row->deletedBy->first_name.' '.$row->deletedBy->last_name.'</label>';
                })
                ->addColumn('start_at', function($row){
                    return date('d-m-Y h:i:s A', strtotime($row->start_at));
                })
                ->addColumn('image', function($row){
                    return '<img src="'.asset('assets/frontend/images/events/'.Str::of($row->image)->replace(' ', '%20')).'" width="50" height="50" />';
                })
                ->addColumn('titles', function ($row) {
                    return $row->title . '<br /><label class="text-primary">' . $row->subtitle . '</label>';
                })
                ->rawColumns(['action', 'deleted_at' ,'checkbox', 'image', 'titles'])
                ->make(true);
        }

        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "Trashed ".$this->section;

        return view('backend.events.trash', $data);
    }

    public function restore(Request $request)
    {
        $restore = Event::withTrashed()->where('id', $request->id)->restore();
        $model = "Sponsor";
        if ($restore) {
            $data['type'] = "success";
            $data['message'] = $model." Restored Successfuly!.";
            $data['icon'] = 'mdi-check-all';
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Restore ".$model.", please try again.";
            $data['icon'] = 'mdi-block-helper';
        }

        return json_encode($data);
    }

    public function buttons()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="' . route("events.index") . '" class="btn btn-sm btn-success">ALL ' . strtoupper($this->section) . '</a> &nbsp;';
        $this->buttons .= '<a href="' . route("events.create") . '" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="' . route('events.trash') . '" class="btn btn-sm btn-danger">TRASH</a>';

        return $this->buttons;
    }
}
