<?php
if(!empty($_POST)){
    require('database/Connection.php');
    $con = new Connection();
    $con = $con->getConnection();
    if($con){
        $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $query = $con->prepare("INSERT INTO users( name, email, username, password) VALUES ( ?,?,?,?)");
        $query->bindParam(1, $_POST['name']);
        $query->bindParam(2, $_POST['email']);
        $query->bindParam(3, $_POST['username']);
        $query->bindParam(4,$pass);
        if($query->execute()){
            header("Location:/auth/login");
        }
    }
}
else{
    header("Location:/auth/login");
}
