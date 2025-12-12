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


/* =======================
   ROTAS - ESTOQUE / PEÇAS
======================= */
$routes->get('pecas', 'Pecas::index'); // futuramente listagem
$routes->get('pecas/cadastrar', 'Pecas::cadastrar');
$routes->post('pecas/salvar', 'Pecas::salvar');

$routes->get('pecas/editar/(:num)', 'Pecas::editar/$1');
$routes->post('pecas/atualizar/(:num)', 'Pecas::atualizar/$1');

$routes->get('pecas/excluir/(:num)', 'Pecas::excluir/$1');




/* =======================
   ROTAS - MOVIMENTAÇÕES
======================= */

$routes->get('movimentacoes/entrada', 'Movimentacoes::entrada');
$routes->post('movimentacoes/salvarEntrada', 'Movimentacoes::salvarEntrada');

$routes->get('movimentacoes/saida', 'Movimentacoes::saida');
$routes->post('movimentacoes/salvarSaida', 'Movimentacoes::salvarSaida');

/* =======================
   ROTAS - USUÁRIOS
======================= */

