<?php
namespace App\Controllers;
use App\Services\AdmsService;
use App\Models\DeviceModel;

class Iclock extends BaseController
{
    protected $admsService;
    protected $deviceModel;
    public function __construct(){
        $this->admsService = new AdmsService();
        $this->deviceModel = new DeviceModel();
        helper('adms');
    }
    public function cdata(){
        $sn = $this->request->getGet('SN');
        $table = $this->request->getGet('table') ?? 'options';
        $stamp = $this->request->getGet('Stamp');
        $opStamp = $this->request->getGet('OpStamp');
        $body = $this->request->getBody();

        log_message('debug', "[ADMS][$sn][$table] Body: ".substr($body,0,500));

        if($sn){
            $this->deviceModel->upsertDevice($sn, [
                'last_activity' => date('Y-m-d H:i:s'),
                'ip_address' => $this->request->getIPAddress(),
                'last_stamp' => $stamp ? (int)$stamp : 0
            ]);
        }

        switch(strtoupper($table)){
            case 'ATTLOG':
                $this->admsService->parseAttlog($sn, $body);
                break;
            case 'OPERLOG':
                $this->admsService->parseOperlog($sn, $body);
                break;
            case 'USER':
                $this->admsService->parseUser($body);
                break;
            case 'FINGERTMP':
                $this->admsService->parseTemplate($body, 'fp');
                break;
            case 'FACE':
                $this->admsService->parseTemplate($body, 'face');
                break;
            case 'ATTPHOTO':
            case 'BIOPHOTO':
                // skip foto kalau mau hemat storage
                break;
            case 'OPTIONS':
            default:
                break;
        }

        $device = $this->deviceModel->find($sn);
        $lastStamp = $device['last_stamp'] ?? 0;
        $lastOpStamp = $device['last_opstamp'] ?? 0;

        $res  = "GET OPTION FROM:$sn\n";
        $res .= "Stamp=$lastStamp\n";
        $res .= "OpStamp=$lastOpStamp\n";
        $res .= "ErrorDelay=10\nDelay=20\nTransTimes=00:00;14:05\nTransInterval=1\n";
        $res .= "TransFlag=TransData AttLog OpLog\nTimeZone=7\nRealtime=1\nEncrypt=0\nOK\n";
        return $this->response->setContentType('text/plain')->setBody($res);
    }

    public function getrequest(){
        $sn = $this->request->getGet('SN');
        $db = \Config\Database::connect();
        $builder = $db->table('adms_commands');
        $cmd = $builder->groupStart()->where('sn', $sn)->orWhere('sn IS NULL')->groupEnd()
                        ->where('status','pending')->orderBy('id','ASC')->get()->getRowArray();
        if(!$cmd){
            return $this->response->setContentType('text/plain')->setBody("OK");
        }
        $db->table('adms_commands')->where('id', $cmd['id'])->update(['status'=>'sent']);
        $out = "C:{$cmd['id']}:{$cmd['command']}";
        return $this->response->setContentType('text/plain')->setBody($out);
    }
}
