<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'cosiepatrzysz';
$dbname = 'moja_strona';

$link = mysqli_connect($dbhost, $dbuser, $dbpass);

if (!$link) {
    die('Przerwane połączenie: ' . mysqli_connect_error());
}

if (!mysqli_select_db($link, $dbname)) {
    die('Nie wybrano bazy: ' . mysqli_error($link));
}
?>