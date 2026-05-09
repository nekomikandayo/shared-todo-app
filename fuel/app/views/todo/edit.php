<h1>Edit Todo</h1>

<form method="post" action="/todo/update/<?php echo $todo['id']; ?>">

    <div>
        <label>Title</label>

        <input
            type="text"
            name="title"
            value="<?php echo htmlspecialchars($todo['title'], ENT_QUOTES, 'UTF-8'); ?>"
        >
    </div>

    <div>
        <label>Description</label>

        <textarea name="description"><?php echo htmlspecialchars($todo['description'], ENT_QUOTES, 'UTF-8'); ?></textarea>
    </div>

    <div>
        <label>Priority</label>

        <select name="priority">

            <option
                value="1"
                <?php if ($todo['priority'] == 1) echo 'selected'; ?>
            >
                Low
            </option>

            <option
                value="2"
                <?php if ($todo['priority'] == 2) echo 'selected'; ?>
            >
                Medium
            </option>

            <option
                value="3"
                <?php if ($todo['priority'] == 3) echo 'selected'; ?>
            >
                High
            </option>

        </select>
    </div>

    <div>
        <label>Status</label>

        <select name="status">

            <option
                value="0"
                <?php if ($todo['status'] == 0) echo 'selected'; ?>
            >
                Incomplete
            </option>

            <option
                value="1"
                <?php if ($todo['status'] == 1) echo 'selected'; ?>
            >
                Complete
            </option>

        </select>
    </div>
    <div>
        <label>Due Date</label>
        <input type="date" name="due_date" value="<?php echo !empty($todo['due_date']) ? htmlspecialchars(substr($todo['due_date'], 0, 10), ENT_QUOTES, 'UTF-8') : ''; ?>">
    </div>
    <button type="submit">
        Update
    </button>

</form>