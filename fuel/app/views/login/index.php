<link rel="stylesheet" href="/assets/css/style.css">

<div class="login-page">

    <div class="card login-card">

        <div class="card-body">

            <h1 class="page-title login-title">
                ログイン
            </h1>
            <?php if ($login_error = Session::get_flash('error')): ?>

                <p class="error-message">
                    <?php echo e($login_error); ?>
                </p>

            <?php endif; ?>
            <form
                class="login-form"
                method="post"
                action="/login">

                <?php echo Form::csrf(); ?>

                <div class="form-group">

                    <input
                        class="form-control"
                        type="text"
                        name="username"
                        placeholder="ユーザー名">

                </div>

                <div class="form-group">

                    <input
                        class="form-control"
                        type="password"
                        name="password"
                        placeholder="パスワード">

                </div>

                <div class="login-form__actions">

                    <button
                        class="btn btn--primary"
                        type="submit"
                        name="mode"
                        value="login">
                        ログイン
                    </button>

                </div>

                <div class="login-form__actions">

                    <button
                        class="btn btn--secondary"
                        type="submit"
                        name="mode"
                        value="register">
                        新規登録
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>