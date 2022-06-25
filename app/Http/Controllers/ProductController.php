<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductCategory;
use App\Models\RelatedProduct;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="' . route("products.index") . '" class="btn btn-sm btn-success">ALL Product</a> &nbsp;';
        $this->buttons .= '<a href="' . route("products.create") . '" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="' . route('product.trash') . '" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "Products";
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('products as b')
                ->join('users', 'b.added_by', '=', 'users.id')
                ->leftJoin('users as users_updated', 'b.updated_by', '=', 'users_updated.id')
                ->leftJoin('product_categories as pc', 'b.category_id', '=', 'pc.id')
                ->select('b.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy', 'pc.title as category_title')
                ->whereNull('b.deleted_at')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('products.edit', $row->id) . '" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
                // ->addColumn('created_at', function ($row) {
                //     return date('d-M-Y', strtotime($row->created_at)).'<br /> <label class="text-primary">'.Carbon::parse($row->created_at)->diffForHumans().'</label>';
                // })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="product_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="product_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('price', function($row){
                    return Str::of("Regular Price:".'&nbsp;'."$".$row->regular_price."<br/>"."Sale Price:".'&nbsp;'."$".$row->sale_price.'<br/>'."Discount:".'&nbsp;'."$".$row->discount) ->limit(100);
                })
                ->addColumn('icon', function ($row) {
                    $imageName = Str::of($row->icon)->replace(' ', '%20');
                    if ($row->icon) {
                        $image = '<img src=' . asset('assets/frontend/images/products/' . $imageName) . ' class="avatar-sm" />';
                    } else {
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('title', function ($row) {
                    return Str::of($row->title)->limit(100);
                })
                ->addColumn('category_title', function ($row) {
                    return Str::of($row->category_title)->limit(100);
                })


                ->addColumn('added_by', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)) . '<br/>' . $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('updated_by', function ($row) {
                    if (isset($row->updatedBy)) {
                        return $row->updated_by_first_name . ' ' . $row->updated_by_last_name . ' <br />(' . Str::of($row->updatedBy)->upper() . ')';
                    } else {
                        return  '-';
                    }
                })
                ->rawColumns(['action', 'is_active', 'icon', 'title', 'added_by', 'updated_by', 'category_title','price'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Product';


        return view('backend.product.all', $data);
    }


    public function create()
    {
        $data['Product'] = Product::where('is_active', '1')->whereNull('deleted_at')->get();
        $data['parents'] = ProductCategory::where('is_active', '1')->whereNull('deleted_at')->get();
        $data['Brand'] = Brand::where('is_active', '1')->whereNull('deleted_at')->get();
        
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Add New Products';
        
        return view('backend.product.add', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'Title' => 'required|max:100',
            'slug' => 'required|max:100',
            'category_id' => 'required',
            'sku' => 'required',
            'regular_price' => 'required',
            'quantity' => 'required',
            'stock_status' => 'required',
            'description' => 'required',
            'stock_alt_quantity' => 'required',
            'description' => 'required',
            'image' => 'required|image|max:1024'
        ], [
            'image.required' => 'Please upload valid image'
        ]);

        $product = new Product();
        $product->added_by = Auth::user()->id;
        $product->title = $request->Title;
        $product->slug = Str::slug($request->slug);
        $product->type = $request->type;
        $product->sku = $request->sku;
        $product->discount = $request->discount;
        $product->brand_id = $request->Brand_id;
        $product->quantity = $request->quantity;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->description = $request->description;
        $product->stock_status = $request->stock_status;
        $product->stock_alert_quantity = $request->stock_alt_quantity;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->is_new = $request->is_new;
        $product->is_featured = $request->is_featured;


        $meta_data['meta_title'] = $request->metadata;
        $product->metaTitle = json_encode($meta_data);
        $image = $request->file('image');

        if ($image->move('assets/frontend/images/products/', $image->getClientOriginalName())) {

            $product->icon = $image->getClientOriginalName();
            if ($product->save()) {

                if (isset($request->related_product_id) && (count((array)$request->related_product_id) > 0)) {
                    foreach ($request->related_product_id as $related_product_id) {
                        $Relatedproduct = new RelatedProduct();
                        $Relatedproduct->product_id = $product->id;
                        $Relatedproduct->related_product_id = $related_product_id;
                        $Relatedproduct->save();
                    }
                }
                if (isset($request->size) && (count((array)$request->size) > 0)) {
                    foreach ($request->size as $size) {
                        $ProductAttribute = new ProductAttribute();
                        $ProductAttribute->product_id = $product->id;
                        $ProductAttribute->colour = $request->color;
                        $ProductAttribute->size = $size;
                        $ProductAttribute->save();
                    }
                }

                $data['type'] = "success";
                $data['message'] = "Product Added Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('products.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add Product, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('products.create')->withInput()->with($data);
            }
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to upload image, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('products.create')->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update Products';
        $data['Product'] = Product::findOrFail($id);
        $data['Products'] = Product::where('is_active', '1')->whereNull('deleted_at')->get();
        $data['parents'] = ProductCategory::where('is_active', '1')->whereNull('deleted_at')->get();
        $data['Brand'] = Brand::where('is_active', '1')->whereNull('deleted_at')->get();
        $data['related_product'] = RelatedProduct::where('product_id', $id)->pluck('related_product_id')->toArray();
        // dd( $data['related_product']);
        // $data['colour'] = ProductAttribute::where('product_id' , $id )->pluck('colour')->toArray();
        // dd($data['colour']);
        $data['size'] = ProductAttribute::where('product_id', $id)->pluck('size')->toArray();
        // dd($data['size']);
        return view('backend.product.edit', $data);
    }

    public function update(Request $request, $id)
    { 
        // dd($request->all());
            $request->validate([
                'Title' => 'required|max:100',
                'slug' => 'required|max:100',
                'category_id' => 'required',
                'sku' => 'required',
                'regular_price' => 'required',
                'quantity' => 'required',
                'stock_status' => 'required',
                'description' => 'required',
                'stock_alt_quantity' => 'required',
                'description' => 'required',
               
            ]);
            $product = Product::findOrFail($id);

            $product->updated_by = Auth::user()->id;
            $product->title = $request->Title;
            $product->slug = Str::slug($request->slug);
            $product->type = $request->type;
            $product->sku = $request->sku;
            $product->discount = $request->discount;
            $product->brand_id = $request->Brand_id;
            $product->quantity = $request->quantity;
            $product->regular_price = $request->regular_price;
            $product->sale_price = $request->sale_price;
            $product->description = $request->description;
            $product->stock_status = $request->stock_status;
            $product->stock_alert_quantity = $request->stock_alt_quantity;
            $product->description = $request->description;
            $product->category_id = $request->category_id;
            $meta_data['meta_title'] = $request->meta_title;
            $product->metaTitle = json_encode($meta_data);
            $related_product = RelatedProduct::where('product_id', $id)->get();
            $product_attribute = ProductAttribute::where('product_id', $id)->get();
            $related_product->each->delete();
            $product_attribute->each->delete();
            // dd("tets");
            if ($request->file('image')) {
                $request->validate([
                    'image' => 'required|image|max:1024'
                ], [
                    'image.required' => 'Please upload valid image'
                ]);
         
                $image = $request->file('image');
                if ($image->move('assets/frontend/images/products/', $image->getClientOriginalName())) {

                    $product->icon = $image->getClientOriginalName();
                    if ($product->save()) {
                        $data['type'] = "success";
                        $data['message'] = "Products Updated Successfuly!.";
                        $data['icon'] = 'mdi-check-all';

                        return redirect()->route('products.index')->with($data);
                    } else {
                        $data['type'] = "danger";
                        $data['message'] = "Failed to Update Products, please try again.";
                        $data['icon'] = 'mdi-block-helper';

                        return redirect()->route('products.edit', $request->id)->withInput()->with($data);
                    }
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to upload image, please try again.";
                    $data['icon'] = 'mdi-block-helper';

                    return redirect()->route('products.edit', $request->id)->withInput()->with($data);
                }
            } else {
                if ($product->save()) {
                    foreach ($request->related_product_id as $related_product_id) {
                        $Relatedproduct = new RelatedProduct();
                        $Relatedproduct->product_id = $id;
                        $Relatedproduct->related_product_id = $related_product_id;
                        $Relatedproduct->save();
                    }

                    foreach ($request->size as $size) {
                        $ProductAttribute = new ProductAttribute();
                        $ProductAttribute->product_id = $product->id;
                        $ProductAttribute->colour = $request->color;
                        $ProductAttribute->size = $size;
                        $ProductAttribute->save();
                    }

                    $data['type'] = "success";
                    $data['message'] = "Products Updated Successfuly!.";
                    $data['icon'] = 'mdi-check-all';

                    return redirect()->route('products.index')->with($data);
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Update Products, please try again.";
                    $data['icon'] = 'mdi-block-helper';

                    return redirect()->route('products.edit', $request->id)->withInput()->with($data);
                }
            }
        
    }

    public function destroy($id)
    {
        $Product = Product::find($id);

        if ($Product) {
            $Product->delete();
            $notification['type'] = "success";
            $notification['message'] = "Product Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Product, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function updateStatus(Request $request)
    {
        // dd($request->all());
        $update = Product::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Product Activated Successfuly!' : $message = 'Product Deactivated Successfuly!';

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
        if ($request->ajax()) {
            $data = Product::onlyTrashed()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" class="restore" data-id="' . $row->id . '"><i title="Restore" class="fas fa-trash-restore-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('deleted_at', function ($row) {
                    return date('d-M-Y', strtotime($row->deleted_at)) . '<br /> <label class="text-primary">' . Carbon::parse($row->deleted_at)->diffForHumans() . '</label>';
                })
                ->addColumn('icon', function ($row) {
                    $imageName = Str::of($row->icon)->replace(' ', '%20');
                    if ($row->icon) {
                        $image = '<img src=' . asset('assets/frontend/images/products/' . $imageName) . ' class="avatar-sm" />';
                    } else {
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('title', function ($row) {
                    return Str::of($row->title)->limit(100);
                })

                ->rawColumns(['action', 'deleted_at', 'icon', 'title'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Product Trash';
        return view('backend.product.trash', $data);
    }
    public function restore(Request $request)
    {
        $Product = Product::withTrashed()->find($request->id);
        if ($Product) {
            $Product->restore();
            $notification['type'] = "success";
            $notification['message'] = "Product Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Product, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
}
