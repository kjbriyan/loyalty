<?php

namespace App\Models;

use CodeIgniter\Model;

class RegisModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'email';
    protected $allowedFields = ['id_user', 'email', 'password', 'name', 'level','alamat'];
}
