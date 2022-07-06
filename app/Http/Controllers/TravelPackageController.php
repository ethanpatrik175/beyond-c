<?php

namespace App\Http\Controllers;

use App\Models\TravelPackage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Models\PackageType;
use App\Models\Tag;
use App\Models\TravelTags;

class TravelPackageController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="'.route("travel-packages.index").'" class="btn btn-sm btn-success">All Travel Package</a> &nbsp;';
        $this->buttons .= '<a href="'.route("travel-packages.create").'" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="'.route('travel-package.trash').'" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "All Travel Package";
    }
   
    public function index(Request $request)
    {
        
        if($request->ajax())
        {
            $data = DB::table('travel_packages as b')
            ->join('users', 'b.added_by', '=', 'users.id')
            ->leftJoin('users as users_updated', 'b.updated_by', '=', 'users_updated.id')
            ->leftJoin('package_types as d', 'b.package_type_id', '=', 'd.id')
            ->select('b.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy','d.name as pack_type'  )
            ->whereNull('b.deleted_at')
            ->get();
        
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('travel-packages.edit', $row->id).'" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
               
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="categories_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="categories_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('image', function ($row) {
                    $imageName = Str::of($row->image)->replace(' ', '%20');
                    if ($row->image) {
                        $image = '<img src=' . asset('assets/frontend/images/travelpackages/' . $imageName) . ' class="avatar-sm" />';
                    } else {
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('name', function($row){
                    return Str::of($row->name)->limit(50);
                })
                ->addColumn('pack_type', function($row){
                    return Str::of($row->pack_type)->limit(100);
                })
                ->addColumn('price', function($row){
                    return Str::of("Price:".'&nbsp;'."$".$row->price."<br/>"."Sale Price:".'&nbsp;'."$".$row->sale_price) ->limit(100);
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
                ->rawColumns(['action', 'is_active','added_by', 'updated_by','name','image','pack_type','price'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Package Type';
     
        return view('backend.travelpackages.all', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['package_type'] = PackageType::wherenull('deleted_at')->get();
        $data['Tags'] = Tag::where('is_active', '1')->whereNull('deleted_at')->get();
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Add New Travel Packages';
        
        return view('backend.travelpackages.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|max:100',
            'slug' => 'required|max:100',
            'package_type_id' => 'required',
            'tag_id' => 'required',
            'location' => 'required',
            'price' => 'required',
            'sale_price' => 'required',
            'no_of_days' => 'required',
            'no_of_members' => 'required',
            'requirment' => 'required',
            'description' => 'required',
            'image' => 'required|image'
        ], [
            'image.required' => 'Please upload valid image'
        ]);

        $TravelPackage = new TravelPackage();
        $TravelPackage->added_by = Auth::user()->id;
        $TravelPackage->name = $request->name;
        $TravelPackage->slug = $request->slug;
        $TravelPackage->location = $request->location;
        $TravelPackage->price = $request->price;
        $TravelPackage->package_type_id = $request->package_type_id;
        $TravelPackage->sale_price = $request->sale_price;
        $TravelPackage->no_of_days = $request->no_of_days;
        $TravelPackage->no_of_members = $request->no_of_members;
        $TravelPackage->requirements = $request->requirment;
        $TravelPackage->description = $request->description;
        $image = $request->file('image');

        if ($image->move('assets/frontend/images/travelpackages/', $image->getClientOriginalName())) {

            $TravelPackage->image = $image->getClientOriginalName();
            if ($TravelPackage->save()) {

                if (isset($request->tag_id) && (count((array)$request->tag_id) > 0)) {
                    foreach ($request->tag_id as $tag_id) {
                        $TravelTags = new TravelTags();
                        $TravelTags->travel_package_id = $TravelPackage->id;
                        $TravelTags->tag_id = $tag_id;
                        $TravelTags->save();
                    }
                }
                // if (isset($request->size) && (count((array)$request->size) > 0)) {
                //     foreach ($request->size as $size) {
                //         $ProductAttribute = new ProductAttribute();
                //         $ProductAttribute->product_id = $product->id;
                //         $ProductAttribute->colour = $request->color;
                //         $ProductAttribute->size = $size;
                //         $ProductAttribute->save();
                //     }
                // }

                $data['type'] = "success";
                $data['message'] = "Travel Package Added Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('travel-packages.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add Travel Package, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('travel-packages.create')->withInput()->with($data);
            }
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to upload image, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('travel-packages.create')->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TravelPackage  $travelPackage
     * @return \Illuminate\Http\Response
     */
    public function show(TravelPackage $travelPackage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TravelPackage  $travelPackage
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd("tets");
        $data['travel_package'] = TravelPackage::findorfail($id);
        $data['package_type'] = PackageType::wherenull('deleted_at')->get();
        $data['Tags'] = Tag::where('is_active', '1')->whereNull('deleted_at')->get();
        $data['tag'] = TravelTags::where('travel_package_id', $id)->pluck('tag_id')->toArray();
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Add New Travel Package';
        
        return view('backend.travelpackages.edit', $data);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TravelPackage  $travelPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'slug' => 'required|max:100',
            'package_type_id' => 'required',
            'tag_id' => 'required',
            'location' => 'required',
            'price' => 'required',
            'sale_price' => 'required',
            'no_of_days' => 'required',
            'no_of_members' => 'required',
            'requirment' => 'required',
            'description' => 'required',
            // 'image' => 'required|image'
        // ], [
        //     'image.required' => 'Please upload valid image'
        ]);
        $TravelPackage = TravelPackage::findOrFail($id);

        $TravelPackage->updated_by = Auth::user()->id;
        $TravelPackage->added_by = Auth::user()->id;
        $TravelPackage->name = $request->name;
        $TravelPackage->slug = $request->slug;
        $TravelPackage->location = $request->location;
        $TravelPackage->price = $request->price;
        $TravelPackage->package_type_id = $request->package_type_id;
        $TravelPackage->sale_price = $request->sale_price;
        $TravelPackage->no_of_days = $request->no_of_days;
        $TravelPackage->no_of_members = $request->no_of_members;
        $TravelPackage->requirements = $request->requirment;
        $TravelPackage->description = $request->description;
        $TravelTags = TravelTags::where('travel_package_id', $id)->get();
        $TravelTags->each->delete();
        // dd("tets");
        if ($request->file('image')) {
            $request->validate([
                'image' => 'required|image|max:1024'
            ], [
                'image.required' => 'Please upload valid image'
            ]);
     
            $image = $request->file('image');
            if ($image->move('assets/frontend/images/travelpackages/', $image->getClientOriginalName())) {

                $TravelPackage->image = $image->getClientOriginalName();
                if ($TravelPackage->save()) {
                    $data['type'] = "success";
                    $data['message'] = "Travel Package Updated Successfuly!.";
                    $data['icon'] = 'mdi-check-all';

                    return redirect()->route('travel-packages.index')->with($data);
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Update Travel Package, please try again.";
                    $data['icon'] = 'mdi-block-helper';

                    return redirect()->route('travel-packages.edit', $request->id)->withInput()->with($data);
                }
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to upload image, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('travel-packages.edit', $request->id)->withInput()->with($data);
            }
        } else {
            if ($TravelPackage->save()) {
                if (isset($request->tag_id) && (count((array)$request->tag_id) > 0)) {
                    foreach ($request->tag_id as $tag_id) {
                        $TravelTags = new TravelTags();
                        $TravelTags->travel_package_id = $TravelPackage->id;
                        $TravelTags->tag_id = $tag_id;
                        $TravelTags->save();
                    }
                }

                // foreach ($request->size as $size) {
                //     $ProductAttribute = new ProductAttribute();
                //     $ProductAttribute->product_id = $product->id;
                //     $ProductAttribute->colour = $request->color;
                //     $ProductAttribute->size = $size;
                //     $ProductAttribute->save();
                // }

                $data['type'] = "success";
                $data['message'] = "Travel Package Updated Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('travel-packages.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Update Travel Package, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('travel-packages.edit', $request->id)->withInput()->with($data);
            }
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TravelPackage  $travelPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $TravelPackage = TravelPackage::find($id);

        if ($TravelPackage) {
            $TravelPackage->delete();
            $notification['type'] = "success";
            $notification['message'] = "Travel Package Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Travel Package, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function updateStatus(Request $request)
    {
        
        $update = TravelPackage::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Travel Package Activated Successfuly!' : $message = 'Travel Package Deactivated Successfuly!';

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
            $data = TravelPackage::onlyTrashed()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" class="restore" data-id="' . $row->id . '"><i title="Restore" class="fas fa-trash-restore-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('deleted_at', function ($row) {
                    return date('d-M-Y', strtotime($row->deleted_at)).'<br /> <label class="text-primary">'.Carbon::parse($row->deleted_at)->diffForHumans().'</label>';
                })
                ->addColumn('name', function($row){
                    return Str::of($row->key)->limit(100);
                })
                ->rawColumns(['action', 'deleted_at', 'key','name'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Travel Package Trash';
        return view('backend.travelpackages.trash', $data);
    }
    public function restore(Request $request)
    {
        $TravelPackage = TravelPackage::withTrashed()->find($request->id);
        if ($TravelPackage) {
            $TravelPackage->restore();
            $notification['type'] = "success";
            $notification['message'] = "Travel Package Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Travel Package, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
}
