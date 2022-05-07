<?php

namespace App\Jobs;

use App\Models\Datas;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Files;
use App\Models\DataFiles;
use Illuminate\Support\Facades\DB;

class UploadCvsPrcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $header;
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
        // $this->header = $header;
    }

    public function handle()
    {
        if($this->data > 0){
            $files = DB::table('files')
                            ->where('id', '=', $this->data)
                            ->first();
            if (!empty($files)){
            //Process the files:
                if(($handle = fopen(storage_path()."/app/".$files->path, "r")) !== FALSE){
                    $header = true;
                    $count = 0;
                    while ($csvLine = fgetcsv($handle, 1000, ",")) {
                        if ($header) {
                            $header = false;
                        } else {
                            $id = isset($csvLine[0])?$this->remove_bs($csvLine[0]):null;
                            if($id != null){
                                $check = DB::table('datas')->where('unique_key', '=',$id)->first();
                                if(empty($check)){
                                    try{
                                        DB::table('datas')->insert([
                                            'unique_key' => $id,
                                            'product_title' => isset($csvLine[1])?$this->remove_bs($csvLine[1]):'-',
                                            'product_description' => isset($csvLine[2])?$this->remove_bs($csvLine[2]):'-',
                                            'style' => isset($csvLine[3])?$this->remove_bs($csvLine[3]):' ',
                                            'sanmar_mainframe_color' => isset($csvLine[29])?$this->remove_bs($csvLine[29]):'-',
                                            'size' => isset($csvLine[19])?$this->remove_bs($csvLine[19]):' ',
                                            'color_name' => isset($csvLine[20])?$this->remove_bs($csvLine[20]):'-',
                                            'piece_price' => isset($csvLine[22])?$this->remove_bs($csvLine[22]):0,
                                            ]);
                                    }catch (\Exception $e){
                                        var_dump($e->getMessage());
                                    }
                                }else{
                                    try{
                                        DB::table('datas')->where('unique_key', '=',$id)->update([
                                            'product_title' => isset($csvLine[1])?$this->remove_bs($csvLine[1]):'-',
                                            'product_description' => isset($csvLine[2])?$this->remove_bs($csvLine[2]):'-',
                                            'style' => isset($csvLine[3])?$this->remove_bs($csvLine[3]):' ',
                                            'sanmar_mainframe_color' => isset($csvLine[29])?$this->remove_bs($csvLine[29]):'-',
                                            'size' => isset($csvLine[19])?$this->remove_bs($csvLine[19]):' ',
                                            'color_name' => isset($csvLine[20])?$this->remove_bs($csvLine[20]):'-',
                                            'piece_price' => isset($csvLine[22])?$this->remove_bs($csvLine[22]):0,
                                            ]);
                                        }catch (\Exception $exception){
                                            $count++;
                                            
                                            echo 'update'.$count;
                                        }
                                }
                                
                            }
                            
                        }
                    }
                    fclose($handle);
                    $files = DB::table('files')
                            ->where('id', '=', $this->data)
                            ->update([
                                'status' => 'completed' 
                            ]);
                }else{
                    $files = DB::table('files')
                            ->where('id', '=', $this->data)
                            ->update([
                                'status' => 'failed' 
                            ]);
                }
            }
        }
    }

    protected function remove_bs($Str) {  
        $StrArr = str_split($Str); $NewStr = '';
        foreach ($StrArr as $Char) {    
          $CharNo = ord($Char);
          if ($CharNo == 163) { $NewStr .= $Char; continue; } // keep £ 
          if ($CharNo > 31 && $CharNo < 127) {
            $NewStr .= $Char;    
          }
        }  
        return $NewStr;
      }
}
