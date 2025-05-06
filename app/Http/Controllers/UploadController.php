<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImportService;

class UploadController extends Controller
{
    public function index () {
        return view('upload') ; 
    }     
    public function upload(Request $request)
{
    $request->validate(['file' => 'required|file|mimes:csv']);

    $upload = ImportService::handle($request->file('file'), 'products');

    return back()->with('success', 'File uploaded and queued!');
}
}
