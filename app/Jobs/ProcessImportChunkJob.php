<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels; 
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Batchable;
use App\Models\Imports;

class ProcessImportChunkJob implements ShouldQueue
{
    use Batchable,Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $header;
    public $chunk;
    public $tries = 5;
    public $retryAfter = 10; // seconds
    public $backoff = 15; // delay before retrying after failure

    /**
     * Create a new job instance.
     */
    public function __construct($header, $chunk)
    {
          $this->header = $header;
          $this->chunk = $chunk;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->chunk as $row) {
            $rowData = array_combine($this->header, $row);
            $rowData = array_map(fn($v) => mb_convert_encoding($v, 'UTF-8', 'UTF-8'), $rowData);

            Imports::updateOrInsert(
                ['UNIQUE_KEY'=> $rowData['UNIQUE_KEY']],
                $rowData
            );
        }
    }
}
