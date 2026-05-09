<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>グループ一覧</title>

    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

    <div class="container container--narrow">

        <div class="group-page">

            <header class="group-section">
                <h1 class="page-title">
                    グループ一覧
                </h1>
            </header>

            <?php if ($msg = Session::get_flash('success')): ?>
                <div class="alert alert--success">
                    <?php echo e($msg); ?>
                </div>
            <?php endif; ?>

            <?php if ($msg = Session::get_flash('error')): ?>
                <div class="alert alert--error">
                    <?php echo e($msg); ?>
                </div>
            <?php endif; ?>

            <?php if ($url = Session::get_flash('invite_url')): ?>

                <div class="invite-box">

                    <p class="text-muted">
                        招待URLを発行しました（24時間以内に共有してください）
                    </p>

                    <input
                        class="invite-input"
                        type="text"
                        value="<?php echo e($url); ?>"
                        readonly
                    >

                    <p class="text-muted">
                        ※ 一度誰かが使用すると無効になります。
                    </p>

                </div>

            <?php endif; ?>

            <section class="group-section">

                <div class="card">

                    <div class="card-header">
                        <h2 class="group-card__title">
                            新しいグループを作る
                        </h2>
                    </div>

                    <div class="card-body">

                        <form
                            class="group-form"
                            action="/groups/create"
                            method="post"
                        >

                            <?php echo Form::csrf(); ?>

                            <div class="form-group">

                                <input
                                    class="form-control"
                                    type="text"
                                    name="group_name"
                                    placeholder="グループ名を入力"
                                    required
                                >

                            </div>

                            <div class="group-form__actions">

                                <button
                                    class="btn btn--primary"
                                    type="submit"
                                >
                                    作成
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </section>

            <section class="group-section">

                <h2 class="group-card__title">
                    参加中のグループ
                </h2>

                <?php if (!empty($groups)): ?>

                    <ul class="group-list">

                        <?php foreach ($groups as $group): ?>

                            <li class="group-card card">

                                <div class="group-card__left">

                                    <a
                                        class="group-card__title"
                                        href="/todo/group/<?php echo (int) $group['id']; ?>"
                                    >
                                        <?php echo e($group['name']); ?>
                                    </a>

                                </div>

                                <div class="group-card__actions">

                                    <a
                                        class="btn btn--secondary btn--sm"
                                        href="/groups/invite/<?php echo (int) $group['id']; ?>"
                                    >
                                        招待URL
                                    </a>

                                    <form
                                        action="/groups/delete/<?php echo (int) $group['id']; ?>"
                                        method="post"
                                    >

                                        <?php echo Form::csrf(); ?>

                                        <button
                                            class="btn btn--secondary btn--sm"
                                            type="submit"
                                            onclick="return confirm('本当にこのグループを削除しますか？\n削除すると元に戻せません。');"
                                        >
                                            削除
                                        </button>

                                    </form>

                                </div>

                            </li>

                        <?php endforeach; ?>

                    </ul>

                <?php else: ?>

                    <div class="card group-empty text-center">

                        <p class="text-muted">
                            参加中のグループはありません。
                        </p>

                    </div>

                <?php endif; ?>

            </section>

            <footer>

                <a
                    class="btn btn--secondary"
                    href="/logout"
                >
                    ログアウト
                </a>

            </footer>

        </div>

    </div>

</body>
</html>
