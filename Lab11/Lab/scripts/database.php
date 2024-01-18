<?php
class database
{
    // Instancja bazy danych
    private static $instance = NULL;
    private static string $db_host = "localhost";
    private static string $db_user = "root";
    private static string $db_pass = "";
    private static string $db_name = "moja_strona";

    public static function getInstance()
    {
        // Sprawdzenie, czy instancja już istnieje
        if (self::$instance === NULL) {
            // Utworzenie nowego połączenia do bazy danych
            self::$instance = new mysqli(self::$db_host, self::$db_user, self::$db_pass, self::$db_name);
        }

        // Zwrócenie instancji bazy danych
        return self::$instance;
    }
}
?>