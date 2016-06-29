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
$router->get('temp', function() use ($twig,$controller){
    include ('temp.php');
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

$router->get('/', function() use($controller,$twig) {
    $arr = $controller->getPosts();
    $controller->getProfilePage($arr,$twig);
});

$router->run();



