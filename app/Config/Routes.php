<?php

use App\Controllers\Api;
use Codegen\CdgHelpers\CdgciHelper;
use CodeIgniter\Router\RouteCollection;
use Config\App;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/codegen', 'Codegen::index');
//$routes->match(['GET', 'POST'], 'codegen', 'CodegenCont::index');

//$routes->post('/testpost', 'Api::testpost');
CdgciHelper::addRoutePost($routes, 'testpost', Api::class);

//$routes->get('/testget', 'Api::testpost');
CdgciHelper::addRouteGet($routes, 'testget', Api::class);

CdgciHelper::addRoutePost($routes, 'getEntityList', Api::class);

CdgciHelper::addRoutePost($routes, 'genCode', Api::class);
