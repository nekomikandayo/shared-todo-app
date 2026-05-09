<h1>Create Todo</h1>

<form method="post" action="/todo/store">

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
            <option value="1">Low</option>
            <option value="2" selected>Medium</option>
            <option value="3">High</option>
        </select>
    </div>

    <div>
        <label>Status</label>

        <select name="status">
            <option value="0">Incomplete</option>
            <option value="1">Complete</option>
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