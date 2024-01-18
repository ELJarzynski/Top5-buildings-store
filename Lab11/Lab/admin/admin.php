<?php
session_start();
include "../scripts/functions.php";
function loginForm()
{
    return
    '		<main>
    <div id="sznyc">
    <form name="loginForm" method="post" enctype = "multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'"">
    <table>
       <tr>
           <td>Login: </td><td><input type="text" id="login" name="login" required placeholder="Wpisz login"></td>
       </tr>
       <tr>
           <td>Hasło: </td><td><input type="password" id="passw"  name="password" required placeholder="Wpisz hasło"></td>
       </tr>
       <tr>
       <td</td><td><input type="submit" id="sub"  name="sub" value="Zaloguj się"></td>
   </tr>
   </table>
    </form>
    </div>
   </main>';
}

function logoutForm()
{
    return
    '		<main>
    <div id="sznyc">
    <form name="loginForm" method="post" enctype = "multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'"">
    <input type="submit" id="sub"  name="logout" value="Wyloguj się">
    </form>
    </div>
   </main>';
}

function editPageForm($id)
{
    $db = database::getInstance();
    $result = $db->query("SELECT `page_title`, `page_content`, `status` FROM `page_list` WHERE `id` = $id");

    $data = $result->fetch_assoc();

    $form = '';
    $form.=     '<form name="contentForm" method="post" action="'.$_SERVER['REQUEST_URI'].'">
    <label for="title">Title:</label>   
    <input type="text" id="title" name="title" value="'.$data['page_title'].'">

    <label for="content">Page Content:</label>
    <textarea id="content" rows=10 cols=100 name="content">'.$data['page_content'].'</textarea>';

    if($data['status'] == 0)
    {
       $form.= '<label>
        <input type="checkbox" id="isActive" name="isActive" value="0">
        Site is active
    </label>

    <input type="submit" name="editContent" value="Submit Content">
    </form>';
    }
    else
    {
        $form.=  '<label>
        <input type="checkbox" id="isActive" name="isActive" value="1" checked>
        Site is active
    </label>

    <input type="submit" name="editContent" value="Submit Content">
    </form>';
    }

    return $form;
}

function removePage($id)
{
    global $link; 
    $db = database::getInstance();
    if($db->query("DELETE FROM `page_list` WHERE `id` = $id"))
    {
        echo "Page removed succesfully";
    }
    else
    {
        echo "Page couldn't be removed";
    }
}

function updatePage($data)
{
    $db = database::getInstance();
    if($db->query("UPDATE `page_list` SET `page_title` = '$data[0]', `page_content` = '$data[1]', `status` = '$data[2]' WHERE `id` = $data[3]"))
    {
        echo "Page updated succesfully";
    }
    else
    {
        echo "Page couldn't be updated";
    }
}

function addPageForm()
{
    $db = database::getInstance();
    $form ='';
    $form.=     '<form name="contentForm" method="post" action="'.$_SERVER['REQUEST_URI'].'">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" value="title">

    <label for="content">Page Content:</label>
    <textarea id="content" rows=10 cols=100 name="content"><main></main></textarea>
    <label>
        <input type="checkbox" id="isActive" name="isActive" value="1" checked>
        Site is active
    </label>

    <input type="submit" name="addContent" value="Submit Content">
    </form>';

    return $form;
}

function addPage($data)
{
    $db = database::getInstance();
    if($db->query("INSERT INTO `page_list`(`page_title`, `page_content`, `status`) VALUES ('$data[0]','$data[1]','$data[2]')"))
    {
        echo "Page added succesfully";
    }
    else
    {
        echo "Page couldn't be added";
    }
}

function logout()
{
    unset($_SESSION['user']);
    session_destroy();
    header("Refresh:0");
}

function login($userName, $pass)
{
    if(checkLogin($userName, $pass)) 
    {
        $_SESSION['user'] = createToken();
        header("Refresh:0");
    }
    else
    {
        echo "Logowanie nie powiodło się";
    }
}

function createToken()
{
    $token = md5(uniqid(mt_rand(), true));
    return $token;
}

function checkLogin($userName, $pass)
{
    if (empty($userName) || empty($pass))
    {
        return false;
    }
    if($userName === cfg::$user_name && $pass === cfg::$user_pass)
    {
        return true;
    }
    return false;
}

function subPagesList()
{
    $db = database::getInstance();
    $result = $db->query("SELECT `id`, `page_title` FROM `page_list` LIMIT 100");
    $select = '<form name="selectPage" method="get" action="'.$_SERVER['REQUEST_URI'].'"><select name = "pages">';

    while($data = $result->fetch_assoc())
    {
        $select.='<option value="'.$data['id'].'">'.$data['page_title'].'</option>';
    }
    $select.='<option value="add">Add new page</option></select>';
    $select.='<input type="submit" name = "sub" value="Wybierz">';
    $select.='<input type="submit" name = "sub" value="Usuń"></form>';
    return $select;
}

if(isset($_SESSION['user']))
{
    echo logoutForm();
    echo subPagesList();
    if(isset($_GET['sub']))
    {
        if($_GET['sub'] === "Wybierz")
        {

            if($_GET['pages'] != 'add')
                echo editPageForm($_GET['pages']);
            else
            {
                echo addPageForm();
            } 
        }
        else
        {
            if($_GET['pages'] === 'add')
                echo "Wrong option";
            else
            {
                removePage($_GET['pages']);
            }
        }
    }
}
else
{
    echo loginForm();
}

if(isset($_POST['editContent']))
{
    $data = [$_POST['title'],
    $_POST['content'],
    $_POST['isActive'], $_GET['pages']];
    updatePage($data);
}
if(isset($_POST['addContent']))
{
    $data = [$_POST['title'],
    $_POST['content'],
    $_POST['isActive']];
    addPage($data);
}

if(isset($_POST['login']) && isset( $_POST['password']))
{
    login($_POST['login'], $_POST['password']);
}

if(isset($_POST['logout']))
{
    logout();
}




?>