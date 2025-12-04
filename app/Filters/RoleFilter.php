<?php
namespace App\Filters;


use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class RoleFilter implements FilterInterface
{
protected $requiredRole;


public function before(RequestInterface $request, $arguments = null)
{
// argumentos: role esperado, ex: ['admin']
$roleNeeded = $arguments[0] ?? null;
$userRole = session()->get('user_role');


if (!$roleNeeded) return; // sem role especificada, permite


if ($userRole !== $roleNeeded) {
// se não tiver permissão, pode redirecionar ou lançar 403
return redirect()->to('/dashboard')->with('error', 'Acesso negado: privilégios insuficientes');
}
}


public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
{
}
}