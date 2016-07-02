<?php

print_r($_GET);
require('database/Connection.php');
$con = new Connection();
$con = $con->getconnection();
if ($con) {
    $query = $con->prepare("INSERT INTO comments( post_id, user_id, comment) VALUES (?,?,?)");
    $query->bindParam(1, $_GET['post_id']);
    $query->bindParam(2, $_GET['user_id']);
    $query->bindParam(3, $_GET['comment']);
    $query->execute();
    echo json_encode(array("commentAdd" => true));
}
