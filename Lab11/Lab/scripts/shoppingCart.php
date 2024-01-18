<?php
session_start();
require_once 'Database.php';

class ShoppingCart
{
    public static function addToCart($productId, $quantity)
    {
        $productInfo = self::getProductInfo($productId);

        if ($productInfo) {
            $cartItem = self::getCartItem($productId);

            if ($cartItem) {
                self::updateCartItem($productId, $cartItem['quantity'] + $quantity);
            } else {
                self::insertCartItem($productId, $quantity, $productInfo['net_price'], $productInfo['vat']);
            }
        }
    }

    public static function removeFromCart($productId)
    {
        $cartItem = self::getCartItem($productId);

        if ($cartItem) {
            self::deleteCartItem($productId);
        }
    }

    public static function showCart()
    {
        $cartItems = self::getCartItems();

        if ($cartItems) {
            echo "<h2>Shopping Cart:</h2>";
            foreach ($cartItems as $cartItem) {
                echo "Product ID: {$cartItem['product_id']}, Quantity: {$cartItem['quantity']}, Total Price: " .
                    self::calculateTotalPrice($cartItem['quantity'], $cartItem['price'], $cartItem['vat']) . "<br>";
            }
        } else {
            echo "Shopping Cart is empty.";
        }
    }

    private static function getProductInfo($productId)
    {
        $db = Database::getInstance();
        $result = $db->query("SELECT * FROM `products` WHERE `id` = $productId LIMIT 1");

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    private static function getCartItem($productId)
    {
        $db = Database::getInstance();
        $session_id = session_id();

        $result = $db->query("SELECT * FROM `shopping_cart` WHERE `session_id` = '$session_id' AND `product_id` = $productId LIMIT 1");

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null;
    }

    private static function getCartItems()
    {
        $db = Database::getInstance();
        $session_id = session_id();

        $result = $db->query("SELECT * FROM `shopping_cart` WHERE `session_id` = '$session_id'");

        if ($result && $result->num_rows > 0) {
            $cartItems = array();
            while ($row = $result->fetch_assoc()) {
                $cartItems[] = $row;
            }
            return $cartItems;
        }

        return null;
    }

    private static function insertCartItem($productId, $quantity, $price, $vat)
    {
        $db = Database::getInstance();
        $session_id = session_id();

        $stmt = $db->prepare("INSERT INTO `shopping_cart` 
            (`session_id`, `product_id`, `quantity`, `price`, `vat`) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siidd", $session_id, $productId, $quantity, $price, $vat);
        $stmt->execute();
        $stmt->close();
    }

    private static function updateCartItem($productId, $quantity)
    {
        $db = Database::getInstance();
        $session_id = session_id();

        $stmt = $db->prepare("UPDATE `shopping_cart` 
            SET `quantity` = ? 
            WHERE `session_id` = ? AND `product_id` = ?");
        $stmt->bind_param("isi", $quantity, $session_id, $productId);
        $stmt->execute();
        $stmt->close();
    }

    private static function deleteCartItem($productId)
    {
        $db = Database::getInstance();
        $session_id = session_id();

        $stmt = $db->prepare("DELETE FROM `shopping_cart` 
            WHERE `session_id` = ? AND `product_id` = ?");
        $stmt->bind_param("si", $session_id, $productId);
        $stmt->execute();
        $stmt->close();
    }

    private static function calculateTotalPrice($quantity, $price, $vat)
    {
        return $quantity * ($price + $price * $vat);
    }
}
?>