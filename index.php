<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require 'controller/Controller.php';
$router = new \Bramus\Router\Router();
$loader = new Twig_Loader_Filesystem('./views');
$twig = new Twig_Environment($loader);
$controller = new Controller();

$router->before('GET','/auth/(\w+)', function($name) {
    if($name === "login" || $name === "register"){
        if(isset($_SESSION['username'])){
            header("Location:/");
        }
    }
});

$router->get('/auth/login', function() use ($twig,$controller){
    $controller->getLogin($twig);
});

$router->get('/auth/register', function()use ($twig,$controller){
    $controller->getRegister($twig);
});

$router->post('/auth/login', function()use ($controller){
    $controller->postLogin();
});

$router->post('/auth/register', function()use ($controller){
    $controller->postRegister();
});

$router->before('GET', '/', function(){
    if(!isset($_SESSION['username'])){
        header("Location:/auth/login");
    }
});

$router->post('/upload', function(){

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    if ($_FILES['image']['size'] == 0 && $_FILES['image']['error'] == 4 ){
        echo "Invalid Image";
    }
    else{
        $rand = generateRandomString();
        $path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/post/" . $rand . basename($_FILES['image']['name']);
        echo $_POST['image'];
        $uri = "/uploads/post/" .$rand.basename($_FILES['image']['name']);
        move_uploaded_file($_FILES["image"]["tmp_name"], $path);

        include('database/Connection.php');

        $con = new Connection();
        $con = $con->getConnection();
        $caption = $_POST['post_text'];
        $stmt = $con->prepare('INSERT INTO posts(post, user_id, picture) VALUES(?,?,?)');
        $stmt->bindParam(1, $caption);
        $stmt->bindParam(2, $_SESSION['id']);
        $stmt->bindParam(3, $uri);

        if($stmt->execute()){
            header("Location:/");
        }
    }
});

$router->get('/files', function(){

    include('database/Connection.php');
    $con = new Connection();
    $con = $con->getConnection();
    $stmt = $con->prepare('SELECT * FROM posts');
    $stmt->execute();

    while($results = $stmt->fetch()){
        echo '<img src="'.$results['picture'].'" width=500> <a href="/delete/'.$results['id'].'">Delete</a><br>';
    }
});

$router->get('/delete/(\w+)', function($id) {
    include('database/Connection.php');
    $con = new Connection();
    $con = $con->getConnection();
    $stmt = $con->prepare('SELECT * FROM posts where id=?');
    $stmt->bindParam(1,$id);
    $stmt->execute();
    $photo = $stmt->fetch();

    if(unlink($_SERVER['DOCUMENT_ROOT'].$photo['picture'])){
        $stmt = $con->prepare('DELETE FROM posts where id=?');
        $stmt->bindParam(1,$id);
        if($stmt->execute()){
            header("Location:/files");
        }
    }
});

$router->get('/', function() use($controller,$twig) {
    $arr = $controller->getPosts($_SESSION['id']);
    $controller->getFeedPage($arr,$twig);
});

$router->run();



