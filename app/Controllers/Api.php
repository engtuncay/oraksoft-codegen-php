<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Api extends ResourceController
{
  public function index()
  {
    return $this->respond(['message' => 'API is working (index)']);
  }

  public function testpost()
  {
    $data = $this->request->getJSON();
    //$musTXTEMAIL = $this->request->getJSON()->musTXTEMAIL;
    return $this->respond(['result' => ['refValue' => 'Gelen veri: ' . json_encode($data,JSON_UNESCAPED_UNICODE)]]);
  }

  public function testget()
  {
    return $this->respond(['message' => 'API is working (testget)']);
  }
}
