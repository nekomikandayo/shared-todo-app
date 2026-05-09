<link rel="stylesheet" href="/assets/css/style.css">
<div class="container">
<a href="/groups">
    ← グループ一覧へ戻る
</a>
<h1>Todo List</h1>

<?php if (!empty($group_id)): ?>
    <a href="/todo/create?group_id=<?php echo (int) $group_id; ?>">
        Create Todo
    </a>
<?php endif; ?>

<?php
$priority_labels = Config::get('todo.priority_labels', []);
$status_labels = Config::get('todo.status_labels', []);
?>
<?php foreach ($todos as $todo): ?>

    <div>
        <?php echo e($todo['title'] ?? ''); ?>
        <div>
            <?php echo e($todo['description'] ?? ''); ?>

            Priority:
            <?php
            $p = (int) $todo['priority'];
            echo isset($priority_labels[$p])
                ? e($priority_labels[$p])
                : e((string) $p);
            ?>

            Status:
            <?php
            $s = (int) $todo['status'];
            echo isset($status_labels[$s])
                ? e($status_labels[$s])
                : e((string) $s);
            ?>

            Due Date:
            <?php echo !empty($todo['due_date']) ? e(substr($todo['due_date'], 0, 10)) : e('(未設定)'); ?>
            <small style="color: #666; margin-left: 10px;">
            作成者: <?php echo e($todo['creator_name'] ?? '不明'); ?>
            </small>
        </div>
        <a href="/todo/edit/<?php echo (int) $todo['id']; ?>">
            Edit
        </a>

        <a href="/todo/delete/<?php echo (int) $todo['id']; ?>">
            Delete
        </a>
    </div>

<?php endforeach; ?>
</div>
