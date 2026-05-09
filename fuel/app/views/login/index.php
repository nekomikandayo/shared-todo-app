<link rel="stylesheet" href="/assets/css/style.css">
<div class="container">
<h1>ログイン</h1>

<form method="post" action="/login">

    <?php echo Form::csrf(); ?>

    <input type="text" name="username" placeholder="ユーザー名">

    <input type="password" name="password" placeholder="パスワード">

    <button
        type="submit"
        name="mode"
        value="login"
    >
        ログイン
    </button>

    <button
        type="submit"
        name="mode"
        value="register"
    >
        新規登録
    </button>

</form>
</div>