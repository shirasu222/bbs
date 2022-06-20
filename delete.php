<?php
if (isset($_POST["id"])) {
    $pdo = new PDO(
        "mysql:dbname=sp91;host=localhost;charaset=utf8;",
        "root", // ユーザー名
        "root" // パスワード
    );
    $sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $row = $stmt->execute([
        $_POST["id"],
    ]);
    //var_dump($row);
    //var_dump($_POST["id"]);
}
    header("Location: ./index.php");
?>
