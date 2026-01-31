<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Services;

class Api extends ResourceController
{
  public function index()
  {
    return $this->respond(['message' => 'API is working (index)']);
  }

  public function testpost()
  {
    $request = Services::request();
    $data = $request->getJSON();
    //$musTXTEMAIL = $this->request->getJSON()->musTXTEMAIL;
    return $this->respond(['result' => ['refValue' => 'Gelen veri: ' . json_encode($data,JSON_UNESCAPED_UNICODE)]]);
  }

  public function testget()
  {
    return $this->respond(['message' => 'API is working (testget)']);
  }

  public function testForm()
  {
    $request = Services::request();
    $file = $request->getFile('excelFile');

    if ($file && $file->isValid() && !$file->hasMoved()) {
      $originalName = $file->getClientName();
      return $this->respond(['filename' => $originalName]);
    }

    return $this->respond(['error' => 'Dosya yok veya geçersiz'], 400);
  }

}
