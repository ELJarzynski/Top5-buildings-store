<?php
require_once 'Database.php';

class Product
{
    private int $id;
    private string $title;
    private ?string $description;
    private string $creationDate;
    private string $modificationDate;
    private ?string $expirationDate;
    private float $netPrice;
    private float $vat;
    private int $availableQuantity;
    private string $availabilityStatus;
    private ?int $categoryId;
    private ?string $productDimensions;
    private ?string $imageUrl;
    private $db;

    public function __construct(int $id)
    {
        $this->db = Database::getInstance();

        $result = $this->db->query("SELECT * FROM `products` WHERE `id` = $id LIMIT 1");
        $data = $result->fetch_assoc();
        $this->id = $id;
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->creationDate = $data['creation_date'];
        $this->modificationDate = $data['modification_date'];
        $this->expirationDate = $data['expiration_date'];
        $this->netPrice = $data['net_price'];
        $this->vat = $data['vat'];
        $this->availableQuantity = $data['available_quantity'];
        $this->availabilityStatus = $data['availability_status'];
        $this->categoryId = $data['category_id'];
        $this->productDimensions = $data['product_dimensions'];
        $this->imageUrl = $data['image_url'];
    }

    public function __toString()
    {
        $text = "<h3>$this->title</h3>";
        $text .= "<p>Description: $this->description</p>";
        $text .= "<p>Net Price: $this->netPrice</p>";
        $text .= "<p>Available Quantity: $this->availableQuantity</p>";
        return $text;
    }

    public static function getAllProducts()
    {
        $db = Database::getInstance();
        $result = $db->query("SELECT * FROM `products` LIMIT 100");
        $products = array();
        while ($data = $result->fetch_assoc()) {
            $productData = array(
                'id' => $data['id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'creation_date' => $data['creation_date'],
                'modification_date' => $data['modification_date'],
                'expiration_date' => $data['expiration_date'],
                'net_price' => $data['net_price'],
                'vat' => $data['vat'],
                'available_quantity' => $data['available_quantity'],
                'availability_status' => $data['availability_status'],
                'category_id' => $data['category_id'],
                'product_dimensions' => $data['product_dimensions'],
                'image_url' => $data['image_url']
            );
            array_push($products, $productData);
        }
        return $products;
    }

    public static function DodajProdukt(array $data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO `products` 
            (`title`, `description`, `net_price`, `vat`, `available_quantity`, `availability_status`, `category_id`, `product_dimensions`, `image_url`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssddisiss",
            $data['title'],
            $data['description'],
            $data['net_price'],
            $data['vat'],
            $data['available_quantity'],
            $data['availability_status'],
            $data['category_id'],
            $data['product_dimensions'],
            $data['image_url']
        );

        if ($stmt->execute()) {
            echo "Product added successfully";
            header("REFRESH:0");
        } else {
            echo "Product couldn't be added";
        }

        $stmt->close();
    }

    public static function UsunProdukt(int $productId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM `products` WHERE `id` = ?");
        $stmt->bind_param("i", $productId);

        if ($stmt->execute()) {
            echo "Product deleted successfully";
            header("REFRESH:0");
        } else {
            echo "Product couldn't be deleted";
        }

        $stmt->close();
    }

    public static function EdytujProdukt(array $data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE `products` SET 
            `title` = ?, `description` = ?, `net_price` = ?, `vat` = ?, `available_quantity` = ?, 
            `availability_status` = ?, `category_id` = ?, `product_dimensions` = ?, `image_url` = ? 
            WHERE `id` = ?");
        $stmt->bind_param(
            "ssddisissi",
            $data['title'],
            $data['description'],
            $data['net_price'],
            $data['vat'],
            $data['available_quantity'],
            $data['availability_status'],
            $data['category_id'],
            $data['product_dimensions'],
            $data['image_url'],
            $data['id']
        );

        if ($stmt->execute()) {
            echo "Product updated successfully";
            header("Location:admin.php");
        } else {
            echo "Product couldn't be updated";
        }

        $stmt->close();
    }

    public static function PokazProdukty()
    {
        $db = Database::getInstance();
        $result = $db->query("SELECT * FROM `products` LIMIT 100");
        $products = array();
        while ($data = $result->fetch_assoc()) {
            $productData = array(
                'id' => $data['id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'creation_date' => $data['creation_date'],
                'modification_date' => $data['modification_date'],
                'expiration_date' => $data['expiration_date'],
                'net_price' => $data['net_price'],
                'vat' => $data['vat'],
                'available_quantity' => $data['available_quantity'],
                'availability_status' => $data['availability_status'],
                'category_id' => $data['category_id'],
                'product_dimensions' => $data['product_dimensions'],
                'image_url' => $data['image_url']
            );
            array_push($products, $productData);
        }

        // Możesz dostosować sposób wyświetlania produktów zależnie od potrzeb
        echo "<h1>Products</h1>";
        foreach ($products as $product) {
            echo "<h3>{$product['title']}</h3>";
            echo "<p>Description: {$product['description']}</p>";
            echo "<p>Net Price: {$product['net_price']}</p>";
            echo "<p>Available Quantity: {$product['available_quantity']}</p>";
            echo "<hr>";
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function getModificationDate(): string
    {
        return $this->modificationDate;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }

    public function getNetPrice(): float
    {
        return $this->netPrice;
    }

    public function getVat(): float
    {
        return $this->vat;
    }

    public function getAvailableQuantity(): int
    {
        return $this->availableQuantity;
    }

    public function getAvailabilityStatus(): string
    {
        return $this->availabilityStatus;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function getProductDimensions(): ?string
    {
        return $this->productDimensions;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }
}
// Przykład użycia konstruktora

// Przykład użycia metody __toString()
echo "\nProduct details as string:\n";
$product = new Product(1); // Dla przykładu pobieramy produkt o ID 1
echo $product->__toString();

// Przykład użycia metody getAllProducts()
echo "\nAll Products:\n";
$allProducts = Product::getAllProducts();
foreach ($allProducts as $productData) {
    echo "ID: " . $productData['id'] . ", Title: " . $productData['title'] . "\n";
}

// Przykład użycia metody showAllCategories()
echo "\nSelect Category Form:\n";
$selectForm = Product::PokazProdukty();
echo $selectForm;
?>

