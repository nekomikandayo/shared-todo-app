<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>グループ一覧</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .alert-success { color: green; border: 1px solid green; padding: 10px; margin-bottom: 10px; }
        .alert-error { color: red; border: 1px solid red; padding: 10px; margin-bottom: 10px; }
        .invite-box { background: #f9f9f9; padding: 10px; border: 1px dashed #ccc; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
    <h1>グループ一覧</h1>

    <?php if ($msg = Session::get_flash('success')): ?>
        <div class="alert-success"><?php echo e($msg); ?></div>
    <?php endif; ?>
    <?php if ($msg = Session::get_flash('error')): ?>
        <div class="alert-error"><?php echo e($msg); ?></div>
    <?php endif; ?>

    <?php if ($url = Session::get_flash('invite_url')): ?>
        <div class="invite-box">
            <p>✅ 招待URLを発行しました。24時間以内に共有してください：</p>
            <input type="text" value="<?php echo e($url); ?>" readonly style="width: 100%;">
            <p><small>※一度誰かが使用すると無効になります。</small></p>
        </div>
    <?php endif; ?>

    <section>
        <h2>新しいグループを作る</h2>
        <form action="/groups/create" method="post">
            <?php echo Form::csrf(); ?>
            <input type="text" name="group_name" placeholder="グループ名を入力" required>
            <button type="submit">作成</button>
        </form>
    </section>

    <hr>

    <section>
        <h2>参加中のグループ</h2>
        <?php if (!empty($groups)): ?>
            <ul>
                <?php foreach ($groups as $group): ?>
                    <li style="margin-bottom: 10px;">
                        <a href="/todo/group/<?php echo (int) $group['id']; ?>" style="font-weight: bold; font-size: 1.1em;">
                            <?php echo e($group['name']); ?>
                        </a>

                        <a href="/groups/invite/<?php echo (int) $group['id']; ?>" style="margin-left: 15px; color: #007bff; text-decoration: none;">
                        [招待URLを発行]
                        </a>

                        <form action="/groups/delete/<?php echo (int) $group['id']; ?>" method="post" style="display: inline; margin-left: 15px;">
                            <?php echo Form::csrf(); ?>
                            <button
                                type="submit"
                                style="color: #dc3545; background: none; border: none; padding: 0; cursor: pointer; text-decoration: none;"
                                onclick="return confirm('本当にこのグループを削除しますか？\n削除すると元に戻せません。');"
                            >
                                [削除]
                            </button>
                        </form>
                    </li>
                <?php endforeach; ?>             
            </ul>
        <?php else: ?>
            <p>参加中のグループはありません。</p>
        <?php endif; ?>
    </section>

    <br>
    <a href="/logout">ログアウト</a>
    </div>
</body>
</html>