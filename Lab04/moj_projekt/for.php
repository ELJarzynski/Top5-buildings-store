<?php 
function ForPotega2($potega) {
    $wynik = 1;
    for($i = 0; $i < $potega; $i++) {
        $wynik *= 2;
    }
    return $wynik;
}

function WhilePotega3($potega) {
    $wynik = 1;
    $i = 0;
    while ($i < $potega) {
        $wynik *=3;
        $i++;
    }
    return $wynik;
}

?>