<?php
namespace App\Models;
use CodeIgniter\Model;
class DeviceModel extends Model {
    protected $table = 'adms_devices';
    protected $primaryKey = 'sn';
    protected $allowedFields = ['sn','last_stamp','last_opstamp','last_activity','ip_address','fw_version','online'];
    public function upsertDevice($sn, $data){
        $exist = $this->find($sn);
        if($exist){
            $this->update($sn, $data);
        } else {
            $data['sn'] = $sn;
            $this->insert($data);
        }
    }
}
