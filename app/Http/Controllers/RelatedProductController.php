<?php

namespace App\Http\Controllers;

use App\Models\RelatedProduct;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class RelatedProductController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="'.route("related_products.index").'" class="btn btn-sm btn-success">ALL Related Product</a> &nbsp;';
        $this->buttons .= '<a href="'.route("related_products.create").'" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="'.route('related_product.trash').'" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "All Related Product";
    }
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = DB::table('related_products as b')
            ->join('users', 'b.added_by', '=', 'users.id')
            ->leftJoin('users as users_updated', 'b.updated_by', '=', 'users_updated.id')
            ->leftJoin('products as pc', 'b.product_id', '=', 'pc.id')
            ->select('b.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy' ,'pc.title as products_title' )
            ->whereNull('b.deleted_at')
            ->get();
        
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('related_products.edit', $row->id).'" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
               
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="relatedproduct_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="relatedproduct_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
              
                ->addColumn('added_by', function($row){
                    return date('d-M-Y', strtotime($row->created_at)).'<br/>'. $row->first_name.' '.$row->last_name;
                })
                ->addColumn('updated_by', function($row){
                    if(isset($row->updatedBy)){
                        return $row->updated_by_first_name.' '.$row->updated_by_last_name.' <br />('.Str::of($row->updatedBy)->upper().')';
                    }else{
                        return  '-';
                    }
                })
                ->rawColumns(['action', 'is_active',  'added_by', 'updated_by','products_title' ])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Related Product';
     
            
        return view('backend.relatedproduct.all', $data);
    }

    public function create()
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Add New Related Product';
        $data['parents'] = Product::where('is_active' , '1')->whereNull('deleted_at')->get();

        return view('backend.relatedproduct.add', $data);
    }

    public function store(Request $request)
    {

        foreach($request->related_product_id as $related_id){
            $RelatedProduct = new RelatedProduct();
            $RelatedProduct->added_by = Auth::user()->id;
            $RelatedProduct->product_id = $request->product_id;
            $RelatedProduct->related_product_id = $related_id;
            $RelatedProduct->save();
        }
      
            if ($RelatedProduct != null) {
                $data['type'] = "success";
                $data['message'] = "Related Product Meta Added Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('related_products.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add Related Product, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('related_products.create')->withInput()->with($data);
            }
         
        
    }
  
    public function show(RelatedProduct $relatedProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RelatedProduct  $relatedProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(RelatedProduct $relatedProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RelatedProduct  $relatedProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RelatedProduct $relatedProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RelatedProduct  $relatedProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(RelatedProduct $relatedProduct)
    {
        //
    }
}
