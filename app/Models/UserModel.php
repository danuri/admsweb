<?php
namespace App\Models;
use CodeIgniter\Model;
class UserModel extends Model {
    protected $table = 'adms_users';
    protected $primaryKey = 'pin';
    protected $allowedFields = ['pin','name','privilege','card','group_id'];
}
