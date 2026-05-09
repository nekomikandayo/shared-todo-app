<h1>Todo List</h1>

<a href="/todo/create">
    Create Todo
</a>

<?php foreach ($todos as $todo): ?>

    <div>
        <?php echo htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8'); ?>
        <div>
            <?php echo htmlspecialchars($todo['description'], ENT_QUOTES, 'UTF-8'); ?>

            Priority:
            <?php echo (int) $todo['priority']; ?>

            Status:
            <?php echo ((int) $todo['status'] === 1) ? 'Complete' : 'Incomplete'; ?>

            Due Date:
            <?php echo !empty($todo['due_date']) ? htmlspecialchars(substr($todo['due_date'], 0, 10), ENT_QUOTES, 'UTF-8') : '(未設定)'; ?>
        </div>
        <a href="/todo/edit/<?php echo $todo['id']; ?>">
            Edit
        </a>

        <a href="/todo/delete/<?php echo $todo['id']; ?>">
            Delete
        </a>
    </div>

<?php endforeach; ?>