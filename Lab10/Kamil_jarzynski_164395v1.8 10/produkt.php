<?php
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'moja_strona';
	
	$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	function DodajProdukt($tytul, $opis, $cena_netto, $podatek_vat, $ilosc_dostepnych_sztuk, $status_dostepnosci, $kategoria, $gabaryt)
	{
		global $link;

		$tytul_clear = mysqli_real_escape_string($link, $tytul);
		$opis_clear = mysqli_real_escape_string($link, $opis);
		$cena_netto_clear = (float)$cena_netto;
		$podatek_vat_clear = (float)$podatek_vat;
		$ilosc_dostepnych_sztuk_clear = (int)$ilosc_dostepnych_sztuk;
		$status_dostepnosci_clear = mysqli_real_escape_string($link, $status_dostepnosci);
		$kategoria_clear = mysqli_real_escape_string($link, $kategoria);
		$gabaryt_clear = mysqli_real_escape_string($link, $gabaryt);

		$query = "INSERT INTO produkty (tytul, opis, cena_netto, podatek_vat, ilosc_dostepnych_sztuk, status_dostepnosci, kategoria, gabaryt, data_utworzenia, data_modyfikacji, data_wygasniecia) VALUES ('$tytul_clear', '$opis_clear', $cena_netto_clear, $podatek_vat_clear, $ilosc_dostepnych_sztuk_clear, '$status_dostepnosci_clear', '$kategoria_clear', '$gabaryt_clear', NOW(), NOW(), '2023-12-31')";
    
    if (mysqli_query($link, $query)) {
        echo 'Produkt dodany pomyślnie.';
    } else {
        echo 'Błąd dodawania produktu: ' . mysqli_error($link);
    }
	}
	
	function UsunProdukt($product_id)
	{
		global $link;

		// Zabezpiecz identyfikator produktu przed SQL injection
		$product_id = mysqli_real_escape_string($link, $product_id);

		// Implementacja funkcji usuwającej produkt
		$query = "DELETE FROM produkty WHERE id = '$product_id'";
		mysqli_query($link, $query);
	}
	
	function EdytujProdukt($product_id, $tytul, $opis, $cena_netto, $podatek_vat, $ilosc_dostepnych_sztuk, $status_dostepnosci, $kategoria, $gabaryt)
	{
		global $link;

        $product_id_clear = (int)$product_id;
        $tytul_clear = mysqli_real_escape_string($link, $tytul);
        $opis_clear = mysqli_real_escape_string($link, $opis);
        $cena_netto_clear = (float)$cena_netto;
        $podatek_vat_clear = (float)$podatek_vat;
        $ilosc_dostepnych_sztuk_clear = (int)$ilosc_dostepnych_sztuk;
        $status_dostepnosci_clear = mysqli_real_escape_string($link, $status_dostepnosci);
        $kategoria_clear = mysqli_real_escape_string($link, $kategoria);
        $gabaryt_clear = mysqli_real_escape_string($link, $gabaryt);

        $query = "UPDATE produkty SET tytul='$tytul_clear', opis='$opis_clear', cena_netto=$cena_netto_clear, podatek_vat=$podatek_vat_clear, ilosc_dostepnych_sztuk=$ilosc_dostepnych_sztuk_clear, status_dostepnosci='$status_dostepnosci_clear', kategoria='$kategoria_clear', gabaryt='$gabaryt_clear', data_modyfikacji=NOW() WHERE id=$product_id_clear";

        if (mysqli_query($link, $query)) {
            echo 'Produkt zaktualizowany pomyślnie.';
        } else {
            echo 'Błąd aktualizacji produktu: ' . mysqli_error($link);
        }
	}
	
	function PokazProdukty()
	{
		global $link;
		$query = "SELECT * FROM produkty LIMIT 100";
		$result = mysqli_query($link, $query);
		
		    if (mysqli_num_rows($result) > 0) {
				echo '<table border="1">
                <tr>
                    <th>ID</th>
                    <th>Tytuł</th>
                    <th>Opis</th>
                    <th>Cena netto</th>
                    <th>Podatek VAT</th>
                    <th>Ilość</th>
                    <th>Status</th>
                    <th>Kategoria</th>
                    <th>Gabaryt</th>
                    <th>Data utworzenia</th>
                    <th>Data modyfikacji</th>
                    <th>Data wygaśnięcia</th>
                </tr>';
				//<th>Zdjęcie</th>

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>
                    <td>' . $row['id'] . '</td>
                    <td>' . $row['tytul'] . '</td>
                    <td>' . $row['opis'] . '</td>
                    <td>' . $row['cena_netto'] . '</td>
                    <td>' . $row['podatek_vat'] . '</td>
                    <td>' . $row['ilosc_dostepnych_sztuk'] . '</td>
                    <td>' . $row['status_dostepnosci'] . '</td>
                    <td>' . $row['kategoria'] . '</td>
                    <td>' . $row['gabaryt'] . '</td>
                    <td>' . $row['data_utworzenia'] . '</td>
                    <td>' . $row['data_modyfikacji'] . '</td>
                    <td>' . $row['data_wygasniecia'] . '</td>
					<td>
                        <form method="post">
                            <input type="hidden" name="product_id" value="' . $row['id'] . '">
                            <input type="submit" name="delete_product" value="Usuń">
                        </form>
                    </td>
                </tr>';
        }
		//<td>' . $row['zdjecie'] . '</td>
        echo '</table>';
    } else {
        echo 'Brak produktów do wyświetlenia.';
    }
	}
	
	if (isset($_POST['delete_product'])) {
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
    UsunProdukt($product_id);
    }
	
	if (isset($_POST['add_product'])) {
    $tytul = isset($_POST['tytul']) ? $_POST['tytul'] : '';
    $opis = isset($_POST['opis']) ? $_POST['opis'] : '';
    $cena_netto = isset($_POST['cena_netto']) ? $_POST['cena_netto'] : '';
    $podatek_vat = isset($_POST['podatek_vat']) ? $_POST['podatek_vat'] : '';
    $ilosc = isset($_POST['ilosc']) ? $_POST['ilosc'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $kategoria = isset($_POST['kategoria']) ? $_POST['kategoria'] : '';
    $gabaryt = isset($_POST['gabaryt']) ? $_POST['gabaryt'] : '';

    DodajProdukt($tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt);
	}
	
	if (isset($_POST['edit_product'])) {
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
	$tytul = isset($_POST['tytul']) ? $_POST['tytul'] : '';
	$opis = isset($_POST['opis']) ? $_POST['opis'] : '';
	$cena_netto = isset($_POST['cena_netto']) ? $_POST['cena_netto'] : '';
	$podatek_vat = isset($_POST['podatek_vat']) ? $_POST['podatek_vat'] : '';
	$ilosc = isset($_POST['ilosc']) ? $_POST['ilosc'] : '';
	$status = isset($_POST['status']) ? $_POST['status'] : '';
	$kategoria = isset($_POST['kategoria']) ? $_POST['kategoria'] : '';
	$gabaryt = isset($_POST['gabaryt']) ? $_POST['gabaryt'] : '';
		
	EdytujProdukt($product_id, $tytul, $opis, $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, $gabaryt);
    }
	
	PokazProdukty();
	
	echo '
	<h3>Dodawanie Produktu</h3>
	<form method="post" enctype="multipart/form-data">
    <label for="tytul">Tytuł:</label>
    <input type="text" name="tytul" required>

    <label for="opis">Opis:</label>
    <textarea name="opis" required></textarea>

    <label for="cena_netto">Cena netto:</label>
    <input type="number" name="cena_netto" required>

    <label for="podatek_vat">Podatek VAT:</label>
    <input type="number" name="podatek_vat" required>

    <label for="ilosc_dostepnych_sztuk">Ilość dostępnych sztuk:</label>
    <input type="number" name="ilosc_dostepnych_sztuk" required>

    <label for="status_dostepnosci">Status dostępności:</label>
    <select name="status_dostepnosci" required>
        <option value="1">Dostępny</option>
        <option value="0">Niedostępny</option>
    </select>

    <label for="kategoria">Kategoria:</label>
    <input type="text" name="kategoria" required>

    <label for="gabaryt">Gabaryt:</label>
    <input type="text" name="gabaryt" required>

    <input type="submit" name="add_product" value="Dodaj produkt">
	</form>
	';
	
	echo '
	<h3>Edytowanie Produktu</h3>
	<form method="post" enctype="multipart/form-data">
	<label for="product_id">ID:</label>
    <input type="text" name="product_id" required>
	
    <label for="tytul">Tytuł:</label>
    <input type="text" name="tytul" required>

    <label for="opis">Opis:</label>
    <textarea name="opis" required></textarea>

    <label for="cena_netto">Cena netto:</label>
    <input type="number" name="cena_netto" required>

    <label for="podatek_vat">Podatek VAT:</label>
    <input type="number" name="podatek_vat" required>

    <label for="ilosc_dostepnych_sztuk">Ilość dostępnych sztuk:</label>
    <input type="number" name="ilosc_dostepnych_sztuk" required>

    <label for="status_dostepnosci">Status dostępności:</label>
    <select name="status_dostepnosci" required>
        <option value="1">Dostępny</option>
        <option value="0">Niedostępny</option>
    </select>

    <label for="kategoria">Kategoria:</label>
    <input type="text" name="kategoria" required>

    <label for="gabaryt">Gabaryt:</label>
    <input type="text" name="gabaryt" required>

    <input type="submit" name="edit_product" value="edytuj produkt">
	</form>
	';
?>