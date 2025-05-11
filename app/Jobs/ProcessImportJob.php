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
use Illuminate\Support\Facades\Bus;

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

        
            Log::info($upload->type);
            if($upload->type === 'imports'){
                try{

                    Log::info('Starting import from: ' . $upload->file_path);
                    $path = storage_path('app/'.$upload->file_path);
                    $rows = Excel::toCollection(null,$upload->file_path,'local')[0];
                    $header = $rows->pull(0);
                    $chunks = $rows->chunk(500);
                    $delaySeconds = 2;
                    $jobs = $chunks->map(function ($chunk, $i) use ($header, $delaySeconds) {
                        return (new ProcessImportChunkJob($header->toArray(), $chunk->values()->toArray()))
                            ->delay(now()->addSeconds($i * 10));
                    });
                    Bus::batch($jobs)->then(function () use ($upload) {
                        $upload->update(['status' => 'completed']);
                    })->catch(function () use ($upload) {
                        $upload->update(['status' => 'failed']);
                    })->dispatch();
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

    }

    public function failed(\Throwable $e)
    {
        FileUpload::where('id', $this->uploadId)->update(['status'=>'failed']);
    }
}
