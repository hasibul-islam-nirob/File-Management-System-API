<?php

namespace App\Http\Controllers;

use App\Models\FileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    function create(Request $request){

        $path = $request->file('fileName')->store('public');

        $host = "http://".$_SERVER['HTTP_HOST'];
        $fileName = (explode("/",$path))[1];
        $location = $host."/storage/".$fileName;

        $result = FileModel::insert([
            'file_url'=>$location,
            'file_name'=>$fileName,
            'user_id'=>'1'
        ]);
        if ($result == true){
            return 1;
        }else{
            return 0;
        }


    }


    function allImages(){
        $result = FileModel::orderBy('id','desc')->get();
        return response()->json(['images' => $result]);
    }



}
