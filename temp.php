<?php
require('database/Connection.php');
$coni = new Connection();
$coni = $coni->getconnection();
if ($coni) {
    $arr = [];
    $query = $coni->prepare("SELECT * FROM posts");
    $query->execute();
    while ($posts = $query->fetch()) {
        $query2 = $coni->prepare("SELECT name FROM users WHERE id = ?");
        $query2->bindParam(1, $posts['user_id']);
        $query2->execute();
        $tempName = $query2->fetch();
        $tempName = $tempName[0];
        $query2 = $coni->prepare("SELECT * FROM comments WHERE post_id = ?");
        $query2->bindParam(1, $posts['id']);
        $query2->execute();
        $comments = [];
        while ($comment = $query2->fetch()) {
            $query3 = $coni->prepare("SELECT name FROM users WHERE id = ?");
            $query3->bindParam(1, $comment['user_id']);
            $query3->execute();
            $tempName2 = $query3->fetch();
            $tempName2 = $tempName2[0];
            array_push($comments ,array('c_name' => $tempName2 , 'comment' => $comment['comment']));
        }
        array_push($arr , array('name' => $tempName , 'post' => $posts['post'] , 'comments' => $comments));
    }
    print "<pre>";
    print_r($arr);
    print "</pre>";
}