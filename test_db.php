<?php

require_once 'classes/Database.php';

$database = new Database();
$conn = $database->connect();

echo "Prisijungimas prie duomenų bazės sėkmingas!";