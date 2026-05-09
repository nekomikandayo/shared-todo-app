<link rel="stylesheet" href="/assets/css/style.css">

<div class="container container--narrow">

    <div class="card">

        <div class="card-header">

            <h1 class="page-title">
                ToDo編集
            </h1>

        </div>

        <div class="card-body">

            <form
                class="todo-form"
                method="post"
                action="/todo/update/<?php echo (int) $todo['id']; ?>"
            >

                <?php echo Form::csrf(); ?>

                <div class="form-group">

                    <label class="form-label">
                        タイトル
                    </label>

                    <input
                        class="form-control"
                        type="text"
                        name="title"
                        value="<?php echo e($todo['title'] ?? ''); ?>"
                        placeholder="タイトルを入力"
                    >

                </div>

                <div class="form-group">

                    <label class="form-label">
                        メモ
                    </label>

                    <textarea
                        class="form-control"
                        name="description"
                        placeholder="メモを入力"
                    ><?php echo e($todo['description'] ?? ''); ?></textarea>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        優先度
                    </label>

                    <select
                        class="form-control"
                        name="priority"
                    >

                        <?php foreach (Config::get('todo.priority_labels', []) as $value => $label): ?>

                            <option
                                value="<?php echo (int) $value; ?>"
                                <?php if ((int) $todo['priority'] === (int) $value) echo 'selected'; ?>
                            >
                                <?php echo e($label); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        ステータス
                    </label>

                    <select
                        class="form-control"
                        name="status"
                    >

                        <?php foreach (Config::get('todo.status_labels', []) as $value => $label): ?>

                            <option
                                value="<?php echo (int) $value; ?>"
                                <?php if ((int) $todo['status'] === (int) $value) echo 'selected'; ?>
                            >
                                <?php echo e($label); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        期限
                    </label>

                    <input
                        class="form-control"
                        type="date"
                        name="due_date"
                        value="<?php echo !empty($todo['due_date']) ? e(substr($todo['due_date'], 0, 10)) : ''; ?>"
                    >

                </div>

                <div class="todo-form__actions">

                    <button
                        class="btn btn--primary"
                        type="submit"
                    >
                        更新する
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
