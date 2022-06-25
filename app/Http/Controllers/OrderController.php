<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;



class OrderController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->section = "Order";
    }
    public function buttons()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="'.route("orders.index").'" class="btn btn-sm btn-success">ALL '.strtoupper($this->section).'</a> &nbsp;';
       
        return $this->buttons;
    }
    public function index(Request $request)
    {
        
        if($request->ajax())
        {
            $data = Order::whereNull('deleted_at')->with(['addedBy', 'updatedBy'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function($row){
                    return '<input type="checkbox" class="checkbox" name="checkbox[]" value="'.$row->id.'">';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('orders.show', $row->id).'" target="_blank" class="text-success"><i title="Show" class="fas fa-eye font-size-18"></i></a>&nbsp;&nbsp;';
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
             
                ->addColumn('name', function($row){
                    return Str::of("Name:".'&nbsp;'.$row->firstName.'&nbsp;'.$row->lastName.'<br/>'."Email:".'&nbsp;'.$row->email.'<br/>'."Mobile:".'&nbsp;'.$row->email) ->limit(100);
                })
                ->addColumn('order', function($row){
                    return Str::of($row->order_number) ->limit(100);
                })
                ->addColumn('order_status', function($row){
                    return Str::of($row->order_status) ->limit(100);
                })
                ->addColumn('total', function($row){
                    return Str::of("$".$row->total) ->limit(100);
                })
               
                ->addColumn('image', function($row){
                    return '<img src="'.asset('assets/frontend/images/speakers/'.Str::of($row->image)->replace(' ', '%20')).'" width="50" height="50" />';
                })
                ->rawColumns(['action', 'created_at', 'updated_at','checkbox', 'image', 'status','name','order','order_status','total'])
                ->make(true);
        }
        
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "All ".$this->section;
        // dd($data);
        return view('backend.orders.index', $data);
    }


  
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        $Order = new Order();
        $id = Auth::user()->id;
        $Order->added_by = Auth::user()->id;
        $date = Carbon::now();
        $ids = $date->format('YmdHis');
        $Order->order_number = "ES".$ids;
        $Order->firstName = $request->Firstname;
        $Order->lastName = $request->lastname;
        $Order->email = $request->email;
        $Order->address = $request->address;
        $Order->company_name = $request->company_name;
        $Order->city = $request->city;
        $Order->zip = $request->zip;
        $Order->mobile = $request->phone;
        $Order->content = $request->content;
        $total = \Cart::getTotal();
        $Order->total = $total;
        $cart_items = \Cart::getContent();
            if ($Order->save()) {
               foreach($cart_items as $order_items){

                     $order_itm = new OrderItem();
                     $order_itm->order_id = $Order->id;
                     $order_itm->product_id = $order_items->id;
                     $order_itm->price = $order_items->price;
                     $order_itm->quantity = $order_items->quantity;
                     $order_itm->save();
               }
            session()->flush('success', 'All Item Cart Clear Successfully !');
                
            $data['type'] = "success";
                $data['message'] = "Product Meta Added Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('front.product.promotion')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add Product Meta, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('front.product.promotion')->withInput()->with($data);
            }
         
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['order'] = Order::with('items', 'items.product')->findOrFail($id);
        $items = $data['order']->items->pluck('product_id');
        $product = Product::whereIn('id', $items)->get();
        $total_discount = 0;
        foreach($product as $discount){
            $total_discount += $discount->discount;
        }
       $data['discount'] = $total_discount;
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "View ".Str::singular($this->section).' Detail';

        return view('backend.orders.show', $data);
    }

    
    public function edit($id)
    {
        $data['order'] =   Order::findOrFail($id);
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "View ".Str::singular($this->section).' Detail';
        
        return view('backend.orders.show', $data);
        
    }

    public function update(Request $request)
    {
     
       
            $Order = Order::findOrFail($request->order_id);
            $Order->order_status = $request->order_status;
            $Order->updated_by = Auth::user()->id;
            $Order->description = $request->description;
                if ($Order->save()) {
                    $order_history = new OrderHistory;
                    $order_history->order_id = $request->order_id;
                    $order_history->status = $request->order_status;
                    $order_history->description = $request->description;
                    $order_history->save();

                    $data['type'] = "success";
                    $data['message'] = "Order Updated Successfuly!.";
                    $data['icon'] = 'mdi-check-all';
    
                    return redirect()->route('orders.index')->with($data);
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Update Order, please try again.";
                    $data['icon'] = 'mdi-block-helper';
    
                    return redirect()->route('orders.edit', $request->id)->withInput()->with($data);
                }
            
    }
    public function orderhistory($id ,Request $request)
    {
       
        if($request->ajax())
        {
            $data = OrderHistory::where('order_id' , $id)->get();
            return DataTables::of($data)
                ->addIndexColumn()
              
                ->addColumn('status', function($row){
                    return Str::of($row->status) ->limit(100);
                })
                ->addColumn('created', function($row){
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->addColumn('description', function($row){
                    return Str::of($row->description) ->limit(100);
                })
                ->rawColumns(['status', 'description','created'])
                ->make(true);
        }
        
        $data['buttons'] = $this->buttons();
        $data['section'] = $this->section;
        $data['page_title'] = "All ".$this->section;
        return view('backend.orders.index', $data);
    }
}
