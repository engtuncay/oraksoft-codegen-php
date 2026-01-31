<?php

namespace Codegen\CdgHelpers;

use CodeIgniter\Router\RouteCollection;
use Engtuncay\Phputils8\FiCiHelpers\FiCiHelper;

class CdgciHelper
{
  public static function addRouteGet(RouteCollection $routes, string $txRouteName, mixed $controller)
  {
    $fiRouteEnt = FiCiHelper::getRouteEnt($txRouteName, $controller);
    //log_message('info', 'CdgciHelper::addRouteGet - ' . $fiRouteEnt->getTxRelUrl() . ' -> ' . $fiRouteEnt->getClassMethodTarget());
    $routes->get($fiRouteEnt->getTxRelUrl(), $fiRouteEnt->getClassMethodTarget());
  }

  public static function addRoutePost(RouteCollection $routes, string $txRouteName, mixed $controller)
  {
    $fiRouteEnt = FiCiHelper::getRouteEnt($txRouteName, $controller);
    //log_message('info', 'CdgciHelper::addRoutePost - ' . $fiRouteEnt->getTxRelUrl() . ' -> ' . $fiRouteEnt->getClassMethodTarget());
    $routes->post($fiRouteEnt->getTxRelUrl(), $fiRouteEnt->getClassMethodTarget());
  }
  
}
