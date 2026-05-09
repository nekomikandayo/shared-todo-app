<link rel="stylesheet" href="/assets/css/style.css">
<div class="container">
<h1>Edit Todo</h1>

<form method="post" action="/todo/update/<?php echo (int) $todo['id']; ?>">
    <?php echo Form::csrf(); ?>

    <div>
        <label>Title</label>

        <input
            type="text"
            name="title"
            value="<?php echo e($todo['title'] ?? ''); ?>"
        >
    </div>

    <div>
        <label>Description</label>

        <textarea name="description"><?php echo e($todo['description'] ?? ''); ?></textarea>
    </div>

    <div>
        <label>Priority</label>

        <select name="priority">
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

    <div>
        <label>Status</label>

        <select name="status">
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
    <div>
        <label>Due Date</label>
        <input type="date" name="due_date" value="<?php echo !empty($todo['due_date']) ? e(substr($todo['due_date'], 0, 10)) : ''; ?>">
    </div>
    <button type="submit">
        Update
    </button>

</form>
</div>
