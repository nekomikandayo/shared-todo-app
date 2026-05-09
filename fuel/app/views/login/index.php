<link rel="stylesheet" href="/assets/css/style.css">
<div class="container">
<h1>ログイン</h1>

<form method="post" action="/login">
    <?php echo Form::csrf(); ?>
    <div>
        <label>ユーザーID</label>
        <input type="text" name="username" placeholder="example@example.com">
    </div>

    <div>
        <label>パスワード</label>
        <input type="password" name="password" placeholder="********">
    </div>

    <button type="submit">
        ログイン
    </button>
</form>
</div>