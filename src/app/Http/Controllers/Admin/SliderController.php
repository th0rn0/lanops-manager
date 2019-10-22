<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Storage;
use Input;
use Image;
use File;

use App\SliderImage;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SliderController extends Controller
{
    /**
     * Update Slider Image
     * @param  Request $request
     * @param  SliderImage $image
     * @return Redirect
     */
	public function update(Request $request, SliderImage $image)
	{
    	$rules = [
            'order'   => 'numeric',
        ];
        $messages = [
            'order.numeric' => 'Order must be a number',
        ];
        $this->validate($request, $rules, $messages);
		$image->order = $request->order;
        if (!$image->save()) {
            Session::flash('alert-danger', 'Cannot update Image!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully updated Image!');
        return Redirect::back();
	}

	/**
     * Delete Slider Image
     * @param  Request $request
     * @param  SliderImage $image
     * @return Redirect
     */
	public function delete(Request $request, SliderImage $image)
	{
        if (
            !Storage::disk('public')->delete(str_replace('/storage', '', $image->path)) || 
            !$image->delete()
        ) {
            Session::flash('alert-danger', 'Cannot delete Image!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully delete Image!');
        return Redirect::back();
	}

	/**
     * Upload Slider Image
     * @param  Request $request
     * @param  SliderImage $image
     * @return Redirect
     */
	public function upload(Request $request)
	{
        $rules = [
            'image.*'   => 'image',
        ];
        $messages = [
            'image.*.image' => 'Item Image must be of Image type',
        ];
        $this->validate($request, $rules, $messages);
        $destinationPath = '/storage/images/main/slider/' . $request->slider . '/'; // upload path
     	$files = Input::file('images');
        //Keep a count of uploaded files
        $fileCount = count($files);
        //Counter for uploaded files
        $uploadcount = 0;
        foreach ($files as $file) {
            $imageName  = $file->getClientOriginalName();
            Image::make($file)->save(public_path() . $destinationPath . $imageName);
         	$image = New SliderImage();
	        $image->path = $destinationPath . $imageName;
	        $image->slider_name = $request->slider;
	        $image->order = '5';
	        if (!$image->save()) {
             	Session::flash('alert-danger', 'Cannot upload Image!');
            	return Redirect::back();
	        }
            $uploadcount++;
        }
        if ($uploadcount != $fileCount) {
            Session::flash('alert-danger', 'Cannot upload Image!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully uploaded Image!');
        return Redirect::back();
	}
}
