<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function profile()
    {
        $data['profile']    = Auth::user();
        return view('backend.profile', $data);
    }

    public function updateProfileProcess(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|unique:users,email,'.Auth::user()->id
        ]);

        $user = User::find(Auth::user()->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        // $user->state = $request->state;
        // $user->address = $request->address;

        if($user->save())
        {
            $data['type'] = "success";
            $data['message'] = "Account Updated Successfuly!.";
            $data['icon'] = 'mdi-check-all';
        }
        else
        {
            $data['type'] = "danger";
            $data['message'] = "Failed to update account, please try again.";
            $data['icon'] = 'mdi-block-helper';
        }

        return redirect()->route('general.profile')->with($data);

    }

    public function changePasswordProcess(Request $request)
    {
        $request->validate([
            'old_password'      => 'required',
            'new_password'      => 'required|min:6',
            'confirm_password'  => 'required|same:new_password'
        ]);

        if(Hash::check($request->old_password, Auth::user()->password))
        {
            $user = User::where('id', Auth::user()->id)->update([
                'password' => Hash::make($request->confirm_password)
            ]);

            if($user){
                $notification['type'] = "success";
                $notification['message'] = "Password Changed Successfuly!";
            }
            else{
                $notification['type'] = "danger";
                $notification['message'] = "Failed to Change Password, please try again.";
            }
        }
        else
        {
            $notification['type'] = "danger";
            $notification['message'] = "Old password does not matched!";
        }
        return redirect()->route('general.profile')->with($notification);
    }

    public function changePictureProcess(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:1024'
        ]);

        $user = User::find($request->id);
        if(!$user){
            abort(404);
        }

        $image = $request->file('image');
        if ($image->move('assets/backend/images/users/', $image->getClientOriginalName())) {

            $user->image = $image->getClientOriginalName();
            if($user->save())
            {
                $data['type'] = "success";
                $data['message'] = "Profile Image Updated Successfuly!.";
                $data['icon'] = 'mdi-check-all';
            }
            else
            {
                $data['type'] = "danger";
                $data['message'] = "Failed to Update Profile Image, please try again.";
                $data['icon'] = 'mdi-block-helper';

            }
            return redirect()->route('general.profile')->with($data);

        }
        else{
            $data['type'] = "danger";
            $data['message'] = "Failed to upload image, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('general.profile')->with($data);
        }
    }
}
