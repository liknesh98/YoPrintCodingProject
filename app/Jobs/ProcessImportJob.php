<?php

namespace App\Jobs;
use App\Imports\ProductsImport;
use App\Models\FileUpload; 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels; 
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessImportJob implements ShouldQueue
{
    use Queueable,Dispatchable,InteractsWithQueue,SerializesModels;
    public $timeout = 300;

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
        $upload->update(['status' => 'processed']); 

        $path = storage_path('app/'.$upload->file_path);
            Log::info($upload->type);
            if($upload->type === 'imports'){
                try{

                    Log::info('Starting import from: ' . $upload->file_path);
                    Excel::import(new ProductsImport,  $upload->file_path, 'local');
                }
                catch (\Throwable $e) 
                {
                    \Log::error('Import job failed', [
                        'error' => $e->getMessage(),
                        'upload_id' => $this->uploadId,
                        'path' => $upload->file_path,
                    ]);
                
                    $upload->update(['status' => 'failed']);
                
                    throw $e; // rethrow so Horizon can mark it as failed
                }
                
            }

            //Other model processors can be added here for scaling purposes. 
        Log::info('✅ Import completed for: ' . $upload->file_path);
        $upload->update(['status'=>'completed']);
    }

    public function failed(\Throwable $e)
    {
        FileUpload::where('id', $this->uploadId)->update(['status'=>'failed']);
    }
}
