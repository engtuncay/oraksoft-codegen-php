<?php
namespace App\ThirdParty;

use CodeIgniter\Router\RouteCollection;
use Engtuncay\Phputils8\FiCiHelpers\FiCiHelper;

class AppCiHelper
{
  public static function addRouteGet(RouteCollection $routes, string $txRouteName, mixed $controller)
  {
    $fiRouteEnt = FiCiHelper::getRouteEnt($txRouteName, $controller);
    //log_message('info', 'AppCiHelper::addRouteGet - ' . $fiRouteEnt->getTxRelUrl() . ' -> ' . $fiRouteEnt->getClassMethodTarget());
    $routes->get($fiRouteEnt->getTxRelUrl(), $fiRouteEnt->getClassMethodTarget());
  }

  public static function addRoutePost(RouteCollection $routes, string $txRouteName, mixed $controller)
  {
    $fiRouteEnt = FiCiHelper::getRouteEnt($txRouteName, $controller);
    //log_message('info', 'AppCiHelper::addRoutePost - ' . $fiRouteEnt->getTxRelUrl() . ' -> ' . $fiRouteEnt->getClassMethodTarget());
    $routes->post($fiRouteEnt->getTxRelUrl(), $fiRouteEnt->getClassMethodTarget());
  }
  
}
