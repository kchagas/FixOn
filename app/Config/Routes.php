<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::store');


$routes->group('dashboard', ['filter' => 'auth'], function($routes){
$routes->get('/', 'Dashboard::index');
$routes->get('admin', 'Dashboard::adminArea', ['filter' => "role:admin"]);
});
