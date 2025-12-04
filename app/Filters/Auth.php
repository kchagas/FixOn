<?php
namespace App\Filters;


use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class Auth implements FilterInterface
{
public function before(RequestInterface $request, $arguments = null)
{
// checa se há sessão ativa
if (! session()->get('isLoggedIn')) {
return redirect()->to('/login');
}
}


public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
{
// sem ação após a resposta
}
}
