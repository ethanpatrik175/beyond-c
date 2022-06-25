<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

use function GuzzleHttp\Promise\all;

class BrandController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="'.route("brands.index").'" class="btn btn-sm btn-success">All Brands</a> &nbsp;';
        $this->buttons .= '<a href="'.route("brands.create").'" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="'.route('brand.trash').'" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "All Brands";
    }
    public function index(Request $request)
    {
       
        if($request->ajax())
        {
            $data = DB::table('brands as b')
            ->join('users', 'b.added_by', '=', 'users.id')
            ->leftJoin('users as users_updated', 'b.updated_by', '=', 'users_updated.id')
            ->select('b.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy')
            ->whereNull('b.deleted_at')
            ->get();
        
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('brands.edit', $row->id).'" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="product_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="product_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('icon', function ($row) {
                    $imageName = Str::of($row->icon)->replace(' ', '%20');
                    if($row->icon){
                        $image = '<img src=' . asset('assets/frontend/images/brands/' . $imageName) . ' class="avatar-sm" />';
                    }else{
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('title', function($row){
                    return Str::of($row->title)->limit(100);
                })
                ->addColumn('description', function($row){
                    return Str::of($row->description)->limit(100);
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
                ->rawColumns(['action', 'is_active', 'icon', 'title', 'added_by', 'updated_by','description' ])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Brand';
     
            
        return view('backend.brand.all', $data);
    }

    public function create()
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Add New Brands';

        return view('backend.brand.add', $data);
    }

    public function store(Request $request)
    {
       
        $request->validate([
            'Title' => 'required|max:100',
            'slug' => 'required|max:100',
            'description' => 'required|max:120',
            'content' => 'required',
            'image' => 'required|image|max:1024'
        ], [
            'image.required' => 'Please upload valid image'
        ]);
       
        $Brand = new Brand();
        $Brand->added_by = Auth::user()->id;
        $Brand->title = $request->Title;
        $Brand->slug = Str::slug($request->slug);
        $Brand->description = $request->description;
        $Brand->content = $request->content;
        
        $image = $request->file('image');
        if ($image->move('assets/frontend/images/brands/', $image->getClientOriginalName())) {

            $Brand->icon = $image->getClientOriginalName();
            if ($Brand->save()) {
                $data['type'] = "success";
                $data['message'] = "Brands Added Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('brands.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add Brands, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('brands.create')->withInput()->with($data);
            }
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to upload image, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('brands.create')->withInput()->with($data);
        }
        
    }

    public function show(Brand $brand)
    {
        //
    }

    public function edit($id)
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update Blogs';
        $data['Brand'] = Brand::findOrFail($id);

        return view('backend.brand.edit', $data);
    }

    public function update(Request $request, $id)
    {
        {
            $request->validate([
                'Title' => 'required|max:100',
                'slug' => 'required|max:100',
                'description' => 'required',
                'content' => 'required',
                'image' => 'nullable|image|max:1024'
            ]);
    
            $Brand = Brand::findOrFail($id);
    
            $Brand->updated_by = Auth::user()->id;
            $Brand->added_by = Auth::user()->id;
            $Brand->title = $request->Title;
        $Brand->slug = Str::slug($request->slug);
        $Brand->content = $request->content;
        $Brand->description = $request->description;
       
    
            if ($request->file('image')) {
                $request->validate([
                    'image' => 'required|image|max:1024'
                ], [
                    'image.required' => 'Please upload valid image'
                ]);
    
                $image = $request->file('image');
                if ($image->move('assets/frontend/images/products/', $image->getClientOriginalName())) {
    
                    $Brand->icon = $image->getClientOriginalName();
                    if ($Brand->save()) {
                        $data['type'] = "success";
                        $data['message'] = "Brand Updated Successfuly!.";
                        $data['icon'] = 'mdi-check-all';
    
                        return redirect()->route('brands.index')->with($data);
                    } else {
                        $data['type'] = "danger";
                        $data['message'] = "Failed to Update Brand, please try again.";
                        $data['icon'] = 'mdi-block-helper';
    
                        return redirect()->route('brands.edit', $request->id)->withInput()->with($data);
                    }
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to upload image, please try again.";
                    $data['icon'] = 'mdi-block-helper';
    
                    return redirect()->route('brands.edit', $request->id)->withInput()->with($data);
                }
            } else {
                if ($Brand->save()) {
                    $data['type'] = "success";
                    $data['message'] = "Brand Updated Successfuly!.";
                    $data['icon'] = 'mdi-check-all';
    
                    return redirect()->route('brands.index')->with($data);
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Update Brand, please try again.";
                    $data['icon'] = 'mdi-block-helper';
    
                    return redirect()->route('brands.edit', $request->id)->withInput()->with($data);
                }
            }
        }
        
        
    }

    public function destroy(Request $request , $id)
    {
        // dd("test");
        $Brand = Brand::find($id);
        if ($Brand) {
            $Brand->delete();
            $notification['type'] = "success";
            $notification['message'] = "Brand Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Brand, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
}
public function updateStatus(Request $request)
    {
        // dd($request->all());
        $update = Brand::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Brand Activated Successfuly!' : $message = 'Brand Deactivated Successfuly!';

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
            $data = Brand::onlyTrashed()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" class="restore" data-id="' . $row->id . '"><i title="Restore" class="fas fa-trash-restore-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('deleted_at', function ($row) {
                    return date('d-M-Y', strtotime($row->deleted_at)).'<br /> <label class="text-primary">'.Carbon::parse($row->deleted_at)->diffForHumans().'</label>';
                })
                ->addColumn('icon', function ($row) {
                    $imageName = Str::of($row->icon)->replace(' ', '%20');
                    if($row->icon){
                        $image = '<img src=' . asset('assets/frontend/images/brands/' . $imageName) . ' class="avatar-sm" />';
                    }else{
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('title', function($row){
                    return Str::of($row->title)->limit(100);
                })
                
                ->rawColumns(['action', 'deleted_at', 'icon','title'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Brand Trash';
        return view('backend.brand.trash', $data);
    }
    public function restore(Request $request)
    {
        $Brand = Brand::withTrashed()->find($request->id);
        if ($Brand) {
            $Brand->restore();
            $notification['type'] = "success";
            $notification['message'] = "Brand Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Brand, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
}