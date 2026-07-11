<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$req = Illuminate\Http\Request::create('http://ibekami.id/invoice/tambah-spb');
$req->server->set('SCRIPT_NAME', '/invoice/index.php');
$app->instance('request', $req);

echo "url('/kategori-barang') => " . url('/kategori-barang') . "\n";
echo "asset('js/halaman/spb.js') => " . asset('js/halaman/spb.js') . "\n";
