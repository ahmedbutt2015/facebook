<!doctype html>
<html lang="en">
<?php
echo $_SESSION['username'];
$title = "Hogwards - Profile";
include('views/common/header.php');
?>
<body>
<a href="logout.php">Logout</a>
<div class="wrapper">
    <?php
    require('database/Connection.php');
    $coni = new Connection();
    $coni = $coni->getconnection();
    if ($coni) {
        $query = $coni->prepare("SELECT * FROM posts");
        $query->execute();
    }
    while ($posts = $query->fetch()) {
        $query2 = $coni->prepare("SELECT name FROM users WHERE id = ?");
        $query2->bindParam(1, $posts['user_id']);
        $query2->execute();
        $tempName = $query2->fetch();
        $tempName = $tempName[0];
        ?>
        <div class="post">

            <div class="post-header">
                <div class="post-header__dp">
                    <img class="post-header__dp--holder"
                         src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xla1/v/t1.0-1/p160x160/12310683_10208224569280060_2394597115133257066_n.jpg?oh=e104af77c2a82da5ced8e10f257abda8&oe=5802191B&__gda__=1477378048_18d4f63db2ad6f2dd72a237acc42d07e">
                </div>
                <div class="post-header__username">
                    <a class="post-header__username--text" href=""><?= $tempName ?></a>
                </div>
            </div>

            <div class="post-body">
                <div class="post-body__img">

                </div>

                <div class="post-body__caption">
                    <span class="post-body__caption--text">
                        <?= $posts['post'] ?>
                    </span>
                </div>
            </div>
            <?php
            $query2 = $coni->prepare("SELECT * FROM comments WHERE post_id = ?");
            $query2->bindParam(1, $posts['id']);
            $query2->execute();
            while ($comment = $query2->fetch()) {
                $query3 = $coni->prepare("SELECT name FROM users WHERE id = ?");
                $query3->bindParam(1, $comment['user_id']);
                $query3->execute();
                $tempName = $query3->fetch();
                $tempName = $tempName[0];
                ?>
                <div class="post-comments">
                    <div class="post_comment--body">
                        <div class="post_comment--username">
                    <span class="post_comment--username__text">
                        <?= $tempName ?>
                    </span>
                        </div>

                        <div class="post_comment__body">
                    <span class="post-comment__body--text">
                        <?= $comment['comment'] ?>
                    </span>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="comment_post">
                <div class="post-comment__dp">
                    <img
                        src="https://fbcdn-profile-a.akamaihd.net/hprofile-ak-xla1/v/t1.0-1/p160x160/12310683_10208224569280060_2394597115133257066_n.jpg?oh=e104af77c2a82da5ced8e10f257abda8&oe=5802191B&__gda__=1477378048_18d4f63db2ad6f2dd72a237acc42d07e"
                        alt="" class="comment_image--holder">
                </div>

                <div class="post-comment__input">
                    <form method="POST" action="postComment.php">
                        <input type="hidden" name="user_id" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" name="post_id" value="<?= $posts['id'] ?>">
                        <input name="comment" type="text" class="comment">
                        <input type="submit" value="Comment" class="btn btn-default btn-lg">
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<?php
include('views/common/footer.php');
?>
</body>
</html>
