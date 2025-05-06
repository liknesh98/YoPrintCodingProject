<?php

namespace App\Imports;

use App\Models\Imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;

class ProductsImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
     
        $header =$rows->first(); 
        $rows = $rows->skip(1); 
        Log::info('Rows received:', ['count' => $rows->count()]);
        foreach ($rows as $row) 
        {    
            Log::info('Row:', $row->toArray());
            try {
            $rawRow = array_combine($header->toArray(),$row->toArray());
            $rowData =array_map(function ($value){
                return mb_convert_encoding($value,'UTF-8','UTF-8');
            },$rawRow);
           
                Imports::updateOrInsert(
                    ['UNIQUE_KEY'=> $rowData['UNIQUE_KEY']],
                    $rowData
                );
            } catch (\Throwable $e) {
                Log::error('Failed to insert row', [
                    'row' => $row->toArray(),
                    'error' => $e->getMessage()
                ]);
            }
           
        }
    }
}
