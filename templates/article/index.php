
<link rel="stylesheet" type="text/css" href="../assets/style.css">
</head>

<body>
<main>
    <div id="content">
        <h1><?= htmlspecialchars($result['name']);?></h1>
        <p><?= htmlspecialchars($result['content']);?></p>
        <div id="article-buttons">
            <button id="edit-article" class="left blue" type="button">Edit</button>
<?php
$liked_style = $liked ? 'liked' : '';
$disliked_style = $disliked ? 'disliked' : '';
$liked_text = $liked ? 'Liked' : 'Like';
$disliked_text = $disliked ? 'Disliked' : 'Dislike';
?>
            <span class="like-dislike-buttons">
                <button id="like" class="<?= $liked_style?>" type="button" <?= $liked ? 'disabled' : ''?>><?= $liked_text?></button>
                <button id="dislike" class="<?= $disliked_style?>" type="button" <?= $disliked ? 'disabled' : ''?>><?= $disliked_text?></button>
            </span>
            <button id="back" class="right" type="button">Back to articles</button>
        </div>
    </div>
</main>
<script src="../assets/article.js"></script>
