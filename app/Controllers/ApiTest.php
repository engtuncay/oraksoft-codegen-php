<?php
namespace App\Controllers;

use Codegen\OcgConfigs\OcgLogger;
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

    $fiPdo = FiPdo::buiWithProfile("mssql");

    OcgLogger::info("ApiTest::test1 called. FiPdo instance created. Conn:". $fiPdo->getBoConnection());

    $fiQuery = new FiQuery();
    $sql = "SELECT * FROM EncFirma"; 
    $fiQuery->setSql($sql);

    $fdr = $fiPdo->selectFkb($fiQuery);

    //OcgLogger::info("ApiTest::test1 called. Result: " . print_r($fdr->genArrResponse(), true));
    
    //['data' => print_r($fdr,true)]

    return $this->respond($fdr->genArrResponse(), 200);
  }
}