<?php

namespace App\Controllers;

class Codegen extends BaseController
{
    public function index()
    {
        // POST verilerini işle (formdan veri gelirse)
        if ($this->request->getMethod() === 'post') {
            return $this->processCodegen();
        }
        $data = [];
        // GET isteği için codegen sayfasını göster
        return view('codegen', $data);
    }
    
    private function processCodegen()
    {
        // Form verilerini al
        $selCsharp = $this->request->getPost('selCsharp');
        $selTs = $this->request->getPost('selTs');
        $selPhp = $this->request->getPost('selPhp');
        $selJava = $this->request->getPost('selJava');





        $data = [];
        // [[../Views/codegen.php]] 
        return view('codegen',$data);
        
        // Dosya yükleme işlemi
        // $excelFile = $this->request->getFile('excelFile');
        
        // if ($excelFile && $excelFile->isValid() && !$excelFile->hasMoved()) {
        //     // Dosya işleme mantığı burada olacak
        //     $uploadPath = FCPATH . 'uploads/';
        //     $fileName = $excelFile->getRandomName();
        //     $excelFile->move($uploadPath, $fileName);
            
        //     // Code generation işlemi burada yapılacak
        //     // ...
            
        //     // Sonuç sayfasını göster veya JSON response döndür
        //     return $this->response->setJSON([
        //         'status' => 'success',
        //         'message' => 'Code generation completed',
        //         'file' => $fileName
        //     ]);
        // }
        
        // return $this->response->setJSON([
        //     'status' => 'error',
        //     'message' => 'File upload failed'
        // ]);
    }
}
