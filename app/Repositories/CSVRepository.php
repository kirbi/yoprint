<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Storage;
//model
use App\Models\Files;

class CSVRepository {

    /**
     * CSVRepository constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param $file
     * @param $extension
     * @return mixed
     */
    public function uploadCSV($file, $extension, $filename){
        return $this->upload($file, $extension, $filename);
    }

    /**
     * @param $file
     * @param $extension
     * @return mixed 
     */
    private function upload($file, $extension, $filename){
        $path = Storage::putFileAs("FilesCSV", $file, uniqid().'_'.$filename);
        try{
            $myModel = new Files();
            $myModel->path = $path;
            $myModel->name_file = uniqid().'_'.$filename;
            $myModel->status = "pending";
            $myModel->time_uploaded = date('Y-m-d H:i:s');
           $saved = $myModel->save(); // returns false
            return $myModel->id;
            
         }
         catch(\Exception $e){
            // do task when error$myModel = new Files();
            // $saved =  $e->getMessage();   // insert query
            return 0;
         }
        //  var_dump($saved);die();
        
    }
}