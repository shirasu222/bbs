<?php
/**
 * 投稿する
 */
session_start();

$message = "";
$replyTo = null;
$email = $_SESSION["userEmail"];
$text = "";

if(isset($_GET["replyTo"])){
    $replyTo = $_GET["replyTo"];
}

if(isset($_GET["text"])) {
    $pdo = new PDO(
        "mysql:dbname=sp91;host=localhost;charaset=utf8;",
        "root", 
        "root" 
    );
    $sql = "INSERT INTO posts (userEmail, text, replyTo) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $row = $stmt->execute([
        $_GET["email"],
        $_GET["text"],
        $replyTo
    ]);
    if ($row) {
        $_SESSION["userEmail"] = $_GET["email"];
        header("Location: ./index.php");
    } else {
        // fail
        $message = "エラーが発生しました。";
        $email = $_GET["email"];
        $text = $_GET["text"];
    } 
}
?>

<?php include "./header.php"; ?>
<main class="grow p-4 my-4">
    <p><?= $message ?></p>
    <form action="./post.php" method="GET">
        <?php
            if(!is_null($replyTo)) {
                echo '<input type="hidden" name="replyTo" value="' . $replyTo . '">';
            }
        ?>
        <div>
            <label class="m-2 text-xs" for="email">メールアドレス</label>
            <input class="block w-full px-4 border rounded-lg m-2 shadow resize-none" required type="text" name="email"
                id="email" value="<?= $email ?>">
        </div>
        <div class="mt-6">
            <label class="m-2 text-xs" or="text">本文</label>
            <textarea class="block w-full px-4 py-2 border rounded-lg m-2 shadow resize-none" required name="text"
                id="text" cols="30" rows="10"><?= $text ?></textarea>
        </div>
        <div class="mt-10">
            <input
                class="m-2 py-3 block w-full tracking-widest text-white text-sm rounded-lg bg-lime-600 hover:bg-indigo-900 transition-all"
                type="submit" value="書き込みを投稿する">
        </div>
    </form>

</main>
<?php include "./footer.php"; ?>