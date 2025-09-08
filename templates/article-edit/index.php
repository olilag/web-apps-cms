
<link rel="stylesheet" type="text/css" href="../assets/style.css">
</head>

<body>
<main>
    <div id="content">
    <label class="label-name" for="name">Name</label>
        <form id="edit" action="../article/<?=$result['id'];?>" method="post">
            <input class="article-name" id="name" name="name" type="text" value="<?= htmlspecialchars($result['name']);?>" maxlength="32" required>
            <label class="label-name" for="article-content">Content</label>
            <textarea id="article-content" name="content" wrap="soft" maxlength="1024"><?= htmlspecialchars($result['content']);?></textarea>
        </form>
        <div id="edit-buttons">
            <button id="save" class="left green" type="submit" form="edit">Save</button>
            <button id="back" class="right red" type="button">Back to articles</button>
        </div>
    </div>
</main>
<script src="../assets/article-edit.js"></script>
