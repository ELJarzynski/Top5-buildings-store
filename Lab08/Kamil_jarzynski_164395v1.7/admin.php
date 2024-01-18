<?php


include('../cfg.php');
session_start();

function FormularzLogowania()
{
    $wynik = '
    <div class="logowanie">
        <h1 class="heading">Panel CMS:</h1>
            <div class="logowanie">
                <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                    <table class="logowanie">
                        <tr><td class="log4_t">Login:</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
                        <tr><td class="log4_t">Hasło:</td><td><input type="password" name="login_haslo" class="logowanie" /></td></tr>
                        <tr><td>&nbsp;</td><td><input type="submit" name="x1_submit" class="logowanie" value="Zaloguj" /></td></tr>
                    </table>
                </form>
            </div>
    </div>
    ';

    return $wynik;
}

function EdytujPodstrone()
{
	echo 'edytuj';
}

function DodajPodstrone()
{
	echo 'dodaj';
}

function UsunPodstrone()
{
	echo 'usun';
}

function ListaPodstron()
{		
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'moja_strona';

	$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
		
	$query = "SELECT * FROM page_list LIMIT 100";
	$result = mysqli_query($link,$query);
	
    echo '<table border="1">
            <tr>
                <th>ID</th>
                <th>Tytuł podstrony</th>
                <th>Akcje</th>
            </tr>';
    
    while ($row = mysqli_fetch_array($result))
    {
        echo '<tr>
                <td>'.$row['id'].'</td>
                <td>'.$row['page_title'].'</td>
                <td>
					<form method="post">
						<input type="hidden" name="edit_id" value="'.$row['id'].'">
						<input type="submit" name="EdytujPodstrone" class="button" value=Edytuj />
						<input type="submit" name="UsunPodstrone" class="button" value=Usuń />
					</form>
                </td>
              </tr>';
    }
    
    echo '</table>';
	
	echo '
		<form method="post">
		<input type="submit" name="DodajPodstrone" class="button" value=Dodaj />
		</form>
		';
}

// Sprawdzenie warunków dostępu
//$_SESSION['logged_in'] = false;


if (isset($_POST['x1_submit'])) {
    $login_form = isset($_POST['login_email']) ? $_POST['login_email'] : '';
    $pass_form = isset($_POST['login_haslo']) ? $_POST['login_haslo'] : '';

    if ($login_form == $login && $pass_form == $pass) {
        // Zalogowano poprawnie, ustaw zmienne sesji
        $_SESSION['logged_in'] = true;
		//session_regenerate_id(true);
        echo 'zalogowano <br/>';
		//ListaPodstron();
        //exit();
    } else {
        // Błąd logowania, wyświetl komunikat i formularz logowania
        echo "Błąd logowania. Spróbuj ponownie.";
    }
}

// Jeśli nie zalogowano, wyświetl formularz logowania
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    echo FormularzLogowania();
} else {
	ListaPodstron();
}

if (isset($_POST['EdytujPodstrone'])) {
$edit_id = isset($_POST['edit_id']) ? $_POST['edit_id'] : '';
EdytujPodstrone();
echo $edit_id;
}

if (isset($_POST['UsunPodstrone'])) {
$edit_id = isset($_POST['edit_id']) ? $_POST['edit_id'] : '';
UsunPodstrone();
echo $edit_id;
}

if (isset($_POST['DodajPodstrone'])) {
DodajPodstrone();
}

?>