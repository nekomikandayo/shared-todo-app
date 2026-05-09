<link rel="stylesheet" href="/assets/css/style.css">

<div class="container">

    <div class="todo-page">

        <div class="todo-page__header">

            <div class="todo-page__header-top">

                <a
                    class="back-link"
                    href="/groups">
                    ← グループ一覧へ戻る
                </a>

                <?php if (!empty($group_id)): ?>

                    <button
                        class="btn btn--primary btn--sm todo-page__create-btn"
                        id="open-modal"
                        type="button">
                        ToDoを作成
                    </button>

                <?php endif; ?>

            </div>

            <div class="todo-page__title-block">

                <h1 class="page-title todo-page__title">
                    <?php if (isset($group_name) && $group_name !== null && trim((string) $group_name) !== ''): ?>
                        「<?php echo e(trim($group_name)); ?>」グループのToDo一覧
                    <?php else: ?>
                        ToDo一覧
                    <?php endif; ?>
                </h1>

            </div>

            <div class="modal-overlay hidden" id="todo-modal">

                <div class="modal-content">

                    <div class="modal-header">

                        <h2 class="modal-title">
                            ToDo作成
                        </h2>

                    </div>

                    <div class="modal-body">

                        <?php echo View::forge('todo/_form', [
                            'group_id' => $group_id,
                        ]); ?>

                    </div>

                </div>

            </div>

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

                            <button
                                class="btn btn--secondary btn--sm"
                                type="button"
                                data-modal="edit-modal-<?php echo (int) $todo['id']; ?>">
                                編集
                            </button>

                            <a
                                class="btn btn--secondary btn--sm"
                                href="/todo/delete/<?php echo (int) $todo['id']; ?>">
                                削除
                            </a>

                        </div>

                    </div>
                    <div
                        class="modal-overlay hidden"
                        id="edit-modal-<?php echo (int) $todo['id']; ?>">

                        <div class="modal-content">

                            <div class="modal-header">

                                <h2 class="modal-title">
                                    ToDo編集
                                </h2>

                            </div>

                            <div class="modal-body">

                                <form
                                    method="post"
                                    action="/todo/update/<?php echo (int) $todo['id']; ?>">

                                    <?php echo Form::csrf(); ?>
                                    <input
                                        type="hidden"
                                        name="group_id"
                                        value="<?php echo (int) $todo['group_id']; ?>">
                                        
                                    <div class="form-group">

                                        <label class="form-label">
                                            タイトル
                                        </label>

                                        <input
                                            class="form-control"
                                            type="text"
                                            name="title"
                                            value="<?php echo e($todo['title'] ?? ''); ?>">

                                    </div>

                                    <div class="form-group">

                                        <label class="form-label">
                                            メモ
                                        </label>

                                        <textarea
                                            class="form-control"
                                            name="description"><?php echo e($todo['description'] ?? ''); ?></textarea>

                                    </div>

                                    <div class="form-group">

                                        <label class="form-label">
                                            優先度
                                        </label>

                                        <select
                                            class="form-control"
                                            name="priority">

                                            <?php foreach (Config::get('todo.priority_labels', []) as $value => $label): ?>

                                                <option
                                                    value="<?php echo (int) $value; ?>"
                                                    <?php if ((int) $todo['priority'] === (int) $value) echo 'selected'; ?>>
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
                                            name="status">

                                            <?php foreach (Config::get('todo.status_labels', []) as $value => $label): ?>

                                                <option
                                                    value="<?php echo (int) $value; ?>"
                                                    <?php if ((int) $todo['status'] === (int) $value) echo 'selected'; ?>>
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
                                            value="<?php echo !empty($todo['due_date']) ? e(substr($todo['due_date'], 0, 10)) : ''; ?>">

                                    </div>

                                    <button
                                        class="btn btn--primary"
                                        type="submit">
                                        更新する
                                    </button>

                                </form>

                            </div>

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
    <script>
        const openButton = document.getElementById('open-modal');
        const createModal = document.getElementById('todo-modal');

        if (openButton) {

            openButton.addEventListener('click', () => {

                createModal.classList.remove('hidden');

            });

        }

        if (createModal) {

            createModal.addEventListener('click', (event) => {

                if (event.target === createModal) {

                    createModal.classList.add('hidden');

                }

            });

        }

        const editButtons = document.querySelectorAll('[data-modal]');

        editButtons.forEach((button) => {

            button.addEventListener('click', () => {

                const modalId = button.dataset.modal;

                const editModal = document.getElementById(modalId);

                if (editModal) {

                    editModal.classList.remove('hidden');

                }

            });

        });

        const editModals = document.querySelectorAll('[id^="edit-modal-"]');

        editModals.forEach((editModal) => {

            editModal.addEventListener('click', (event) => {

                if (event.target === editModal) {

                    editModal.classList.add('hidden');

                }

            });

        });
    </script>

</div>