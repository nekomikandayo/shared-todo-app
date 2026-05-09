<link rel="stylesheet" href="/assets/css/style.css">

<div class="container container--narrow">

    <div class="card">

        <div class="card-header">

            <h1 class="page-title">
                ToDo作成
            </h1>

        </div>

        <div class="card-body">

        <?php echo View::forge('todo/_form', [
            'group_id' => $group_id,
        ], false); ?>

        </div>

    </div>

</div>