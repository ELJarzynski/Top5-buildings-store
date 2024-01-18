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



/**
 * Funkcja wyświetlająca treść strony o podanym identyfikatorze.
 * @param int $id - identyfikator strony
 * @return string - treść strony w formie HTML
 * @throws InvalidArgumentException - wyjątek rzucany w przypadku niepoprawnego typu argumentu $id
 * @throws Exception - wyjątek rzucany, gdy strona nie zostanie znaleziona
 */
function showPage($id = 1)
{
    $db = database::getInstance();

    // Sprawdzenie poprawności typu argumentu $id
    if (!is_int($id)) {
        throw new InvalidArgumentException('Invalid argument type for $id. Must be of type int.');
    }

    // Zapytanie SQL pobierające treść strony o podanym identyfikatorze
    $query = "SELECT `page_content` FROM `page_list` WHERE id = ? LIMIT 1";

    // Przygotowanie i wykonanie zapytania SQL zabezpieczonego przed SQL injection
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Pobranie wyników zapytania
    $result = $stmt->get_result();
    $pageData = $result->fetch_assoc();

    // Sprawdzenie czy strona została znaleziona
    if (!$pageData) {
        throw new Exception("Page not found");
    }

    // Zwrócenie treści strony zdekodowanej z encji HTML
    return html_entity_decode($pageData['page_content']);
    }
    function getTitle($id = 1)
{
    $db = Database::getInstance();

    // Zapytanie SQL pobierające tytuł strony o podanym identyfikatorze
    $query = "SELECT `page_title` FROM `page_list` WHERE id = ? LIMIT 1";

    // Przygotowanie i wykonanie zapytania SQL zabezpieczonego przed SQL injection
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Pobranie wyników zapytania
    $result = $stmt->get_result();
    $pageData = $result->fetch_assoc();

    // Sprawdzenie czy strona została znaleziona
    if (!$pageData) {
        throw new Exception("Page not found");
    }

    // Zwrócenie tytułu strony
    return $pageData['page_title'];
}
?>