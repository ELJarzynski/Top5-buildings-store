<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
require_once "scripts/category.php";
require_once "scripts/product.php";
require_once 'scripts/database.php';
session_start();

// Dodawanie przedmiotu do koszyka

if (isset($_POST['add_to_cart'])) {
    $quantity = $_POST['quantity'];
    $db = Database::getInstance();
    $productId = $_GET['id'];
    $query = "SELECT quantity FROM product WHERE id = $productId";
    $result = $db->query($query);
    $productData = $result->fetch_assoc();
    $availableQuantity = $productData['quantity'];

    if ($quantity > $availableQuantity) {
        echo "<script>alert('Przepraszamy, produkt jest niedostępny w takiej ilości.');</script>";
    } else {
        if (isset($_SESSION['product'])) {
            $found = false;

            foreach ($_SESSION['product'] as &$item) {
                if ($item['id'] == $_GET['id']) {
                    // Produkt istnieje w koszyku
                    $item['quantity'] += $quantity;
                    $found = true;

                    // Aktualizuj ilość dostępna w bazie danych
                    $newQuantity = $availableQuantity - $quantity;
                    $updateQuery = "UPDATE product SET quantity = $newQuantity WHERE id = $productId";
                    $db->query($updateQuery);

                    break;
                }
            }

            if (!$found) {
                // Produkt nie istnieje w koszyku
                $session_array = array(
                    'id' => $_GET['id'],
                    'title' => $_POST['title'],
                    'netto_price' => floatval($_POST['netto_price']),
                    'vat' => floatval($_POST['vat']),
                    'quantity' => $quantity,
                );

                $_SESSION['product'][] = $session_array;

                // Aktualizacja ilość w bazie danych
                $newQuantity = $availableQuantity - $quantity;
                $updateQuery = "UPDATE product SET quantity = $newQuantity WHERE id = $productId";
                $db->query($updateQuery);
            }
        } else {
            // Brak koszyka
            $session_array = array(
                'id' => $_GET['id'],
                'title' => $_POST['title'],
                'netto_price' => floatval($_POST['netto_price']),
                'vat' => floatval($_POST['vat']),
                'quantity' => $quantity,
            );

            $_SESSION['product'][] = $session_array;

            // Aktualizacja ilość w bazie danych
            $newQuantity = $availableQuantity - $quantity;
            $updateQuery = "UPDATE product SET quantity = $newQuantity WHERE id = $productId";
            $db->query($updateQuery);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
</head>
<body>

    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <ul>
                    <li class="text-center"><a href="index.php">Strona Główna</a></li>
                    </ul>

                    <h2 class="text-center"> Produkty</h2>
                    <div class="col-md-12">
                       <div>
                            <h2>Kategorie: </h2>

                            <?php

                            $db = Database::getInstance();
                            $categories = [];

                            $query = "SELECT `id` FROM `categories` WHERE `parent` = 0";
                            $result = $db->query($query);

                            while($data = $result->fetch_assoc()) {
                                array_push($categories, new Category($data['id']));
                            }

                            foreach($categories as $category) {
                                echo $category;
                            }
                            ?>
                                <h2>Produkty: </h2><br>

                                <?php
                                    if(isset($_GET['cat'])) {
                                        $catId = $_GET['cat'];
                                        $categories = new Category($catId);
                                        $allCategoryIds = [];
                                        $categories->traverseCategory($allCategoryIds);
                                        $allCategoryIds[] = $catId;

                                        $categoryIdsString = implode(',', $allCategoryIds);

                                        $query = "SELECT * FROM product WHERE category_id IN ($categoryIdsString) AND availability != 0";
                                        $result = $db->query($query);

                                        echo "<div class='row'>";
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <div class="col-md-4">
                                                <form method="post" action="shop.php?id=<?= $row['id'] ?>">
                                                    <img src="pictures/shop/<?= $row['photo'] ?>" style='height: 150px;'>
                                                    <h5 class="text-center"><?= $row['title']; ?></h5>
                                                    <p class="text-center"><?= $row['description']; ?></p>
                                                    <p class="text-center">Netto Price: PLN <?= number_format($row['netto_price'], 2); ?></p>
                                                    <p class="text-center">VAT: <?= $row['vat']; ?></p>
                                                    <p class="text-center">Quantity: <?= $row['quantity']; ?></p>
                                                    <p class="text-center">Dimensions: <?= $row['dimensions']; ?></p>
                                                    <input type="hidden" name="title" value="<?= $row['title'] ?>">
                                                    <input type="hidden" name="netto_price" value="<?= number_format($row['netto_price'], 2, '.', '') ?>">
                                                    <input type="hidden" name="vat" value="<?= $row['vat'] ?>">
                                                    <input type="number" name="quantity" value="1" class="form-control">
                                                    <input type="submit" name="add_to_cart" class="btn btn-warning btn block my-2" value="Add To Cart">
                                                </form>
                                            </div>
                                            <?php
                                        }
                                        echo "</div>";
                                    }
                                ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="text-center">Koszyk</h2>
                    <?php
                        $total = 0;
                        $output = "
                            <table class='table table-bordered table-striped'>
                                <tr>
                                    <th>ID</th>
                                    <th>Item name</th>
                                    <th>Item Price (Netto)</th>
                                    <th>VAT</th>
                                    <th>Item Quantity</th>
                                    <th>Total Price (Brutto)</th>
                                    <th>Action</th>
                                </tr>
                        ";

                        if (!empty($_SESSION['product'])) {
                            foreach ($_SESSION['product'] as $key => $value) {
                                $nettoPrice = $value['netto_price'];
                                $vat = $value['vat'];
                                $bruttoPrice = $nettoPrice + $vat;

                                $output .= "
                                    <tr>
                                        <td>".$value['id']."</td>
                                        <td>".$value['title']."</td>
                                        <td>PLN ".number_format($nettoPrice, 2)."</td>
                                        <td>".$vat." PLN</td>
                                        <td>".$value['quantity']."</td>
                                        <td>PLN ".number_format($bruttoPrice * $value['quantity'], 2)."</td>
                                        <td>
                                            <a href='shop.php?action=remove&id=".$value['id']."'>
                                                <button class='btn btn-danger btn-block'>Remove</button>
                                            </a>
                                        </td>
                                    ";
                                $total = $total + $value['quantity'] * $bruttoPrice;
                            }
                        }

                        $output .= "
                            <tr>
                                <td colspan='4'></td>
                                <td><b>Total Price</b></td>
                                <td>PLN ".number_format($total, 2)."</td>
                                <td>
                                    <a href='shop.php?action=clearall'>
                                        <button class='btn btn-warning'>Buy</button>
                                    </a>
                                </td>
                            </tr>
                        ";

                        echo $output;
                    ?>
            </div>
        </div>
    </div>

    <?php 
    // Usuwanie wszystkich produktów z koszyka
    if(isset($_GET['action'])) {
        if($_GET['action'] == "clearall") {
            unset($_SESSION['product']);
        }
        // Usuwanie jednego produktu z koszyka
        if ($_GET['action'] == "remove") {
            foreach ($_SESSION['product'] as $key => $value) {
                if ($value['id'] == $_GET['id']) {
                    
                    $productId = $_GET['id'];
                    $quantityToAddBack = $value['quantity'];
                    $updateQuery = "UPDATE product SET quantity = quantity + $quantityToAddBack WHERE id = $productId";
                    $db->query($updateQuery);
        
                    unset($_SESSION['product'][$key]);
                }
            }
        }
    }
    ?>
</body>
</html>
