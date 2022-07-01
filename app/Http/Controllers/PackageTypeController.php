<?php

namespace App\Http\Controllers;

use App\Models\PackageType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;



class PackageTypeController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="'.route("package-types.index").'" class="btn btn-sm btn-success">ALL Package Types</a> &nbsp;';
        $this->buttons .= '<a href="'.route("package-types.create").'" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="'.route('package-type.trash').'" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "All Package Types";
    }
   
    public function index(Request $request)
    {
        
        if($request->ajax())
        {
            $data = DB::table('package_types as b')
            ->join('users', 'b.added_by', '=', 'users.id')
            ->leftJoin('users as users_updated', 'b.updated_by', '=', 'users_updated.id')
            ->select('b.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy'  )
            ->whereNull('b.deleted_at')
            ->get();
        
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('package-types.edit', $row->id).'" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
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
                ->addColumn('name', function($row){
                    return Str::of($row->name)->limit(100);
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
                ->rawColumns(['action', 'is_active','added_by', 'updated_by','name' ])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Package Type';
     
        return view('backend.Packagetype.all', $data);
    }

  
    public function create()
    {
        
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Add New Package Type';
        
        return view('backend.packagetype.add', $data);
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
         $PackageType = new PackageType();
         $PackageType->added_by = Auth::user()->id;
         $PackageType->name = $request->name;
             if ($PackageType->save()) {
                 $data['type'] = "success";
                 $data['message'] = "Package Type Added Successfuly!.";
                 $data['icon'] = 'mdi-check-all';
 
                 return redirect()->route('package-types.index')->with($data);
             } else {
                 $data['type'] = "danger";
                 $data['message'] = "Failed to Add Package Type, please try again.";
                 $data['icon'] = 'mdi-block-helper';
 
                 return redirect()->route('package-types.create')->withInput()->with($data);
             }
          
    }

    public function show(PackageType $packageType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PackageType  $packageType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update Products';
        $data['PackageType'] = PackageType::findOrFail($id);
        return view('backend.packagetype.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PackageType  $packageType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $PackageType = PackageType::findOrFail($id);
    
        $PackageType->updated_by = Auth::user()->id;
        $PackageType->added_by = Auth::user()->id;
        $PackageType->name = $request->name;
            if ($PackageType->save()) {
                $data['type'] = "success";
                $data['message'] = "Package Type Updated Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('package-types.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Update Package Type, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('package-types.edit', $request->id)->withInput()->with($data);
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PackageType  $packageType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $PackageType = PackageType::find($id);
        if ($PackageType) {
            $PackageType->delete();
            $notification['type'] = "success";
            $notification['message'] = "Package Type Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Package Type, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function updateStatus(Request $request)
    {
        
        $update = PackageType::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Package Type Activated Successfuly!' : $message = 'Package Type Deactivated Successfuly!';

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
            $data = PackageType::onlyTrashed()->get();
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
        $data['page_title'] = 'Package Type Trash';
        return view('backend.packagetype.trash', $data);
    }
    public function restore(Request $request)
    {
        $PackageType = PackageType::withTrashed()->find($request->id);
        if ($PackageType) {
            $PackageType->restore();
            $notification['type'] = "success";
            $notification['message'] = "Package Type Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Package Type, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }

}
