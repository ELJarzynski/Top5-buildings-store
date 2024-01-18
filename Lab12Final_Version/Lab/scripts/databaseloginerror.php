<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "cfg.php";
include "database.php";

$db = database::getInstance();
if($db->connect_error)
{
    echo "Błąd w połączeniu z bazą danych: " . $db->connect_error;
}
