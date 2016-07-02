<?php
if(!empty($_POST)) {
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    require('database/Connection.php');
    $con = new Connection();
    $con = $con->getConnection();

    if ($_FILES['image']['size'] == 0 && $_FILES['image']['error'] == 4) {
        echo "Invalid Image";
    } else {
        $rand = generateRandomString();
        $path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/dp/" . $rand . basename($_FILES['image']['name']);
        $uri = "/uploads/dp/" . $rand . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES["image"]["tmp_name"], $path);
        $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $query = $con->prepare("INSERT INTO users( name, email, username, password,dp) VALUES ( ?,?,?,?,?)");
        $query->bindParam(1, $_POST['name']);
        $query->bindParam(2, $_POST['email']);
        $query->bindParam(3, $_POST['username']);
        $query->bindParam(4, $pass);
        $query->bindParam(5, $uri);
        if ($query->execute()) {
            header("Location:/auth/login");
        }
    }
}
else{
    header("Location:/auth/login");
}
