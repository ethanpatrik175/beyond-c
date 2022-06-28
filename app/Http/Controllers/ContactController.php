<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    
    public function index(Request $request)
    {
        
        
        
        if($request->ajax())
        {
            $data = DB::table('contacts')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0);" data-id="'.$row->id.'" class="mb-1 read-message" data-bs-toggle="modal" data-bs-target="#read-message"><i title="Read Message" class="fas fa-eye font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('name', function($row){
                    $name = $row->first_name.' '.$row->last_name ?? '';
                    return "Name&nbsp;:&nbsp;".Str::of($name)->upper()."<br />"."Email&nbsp;:&nbsp;".'<a href="mailto:'.$row->email.'">'.$row->email.'</a>';;
                })
                ->addColumn('message', function($row){
                    return Str::of($row->message)->limit(150, '<strong>...</strong>');
                })
                ->addColumn('created_at', function($row){
                    if(!isset($row->created_at)){
                        $date = '';
                    }
                    else{
                        $date = date('d-M-Y', strtotime($row->created_at))."<br/><label class='text-primary'>".Carbon::parse($row->created_at)->diffForHumans()."</label>";
                    }
                    return $date;
                })
                ->addColumn('read_at', function($row){
                    if(isset($row->read_at)){
                        $date = date('d-M-Y', strtotime($row->read_at))."<br /><label class='text-primary'>".Carbon::parse($row->read_at)->diffForHumans()."</label>";
                    }
                    else{
                        $date = "<label class='badge badge-danger'>UNREAD</label>";
                    }
                    return $date;
                })
                ->rawColumns(['action', 'name', 'created_at','message', 'read_at'])
                ->make(true);
        }
        return view('backend.contact.contact-messages');
        
    }

    public function readMessage(Request $request)
    {
        $contact = Contact::find($request->id);
        if(isset($contact) && (count((array)$contact) > 0))
        {
            if(! isset($contact->read_at)){
                Contact::where('id', $request->id)->update(['read_at' => Carbon::now()]);
            }

            $message = DB::table('contacts')->select('message', 'read_at')->where('id', $contact->id)->first();
            $data['type'] = "success";
            $data['message'] = $message->message;
            return json_encode($data);
        }
        else{
            $data['type'] = "error";
            $data['message'] = "No Data Found!";
            return json_encode($data);
        }
    }
    public function removeMessage(Request $request)
    {
        $msg = Contact::findOrFail($request->id);

        if ($msg->delete()) {
            $notification['type'] = "success";
            $notification['message'] = "Message Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Message, please try again.";
            $notification['icon'] = 'mdi-block-helper';
        }

        return json_encode($notification);

    }

    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       

        // $request->validate([
        //     'first_name' => 'required|',
        //     'email' => 'required|',
        //     'message' => 'required'
        // ]);

        $contact = new Contact();
        $contact->first_name = $request->name;
        $contact->last_name = $request->lastname;
        $contact->email = $request->email;
        $contact->message = $request->message;
        $contact->phone = $request->number;
        // dd($contact);
        if ($contact->save()) {
            $content['first_name'] = $request->name;
            $content['last_name'] = $request->lastname;
            $content['email'] = $request->email;
            $content['message'] = $request->message;

            $body = 'Email:' . $request->email;
            if (isset($request->number)) {
                $body .= "<br/>". 'Phone:' . $request->number;
            }
            $body .= 'Message: ' . $request->message;
            $content['body'] = $body;

            try {

                Mail::to($request->email)->send(new ContactMail($content));
                $content['is_admin'] = true;
                Mail::to('johnbh269@gmail.com')->send(new ContactMail($content));
            } catch (\Throwable $th) {
                Log::info($th);
            }

            $notification['type'] = "success";
            $notification['message'] = "Your message sent successfully.";
            return redirect()->back()->with($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Some error occured, please try again.";
            return redirect()->back()->with($notification);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
