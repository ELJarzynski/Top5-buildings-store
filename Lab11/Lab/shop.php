<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
include "scripts/functions.php";
require_once "scripts/Category.php";
require_once "scripts/Product.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="Content-Language" content="pl"/>
    <link rel="stylesheet" href="style/style.css" type="text/css"/>
    <script src="script/jquery.js" defer></script>
    <script src="script/script.js" defer></script>
</head>
<body onload="showTime()">
<div id="container">
    <div id="date">
        <div id="watch">dd</div>
    </div>
    <div id="main">
        <nav>
            <ul>
                <li><a href="index.php">Strona główna</a></li>
                <li><a href="?idp=podstrona1">Burj Khalifa</a></li>
                <li><a href="?idp=podstrona2">Shanghai Tower</a></li>
                <li><a href="?idp=podstrona3">Makkah Royal Clock Tower</a></li>
                <li><a href="?idp=podstrona4">Ping An Finance Center</a></li>
                <li><a href="?idp=podstrona5">Lotte World Tower</a></li>
                <li><a href="?idp=podstrona6">Filmy na temat budynków które mogą cie zaciekawić</a></li>
            </ul>
        </nav>

        <main>
            <?php
            echo new Product(1);
            echo new Product(2);
            ?>
        </main>

        <footer>
            <?php
            $nr_indeksu = '164395';
            $nrGrupy = '2';
            echo 'Autor: Kamil Jarzyński '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br /><br /><br />';
            ?>
        </footer>
    </div>
</div>
</body>
</html>
