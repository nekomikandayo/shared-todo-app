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

                <button
                    class="btn btn--primary btn--sm todo-page__create-btn"
                    id="open-modal"
                    type="button">
                    ToDoを作成
                </button>

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

                        <div class="form-group">

                            <label class="form-label">
                                タイトル
                            </label>

                            <input
                                class="form-control"
                                type="text"
                                placeholder="タイトルを入力"
                                data-bind="value: newTodoTitle">

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                メモ
                            </label>

                            <textarea
                                class="form-control"
                                placeholder="メモを入力"
                                data-bind="value: newTodoDescription"></textarea>

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                優先度
                            </label>

                            <select
                                class="form-control"
                                data-bind="value: newTodoPriority">

                                <option value="1">低</option>
                                <option value="2">中</option>
                                <option value="3">高</option>

                            </select>

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                ステータス
                            </label>

                            <select
                                class="form-control"
                                data-bind="value: newTodoStatus">

                                <option value="0">未完了</option>
                                <option value="1">完了</option>

                            </select>

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                期限
                            </label>

                            <input
                                class="form-control"
                                type="date"
                                data-bind="value: newTodoDueDate">

                        </div>

                        <div class="todo-form__actions">

                            <button
                                class="btn btn--primary"
                                type="button"
                                data-bind="click: addTodo">

                                作成する

                            </button>

                        </div>

                    </div>



                </div>

            </div>

        </div>

    </div>

    <div class="modal-overlay hidden" id="edit-modal">

        <div class="modal-content">

            <div class="modal-header">

                <h2 class="modal-title">
                    ToDo編集
                </h2>

            </div>

            <div class="modal-body">

                <div class="form-group">

                    <label class="form-label">
                        タイトル
                    </label>

                    <input
                        class="form-control"
                        type="text"
                        data-bind="value: editTodoTitle">

                </div>

                <div class="form-group">

                    <label class="form-label">
                        メモ
                    </label>

                    <textarea
                        class="form-control"
                        data-bind="value: editTodoDescription"></textarea>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        優先度
                    </label>

                    <select
                        class="form-control"
                        data-bind="value: editTodoPriority">

                        <option value="1">低</option>
                        <option value="2">中</option>
                        <option value="3">高</option>

                    </select>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        ステータス
                    </label>

                    <select
                        class="form-control"
                        data-bind="value: editTodoStatus">

                        <option value="0">未完了</option>
                        <option value="1">完了</option>

                    </select>

                </div>

                <div class="form-group">

                    <label class="form-label">
                        期限
                    </label>

                    <input
                        class="form-control"
                        type="date"
                        data-bind="value: editTodoDueDate">

                </div>

                <div class="todo-form__actions">

                    <button
                        class="btn btn--primary"
                        type="button"
                        data-bind="click: updateTodo">

                        保存する

                    </button>

                </div>

            </div>

        </div>

    </div>

    <div class="todo-list" data-bind="foreach: todos">

        <div class="todo-card card">

            <div class="todo-card__header">

                <h2 class="todo-card__title">
                    <span data-bind="text: title()"></span>
                </h2>

            </div>

            <div class="todo-card__body">

                <div
                    class="todo-memo"
                    data-bind="visible: description()">
                    <span class="todo-memo__label">メモ</span>
                    <p class="todo-memo__content" data-bind="text: description()"></p>
                </div>

                <div class="todo-meta">

                    <div class="todo-meta__item">
                        <span class="todo-meta__label">優先度</span>
                        <span
                            class="todo-badge"
                            data-bind="
                                    text: $parent.getPriorityLabel(priority),
                                    css: $parent.getPriorityClass(priority)
                                ">
                        </span>
                    </div>

                    <div class="todo-meta__item">
                        <span class="todo-meta__label">ステータス</span>
                        <button
                            class="todo-badge"
                            type="button"
                            data-bind="
        text: $parent.getStatusLabel(status),
        css: $parent.getStatusClass(status),
        click: $parent.toggleStatus
    ">
                        </button>
                    </div>

                    <div class="todo-meta__item">
                        <span class="todo-meta__label">期限</span>
                        <span
                            class="todo-meta__value"
                            data-bind="text: due_date() || '未設定'">
                        </span>
                    </div>

                    <div class="todo-meta__item">
                        <span class="todo-meta__label">作成者</span>
                        <span
                            class="todo-meta__value"
                            data-bind="text: creator_name() || '不明'">
                        </span>
                    </div>

                </div>

            </div>

            <div class="todo-card__footer">

                <button
                    class="btn btn--secondary btn--sm"
                    type="button"
                    data-bind="click: $parent.openEditModal">

                    編集

                </button>

                <button
                    class="btn btn--secondary btn--sm"
                    type="button"
                    data-bind="click: $parent.deleteTodo">
                    削除
                </button>

            </div>

        </div>

    </div>

    <div
        class="empty-card card text-center"
        data-bind="visible: todos().length === 0">

        <p class="text-muted">
            ToDoはまだありません
        </p>

    </div>

</div>

<script>
    const todosData = <?php echo json_encode($todos); ?>;

    const groupId = <?php echo $group_id; ?>;

    const priorityLabels =
        <?php echo json_encode(Config::get('todo.priority_labels')); ?>;

    const statusLabels =
        <?php echo json_encode(Config::get('todo.status_labels')); ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.js"></script>

<script src="/assets/js/todo.js"></script>