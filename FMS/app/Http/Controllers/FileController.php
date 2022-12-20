<?php

namespace App\Http\Controllers;

use App\Models\FileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{


    function getImageBetween(Request $request){
        $numMin = $request->input('startPos');
        $numMax = $request->input('endPos');

        $result = FileModel::where('id','>=' ,$numMin)->where('id','<=',$numMax)->get();
        return response()->json(['images' => $result],200);
    }

    function getLimitImageAsc(Request $request){
        $number = $request->input('number');
        $result = FileModel::orderBy('id','asc')->limit($number)->get();
        return response()->json(['images' => $result],200);
    }

    function getLimitImageDesc(Request $request){
        $number = $request->input('number');
        $result = FileModel::orderBy('id','desc')->limit($number)->get();
        return response()->json(['images' => $result],200);
    }

    function getFirstImage(){
        $result = FileModel::orderBy('id','asc')->first();
        return response()->json(['images' => $result],200);
    }

    function getLastImage(){
        $result = FileModel::orderBy('id','desc')->first();
        return response()->json(['images' => $result],200);
    }

    function allImages(){
        $result = FileModel::orderBy('id','desc')->get();
        return response()->json(['images' => $result],200);
    }

    function delete(Request $request){
        $id = $request->input('id');
        $getFile = FileModel::where('id',$id)->first();
        $oldFileName = $getFile['file_name'];

        if ( (Storage::disk('public')->exists($oldFileName)) ) {

            Storage::disk('public')->delete($oldFileName);
            $result = FileModel::where('id',$id)->delete();
            if ($result == true) {
                return 1;
            } else {
                return 0;
            }

        }else{
            $result = FileModel::where('id',$id)->delete();
            if ($result == true) {
                return 1;
            } else {
                return 0;
            }
        }

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
        $newFile = $request->file('fileName');

        $getFile = FileModel::where('id',$id)->first();
        $oldFileUrl = $getFile['file_url'];
        $oldFileName = $getFile['file_name'];

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
