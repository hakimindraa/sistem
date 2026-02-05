<?php
// Temporary debug file - DELETE after fixing
echo "<h1>PHP Info</h1>";
echo "<h2>Environment Variables:</h2>";
echo "<pre>";
echo "CI_ENVIRONMENT: " . getenv('CI_ENVIRONMENT') . "\n";
echo "RAILWAY_ENVIRONMENT: " . getenv('RAILWAY_ENVIRONMENT') . "\n";
echo "RAILWAY_PUBLIC_DOMAIN: " . getenv('RAILWAY_PUBLIC_DOMAIN') . "\n";
echo "PORT: " . getenv('PORT') . "\n";
echo "\nDatabase Config:\n";
echo "MYSQLHOST: " . (getenv('MYSQLHOST') ? 'SET' : 'NOT SET') . "\n";
echo "MYSQLDATABASE: " . (getenv('MYSQLDATABASE') ? 'SET' : 'NOT SET') . "\n";
echo "MYSQLUSER: " . (getenv('MYSQLUSER') ? 'SET' : 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>Writable Permissions:</h2>";
echo "<pre>";
$writable = __DIR__ . '/../writable';
echo "Writable dir: " . $writable . "\n";
echo "Is writable: " . (is_writable($writable) ? 'YES' : 'NO') . "\n";
echo "Permissions: " . substr(sprintf('%o', fileperms($writable)), -4) . "\n";
echo "</pre>";

phpinfo();
