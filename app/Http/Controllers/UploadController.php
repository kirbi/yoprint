<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Jobs\UploadCvsPrcess;
use App\Repositories\CSVRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    public function index()
    {
        $data = Files::all();
        return view('upload/index',compact('data'));
    }

    // public function upload_csv_records(Request $request)
    // {
    //     if( $request->has('csv') ) {

    //         $csv    = file($request->csv);
    //         $chunks = array_chunk($csv,1000);
    //         $header = [];
    //         $batch  = Bus::batch([])->dispatch();

    //         foreach ($chunks as $key => $chunk) {
    //         $data = array_map('str_getcsv', $chunk);
    //             if($key == 0){
    //                 $header = $data[0];
    //                 unset($data[0]);
    //             }
    //             $batch->add(new UploadCvsPrcess($data, $header));
    //         }
    //         return $batch;
    //     }
    //     return "please upload csv file";
    // }

    public function upload(Request $request,CSVRepository $CSVRepository)
    {
        try{
            if($file = $request->has('csv') ) {
                $file   = $request->csv;
                $filename = $file->getClientOriginalName();
                $extension = strtolower($file->getClientOriginalExtension());
                if ($extension !== 'csv'){
                    $errors['file'] = 'This is not a .csv file!';
                    return redirect()->back()->withInput()->withErrors($errors);
                }
                
                $data = $CSVRepository->uploadCSV($file, $extension, $filename); 
                
                $message = array(
                    'type' => 'success',
                    'text' => 'Your file has been uploaded! Status will be update when processing is complete!',
                    'title' => 'Success',
                );
                
                $batch  = Bus::batch([])->dispatch();
                $batch->add(new UploadCvsPrcess($data));
                session()->flash('message', $message);
                // if($data > 0){
                //     $files = DB::table('files')
                //                     ->where('id', '=', $data)
                //                     ->first();
                //     if (!empty($files)){
                //     //Process the files:
                //         if(($handle = fopen(storage_path()."/app/".$files->path, "r")) !== FALSE){
                //             $header = true;
                //             $count = 0;
                //             while ($csvLine = fgetcsv($handle, 1000, ",")) {
                //                 if ($header) {
                //                     $header = false;
                //                 } else {
                //                     $id = isset($csvLine[0])?$this->remove_bs($csvLine[0]):null;
                //                     if($id != null){
                //                         $check = DB::table('datas')->where('unique_key', '=',$id)->first();
                //                         echo 'check ';
                //                         if(empty($check)){
                //                             try{
                //                                 DB::table('datas')->insert([
                //                                     'unique_key' => $id,
                //                                     'product_title' => isset($csvLine[1])?$this->remove_bs($csvLine[1]):'-',
                //                                     'product_description' => isset($csvLine[2])?$this->remove_bs($csvLine[2]):'-',
                //                                     'style' => isset($csvLine[3])?$this->remove_bs($csvLine[3]):' ',
                //                                     'sanmar_mainframe_color' => isset($csvLine[29])?$this->remove_bs($csvLine[29]):'-',
                //                                     'size' => isset($csvLine[19])?$this->remove_bs($csvLine[19]):' ',
                //                                     'color_name' => isset($csvLine[20])?$this->remove_bs($csvLine[20]):'-',
                //                                     'piece_price' => isset($csvLine[22])?$this->remove_bs($csvLine[22]):0,
                //                                     ]);
                //                             }catch (\Exception $e){
                //                                 var_dump($e->getMessage());die();
                //                             }
                //                         }else{
                //                             try{
                //                                 DB::table('datas')->where('unique_key', '=',$id)->update([
                //                                     'product_title' => isset($csvLine[1])?$this->remove_bs($csvLine[1]):'-',
                //                                     'product_description' => isset($csvLine[2])?$this->remove_bs($csvLine[2]):'-',
                //                                     'style' => isset($csvLine[3])?$this->remove_bs($csvLine[3]):' ',
                //                                     'sanmar_mainframe_color' => isset($csvLine[29])?$this->remove_bs($csvLine[29]):'-',
                //                                     'size' => isset($csvLine[19])?$this->remove_bs($csvLine[19]):' ',
                //                                     'color_name' => isset($csvLine[20])?$this->remove_bs($csvLine[20]):'-',
                //                                     'piece_price' => isset($csvLine[22])?$this->remove_bs($csvLine[22]):0,
                //                                     ]);
                //                                 }catch (\Exception $exception){
                //                                     $count++;
                                                    
                //                                 echo 'update'.$count;
                //                                 }
                //                         }
                                        
                //                     }
                                    
                //                 }
                //             }
                //             fclose($handle);
                //             $files = DB::table('files')
                //                     ->where('id', '=', $data)
                //                     ->update([
                //                         'status' => 'completed' 
                //                     ]);
                //             echo 'done';
                //             die();
                //         }else{
                //             $files = DB::table('files')
                //                     ->where('id', '=', $data)
                //                     ->update([
                //                         'status' => 'failed' 
                //                     ]);
                //         }
                //     }
                // }
                return redirect('upload');
            }
        }catch (\Exception $exception){
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Internal Server Error');
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