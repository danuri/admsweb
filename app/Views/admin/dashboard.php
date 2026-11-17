
<!doctype html>
<html>
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ADMS Level 4 - Web GUI</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark"><div class="container"><span class="navbar-brand">ADMS Server Level 4 - CI4 Boilerplate</span></div></nav>
<div class="container py-4">

<?php if(session()->getFlashdata('msg')): ?>
<div class="alert alert-success"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>

<div class="row g-3">
<div class="col-md-8">
<div class="card mb-3"><div class="card-header">Devices Online (<?= count($devices) ?>)</div>
<div class="card-body p-0"><table class="table table-sm mb-0">
<tr><th>SN</th><th>IP</th><th>Last Seen</th><th>Action</th></tr>
<?php foreach($devices as $d): ?>
<tr>
<td><code><?= $d['sn'] ?></code></td>
<td><?= $d['ip_address'] ?></td>
<td><?= $d['last_activity'] ?> <?php if(strtotime($d['last_activity']) > time()-300) echo '<span class="badge bg-success">online</span>'; ?></td>
<td><a href="/admin/reboot/<?= $d['sn'] ?>" class="btn btn-sm btn-warning">Reboot</a></td>
</tr>
<?php endforeach; ?>
</table></div></div>

<div class="card"><div class="card-header">50 Log Absen Terakhir</div>
<div class="card-body p-0"><table class="table table-sm mb-0">
<tr><th>Waktu</th><th>PIN</th><th>Nama</th><th>SN</th></tr>
<?php foreach($logs as $l): ?>
<tr><td><?= $l['scan_date'] ?></td><td><?= $l['pin'] ?></td><td><?= $l['name'] ?? '-' ?></td><td><?= $l['sn'] ?></td></tr>
<?php endforeach; ?>
</table></div></div>
</div>

<div class="col-md-4">
<div class="card mb-3"><div class="card-header">Tambah User ke Semua Mesin (Level 2)</div>
<div class="card-body">
<form method="post" action="/admin/addUser">
<input name="pin" class="form-control mb-2" placeholder="PIN / NIK (mis: 123)" required>
<input name="name" class="form-control mb-2" placeholder="Nama Karyawan" required>
<button class="btn btn-primary w-100">Kirim ke Mesin</button>
</form>
</div></div>

<div class="card mb-3"><div class="card-header">Users (<?= count($users) ?>)</div>
<div class="card-body p-0"><table class="table table-sm mb-0">
<tr><th>PIN</th><th>Nama</th><th></th></tr>
<?php foreach($users as $u): ?>
<tr><td><?= $u['pin'] ?></td><td><?= $u['name'] ?></td><td><a href="/admin/deleteUser/<?= $u['pin'] ?>" class="btn btn-sm btn-danger">Del</a></td></tr>
<?php endforeach; ?>
</table></div></div>

<div class="card"><div class="card-header">Command Queue (Level 4)</div>
<div class="card-body p-0"><table class="table table-sm mb-0">
<tr><th>ID</th><th>SN</th><th>Status</th><th>Cmd</th></tr>
<?php foreach($commands as $c): ?>
<tr><td><?= $c['id'] ?></td><td><?= $c['sn'] ?? 'ALL' ?></td><td><span class="badge bg-<?= $c['status']=='pending'?'warning':'success' ?>"><?= $c['status'] ?></span></td><td><small><?= substr($c['command'],0,40) ?></small></td></tr>
<?php endforeach; ?>
</table></div></div>

</div>
</div>

<div class="alert alert-info mt-4">
<b>Cara Setting Mesin:</b><br>
ADMS Server: <?= $_SERVER['HTTP_HOST'] ?? 'domainkamu.com' ?><br>
Port: 443 (https) / 80 (http)<br>
Endpoint test: <code>/iclock/cdata?SN=TEST&table=ATTLOG</code>
</div>

</div>
</body>
</html>
