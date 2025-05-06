<?php

namespace App\Services; 

use App\Models\FileUpload; 
use App\Jobs\ProcessImportJob; 

class ImportService
{
    public static function handle ($file, $type)
    {
        $path = $file->store('uploads');

        $upload = FileUpload::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path, 
            'type'      => $type, 
            'status'    => 'pending', 
        ]);

        ProcessImportJob::dispatch($upload->id); 

        return $upload ;
    }

}
