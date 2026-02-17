<?php

use App\Controllers\Api;
use App\Controllers\ApiConfig;
use App\Controllers\ApiTest;
use Codegen\OcgHelpers\CdgciHelper;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
//routes->post('/codegen', 'Codegen::index');
//$routes->match(['GET', 'POST'], 'codegen', 'CodegenCont::index');

// ---------API Routes
//$routes->post('/testpost', 'Api::testpost');
CdgciHelper::addRoutePost($routes, 'genCode', Api::class);

//$routes->get('/testget', 'Api::testpost');
CdgciHelper::addRouteGet($routes, 'getDbProfiles', ApiConfig::class);
CdgciHelper::addRoutePost($routes, 'getEntityList', Api::class);

// --------Test Routes
CdgciHelper::addRouteGet($routes, 'testget', Api::class);
CdgciHelper::addRouteGet($routes, 'test', Api::class);
CdgciHelper::addRouteGet($routes, 'test1', ApiTest::class);
CdgciHelper::addRoutePost($routes, 'testpost', Api::class);
