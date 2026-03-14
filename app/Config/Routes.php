<?php

use App\Controllers\Api;
use App\Controllers\ApiConfig;
use App\Controllers\ApiTest;
use App\ThirdParty\AppCiHelper;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
//routes->post('/codegen', 'Codegen::index');
//$routes->match(['GET', 'POST'], 'codegen', 'CodegenCont::index');

// ---------API Routes
//$routes->post('/testpost', 'Api::testpost');
//$routes->get('/testget', 'Api::testpost');

AppCiHelper::addRoutePost($routes, 'genCode', Api::class);
AppCiHelper::addRoutePost($routes, 'execCmd', Api::class);

AppCiHelper::addRouteGet($routes, 'getDbProfiles', ApiConfig::class);
AppCiHelper::addRoutePost($routes, 'getEntityList', Api::class);

// --------Test Routes
AppCiHelper::addRouteGet($routes, 'testget', Api::class);
AppCiHelper::addRouteGet($routes, 'test', Api::class);
AppCiHelper::addRouteGet($routes, 'test1', ApiTest::class);
AppCiHelper::addRoutePost($routes, 'testpost', Api::class);
