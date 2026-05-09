<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>グループ参加確認</title>

    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

    <div class="container container--narrow">

        <div class="card confirm-card">

            <div class="card-header">

                <h1 class="page-title text-center">
                    グループ参加確認
                </h1>

            </div>

            <div class="card-body">

                <p class="confirm-message">

                    「<?php echo e($invite['group_name']); ?>」に参加しますか？

                </p>

                <form
                    class="confirm-form"
                    method="post"
                    action="/groups/join/<?php echo e($token); ?>"
                >

                    <?php echo Form::csrf(); ?>

                    <div class="confirm-form__actions">

                        <a
                            class="btn btn--secondary"
                            href="/login"
                        >
                            キャンセル
                        </a>

                        <button
                            class="btn btn--primary"
                            type="submit"
                        >
                            参加する
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</body>
</html>
