<?php
require_once '../scripts/cfg.php';  // Dołączenie pliku cfg.php, aby uzyskać połączenie z bazą danych

function PokazPodstrone($id) {
    global $link;  // Zmienna Globalna $link, aby uzyskać dostęp do połączenia z bazą danych

    // Czyszczenie $id aby przez GET ktoś nie próbował wykonać ataku SQL INJECTION
    $id_clear = htmlspecialchars($id);

    // Zapytanie SQL z użyciem MySQLi
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);

    // Sprawdzenie, czy zapytanie zakończyło się sukcesem
    if (!$result) {
        die('Błąd zapytania: ' . mysqli_error($link));
    }

    // Fetchujemy wyniki
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    // Wywołanie strony z bazy
    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }

    return $web;
}
?>