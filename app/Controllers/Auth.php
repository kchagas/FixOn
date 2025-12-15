<?php

namespace App\Controllers;
use App\Models\UserModel;


class Auth extends BaseController
{
protected $userModel;


public function __construct()
{
$this->userModel = new UserModel();

}
// mostra formulário de login
public function login()
{
echo view('auth/login');
}
// processa login
public function attempt()
{
$session = session();
$email = $this->request->getPost('email');
$password = $this->request->getPost('senha');


// validação básica
if (! $this->validate([
'email' => 'required|valid_email',
'senha' => 'required|min_length[4]'
])) {
return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
}


$user = $this->userModel->findByEmail($email);


if (!$user) {
return redirect()->back()->with('error', 'Usuário não encontrado');
}


// verifica senha usando password_verify
if (!password_verify($password, $user['senha'])) {
return redirect()->back()->with('error', 'Senha inválida');
}


// cria sessão segura
$session->set([
    'isLoggedIn' => true,

    // Dados do usuário logado
    'user_id'    => $user['id'],
    'user_name'  => $user['nome'],
    'user_email' => $user['email'],
    'user_role'  => $user['role'],

    /**
     * 🔥 IMPORTANTE (SaaS)
     * Empresa associada à sessão.
     * 
     * Por enquanto usamos empresa fixa (ID = 1).
     * No futuro isso virá do vínculo usuário x empresa.
     */
    'empresa_id' => 1
]);



return redirect()->to('/dashboard');
}
// registro (opcional, para criar usuários)
public function register()
{
echo view('auth/register');
}
public function store()
{
$data = $this->request->getPost();


if (! $this->validate([
'nome' => 'required|min_length[3]',
'email' => 'required|valid_email|is_unique[usuarios.email]',
'senha' => 'required|min_length[6]'
])) {
return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
}


$this->userModel->insert([
'nome' => $data['nome'],
'email' => $data['email'],
'senha' => password_hash($data['senha'], PASSWORD_DEFAULT),
'role' => isset($data['role']) ? $data['role'] : 'user'
]);


return redirect()->to('/login')->with('message', 'Conta criada com sucesso');
}

// logout
public function logout()
{
session()->destroy();
return redirect()->to('/login');
}
}

