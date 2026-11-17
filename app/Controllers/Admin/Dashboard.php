<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\DeviceModel;
use App\Models\AttlogModel;
use App\Models\UserModel;
use App\Models\CommandModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $deviceModel = new DeviceModel();
        $attlogModel = new AttlogModel();
        $userModel = new UserModel();
        $commandModel = new CommandModel();

        $devices = $deviceModel->orderBy('last_activity', 'DESC')->findAll();
        $logs = $attlogModel->select('adms_attlog.*, adms_users.name')
            ->join('adms_users', 'adms_users.pin=adms_attlog.pin', 'left')
            ->orderBy('scan_date', 'DESC')->limit(50)->findAll();
        $users = $userModel->findAll();
        $commands = $commandModel->orderBy('id', 'DESC')->limit(20)->findAll();

        return view('admin/dashboard', compact('devices', 'logs', 'users', 'commands'));
    }
    public function addUser()
    {
        $pin = $this->request->getPost('pin');
        $name = $this->request->getPost('name');
        
        $userModel = new UserModel();
        if ($userModel->find($pin)) {
            $userModel->update($pin, ['name' => $name]);
        } else {
            $userModel->insert(['pin' => $pin, 'name' => $name]);
        }

        // broadcast ke semua mesin
        $cmd = "DATA UPDATE USERINFO PIN=$pin\tName=$name\tPri=0\tPasswd=\tGrp=1\tTZ=0000000100000000";
        $commandModel = new CommandModel();
        $commandModel->insert(['sn' => null, 'command' => $cmd, 'status' => 'pending']);

        return redirect()->to('/admin')->with('msg', 'User ditambah, antri ke mesin');
    }
    public function deleteUser($pin)
    {
        $userModel = new UserModel();
        $userModel->delete($pin);

        $commandModel = new CommandModel();
        $commandModel->insert(['sn' => null, 'command' => "DATA DELETE USERINFO PIN=$pin", 'status' => 'pending']);

        return redirect()->to('/admin');
    }
    public function reboot($sn)
    {
        $commandModel = new CommandModel();
        $commandModel->insert(['sn' => $sn, 'command' => 'REBOOT', 'status' => 'pending']);

        return redirect()->to('/admin')->with('msg', "Reboot $sn dikirim");
    }
}
