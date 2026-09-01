<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = App\Models\User::first();
if ($user) {
    auth()->login($user);
}

$request = Illuminate\Http\Request::create('/admin/contabilletes', 'GET');
$response = $kernel->handle($request);
echo $response->getContent();
