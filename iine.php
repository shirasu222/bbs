<?php
/**
 * いいね！機能、データベースにいいねを記録する
 */
if (isset($_GET["id"])) {
    $pdo = new PDO(
        "mysql:dbname=sp91;host=localhost;charaset=utf8;",
        "root", 
        "root" 
    );
    $sql = "INSERT INTO likes (postId) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $row = $stmt->execute([
        $_GET["id"],
    ]);
}
?>
