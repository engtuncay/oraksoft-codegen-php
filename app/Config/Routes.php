<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
//$routes->post('/codegen', 'Codegen::index');
$routes->match(['get', 'post'], 'codegen', 'CodegenCont::index');
