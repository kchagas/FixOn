<?php
namespace App\Controllers;


class Dashboard extends BaseController
{
public function index()
{
// exemplo: só exibir nome do usuário da sessão
$data['title'] = 'Painel';
echo view('dashboard/index', $data);
}


// exemplo de rota que apenas admin pode acessar
public function adminArea()
{
echo "Área exclusiva para administradores";
}
}