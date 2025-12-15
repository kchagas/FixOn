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

/*=======================
   ROTAS - ESTOQUE do dashboard de estoque
======================= */

$routes->get('estoque/dashboard/(:num)', 'EstoqueDashboard::index/$1');
$routes->get('estoque/dashboard', 'EstoqueDashboard::index');


/*=======================
   ROTAS - RELATÓRIOS
======================= */


// RELATÓRIO DE MOVIMENTAÇÕES DE ESTOQUE
$routes->get('estoque/relatorio', 'Estoque::relatorioMov');


// DETALHES DA PEÇA NO RELATÓRIO
$routes->get('pecas/detalhes/(:num)', 'Pecas::detalhes/$1');

// AJUSTE DE ESTOQUE
$routes->post('estoque/ajustar', 'Movimentacoes::ajustarEstoque');

// =======================================
// RELATÓRIO DE MOVIMENTAÇÕES - PDF
// =======================================
$routes->get('estoque/relatorio/pdf', 'Estoque::relatorioMovPdf');
// =======================================

// categorias
$routes->get('categorias', 'Categorias::index');
$routes->get('categorias/cadastrar', 'Categorias::cadastrar');
$routes->post('categorias/salvar', 'Categorias::salvar');
$routes->get('categorias/editar/(:num)', 'Categorias::editar/$1');
$routes->post('categorias/atualizar/(:num)', 'Categorias::atualizar/$1');
$routes->get('categorias/desativar/(:num)', 'Categorias::desativar/$1');


/*
|--------------------------------------------------------------------------
| ROTAS - CATEGORIAS
|--------------------------------------------------------------------------
*/

$routes->group('categorias', ['filter' => 'auth'], function($routes) {

    $routes->get('/', 'Categorias::index');
    $routes->get('cadastrar', 'Categorias::cadastrar');
    $routes->post('salvar', 'Categorias::salvar');

    $routes->get('editar/(:num)', 'Categorias::editar/$1');
    $routes->post('atualizar/(:num)', 'Categorias::atualizar/$1');

    $routes->get('desativar/(:num)', 'Categorias::desativar/$1');
    $routes->get('ativar/(:num)', 'Categorias::ativar/$1');

});

// REPOSIÇÃO DE ESTOQUE
$routes->get('estoque/repor/(:num)', 'Estoque::repor/$1');
$routes->post('estoque/repor/(:num)', 'Estoque::salvarReposicao/$1');
$routes->post('estoque/processarReposicao', 'Estoque::processarReposicao');














