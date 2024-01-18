<?php
if ((include 'header.php') == TRUE) {
    echo 'OK<br/>';
 }  
 $nr_indeksu = '164395';
 $nrGrupy = '2';
 echo 'Kamil Jarzynski '.$nr_indeksu.' grupa '.$nrGrupy.' <br /><br />';
 echo 'Zastosowanie metody include() <br />';
 
 include 'zmienne.php';
 echo 'Numer Telefonu: '.$numer.'<br />';
 echo 'Email: '.$email.'<br />';
 $wynik_sumy = suma(5,3);
 echo 'Wynik dodawania 5 i 3 = '.$wynik_sumy.'<br /><br />';
 require_once 'zmienne.php';
 $wynik_sumy2 = suma(5,4);
 echo 'Wynik dodawania 5 i 4 = '.$wynik_sumy2.'<br /><br />';

include 'funkcje.php';
echo 'Funkcje wykorzystujace if else elseif i switch <br />';
echo 'Wynik dla 92% uzyskanych: '.ocenWynik(92).'<br />';
echo 'Wynik dla 82% uzyskanych: '.ocenWynik(82).'<br />';
echo 'Wynik dla 72% uzyskanych: '.ocenWynik(72).'<br />';
echo 'Wynik dla 50% uzyskanych: '.ocenWynik(50).'<br />';
echo 'Dni tygodnia dla switch<br/>';
echo dzienTygodnia(1).'<br />';
echo dzienTygodnia(2).'<br />';
echo dzienTygodnia(7).'<br /><br /><br />';

include 'for.php';
echo 'Funkcje z petla while i for<br /><br />';
echo 'Za pomoca pętli for potega 2 do 5 = '.ForPotega2(5).'<br />';
echo 'Za pomoca pętli while potega 3 do 5 = '.ForPotega2(5);

echo'<br/>zmienne get post session<br/>';
echo'<br/>Zmienna Get<br/>';
$_GET["name"] = 'Kamil';
echo 'Hello '.htmlspecialchars($_GET["name"]).'!';

echo'<br/><br/>Zmienna POST<br/>';
$_POST["surrname"] = 'Jarzyński';
echo 'Hello'.htmlspecialchars($_POST["surrname"]).'!';

echo'<br/><br/> SESSION<br/>';
session_start();
$_SESSION["secondname"] = "Arkadiusz";
echo'Wartość SESSION ustawiona';

?>
