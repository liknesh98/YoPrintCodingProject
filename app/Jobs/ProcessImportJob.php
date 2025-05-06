<?php

namespace App\Jobs;
use App\Imports\ProductsImport;
use App\Models\FileUpload; 
use App\Models\Imports; 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;
use Maatwebsite\Excel\Excel; 

class ProcessImportJob implements ShouldQueue
{
    use Queueable,Dispatchable,InteractsWithQueue,SerializesModels;

    public $uploadId ; 
    /**
     * Create a new job instance.
     */
    public function __construct($uploadId)
    {
        $this->uploadId = $uploadId; 
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $upload = FileUpload::find($this->uploadId); 
        $upload->update(['status' => 'processing']); 

        $path = storage_path('app/'.$upload->file_path);

            if($upload->type === 'imports'){
                Excel::import(new ProductsImport,$path);
            }

            //Other model processors can be added here for scaling purposes. 
       
        $upload->update(['status'=>'completed']);
    }

    public function failed(\Throwable $e)
    {
        FileUpload::where('id', $this->uploadId)->update(['status'=>'failed']);
    }
}
