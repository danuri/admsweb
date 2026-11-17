<?php
namespace App\Models;
use CodeIgniter\Model;
class CommandModel extends Model {
    protected $table = 'adms_commands';
    protected $allowedFields = ['sn','command','status','response'];
}
