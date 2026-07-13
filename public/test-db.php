<?php
// public/test-db.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Diagnosis Script</h2>";

// 1. Check raw Environment Variables
echo "<h3>1. Environment Variables Check</h3>";
$host = getenv('database.default.hostname');
$user = getenv('database.default.username');
$pass = getenv('database.default.password');
$db   = getenv('database.default.database');
$port = getenv('database.default.port');
$cert = getenv('database.default.encrypt.ssl_ca');

echo "Host: " . ($host ?: "NOT FOUND") . "<br>";
echo "User: " . ($user ?: "NOT FOUND") . "<br>";
echo "Port: " . ($port ?: "NOT FOUND") . " (Type: " . gettype($port) . ")<br>";
echo "Cert Env Path: " . ($cert ?: "NOT FOUND") . "<br>";

// 2. Check Certificate Accessibility
echo "<h3>2. Certificate File Check</h3>";
$target_cert = '/etc/secrets/ca.pem';
if (file_exists($target_cert)) {
    echo "✅ File exists at $target_cert<br>";
    if (is_readable($target_cert)) {
        echo "✅ File is READABLE by PHP user: " . exec('whoami') . "<br>";
        echo "File size: " . filesize($target_cert) . " bytes<br>";
    } else {
        echo "❌ File exists but is NOT READABLE (Permissions issue)<br>";
    }
} else {
    echo "❌ File DOES NOT EXIST at $target_cert<br>";
}

// 3. Attempt Raw Native PHP Connection
echo "<h3>3. Raw MySQLi Connection Attempt</h3>";
$db_obj = mysqli_init();
if (!$db_obj) {
    die("mysqli_init failed");
}

// Apply SSL if file exists
if (file_exists($target_cert) && is_readable($target_cert)) {
    mysqli_ssl_set($db_obj, NULL, NULL, $target_cert, NULL, NULL);
}

echo "Attempting raw connection to $host on port " . (int)$port . "...<br>";
$link = @mysqli_real_connect($db_obj, $host, $user, $pass, $db, (int)$port);

if ($link) {
    echo "🎉 SUCCESS: Raw connection established perfectly!";
    mysqli_close($db_obj);
} else {
    echo "❌ RAW CONNECTION FAILED<br>";
    echo "Error Number: " . mysqli_connect_errno() . "<br>";
    echo "Error Message: " . mysqli_connect_error() . "<br>";
}