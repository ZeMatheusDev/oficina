<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProtectedDownloads extends Controller
{
    public function showJobImage($filename)
    {
        $filename = str_replace('-', '/', $filename);
        //check image exist or not
        $exists = Storage::exists($filename);

        if ($exists) {

            //get content of image
            $content = Storage::get($filename);

            //get mime type of image
            $mime = Storage::mimeType($filename);
            //prepare response with image content and response code
            $response = Response()->make($content, 200);
            //set header 
            $response->header("Content-Type", $mime);
            // return response
            return $response;
        } else {
            abort(404);
        }
    }

    public function download($path)
    {
              
        $path = str_replace('-', '/', $path);
 
        try {
            return Storage::download($path);
        } catch (Exception $e) {
            return redirect()->back();
        }
   

    }
    public function download2($path)
    {
     
        try {
            return Storage::download('relatorio/'.$path);

        } catch (Exception $e) {
            return redirect()->back();
        }
    }
}
