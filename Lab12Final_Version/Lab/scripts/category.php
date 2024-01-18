<?php
require_once 'database.php';

class Category
{
    private int $id;
    private $parent; 
    private string $name;

    public function __construct(int $id)
    {
        $db = Database::getInstance();

        $result = $db->query("SELECT * FROM `categories` WHERE `id` = $id LIMIT 1");
        $data = $result->fetch_assoc();
        $this->id = $id;
        
        if (intval($data['parent']) != 0)
            $this->parent = new Category(intval($data['parent']));
        else
            $this->parent = NULL;

        $this->name = $data['name'];
    }

    public function __toString()
    {
        $children = $this->getChildren();
        $text = "<ul><li><a href='shop.php?cat=$this->id'>$this->name</a></li>";
    
        if (count($children) != 0)
        {
            foreach ($children as $child)
            {
                $text .= $child->__toString(); // Recurencjnie odwołuje sie do każdego dziecka dla __toString()
            }
        }
        $text .= "</ul>";
        return $text;
    }

    // Metoda jest odpowiedzialna za pobieranie i zwracanie obiektów reprezentujących dzieci kategorii z bazy danych.
    public function getChildren()
    {
        $children = [];
        $db = Database::getInstance();
        $result = $db->query("SELECT id FROM `categories` WHERE `parent` = $this->id");

        while ($data = $result->fetch_assoc())
        {

            $childrenCategory = new Category(intval($data['id']));
            array_push($children, $childrenCategory);
        }
        return $children;
    }

    // Metoda rekurencyjnie przemierza hierarchię kategorii, zbierając identyfikatory każdej kategorii w porządku przechodzenia w głąb.

    public function traverseCategory(&$categoryIds)
    {

        $categoryIds[] = $this->getId();

        foreach($this->getChildren() as $child)
        {
            $child->traverseCategory($categoryIds);
        }
    }

    // Metoda do pobrania wszystkich kategorii
    public static function getAllCategories()
    {
        $db = Database::getInstance();
        $result = $db->query("SELECT `id`, `parent`, `name` FROM `categories` LIMIT 100");
        $categories = array();

        while ($data = $result->fetch_assoc())
        {
            $categoryData = array('id' => $data['id'], 'parent' => $data['parent'], 'name' => $data['name']);
            array_push($categories, $categoryData);
        }
        return $categories;
    }

    // Metoda do generowania rozwijanej listy kategorii do wyboru
    public static function showAllCategories()
    {
        $db = Database::getInstance();
        $result = $db->query("SELECT `id`, `parent`, `name` FROM `categories` LIMIT 100");
        $select = '<h1>KATEGORIE</h1><form name="selectCategory" method="get" action="' . $_SERVER['REQUEST_URI'] . '"><select name = "categories" id = "categoryList">';

        while ($data = $result->fetch_assoc()) {
            $select .= '<option value="' . $data['id'] . '">' . $data['name'] . '</option>';
        }

        $select .= '<option value="add">Add new category</option></select>';
        $select .= '<input type="submit" name = "subCat" value="Wybierz">';
        $select .= '<input type="submit" name = "subCat" value="Usuń"></form>';
        return $select;
    }

    // Metoda usuwająca kategorie
    public static function removeCategory($id)
    {
        $category = new Category($id);

        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM `categories` WHERE `id` = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "Category removed successfully";
            header("Location: admin.php");
        } else {
            echo "Category couldn't be removed";
        } 

        foreach($category->getChildren() as $childCat)
        {
            Category::updateCategory([$childCat->getId(), $childCat->getName(), 0]);
        }
        
        $stmt->close();
    }

    // Metoda do dodawania nowej kategorii
    public static function addCategory(array $data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO `categories`(`name`, `parent`) VALUES (?, ?)");
        $stmt->bind_param("si", $data[0], $data[1]);

        if ($stmt->execute()) {
            echo "Category added successfully";
            header("REFRESH:0");
        } else {
            echo "Category couldn't be added";
        }

        $stmt->close();
    }

    // Metoda do edytowania kategorii
    public static function updateCategory(array $data)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE `categories` SET `name` = ?, `parent` = ? WHERE `id` = ?");
        $stmt->bind_param("ssi", $data[1], $data[2], $data[0]);

        if ($stmt->execute()) {
            echo "Category updated successfully";
        } else {
            echo "Category couldn't be updated";
        }

        $stmt->close();
    }

    // Gettery i Settery
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
?>