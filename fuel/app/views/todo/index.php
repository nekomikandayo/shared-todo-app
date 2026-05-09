<link rel="stylesheet" href="/assets/css/style.css">

<div class="container">

    <div class="todo-page">

        <div class="todo-page__header">

            <a
                class="back-link"
                href="/groups"
            >
                ← グループ一覧へ戻る
            </a>

            <div class="todo-page__title-block">

                <h1 class="page-title">
                    <?php if (isset($group_name) && $group_name !== null && trim((string) $group_name) !== ''): ?>
                        「<?php echo e(trim($group_name)); ?>」グループのToDo一覧
                    <?php else: ?>
                        ToDo一覧
                    <?php endif; ?>
                </h1>

            </div>

            <?php if (!empty($group_id)): ?>

                <a
                    class="btn btn--primary"
                    href="/todo/create?group_id=<?php echo (int) $group_id; ?>"
                >
                    ToDoを作成
                </a>

            <?php endif; ?>

        </div>

        <?php
        $priority_labels = Config::get('todo.priority_labels', []);
        $status_labels = Config::get('todo.status_labels', []);
        ?>

        <div class="todo-list">

            <?php if (!empty($todos)): ?>

                <?php foreach ($todos as $todo): ?>

                    <div class="todo-card card">

                        <div class="todo-card__header">

                            <h2 class="todo-card__title">
                                <?php echo e($todo['title'] ?? ''); ?>
                            </h2>

                        </div>

                        <div class="todo-card__body">

                        <?php if (!empty($todo['description'])): ?>
                            <div class="todo-memo">
                                <span class="todo-memo__label">メモ</span>
                                <p class="todo-memo__content">
                                    <?php echo e($todo['description']); ?>
                                </p>
                            </div>
                            <?php endif; ?> 

                            <div class="todo-meta">

                            <?php
                                $p = (int) $todo['priority'];
                                $priority_class = '';
                                if ($p === 1) $priority_class = 'todo-badge--low';
                                elseif ($p === 2) $priority_class = 'todo-badge--medium';
                                elseif ($p === 3) $priority_class = 'todo-badge--high';
                            ?>
                                <div class="todo-meta__item">

                                    <span class="todo-meta__label">
                                        優先度
                                    </span>

                                    <span class="todo-badge <?php echo $priority_class; ?>">

                                        <?php
                                        
                                        echo isset($priority_labels[$p])
                                            ? e($priority_labels[$p])
                                            : e((string) $p);
                                        ?>

                                    </span>

                                </div>
                                <?php
                                $s = (int) $todo['status'];
                                $status_class = ($s === 1) ? 'todo-badge--done' : 'todo-badge--undone';
                                ?>
                                <div class="todo-meta__item">

                                    <span class="todo-meta__label">
                                        ステータス
                                    </span>

                                    <span class="todo-badge <?php echo $status_class; ?>">

                                        <?php
                                    

                                        echo isset($status_labels[$s])
                                            ? e($status_labels[$s])
                                            : e((string) $s);
                                        ?>

                                    </span>

                                </div>

                                <div class="todo-meta__item">

                                    <span class="todo-meta__label">
                                        期限
                                    </span>

                                    <span class="todo-meta__value">

                                        <?php
                                        echo !empty($todo['due_date'])
                                            ? e(substr($todo['due_date'], 0, 10))
                                            : e('未設定');
                                        ?>

                                    </span>

                                </div>

                                <div class="todo-meta__item">

                                    <span class="todo-meta__label">
                                        作成者
                                    </span>

                                    <span class="todo-meta__value">
                                        <?php echo e($todo['creator_name'] ?? '不明'); ?>
                                    </span>

                                </div>

                            </div>

                        </div>

                        <div class="todo-card__footer">

                            <a
                                class="btn btn--secondary btn--sm"
                                href="/todo/edit/<?php echo (int) $todo['id']; ?>"
                            >
                                編集
                            </a>

                            <a
                                class="btn btn--secondary btn--sm"
                                href="/todo/delete/<?php echo (int) $todo['id']; ?>"
                            >
                                削除
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="empty-card card text-center">

                    <p class="text-muted">
                        ToDoはまだありません
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>
