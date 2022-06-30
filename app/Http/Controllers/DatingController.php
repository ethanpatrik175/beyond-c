<?php

namespace App\Http\Controllers;

use App\Models\Dating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

class DatingController extends Controller
{
    public function findYourDate()
    {
        $dating = Dating::where('user_id', Auth::user()->id)->first();

        dd($dating);
    }

    public function createAccount()
    {
        return view('frontend.dating.create-account');
    }

    public function stepOneProcess(Request $request)
    {
        $request->validate([
            'gender' => 'required',
            'age_start' => 'required|integer',
            'age_end' => 'required|integer',
            'zipcode' => 'required'
        ], [
            'age_start.required' => 'Select valid start age',
            'age_end.required' => 'Select valid end age'
        ]);

        $stepOne = new stdClass;
        $stepOne->gender = $request->gender;
        $stepOne->age_start = $request->age_start;
        $stepOne->age_end = $request->age_end;
        $stepOne->zipcode = $request->zipcode;

        $request->session()->put('datingStepOne', $stepOne);
        $request->session()->put('stepStatus', 'frontend.dating.step-two');

        return view('frontend.dating.step-two');
    }

    public function stepTwoProcess(Request $request)
    {
        $request->validate([
            'date_of_birth' => 'required'
        ], [
            'date_of_birth.required' => 'Please select valid date of birth'
        ]);

        $dob = \Carbon\Carbon::parse($request->date_of_birth)->age;
        if($dob < 18)
        {
            $data['type'] = "error";
            $data['message'] = "You must be at least 18 to use our site.";
            return response($data);
        }

        $stepTwo = new stdClass;
        $stepTwo->date_of_birth = $request->date_of_birth;

        $request->session()->put('datingStepTwo', $stepTwo);
        $request->session()->put('stepStatus', 'frontend.dating.step-three');

        return view('frontend.dating.step-three');
    }

    public function stepThreeProcess(Request $request)
    {
        $stepthree = new stdClass;
        $stepthree->have_kids = $request->have_kids;
        $stepthree->want_kids = $request->want_kids;
        $stepthree->education = $request->education;
        $stepthree->do_smoke = $request->do_smoke;

        $request->session()->put('datingStepThree', $stepthree);
        $request->session()->put('stepStatus', 'frontend.dating.step-four');

        return view('frontend.dating.step-four');
    }

    public function stepFourProcess(Request $request)
    {
        $request->validate([
            'passion' => 'nullable|max:250',
            'about' => 'nullable|max:250'
        ]);

        $stepFour = new stdClass;
        $stepFour->religion = $request->religion;
        $stepFour->passion = $request->passion;
        $stepFour->about = $request->about;

        $request->session()->put('datingStepFour', $stepFour);
        $request->session()->put('stepStatus', 'frontend.dating.final-step');

        return view('frontend.dating.final-step');
    }

    public function restoreStep(Request $request)
    {
        if(Auth::check())
        {
            $view = $request->session()->get('stepStatus');
            return view($view);
        }
        else{
            $data['type'] = "error";
            $data['message'] = "";
            return response($data);
        }
    }

    public function stepBack(Request $request)
    {
        if(Auth::check())
        {   
            if($request->backStep == "step-four")
            {
                return view('frontend.dating.step-four');
            }
            elseif($request->backStep == "step-three")
            {
                return view('frontend.dating.step-three');
            }
            elseif($request->backStep == "step-two")
            {
                return view('frontend.dating.step-two');
            }
            else if($request->backStep == "step-one")
            {
                return view('frontend.dating.step-one');
            }
            
        }
        else{
            $data['type'] = "error";
            $data['message'] = "";
            return response($data);
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'filepond' => 'required|image|max:1024'
        ]);

        if($request->hasFile('filepond'))
        {
            $image = $request->file('filepond');
            $imageName = $image->getClientOriginalName();
            if($image->move('assets/frontend/images/users/'.Auth::user()->id,$imageName))
            {
                $data['type'] = 'error';
                $data['message'] = 'Profile Image uploaded successfully';
                return response()->json($data);
            }
        }

        return response()->json(['error'=>'Failed to upload image, please try again.']);
    }

    public function removeImage(Request $request)
    {
        dd($request->all());

        $filename =  $request->get('filepond');
        $path = asset('assets/frontend/images/users/'.Auth::user()->id.'/'.$filename);
        dd($path, file_exists($path), $request->all());
        if (file_exists($path)) {
            unlink($path);
            return response()->json(['message'=>'Image has been removed successfully!']);
        }
        else{
            return response()->json(['message'=>'Error Occured!']);
        }
    }
}
