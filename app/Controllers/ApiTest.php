<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Engtuncay\Phputils8\FiCores\FiString;

class ApiTest extends ResourceController
{
  public function index()
  {
    return $this->respond(['message' => 'API Test is working (index)']);
  }

  public function test1()
  {
    $txEnv = env('fidb.profiles','');

    $result = FiString::toArray($txEnv, ',', true);

    return $this->respond(['message' => 'API Test is working (test1)', 'env' => $txEnv, 'result' => $result]);
  }
}