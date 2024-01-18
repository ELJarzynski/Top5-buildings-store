<!DOCTYPE html>
<html>
<head>
    <!-- Dołączenie jQuery oraz pliki skryptowe niestandardowe z odroczonym ładowaniem. -->
    <script>
        window.addEventListener("load", myInit, true); function myInit(){
            rememberSelectedItem(document.getElementById('queryList'))
            rememberSelectedItem(document.getElementById('categoryList'))
            rememberSelectedItem(document.getElementById('productList'))
        }
    </script>
</head>
<body>
    <main>
        <?php
            session_start();
            include "../scripts/databaseloginerror.php";
            require_once "../scripts/product.php";
            require_once "../scripts/category.php";

            class PageManager
            {
                private $db;

                public function __construct()
                {
                    // Inicjalizacja połączenia z bazą danych
                    $this->db = Database::getInstance();
                }

                // Metoda sprawdza, czy administrator jest zalogowany, a następnie renderujemy odpowiednie elementy panelu administracyjnego
                // W tym miejscu obsługiwane są także przesłane dane POST z formularzy edycji, dodawania i usuwania stron, kategorii oraz produktów

                public function render()
                {
                    if (isset($_SESSION['user'])) {
                        echo $this->logoutForm(); 
                        echo $this->subPagesList();
                        echo Category::showAllCategories();
                        echo Product::showAllProducts();

                        // Sprawdzenie czy przesłano zmienną $_GET['sub']
                        if (isset($_GET['sub'])) {
                            if ($_GET['sub'] === "Wybierz") {
                                if ($_GET['pages'] != 'add') {
                                    echo $this->editPageForm($_GET['pages']);
                                } else {
                                    echo $this->addPageForm();
                                }
                            } else {
                                if ($_GET['pages'] === 'add') {
                                    echo "Wrong option";
                                } else {
                                    $this->removePage($_GET['pages']);
                                }
                            }
                        }
                                
                        if (isset($_GET['subCat'])) {
                            if ($_GET['subCat'] === "Wybierz") {
                                if ($_GET['categories'] != 'add') {
                                    echo $this->editCategoryForm($_GET['categories']);
                                } else {
                                    echo $this->addCategoryForm();
                                }
                            } else {
                                if ($_GET['categories'] === 'add') {
                                    echo "Wrong option";
                                } else {
                                    Category::removeCategory($_GET['categories']);
                                }
                            }
                        }

                        if (isset($_GET['subProd'])) {
                            if ($_GET['subProd'] === "Wybierz") {
                                if ($_GET['product'] != 'add') {
                                    echo $this->editProductForm($_GET['product']);
                                } else {
                                    echo $this->addProductForm();
                                }
                            } else {
                                if ($_GET['product'] === 'add') {
                                    echo "Wrong option";
                                } else {
                                    Product::removeProduct($_GET['product']);
                                }
                            }
                        }

                    } else {
                        // Jeżeli administrator nie jest zalogowany, wyświetla formularz logowania
                        echo $this->loginForm();
                    }

                    if (isset($_POST['editProduct'])) {
                        $target_dir = "../pictures/shop/";
                        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
                        $checkProduct = 1;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                        // Sprawdzenie czy wybrany plik jest zdjęciem
                        if(isset($_POST["submit"])) {
                        $check = getimagesize($_FILES["photo"]["tmp_name"]);
                        if($check !== false) {
                            echo "File is an image - " . $check["mime"] . ".";
                            $checkProduct = 1;
                        } else {
                            echo "File is not an image.";
                            $checkProduct = 0;
                        }
                        }
                        
                        $data = [
                            'title' => $_POST['title'],
                            'description' => $_POST['description'],
                            'netto_price' => $_POST['netto_price'],
                            'vat' => $_POST['vat'],
                            'quantity' => $_POST['quantity'],
                            'expiration_time' => $_POST['expirationTime'],
                            'availability' => isset($_POST['availability']) ? 1 : 0,
                            'category_id' => $_POST['category_id'],
                            'dimensions' => $_POST['dimensions'],
                            'photo' => $_FILES["photo"]["name"],
                        ];
                        $product = new Product($_GET['product']);
                        $product->editProduct($data);
                    }

                    if (isset($_POST['addProduct'])) {
                        $target_dir = "../pictures/shop/";
                        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
                        $checkProduct = 1;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                        // Sprawdzenie czy wybrany plik jest zdjęciem
                        if(isset($_POST["submit"])) {
                        $check = getimagesize($_FILES["photo"]["tmp_name"]);
                        if($check !== false) {
                            echo "File is an image - " . $check["mime"] . ".";
                            $checkProduct = 1;
                        } else {
                            echo "File is not an image.";
                            $checkProduct = 0;
                        }
                        }
                        
                        $data = [
                            'title' => $_POST['title'],
                            'description' => $_POST['description'],
                            'netto_price' => $_POST['netto_price'],
                            'vat' => $_POST['vat'],
                            'quantity' => $_POST['quantity'],
                            'expiration_time' => $_POST['expirationTime'],
                            'availability' => isset($_POST['availability']) ? 1 : 0,
                            'category_id' => $_POST['category_id'],
                            'dimensions' => $_POST['dimensions'],
                            'photo' => $_FILES["photo"]["name"],
                        ];
                        Product::addProduct($data);

                    }

                    // Sprawdzenie przesłanych danych z formularza edycji
                    if (isset($_POST['editContent'])) {
                        $data = [$_POST['title'], htmlspecialchars($_POST['content']), $_POST['isActive'], $_GET['pages']];
                        $this->updatePage($data);
                    }

                    // Sprawdzenie przesłanych danych z formularza dodawania
                    if (isset($_POST['addContent'])) {
                        $data = [$_POST['title'], $_POST['content'], $_POST['isActive']];
                        $this->addPage($data);
                    }

                    if(isset($_POST['editCategory']))
                    {
                        $data = array(
                            $_GET['category'],
                            $_POST['name'],
                            $_POST['parent']
                        );
                        Category::updateCategory($data);
                    }

                    if(isset($_POST['addCategory']))
                    {
                        $data = array(
                            $_POST['name'],
                            $_POST['parent']
                        );
                        Category::addCategory($data);
                    }

                    // Sprawdzenie przesłanych danych z formularza logowania
                    if(isset($_POST['sub']))
                    {
                        if (isset($_POST['login']) && isset($_POST['password'])) {
                            $this->login($_POST['login'], $_POST['password']);
                        }
                    }

                    // Sprawdzenie przesłanych danych z formularza wylogowania
                    if (isset($_POST['logout'])) {
                        $this->logout();
                    }
                }
                
                // Metoda generująca formularz logowania
                 
                private function loginForm()
                {
                    return '
                        <div id="mila">
                            <form name="loginForm" method="post" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                                <table>
                                    <tr>
                                        <td>Login: </td><td><input type="text" id="login" name="login" placeholder="Wpisz login"></td>
                                    </tr>
                                    <tr>
                                        <td>Hasło: </td><td><input type="password" id="passw" name="password" placeholder="Wpisz hasło"></td>
                                    </tr>
                                    <tr>
                                    <td><input type="submit" id="sub" name="sub" value="Zaloguj się"></td>
                                    </tr>
                                </table>
                            </form>
                        </div>';
                }

                
                // Metoda generująca formularz wylogowania
                
                private function logoutForm()
                {
                    return '
                        <div id="mila">
                            <form name="loginForm" method="post" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '">
                                <input type="submit" id="sub" name="logout" value="Wyloguj się">
                            </form>
                        </div>';
                }

                
                //Metoda generująca formularz edycji strony
                 
                private function editPageForm($id)
                {
                    $result = $this->db->query("SELECT `page_title`, `page_content`, `status` FROM `page_list` WHERE `id` = $id");
                    $data = $result->fetch_assoc();

                    $form = '<form name="contentForm" method="post" action="' . $_SERVER['REQUEST_URI'] . '">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="' . $data['page_title'] . '">

                        <label for="content">Page Content:</label>
                        <textarea id="content" rows=5 cols=100 name="content">' . $data['page_content'] . '</textarea>';

                    $form .= '<label>
                        <input type="checkbox" id="isActive" name="isActive" value="' . $data['status'] . '" ' . ($data['status'] == 1 ? 'checked' : '') . '>
                        Site is active
                    </label>

                    <input type="submit" name="editContent" value="Submit Content">
                    </form>';

                    return $form;
                }

                
                // Metoda usuwająca stronę
                
                private function removePage($id)
                {
                    $stmt = $this->db->prepare("DELETE FROM `page_list` WHERE `id` = ?");
                    $stmt->bind_param("i", $id);

                    if ($stmt->execute()) {
                        echo "Page removed successfully";
                        header("Location:admin.php");
                    } else {
                        echo "Page couldn't be removed";
                    }

                    $stmt->close();
                }

                
                // Metoda aktualizująca stronę
                
                private function updatePage($data)
                {
                    $stmt = $this->db->prepare("UPDATE `page_list` SET `page_title` = ?, `page_content` = ?, `status` = ? WHERE `id` = ?");
                    $stmt->bind_param("ssii", $data[0], $data[1], $data[2], $data[3]);

                    if ($stmt->execute()) {
                        echo "Page updated successfully";
                        header("Location:admin.php");
                    } else {
                        echo "Page couldn't be updated";
                    }

                    $stmt->close();
                }

                
                // Metoda generująca formularz dodawania nowej strony
                
                private function addPageForm()
                {
                    $form = '<form name="contentForm" method="post" action="' . $_SERVER['REQUEST_URI'] . '">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="title">

                        <label for="content">Page Content:</label>
                        <textarea id="content" rows=10 cols=100 name="content"></textarea>
                        <label>
                            <input type="checkbox" id="isActive" name="isActive" value="1" checked>
                            Site is active
                        </label>

                        <input type="submit" name="addContent" value="Submit Content">
                    </form>';

                    return $form;
                }

                
                // Metoda dodająca nową stronę
                
                private function addPage($data)
                {
                    $stmt = $this->db->prepare("INSERT INTO `page_list`(`page_title`, `page_content`, `status`) VALUES (?, ?, ?)");
                    $stmt->bind_param("ssi", $data[0], $data[1], $data[2]);

                    if ($stmt->execute()) {
                        echo "Page added successfully";
                        header("REFRESH:0");
                    } else {
                        echo "Page couldn't be added";
                    }

                    $stmt->close();
                }

                // Metoda edytująca kategorie

                private function editCategoryForm($id)
                {
                    $result = $this->db->query("SELECT `parent`, `name`FROM `categories` WHERE `id` = $id LIMIT 1");
                    $data = $result->fetch_assoc();
                    
                    $parentId = intval($_GET['categories']);

                    if($parentId != 0)
                    {
                        $result2 = $this->db->query("SELECT `parent` FROM `categories` WHERE `id` = $parentId LIMIT 1");
                        $data2 = $result2->fetch_assoc();

                        $parentId = intval($data2['parent']);
                        if($parentId != 0)
                        {
                            $result3 = $this->db->query("SELECT `name` FROM `categories` WHERE `id` = $parentId LIMIT 1");
                            $data3 = $result3->fetch_assoc(); 
                        }

                    }

                    $foundParent = false;
                    
                    $form = '<form name="contentForm" method="post" action="' . $_SERVER['REQUEST_URI'] . '">
                        <label for="title">Name:</label>
                        <input type="text" id="name" name="name" value="' . $data['name'] . '">
                        <label for="parent">Parent:</label>
                        <select id="parent" name="parent">';
                        foreach(Category::getAllCategories() as $category)
                        {   
                            if(isset($data3['name']))
                            {

                                if($category['name'] == $data3['name'])
                                {
                                    $foundParent = true;
                                    $form.='<option value='.$category['id'].' selected="selected">'.$category['name'].'</option>';
                                }

                                else
                                    $form.='<option value='.$category['id'].'>'.$category['name'].'</option>';
                            }
                            else
                            {
                                $form.='<option value='.$category['id'].'>'.$category['name'].'</option>';
                            }
                        }
                        if(!$foundParent)
                            $form.='<option value = "NOPARENT" selected="selected">Brak Rodzica</option>';
                        else
                            $form.='<option value = "NOPARENT">Brak Rodzica </option>';
                        $form.='</select>
                    <input type="submit" name="editCategory" value="Edit Category">
                    </form>';

                    return $form;
                }

                // Metoda dodająca kategorie

                private function addCategoryForm()
                {
                    $form = '<form name="contentForm" method="post" action="' . $_SERVER['REQUEST_URI'] . '">
                        <label for="title">Title:</label>
                        <input type="text" id="name" name="name" value="name">
                        <select id="parent" name="parent">';
                        foreach(Category::getAllCategories() as $category)
                        {
                            $form.='<option value='.$category['id'].'>'.$category['name'].'</option>';
                        }

                        $form.='<option value = "NOPARENT">Brak Rodzica</option>
                        </select>
                        <input type="submit" name="addCategory" value="Submit Category">
                    </form>';

                    return $form;
                }

                // Metoda edytująca kategorie

                private function editProductForm($id)
                {
                    $product = new Product($id);

                    $form = '<form name="productForm" method="post" action="' . $_SERVER['REQUEST_URI'] . '" enctype="multipart/form-data">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="' . $product->getTitle() . '">

                    <label for="description">Description:</label>
                    <textarea id="description" rows=3 cols=100 name="description">' . $product->getDescription() . '</textarea>

                    <label for="netto_price">Netto Price:</label>
                    <input type="text" id="price" name="netto_price" value="' . $product->getNettoPrice() . '">

                    <label for="vat">VAT:</label>
                    <input type="text" id="vat" name="vat" value="' . $product->getVat() . '">

                    <label for="quantity">Quantity:</label>
                    <input type="text" id="quantity" name="quantity" value="' . $product->getQuantity() . '">

                    <label for="expirationTime">Expiration Time:</label>
                    <input type="date" id="expirationTime" name="expirationTime" value="' . $product->getExpirationTime()->format('Y-m-d') . '">

                    <label for="availability">Availability: </label>';
                    if ($product->getAvailability())
                        $form .= '<input type="checkbox" id="availability" name="availability" checked>';
                    else
                        $form .= '<input type="checkbox" id="availability" name="availability">';

                    $db = Database::getInstance();
                    $result = $db->query("SELECT `id`, `parent`, `name` FROM `categories` LIMIT 100");
                    $select = '<br><label for="categoryList">Category:</label><select name="category_id" id="categoryList">';

                    while ($data = $result->fetch_assoc()) {
                        if ($data['id'] == $product->getCategory()->getId())
                            $select .= '<option value="' . $data['id'] . '" selected>' . $data['name'] . '</option>';
                        else
                            $select .= '<option value="' . $data['id'] . '">' . $data['name'] . '</option>';
                    }
                    $select .= '</select>';

                    $form .= $select;

                    $form .= '<label for="dimensions">Dimensions:</label>
                        <input type="text" id="dimensions" name="dimensions" value="' . $product->getDimensions() . '">

                        <label for="photo">Photo:</label><br>
                        <img src = "../pictures/shop/'.$product->getPhoto().'" width="256px" height="256px">
                        <input type="file" id = "photo" name="photo">
                        <br>
                        <br>
                        <input type="submit" name="editProduct" value="Submit Product">
                    </form>';

                    return $form;

                }

                // Metoda dodająca product

                private function addProductForm()
                {
                    $form = '<form name="productForm" method="post" action="' . $_SERVER['REQUEST_URI'] . '" enctype="multipart/form-data">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" value="Title">

                    <label for="description">Description:</label>
                    <textarea id="description" rows=10 cols=100 name="description"></textarea>

                    <label for="netto_price">Netto Price:</label>
                    <input type="text" id="netto_price" name="netto_price" value="0">

                    <label for="vat">VAT:</label>
                    <input type="text" id="vat" name="vat" value="0">

                    <label for="quantity">Quantity:</label>
                    <input type="text" id="quantity" name="quantity" value="0">

                    <label for="availability">Availability:</label>
                    <input type="checkbox" id="availability" name="availability">

                    <label for="expirationTime">Expiration date:</label>
                    <input type="date" id="expirationTime" name="expirationTime">

                    <label for="categoryList">Category:</label>
                    <select name="category_id" id="categoryList">';
                    
                    $db = Database::getInstance();
                    $result = $db->query("SELECT `id`, `parent`, `name` FROM `categories` LIMIT 100");
                    while ($data = $result->fetch_assoc()) {
                        $form .= '<option value="' . $data['id'] . '">' . $data['name'] . '</option>';
                    }

                    $form .= '</select>

                        <label for="dimensions">Dimensions:</label>
                        <input type="text" id="dimensions" name="dimensions">

                        <label for="photo">Photo:</label><br>
                        <input type="file" id="photo" name="photo">

                        <input type="submit" name="addProduct" value="Submit Product">
                    </form>';

                    return $form;
                }

                
                // Metoda wylogowująca administratora
                
                private function logout()
                {
                    unset($_SESSION['user']);
                    session_destroy();  
                }

                
                // Metoda logująca administratora
                
                private function login($userName, $pass)
                {
                    $userName = strtolower($userName);
                    if ($this->checkLogin($userName, $pass)) {
                        $_SESSION['user'] = $this->spawnLoginToken();
                    } else {
                        echo "Logowanie nie powiodło się";
                    }
                }

                
                // Metoda generująca unikalny token
                
                private function spawnLoginToken()
                {
                    $token = md5(uniqid(mt_rand(), true));
                    return $token;
                }

                
                // Metoda sprawdzająca poprawność danych logowania
                
                private function checkLogin($userName, $pass)
                {
                    if (empty($userName) || empty($pass)) {
                        return false;
                    }
                    if ($userName === Cfg::$user_name && $pass === Cfg::$user_pass) {
                        return true;
                    }
                    return false;
                }

                
                // Metoda generująca listę rozwijaną z podstronami
                
                private function subPagesList()
                {
                    $result = $this->db->query("SELECT `id`, `page_title` FROM `page_list` LIMIT 100");
                    $select = '<h1>STRONY</h1><form name="selectPage" method="get" action="' . $_SERVER['REQUEST_URI'] . '"><select name = "pages" id = "queryList">';

                    while ($data = $result->fetch_assoc()) {
                        $select .= '<option value="' . $data['id'] . '">' . $data['page_title'] . '</option>';
                    }
                    $select .= '<option value="add">Add new page</option></select>';
                    $select .= '<input type="submit" name = "sub" value="Wybierz">';
                    $select .= '<input type="submit" name = "sub" value="Usuń"></form>';
                    return $select;
                }
            }

            // Utworzenie instancji klasy PageManager i wywołanie metody render
            $pageManager = new PageManager();
            $pageManager->render();
        ?>
    </main>
</body>
</html>