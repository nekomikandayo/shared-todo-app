<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>グループ参加確認</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<div class="container">

    <h1>グループ参加確認</h1>

    <p>
        「<?php echo e($invite['group_name']); ?>」
        に参加しますか？
    </p>

    <form method="post" action="/groups/join/<?php echo e($token); ?>">
        <?php echo Form::csrf(); ?>

        <button type="submit">
            参加する
        </button>
    </form>

    <br>

    <a href="/login">
        キャンセル
    </a>

</div>

</body>
</html>