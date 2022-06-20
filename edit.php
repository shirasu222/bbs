<?php
/**
 * 投稿を編集する
 */
session_start();

$message = "";
$id = $_POST["id"];
$email = $_POST["email"];
$text = $_POST["text"];

if (isset($_POST["token"])) {
    $pdo = new PDO(
        "mysql:dbname=sp91;host=localhost;charaset=utf8;",
        "root", 
        "root" 
    );
    $sql = "UPDATE posts SET userEmail= ?, text = ? WHERE id = ?";

    $stmt = $pdo->prepare($sql);
    $row = $stmt->execute([
        $_POST["email"],
        $_POST["text"],
        $_POST["id"],
    ]);
    
    if ($row) {
        header("Location: ./index.php");
    } else {
        // fail
        $message = "エラーが発生しました。";
        $email = $_POST["email"];
        $text = $_POST["text"];
    }
}
?>

<?php include "./header.php"; ?>
<main class="grow p-4 my-4">
    <p><?= $message ?></p>
    <form action="./edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="token" value="haltokyo">
        <div>
            <label class="m-2 text-xs" for="email">メールアドレス</label>
            <input class="block w-full px-4 py-2 border rounded-lg m-2 shadow" required type="text" name="email"
                id="email" value="<?= $email ?>">
        </div>
        <div class="mt-6">
            <label class="m-2 text-xs" for="text">本文</label>
            <textarea class="block w-full px-4 py-2 border rounded-lg m-2 shadow resize-none" required name="text"
                id="text" cols="30" rows="10"><?= $text ?></textarea>
        </div>
        <div class="mt-10">
            <input
                class="m-2 py-3 block w-full tracking-widest text-white text-sm rounded-lg bg-indigo-700 hover:bg-indigo-900 transition-all"
                type="submit" value="書き込みを投稿する">
        </div>
    </form>

</main>
<?php include "./footer.php"; ?>
