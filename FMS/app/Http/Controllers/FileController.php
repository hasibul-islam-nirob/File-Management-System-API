<?php

namespace App\Http\Controllers;

use App\Models\FileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{


    function allImages(){
        $result = FileModel::orderBy('id','desc')->get();
        return response()->json(['images' => $result],200);
    }



    function create(Request $request){

        $fileExt = $request->file('fileName')->getClientOriginalExtension();
        if( $fileExt=='js'){
            return 0;
        }else{
            $path = $request->file('fileName')->store('public');
            $host = "http://".$_SERVER['HTTP_HOST'];
            $fileName = (explode("/",$path))[1];
            $location = $host."/storage/".$fileName;

            date_default_timezone_set('Asia/Dhaka');
            $date = date("d-m-Y");
            $time = date("h:i:sa");

            $result = FileModel::insert([
                'file_url'=>$location,
                'file_name'=>$fileName,
                'create_time'=>$time,
                'create_date'=>$date,
            ]);
            if ($result == true){
                return 1;
            }else{
                return 0;
            }
        }

    }



    function update(Request $request){
        $id = $request->input('id');
        $getFile = FileModel::where('id',$id)->first();
        $oldFileUrl = $getFile['file_url'];
        $oldFileName = $getFile['file_name'];

        $newFile = $request->file('fileName');

        date_default_timezone_set('Asia/Dhaka');
        $date = date("d-m-Y");
        $time = date("h:i:sa");

        if ($newFile != null) {

            if (Storage::disk('public')->exists($oldFileName)) {
                Storage::disk('public')->delete($oldFileName);

                $host = "http://" . $_SERVER['HTTP_HOST'];
                $path = $request->file('fileName')->store('public');
                $fileName = (explode("/", $path))[1];
                $location = $host . "/storage/" . $fileName;

                $result = FileModel::where('id', $id)->update([
                    'file_url'=>$location,
                    'file_name'=>$fileName,
                    'update_time'=>$time,
                    'update_date'=>$date,
                ]);
                if ($result == true) {
                    return 1;
                } else {
                    return 0;
                }

            } else {

                $result = FileModel::where('id', $id)->update([
                    'file_url'=>$oldFileUrl,
                    'file_name'=>$oldFileName,
                    'update_time'=>$time,
                    'update_date'=>$date,
                ]);
                if ($result == true) {
                    return 1;
                } else {
                    return 0;
                }
            }

        } else {
            $result = FileModel::where('id', $id)->update([
                'file_url'=>$oldFileUrl,
                'file_name'=>$oldFileName,
                'update_time'=>$time,
                'update_date'=>$date,
            ]);
            if ($result == true) {
                return 1;
            } else {
                return 0;
            }
        }

    }



}
