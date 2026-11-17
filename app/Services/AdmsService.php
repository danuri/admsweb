<?php
namespace App\Services;
use Config\Database;

class AdmsService
{
    protected $db;
    public function __construct(){ $this->db = Database::connect(); }

    public function parseAttlog($sn, $body){
        $lines = explode("\n", trim($body));
        $batch = [];
        foreach($lines as $line){
            $line = trim($line);
            if(!$line) continue;
            $c = explode("\t", $line);
            if(count($c) < 2) continue;
            $batch[] = [
                'sn' => $sn,
                'pin' => $c[0],
                'scan_date' => $c[1],
                'status' => $c[2] ?? 0,
                'verify' => $c[3] ?? 0,
                'raw' => $line
            ];
        }
        if($batch) $this->db->table('adms_attlog')->insertBatch($batch);
    }

    public function parseUser($body){
        $lines = explode("\n", trim($body));
        foreach($lines as $line){
            // PIN=1	Name=Budi	Pri=0	...
            $data = $this->parseKeyValueLine($line);
            if(!isset($data['PIN'])) continue;
            $this->db->table('adms_users')->upsert([
                'pin' => $data['PIN'],
                'name' => $data['Name'] ?? $data['PIN'],
                'privilege' => $data['Pri'] ?? 0,
                'card' => $data['Card'] ?? null,
            ], 'pin');
        }
    }

    public function parseTemplate($body, $type='fp'){
        $lines = explode("\n", trim($body));
        foreach($lines as $line){
            $data = $this->parseKeyValueLine($line);
            if(!isset($data['PIN']) || !isset($data['TMP'])) continue;
            $this->db->table('adms_templates')->insert([
                'pin' => $data['PIN'],
                'fid' => $data['FID'] ?? 0,
                'type' => $type,
                'tmp' => $data['TMP'],
                'valid' => $data['Valid'] ?? 1
            ]);
        }
    }

    public function parseOperlog($sn, $body){
        // Contoh feedback: ID=1 Return=0 (sukses)
        // Update command jadi done
        if(preg_match('/ID=(\d+).*?Return=0/', $body, $m)){
            $this->db->table('adms_commands')->where('id', $m[1])->update(['status'=>'done','response'=>$body]);
        }
    }

    private function parseKeyValueLine($line){
        $result = [];
        $parts = explode("\t", $line);
        foreach($parts as $p){
            if(!str_contains($p, '=')) continue;
            [$k,$v] = explode('=', $p, 2);
            $result[trim($k)] = trim($v);
        }
        return $result;
    }
}
