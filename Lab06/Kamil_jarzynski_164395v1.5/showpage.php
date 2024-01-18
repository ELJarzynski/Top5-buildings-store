<?php
include('cfg.php');  // Dodaj dołączenie pliku cfg.php, aby uzyskać połączenie z bazą danych

function PokazPodstrone($id) {
    global $link;  // Użyj globalnej zmiennej $link, aby uzyskać dostęp do połączenia z bazą danych

    //czyscimy $id aby przez GET ktoś nie próbował wykonać ataku SQL INJECTION
    $id_clear = htmlspecialchars($id);

    // przygotowujemy zapytanie SQL z użyciem MySQLi
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);

    // sprawdzamy, czy zapytanie zakończyło się sukcesem
    if (!$result) {
        die('Błąd zapytania: ' . mysqli_error($link));
    }

    // fetchujemy wyniki
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    // wywołanie strony z bazy
    if (empty($row['id'])) {
        $web = '[nie_znaleziono_strony]';
    } else {
        $web = $row['page_content'];
    }

    return $web;
}
?>