<?php
    class Controller{

    function getLogin($twig){
        echo $twig->render('login.html', array('the' => 'variables', 'go' => 'here'));
    }

    public function getRegister($twig){
        echo $twig->render('register.html', array('the' => 'variables', 'go' => 'here'));
    }

    public function postRegister(){
        include("addUser.php");
    }

    public function postLogin(){
        include ('userAuthenticated.php');
    }

    public function getFeedPage($arr, $twig){
        echo $twig->render('feed.html',
            array('data' =>  $arr , 'name'=>$_SESSION['username'] , 'user_id' => $_SESSION['id']));
    }

        /**
         * @param $id
         * @return array
         */
        public  function getPosts($id){
        require('database/Connection.php');
        $coni = new Connection();
        $coni = $coni->getconnection();
        if ($coni) {
            $arr = [];
            $tempArr = [];
            $query2 = $coni->prepare("SELECT dp FROM users WHERE id = ?");
            $query2->bindParam(1, $id);
            $query2->execute();
            $picture = $query2->fetch();
            $picture = $picture[0];
            array_push($arr ,  $picture);
            $query = $coni->prepare("SELECT * FROM posts ORDER BY id DESC");
            $query->execute();
            while ($posts = $query->fetch()) {
                $query2 = $coni->prepare("SELECT * FROM users WHERE id = ?");
                $query2->bindParam(1, $posts['user_id']);
                $query2->execute();
                $tempName = $query2->fetchAll();
                $tempName = $tempName[0];
                $name = $tempName[1];
                $pic = $tempName[5];
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
                    array_push($comments, array('c_name' => $tempName2, 'comment' => $comment['comment']));
                }
                array_push($tempArr, array('id' => $posts['id'],'name' => $name ,
                            'post_pic' => $posts['picture'],'pic' => $pic ,
                            'post' => $posts['post'],'comments' => $comments));
            }
        }
            array_push($arr,$tempArr);
        return $arr;
    }
}