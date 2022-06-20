<?php
/**
 * 掲示版の投稿一覧ページ
 */
session_start();

function getFormatDate($date) {
    return date_format(
        date_create($date),
        'Y年m月d日 H時i分s秒'
    );
}

$_SESSION["name"] = "Nonoka";

$sql = "";

if (isset($_GET["search"])) {
    $sql = "SELECT * FROM posts WHERE userEmail LIKE ? OR text LIKE ?";
} else {
    $sql = "SELECT * FROM `posts` WHERE 1";
}

$pdo = new PDO(
    "mysql:dbname=sp91;host=localhost;charaset=utf8;",
    "root", 
    "root" 
);

$stmt = $pdo->prepare($sql);
if (isset($_GET["search"])) {
    $stmt->execute([
    "%" . $_GET["search"] . "%",
    "%" . $_GET["search"] . "%",
    ]);
} else {
    $stmt->execute();
}
?>

<?php include "./header.php"; ?>
<main class="p-4 my-4 grow">
    <form class="relative my-4 shadow border rounded-full" method="get">
        <input value="<?= $GET_["search"] ?>" placeholder="メールアドレス or 本文の一部" class="w-full py-1 pl-4 pr-14 rounded-full placeholder:text-gray-300" type="search" name="search">
        <button type="submit" class="absolute top-0 bottom-0 right-0 px-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </form>

     <ul class="flex flex-col space-y-4">
        <?php
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <li class="p-4 border shadow rounded-xl bg-white" id="card_<?= $result["id"] ?>">
            <div class="card">

                <?php if(!is_null($result["replyTo"])) : ?>
                    <p class="text-xs">
                        <a href="#card_<?= $result["replyTo"] ?>"> >> <?= $result["replyTo"] ?></a>
                    </p>
                <?php endif ?>

                <p class="text-xs">
                        <time class="text-gray-900">
                            <?= getFormatDate($result["createdAt"])  ?>
                        </time>
                    </p>
                    <p class="text-sm pt-1">
                        <span><?= $result["userEmail"] ?></span>
                    </p>
                <p class="pt-1 leading-8">
                    <?= $result["text"] ?>
                </p>
                <div class="flex">
                    <div class="flex justify-between space-x-2 mt-4">
                        <p class="w-full">
                        <a class="grid w-full px-2 py-1 rounded-lg bg-lime-600 place-items-center" href="./post.php?replyTo=<?= $result["id"] ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-1 stroke-white inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                                </svg>
                            </a>
                        </p>
                        <form class="w-full" action="./edit.php" method="POST">
                            <input type="hidden" name="id" value="<?= $result["id"] ?>">
                            <input type="hidden" name="email" value="<?= $result["userEmail"] ?>">
                            <input type="hidden" name="text" value="<?= $result["text"] ?>">
                            <button class="grid place-items-center w-full px-2 py-1 rounded-lg bg-lime-600" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-1 stroke-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button> 
                        </form>
                        <form class="w-full" action="./delete.php" method="POST">
                            <input type="hidden" name="id" value="<?= $result["id"] ?>">
                            <button class="grid w-full px-2 py-1 rounded-lg place-items-center bg-rose-500" type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 stroke-1 stroke-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                            </button>
                        </form>  
                    </div>
                    <div class="space-x-2 mt-4 ml-auto">
                        <!--いいねボタン-->
                        <form class="w-full" action="./iine.php" method="POST">
                            <input type="hidden" name="id" value="<?= $result["id"] ?>">
                            <label class="flex justify-center w-full px-2 py-1 rounded-lg" type="submit">
                                <input type="checkbox" id="heart<?= $result["id"] ?>" data-post_id='<?= $result["id"] ?>' class="hidden peer like">
                                <svg xmlns="http://www.w3.org/2000/svg" class="transition-colors peer-checked:fill-rose-500 fill-white h-6 w-6 stroke-1 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span class="ml-1" id="heart<?= $result["id"] ?>_count">
                                    <?php
                                        $sql = "SELECT count(*) FROM `likes` WHERE postId = ?";
                                        $stmt1 = $pdo->prepare($sql);
                                        $row = $stmt1->execute([$result["id"]]);
                                        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                                        echo $result1["count(*)"];
                                    ?>
                                </span>
                            </label>
                        </form> 
                    </div>
                </div>
            </div>
        </li>
        <?php
        }
        ?>
    </ul>

</main>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.querySelectorAll(".like").forEach((element) => {
    element.onchange = function(event) {
        if (element.checked) {
            axios.get(`iine.php?id=${element.dataset.post_id}`)
            let count = document.getElementById(`heart${element.dataset.post_id}_count`)
            count.innerText = parseInt(count.innerText) + 1
        } else {
            let count = document.getElementById(`heart${element.dataset.post_id}_count`)
            count.innerText = parseInt(count.innerText) - 1
        }
    }
})
</script>
<?php include "./footer.php"; ?>