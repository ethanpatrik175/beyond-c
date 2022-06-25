<?php

namespace App\Http\Controllers;

use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class SpeakerController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->section = "Speakers";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = Speaker::whereNull('deleted_at')->with(['addedBy', 'updatedBy'])->get();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function($row){
                    return '<input type="checkbox" class="checkbox" name="checkbox[]" value="'.$row->id.'">';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('speakers.show', $row->id).'" target="_blank" class="text-success"><i title="Show" class="fas fa-eye font-size-18"></i></a>&nbsp;&nbsp;';
                    $btn .= '<a href="'.route('speakers.edit', $row->id).'" target="_blank" ><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" data-toggle="tooltip" onClick="deleteRecord('.$row->id.')" data-original-title="Delete" class="text-danger"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('created_at', function($row){
                    return date('d-m-Y', strtotime($row->created_at)).'<br /><label class="text-primary">By: '.$row->addedBy->first_name.' '.$row->addedBy->last_name.'</label>';
                })
                ->addColumn('updated_at', function($row){
                    if(isset($row->updatedBy)){
                        return date('d-m-Y', strtotime($row->updated_at)).'<br /><label class="text-primary">By: '.$row->updatedBy->first_name.' '.$row->updatedBy->last_name.'</label>';
                    }
                })
                ->addColumn('status', function($row){
                    if ($row->is_active == 0) {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    }else if($row->is_active == 1) {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('image', function($row){
                    return '<img src="'.asset('assets/frontend/images/speakers/'.Str::of($row->image)->replace(' ', '%20')).'" width="50" height="50" />';
                })
                ->rawColumns(['action', 'created_at', 'updated_at','checkbox', 'image', 'status'])
                ->make(true);
        }
        
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "All ".$this->section;

        return view('backend.speakers.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "Add New ".Str::singular($this->section);

        return view('backend.speakers.create', $data);
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
            "name" => "required|string|min:3|max:100",
            'slug' => 'required|unique:speakers,slug',
            "summary" => "required|max:250",
            'image' => 'required|image|max:1024'
        ]);

        $speaker = new Speaker();
        $speaker->added_by = Auth::user()->id;
        $speaker->name = $request->name;
        $speaker->summary = $request->summary;
        $speaker->description = $request->description;
        $speaker->slug = $request->slug;

        $metadata['facebook'] = $request->facebook;
        $metadata['twitter'] = $request->twitter;
        $metadata['linkedin'] = $request->linkedin;

        $speaker->metadata = json_encode($metadata);

        $image = $request->file('image');
        if ($image->move('assets/frontend/images/speakers/', $image->getClientOriginalName())) {

            $speaker->image = $image->getClientOriginalName();
            if($speaker->save())
            {
                $data['type'] = "success";
                $data['message'] = "Speaker Added Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('speakers.index')->with($data);
            }
            else
            {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add Speaker, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('speakers.create')->withInput()->with($data);
            }

        }
        else{
            $data['type'] = "danger";
            $data['message'] = "Failed to upload image, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('speakers.create')->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Speaker  $speaker
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['speaker'] = Speaker::findOrFail($id);
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "View ".Str::singular($this->section).' Detail';

        return view('backend.speakers.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Speaker  $speaker
     * @return \Illuminate\Http\Response
     */
    public function edit(Speaker $speaker)
    {
        $data['speaker'] = Speaker::findOrFail($speaker->id);
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "Edit ".Str::singular($this->section);

        return view('backend.speakers.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Speaker  $speaker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Speaker $speaker)
    {
        $request->validate([
            "name" => "required|string|min:3|max:100",
            'slug' => 'required|unique:speakers,slug,'.$speaker->id,
            "summary" => "required|max:250",
            'image' => 'nullable|image|max:1024'
        ]);

        $speaker = Speaker::findOrFail($speaker->id);
        $speaker->updated_by = Auth::user()->id;
        $speaker->name = $request->name;
        $speaker->summary = $request->summary;
        $speaker->description = $request->description;
        $speaker->slug = $request->slug;

        $metadata['facebook'] = $request->facebook;
        $metadata['twitter'] = $request->twitter;
        $metadata['linkedin'] = $request->linkedin;

        $speaker->metadata = json_encode($metadata);

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            if ($image->move('assets/frontend/images/speakers/', $image->getClientOriginalName())) {
                $speaker->image = $image->getClientOriginalName();
            }
        }
            
        if($speaker->save())
        {
            $data['type'] = "success";
            $data['message'] = "Speaker Updated Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return redirect()->route('speakers.index')->with($data);
        }
        else
        {
            $data['type'] = "danger";
            $data['message'] = "Failed to Update Speaker, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('speakers.edit', $speaker->id)->withInput()->with($data);
        }
    }

    public function updateStatus(Request $request)
    {
        $update = Speaker::where('id', $request->id)->update(['is_active' => $request->status]);
        $modelName = "Speaker";
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
     * @param  \App\Models\Speaker  $speaker
     * @return \Illuminate\Http\Response
     */
    public function destroy(Speaker $speaker)
    {
        $speaker = Speaker::findOrFail($speaker->id);
        if($speaker->delete())
        {
            $speaker->deleted_by = Auth::user()->id;
            $speaker->save();

            $data['type'] = "success";
            $data['message'] = "Speaker Deleted Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return json_encode($data);
        }
        else{
            $data['type'] = "danger";
            $data['message'] = "Failed to Delete Speaker, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return json_encode($data);
        }
    }

    public function trash(Request $request)
    {
        if($request->ajax())
        {
            $data = Speaker::onlyTrashed()->with(['deletedBy'])->get();

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
                ->addColumn('image', function($row){
                    return '<img src="'.asset('assets/frontend/images/speakers/'.Str::of($row->image)->replace(' ', '%20')).'" width="50" height="50" />';
                })
                ->rawColumns(['action', 'deleted_at' ,'checkbox', 'image', 'status'])
                ->make(true);
        }

        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "Trashed ".$this->section;

        return view('backend.speakers.trash', $data);
    }

    public function restore(Request $request)
    {
        $speaker = Speaker::onlyTrashed()->findOrFail($request->id);
        
        if($speaker->restore())
        {
            $speaker->deleted_by = NULL;
            $speaker->save();

            $data['type'] = "success";
            $data['message'] = "Speaker Restored Successfuly!.";
            $data['icon'] = 'mdi-check-all';
        }
        else{
            $data['type'] = "danger";
            $data['message'] = "Failed to Restore, please try again!.";
            $data['icon'] = 'mdi-block-helper';
        }
        return json_encode($data);
    }

    public function buttons()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="'.route("speakers.index").'" class="btn btn-sm btn-success">ALL '.strtoupper($this->section).'</a> &nbsp;';
        $this->buttons .= '<a href="'.route("speakers.create").'" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="'.route('speakers.trash').'" class="btn btn-sm btn-danger">TRASH</a>';

        return $this->buttons;
    }
}
