<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
//$routes->post('/codegen', 'Codegen::index');
$routes->match(['GET', 'POST'], 'codegen', 'CodegenCont::index');

$routes->post('/testpost', 'Api::testpost');
$routes->get('/testget', 'Api::testpost');
