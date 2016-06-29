<?php
if(!empty($_POST)){
    require('database/Connection.php');
    $con = new Connection();
    $con = $con->getconnection();
    if ($con) {
        $query = $con->prepare("INSERT INTO comments( post_id, user_id, comment) VALUES (?,?,?)");
        $query->bindParam(1, $_POST['post_id']);
        $query->bindParam(2, $_POST['user_id']);
        $query->bindParam(3, $_POST['comment']);
        $query->execute();
        header('Location:/');
    }
}
