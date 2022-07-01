<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class CustomerController extends Controller
{
    public function bookTicket(Request $request)
    {
        $event = Event::findOrFail($request->id);

        $eventPrice = 0;
        $price = json_decode(@$event->metadata)->price;
        $origPrice = json_decode(@$event->metadata)->orig_price;

        if($origPrice > $price) {
            $eventPrice = $price;
        } else {
            $eventPrice = $origPrice;
        }

        $ticket = new EventTicket();

        $paymentInfo = new stdClass;
        $paymentInfo->amount = $eventPrice;
        $paymentInfo->currency = 'USD';
        $paymentInfo->description = $event->title;

        $ticket->user_id = Auth::user()->id;
        $ticket->event_id = $request->id;
        $ticket->price = $eventPrice;
        $ticket->total_price = $eventPrice;
        $ticket->status = 'new';
        $ticket->payment_info = json_encode($paymentInfo);

        if($ticket->save())
        {
            $data['type'] = "success";
            $data['message'] = "Ticket booked successfully";
        }
        else
        {
            $data['type'] = "success";
            $data['message'] = "Ticket booked successfully";
        }

        return redirect()->route('thank.you')->with($data);
    }
}
