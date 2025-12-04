<?php
namespace App\Models;


use CodeIgniter\Model;


class UserModel extends Model
{
protected $table = 'usuarios';
protected $primaryKey = 'id';
protected $allowedFields = ['nome','email','senha','role'];
protected $useTimestamps = true; // created_at, updated_at se existir


// busca por email
public function findByEmail(string $email)
{
return $this->where('email', $email)->first();
}
}