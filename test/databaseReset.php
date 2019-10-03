<?php

// reset database
echo "Database reset started...\n";
$dsn = "mysql:dbname=$config->dbName;host=$config->dbHost";
$sql = \file_get_contents(__DIR__ . "/skyscrapers.sql");
$pdo = new \PDO($dsn, $config->dbUser, $config->dbPass, []);
$pdo->exec($sql);
echo "Database reset finished.\n\n";
