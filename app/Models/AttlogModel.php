<?php
namespace App\Models;
use CodeIgniter\Model;
class AttlogModel extends Model {
    protected $table = 'adms_attlog';
    protected $allowedFields = ['sn','pin','scan_date','status','verify','raw'];
}
