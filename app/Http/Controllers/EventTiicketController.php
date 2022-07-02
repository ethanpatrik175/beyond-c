<?php

namespace App\Http\Controllers;
use App\Models\EventTicket;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;


class EventTiicketController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->section = "Event Tickets";
    }
    public function buttons()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="' . route("event-tickets.index") . '" class="btn btn-sm btn-success">ALL ' . strtoupper($this->section) . '</a> &nbsp;';
        // $this->buttons .= '<a href="' . route("event-tickets.create") . '" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        // $this->buttons .= '<a href="' . route('event-tickets.trash') . '" class="btn btn-sm btn-danger">TRASH</a>';

        return $this->buttons;
    }
    
    public function index(Request $request)
    {
       
        // dd($data);
        if ($request->ajax()) {
            $data = EventTicket::with('addedBy','events')->get();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="checkbox" name="checkbox[]" value="' . $row->id . '">';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('event-tickets.show', $row->id) . '" target="_blank" class="text-success"><i title="Show" class="fas fa-eye font-size-18"></i></a>&nbsp;&nbsp;';
                    // $btn .= '<a href="' . route('event-tickets.edit', $row->id) . '" target="_blank" ><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    // $btn .= ' <a href="javascript:void(0);" data-toggle="tooltip" onClick="deleteRecord(' . $row->id . ')" data-original-title="Delete" class="text-danger"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at)) . '<br /><label class="text-primary">By: ' . $row->addedBy->first_name . ' ' . $row->addedBy->last_name . '</label>';
                })
                // ->addColumn('updated_at', function ($row) {
                //     if (isset($row->updated_at)) {
                //         return date('d-m-Y', strtotime($row->updated_at));
                //     }
                // })
                ->addColumn('Ticket_info', function ($row) {
                    return  'Name&nbsp;:&nbsp'.$row->addedBy->first_name . ' ' . $row->addedBy->last_name.'<br/>'.'Email&nbsp;:&nbsp'.$row->addedBy->email.'<br/>'.'Booked At&nbsp;:&nbsp;'.date('d-m-Y', strtotime($row->created_at));
                })
                // ->addColumn('status', function ($row) {
                //     if ($row->is_active == 0) {
                //         $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                //         return $btn0;
                //     } else if ($row->is_active == 1) {
                //         $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                //         return $btn1;
                //     }
                // })
                ->addColumn('price', function($row){
                    return Str::of("Price:".'&nbsp;'."$".$row->price."<br/>"."Total Price:".'&nbsp;'."$".$row->total_price.'<br/>');
                })
                ->addColumn('titles', function ($row) {
                    return 'Title:'.$row->events->title . '<br />'.'Sub Title:' . $row->events->sub_title ;
                })
                // ->addColumn('image', function ($row) {
                //     return '<img src="' . asset('assets/frontend/images/events/' . Str::of($row->image)->replace(' ', '%20')) . '" width="50" height="50" />';
                // })
                ->addColumn('ticket_price', function ($row) {
                    $ticket = json_decode($row->payment_info, true);
                    $price = '<label class="text-primary">Price: $' . @$ticket['amount'] . '</label><br />';
                    $price .= '<label class="text-primary">USD: $' . @$ticket['currency'] . '</label><br />';
                    $price .= '<label class="text-primary">Description:' . @$ticket['description'] . '</label>';
                    return $price;
                })
                ->rawColumns(['action', 'checkbox', 'status', 'titles' , 'Ticket_info'])
                ->make(true);
        }

        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "All " . $this->section;

        return view('backend.eventticket.index', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $data['EventTicket'] = EventTicket::with('events')->findOrFail($id);
        $data['payment_info'] = json_decode($data['EventTicket']->payment_info);
        $data['event'] = json_decode($data['EventTicket']->events->metadata);
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "View ".Str::singular($this->section).' Detail';

        return view('backend.eventticket.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "View ".Str::singular($this->section).' Detail';
        $data['EventTicket'] = EventTicket::findorfail($id);
        return view('backend.eventticket.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $EventTicket = EventTicket::findOrFail($id);
            $EventTicket->status = $request->status;
            $EventTicket->message = $request->message;
                if ($EventTicket->save()) {
                    $data['type'] = "success";
                    $data['message'] = "Event Ticket Updated Successfuly!.";
                    $data['icon'] = 'mdi-check-all';
    
                    return redirect()->route('event-tickets.index')->with($data);
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Update Event Ticket, please try again.";
                    $data['icon'] = 'mdi-block-helper';
    
                    return redirect()->route('event-tickets.edit', $id)->withInput()->with($data);
                }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
