<?php
namespace App\Controllers;

use Codegen\OcdConfig\OcgLogger;
use CodeIgniter\RESTful\ResourceController;
use Engtuncay\Phputils8\FiApps\FiAppConfig;
use Engtuncay\Phputils8\FiCores\FiString;
use Engtuncay\Phputils8\FiDbs\FiQuery;
use Engtuncay\Phputils8\FiPdos\FiPdo;
use Engtuncay\Phputils8\FiPdos\FiPdoExt;
use Engtuncay\Phputils8\FiPdos\FiPdow;

class ApiTest extends ResourceController
{
  public function index()
  {
    return $this->respond(['message' => 'API Test is working (index)']);
  }

  public function test1()
  {
    //$ocgAppConfig = new OcgCLogger
    //FiAppConfig::$fiConfig->getProfile()
    $fiPdo = FiPdo::buiWithProfile("");

    $fiQuery = new FiQuery();
    $sql = "SELECT * FROM settings"; 
    $fiQuery->setSql($sql);

    $fdr = $fiPdo->selectFkb($fiQuery);

    return $this->respond(['data' => print_r($fdr,true)]);
  }
}