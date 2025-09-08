
<link rel="stylesheet" type="text/css" href="assets/style.css">
</head>

<body>
<main>
    <dialog id="create-dialog">
        <form method="POST">
            <label class="label-name" for="name">Article name</label>
            <input class="article-name" type="text" id="name" name="name" maxlength="32" required>
            <button id="dialog-submit" class="left dialog-button dialog-disabled" type="submit" disabled>Create</button>
            <button id="dialog-cancel" class="right dialog-button red">Cancel</button>
        </form>
    </dialog>
    <div id="content">
        <h1>Article list</h1>
        <div id="list-head">
            <span id="article-name">Article name</span>
            <span id="number-dislikes" class="right">Dislikes</span>
            <span id="number-likes" class="right">Likes</span>
        </div>
        <div id="articles">
            <ul>
<?php
$counter = 0;
while ($row = $result->fetch_assoc())
{
    $style = $counter < 10 ? "block" : "none";
    echo "<li style=\"display: $style;\">";
    echo htmlspecialchars($row['name']);
?>
<span class="article-buttons" id="<?= $row['id'];?>">   <!-- use data-id -->
    <span class="likes"><?= $row['likes'];?></span>
    <span class="dislikes"><?= $row['dislikes'];?></span>
    <a href="article/<?= $row['id'];?>" class="show">Show</a>
    <a href="article-edit/<?= $row['id'];?>" class="edit">Edit</a>
    <button class="red delete">Delete</button>
</span>
<?php
    echo "</li>";
    $counter++;
}

?>
            </ul>
        </div>
        <div id="articles-buttons">
            <div class="left">
                <button id="previous" disabled>Previous</button>
                <button id="next"<?= $counter < 10 ? "disabled" : ""?>>Next</button>
            </div>
            <div class="right">
                <button id="sort-by-likes" class="sort">↕</button>
                <button id="sort-by-dislikes" class="sort">↕</button>
                <span id="page-count">Page count: <?= ceil($counter/10)?></span>
                <button id="create-article">Create article</button>
            </div>
        </div>
    </div>
</main>
<script src="assets/articles.js"></script>
