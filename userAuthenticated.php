<?php
if (!isset($_SESSION['username'], $_SESSION['password'])) {
    require('database/Connection.php');
    if (!empty($_POST)) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $con = new Connection();
        $con = $con->getConnection();
        $userFound = false;
        if ($con) {
            $query = $con->prepare("SELECT * FROM users WHERE username = ?");
            $query->bindParam(1, $user);
            $query->execute();
            $tempUser = $query->fetch();
            if (password_verify($pass, $tempUser['password'])) {
                $userFound = true;
            }
        }
        if (!$userFound) {
            session_destroy();
            header("Location:/auth/login");
        }
        $_SESSION['username'] = $user;
        $_SESSION['id'] = $tempUser['id'];
        $_SESSION['password'] = $pass;
        echo $_SESSION['username'];
    }else{
        header('Location:/auth/login');
    }
}
header('Location:/');
?>