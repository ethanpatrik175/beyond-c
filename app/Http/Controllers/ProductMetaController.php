<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductMeta;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;


class ProductMetaController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="'.route("product_metas.index").'" class="btn btn-sm btn-success">ALL Product Meta</a> &nbsp;';
        $this->buttons .= '<a href="'.route("product_metas.create").'" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="'.route('product_meta.trash').'" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "All Product Meta";
    }
   
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = DB::table('product_metas as b')
            ->join('users', 'b.added_by', '=', 'users.id')
            ->leftJoin('users as users_updated', 'b.updated_by', '=', 'users_updated.id')
            ->leftJoin('products as pc', 'b.product_id', '=', 'pc.id')
            ->select('b.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy' ,'pc.title as products_title' )
            ->whereNull('b.deleted_at')
            ->get();
        
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('product_metas.edit', $row->id).'" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
               
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="productmetas_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="productmetas_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('key', function($row){
                    return Str::of($row->key)->limit(100);
                })
                ->addColumn('content', function($row){
                    return Str::of($row->content)->limit(50);
                })
                ->addColumn('products_title', function($row){
                    return Str::of($row->products_title)->limit(100);
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
                ->rawColumns(['action', 'is_active', 'key', 'added_by', 'updated_by','products_title','content' ])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Product Meta';
     
            
        return view('backend.productmeta.all', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Add New Products Meta';
        $data['parents'] = Product::where('is_active' , '1')->whereNull('deleted_at')->get();

        return view('backend.productmeta.add', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $ProductMeta = new ProductMeta();
        $ProductMeta->added_by = Auth::user()->id;
        $ProductMeta->key = $request->key;
        $ProductMeta->product_id = $request->product_id;
        $ProductMeta->content = $request->content;
            if ($ProductMeta->save()) {
                $data['type'] = "success";
                $data['message'] = "Product Meta Added Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('product_metas.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add Product Meta, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('product_metas.create')->withInput()->with($data);
            }
         
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductMeta  $productMeta
     * @return \Illuminate\Http\Response
     */
    public function show(ProductMeta $productMeta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductMeta  $productMeta
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update Products';
        $data['ProductMeta'] = ProductMeta::findOrFail($id);
        $data['Product'] = Product::where('is_active', 1)->whereNull('deleted_at')->get();
        return view('backend.productmeta.edit', $data);
    }
    
    public function update(Request $request, $id)
    {
       
        $ProductMeta = ProductMeta::findOrFail($id);
    
        $ProductMeta->updated_by = Auth::user()->id;
        $ProductMeta->added_by = Auth::user()->id;
        $ProductMeta->key = $request->key;
        $ProductMeta->product_id = $request->product_id;
        $ProductMeta->content = $request->content;
            if ($ProductMeta->save()) {
                $data['type'] = "success";
                $data['message'] = "Product Meta Updated Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('product_metas.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Update Product Meta, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('product_metas.edit', $request->id)->withInput()->with($data);
            }
        }

    public function destroy($id)
    {
        $ProductMeta = ProductMeta::find($id);
        if ($ProductMeta) {
            $ProductMeta->delete();
            $notification['type'] = "success";
            $notification['message'] = "Product Meta Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Product Meta, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function updateStatus(Request $request)
    {
        $update = ProductMeta::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Product Meta Activated Successfuly!' : $message = 'Product Meta Deactivated Successfuly!';

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
    public function trash(Request $request)
    {
        if($request->ajax())
        {
            $data = ProductMeta::onlyTrashed()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" class="restore" data-id="' . $row->id . '"><i title="Restore" class="fas fa-trash-restore-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('deleted_at', function ($row) {
                    return date('d-M-Y', strtotime($row->deleted_at)).'<br /> <label class="text-primary">'.Carbon::parse($row->deleted_at)->diffForHumans().'</label>';
                })
                ->addColumn('key', function($row){
                    return Str::of($row->key)->limit(100);
                })
                ->addColumn('content', function($row){
                    return Str::of($row->content)->limit(100);
                })
                
                ->rawColumns(['action', 'deleted_at', 'key','content'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Product Meta Trash';
        return view('backend.productmeta.trash', $data);
    }
    public function restore(Request $request)
    {
        $ProductMeta = ProductMeta::withTrashed()->find($request->id);
        if ($ProductMeta) {
            $ProductMeta->restore();
            $notification['type'] = "success";
            $notification['message'] = "Product Meta Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Product Meta, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }

}
