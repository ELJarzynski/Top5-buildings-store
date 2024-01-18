<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="pl" />
    <meta name="Author" content="Kamil Jarzyński" />

    <title>Najwyższe budynki świata</title>
    <link rel="stylesheet" href="./css/style.css" />

    <script src="js/timedate.js" type="text/javascript"></script>
    <script src="./js/kolorujtlo.js" type="text/javascript"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        body {
            background-image: url('./pictures/wtle2.png'); 
            background-size: cover; 
            background-repeat: no-repeat; 
        }
    </style>
</head>
<!-- Skrypt który zwiększa rozmiar nazwy budynow po najechaniu na nie -->
<script>
    $(".clickable").on("click", function() {
    if (!$(this).is(":animated")) {
        $(this).css("font-size", "+=5px"); // Natychmiastowe zastosowanie zmiany rozmiaru
        $(this).animate({ 
            fontSize: "-=5"
        }, 10000); 
    }
});
</script>
<body onload= "startclock()">


<!-- Kod ten dynamicznie wybiera i dołącza plik HTML na podstawie wartości parametru idp w adresie URL. -->
<?php 
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    $strona = '';
    if($_GET['idp'] == '' && file_exists('html/glowna.html')) $strona = 'html/glowna.html';
    if($_GET['idp'] == 'admin' && file_exists('admin/admin.php')) $strona = 'admin/admin.php';
    if($_GET['idp'] == 'sklep' && file_exists('shop.php')) $strona = 'shop.php';
    if($_GET['idp'] == 'kontakt'  && file_exists('html/contact.html')) $strona = 'html/contact.html';
    if($_GET['idp'] == 'podstrona1' && file_exists('html/index1.html')) $strona = 'html/index1.html';
    if($_GET['idp'] == 'podstrona2' && file_exists('html/index2.html')) $strona = 'html/index2.html';
    if($_GET['idp'] == 'podstrona3' && file_exists('html/index3.html')) $strona = 'html/index3.html';
    if($_GET['idp'] == 'podstrona4' && file_exists('html/index4.html')) $strona = 'html/index4.html';
    if($_GET['idp'] == 'podstrona5' && file_exists('html/index5.html')) $strona = 'html/index5.html';
    if($_GET['idp'] == 'podstrona6' && file_exists('html/index6.html')) $strona = 'html/index6.html';
    include($strona);
?>
<!-- Panel w którym znajdują się zdjecia i nazwy budynków. Naciskając na nazwe podstrony przekierowuje nas na nią -->
 <section>
    <ul>
        <li><a href="?idp=podstrona1">Burj Khalifa</a>
        <br>
            <img class="towers" src="pictures/burj.png" alt="Burj Khalifa">
        </br></li>
        <li><a href="?idp=podstrona2">Shanghai Tower</a>
        <br>
            <img class="towers" src="pictures/shanghai.png" alt="Shanghai Tower">
        </br></li>
        <li><a href="?idp=podstrona3">Makkah Royal Clock Tower</a>
        <br>
            <img class="towersMekkah" src="pictures/mekkah.png" alt="Makkah Royal Tower">
        </br></li>
        <li><a href="?idp=podstrona4">Ping An Finance Center</a>
        <br>
            <img class="towers" src="pictures/ping.png" alt="Ping An Finance Center">
        </br></li>
        <li><a href="?idp=podstrona5">Lotte World Tower</a>
        <br>
            <img class="towers" src="pictures/lotte.ong" alt="Lotte World Tower">
        </br></li>
    </ul>
    <ul class="MenuNaBoki">
        <li><a href="index.php">Strona główna</a></li>
        <li><a href="?idp=podstrona6">Filmy na temat budynków które mogą cie zaciekawić</a></li>
        <li><a href="shop.php">Sklep Strony</a><li>
        <li><a href="?idp=kontakt">Kontakt</a><li>
        <li><a href="admin/admin.php">Admin Panel</a><li>
    </ul>
</section>
</body>
</html>
<?php
    $nr_indeksu = '164395';
    $nrGrupy = '2';
    echo 'Autor: Kamil Jarzyński '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br /><br /><br />';
?>