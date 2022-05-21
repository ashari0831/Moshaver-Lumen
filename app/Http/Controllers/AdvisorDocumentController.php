<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Advisor, Advisor_document};
use Illuminate\Support\Facades\File;

class AdvisorDocumentController extends Controller
{
    public function show($advisor){
        $docs = Advisor::find($advisor)->docs;
        // dd($docs);
        return response()->json([$docs]);
    }

    public function download($file){
        $file_path = Advisor_document::find($file)->doc_file;
        return response()->download(storage_path(DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $file_path ));
    }

    public function store($file){
        if(request()->hasFile('doc_file')){
            $picName = request()->file('doc_file')->getClientOriginalName();
            $picName = uniqid() . '_' . $picName;
            $filePath = request()->doc_file->storeAs('uploads' . DIRECTORY_SEPARATOR . 'documents', $picName); 
            // $path = public_path('uploads' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR);
            // File::makeDirectory($path, 0777, true, true);
            // request()->file('doc_file')->move($path, $picName);

            Advisor_document::create([
                'advisor_id' => $advisor,
                'doc_file' => $filePath
            ]);
            return response()->json("true");
        }
        return response()->json("failed", 400);
    }

    public function destroy($file){
        $file = Advisor_document::find($file);
        $relative_path = $file->doc_file;
        File::delete(storage_path(DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $relative_path));
        
        return response()->json($file->delete(), 204);
    }

    public function update(){
        $file = Advisor_document::find(request()->file_id);
        
        if($file->update([
            'confirmed_at' => request()->is_confirmed
        ])){
            $file = Advisor_document::find(request()->file_id);
            return response()->json([$file], 200);
        }
        return response()->json("failed", 400);
    }
}
