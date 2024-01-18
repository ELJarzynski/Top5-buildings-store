<?php
class database
{
    // Instancja bazy danych
    private static $instance = NULL;
    private static string $db_host = "localhost";
    private static string $db_user = "root";
    private static string $db_pass = "";
    private static string $db_name = "moja_strona";

    // Metoda tworząca połączenie do instancji bazy danych
    public static function getInstance()
    {
        if (self::$instance === NULL) {

            self::$instance = new mysqli(self::$db_host, self::$db_user, self::$db_pass, self::$db_name);
        }

        return self::$instance;
    }
}
?>