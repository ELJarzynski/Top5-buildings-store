<?php
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'kategorie';

	$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	function DodajKategorie($link, $matka, $nazwa)
	{
		$query = "INSERT INTO kategorie (matka, nazwa) VALUES (?, ?)";
		$stmt = mysqli_prepare($link, $query);
		mysqli_stmt_bind_param($stmt, 'is', $matka, $nazwa);

		if (mysqli_stmt_execute($stmt)) {
			echo "Dodano kategorię: $nazwa<br>";
		} else {
			echo "Błąd podczas dodawania kategorii: " . mysqli_error($link) . "<br>";
		}

		mysqli_stmt_close($stmt);
	}

	function EdytujKategorie($link, $id, $nazwa)
	{
		$query = "UPDATE kategorie SET nazwa = ? WHERE id = ?";
		$stmt = mysqli_prepare($link, $query);
		mysqli_stmt_bind_param($stmt, 'si', $nazwa, $id);

		if (mysqli_stmt_execute($stmt)) {
			echo "Zaktualizowano nazwę kategorii o ID: $id<br>";
		} else {
			echo "Błąd podczas aktualizacji nazwy kategorii: " . mysqli_error($link) . "<br>";
		}

		mysqli_stmt_close($stmt);
	}
	
	function PokazKategorie($link, $parentID = 0, $space = 0)
	{
		$query = "SELECT id, nazwa FROM kategorie WHERE matka = '$parentID'";
		$result = mysqli_query($link, $query);

		if ($result->num_rows > 0) {
			echo '<ul>';  // Rozpoczęcie listy
			while ($row = $result->fetch_assoc()) {
				echo '<li>';
				echo str_repeat('...', $space) . $row['nazwa'];

				// Dodanie formularza z przyciskami
				echo '<form method="post" action="">';
				echo '<input type="hidden" name="id" value="' . $row['id'] . '">';

				echo ' <button type="submit" name="dodaj">Dodaj</button>';
				echo ' <button type="submit" name="usun">Usuń</button>';
				echo ' <button type="submit" name="edytuj">Edytuj</button>';

				echo '</form>';

				echo '</li>';

				// Rekurencyjne wywołanie dla podkategorii
				PokazKategorie($link, $row['id'], $space + 1);
			}
			echo '</ul>';  // Zamknięcie listy
		}
	}
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['dodaj'])) {
			DodajKategorie($link, $_POST['id'], 'Nowa Podkategoria');
		} elseif (isset($_POST['usun'])) {
			UsunKategorie($link, $_POST['id']);
		} elseif (isset($_POST['edytuj'])) {
			EdytujKategorie($link, $_POST['id'], 'Nowa Nazwa Kategorii');
		} else {
			PokazKategorie($link);
		}
	}else{
		PokazKategorie($link);
	}
?>