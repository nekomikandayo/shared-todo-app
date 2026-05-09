<h1>Create Todo</h1>

<form method="post" action="/todo/store">
    <?php echo Form::csrf(); ?>

    <div>
        <label>Title</label>
        <input type="text" name="title">
    </div>

    <div>
        <label>Description</label>
        <textarea name="description"></textarea>
    </div>

    <div>
        <label>Priority</label>

        <select name="priority">
            <?php foreach (Config::get('todo.priority_labels', []) as $value => $label): ?>
                <option value="<?php echo (int) $value; ?>"<?php if ((int) $value === 2) echo ' selected'; ?>>
                    <?php echo e($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label>Status</label>

        <select name="status">
            <?php foreach (Config::get('todo.status_labels', []) as $value => $label): ?>
                <option value="<?php echo (int) $value; ?>">
                    <?php echo e($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label>Due Date</label>
        <input type="date" name="due_date">
    </div>

    <button type="submit">
        Create
    </button>

</form>