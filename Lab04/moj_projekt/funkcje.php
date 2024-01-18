<?php
function ocenWynik($wynik) {
    if ($wynik >= 90) {
        return "5";
    } elseif ($wynik >= 80) {
        return "4";
    } elseif ($wynik >= 70) {
        return "3";
    } elseif ($wynik >= 60) {
        return "2";
    } else {
        return "1";
    }
}

function dzienTygodnia($dzien) {
    switch($dzien) {
        case 1:
            return "Poniedziałek";
        case 2:
            return "Wtorek";
        case 3:
            return "Środa";
        case 4:
            return "Czwartek";
        case 5:
            return "Piątek";
        case 6:
            return "Sobota";
        case 7:
            return "Niedziela";
        default:
            return "Nieprawidłowy dzien tygodnia";
    }
}
?>