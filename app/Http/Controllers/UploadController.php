<?php

namespace App\Http\Controllers;
use App\Models\FileUpload;
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

    $upload = ImportService::handle($request->file('file'), 'imports');

    return back()->with('success', 'File uploaded and queued!');
    }

    public function status()
    {
        $uploads = FileUpload::latest()->take(10)->get();

        return response()->json([
            'data' => $uploads->map(function ($upload){
                return [
                    'file_name' => $upload->file_name,
                    'created_at' => $upload->created_at->format('Y-m-d H:i:s'),
                    'human_time' => $upload->created_at->diffForHumans(),
                    'status' => ucfirst($upload->status),
                ];
            }),
        ]);
    }


}
