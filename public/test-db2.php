<?php
// public/test.php

// 1. Force load CodeIgniter's bootloader framework to read environments
define('FCPATH', __DIR__ . DIRECTORY_ENV);
require __DIR__ . '/../app/Config/Boot/development.php';
require __DIR__ . '/../system/Test/bootstrap.php';

// 2. Dump variables to check if PHP sees them now
echo "Environment: " . getenv('CI_ENVIRONMENT') . "<br>";
echo "DB Host: " . getenv('DATABASE_DEFAULT_HOSTNAME') . "<br>";

// 3. Test the actual mapped connection group
try {
    $db = \Config\Database::connect();
    $query = $db->query("SELECT VERSION()");
    echo "Success! Database Version: " . json_encode($query->getResult());
} catch (\Exception $e) {
    echo "Connection Failed: " . $e->getMessage();
}
