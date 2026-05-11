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

function createTodo(todo) {

    return {

        id: todo.id,

        title: ko.observable(todo.title),

        description: ko.observable(todo.description),

        priority: ko.observable(todo.priority),

        status: ko.observable(todo.status),

        due_date: ko.observable(todo.due_date),

        creator_name: ko.observable(todo.creator_name)

    };

}

function TodoViewModel() {

    const self = this;

    self.editTodoId = ko.observable(null);

    self.editTodoTitle = ko.observable("");
    self.editTodoDescription = ko.observable("");
    self.editTodoPriority = ko.observable(2);
    self.editTodoStatus = ko.observable(0);
    self.editTodoDueDate = ko.observable("");

    self.todos = ko.observableArray(

        todosData.map((todo) => {

            return {

                id: todo.id,

                title: ko.observable(todo.title),

                description: ko.observable(todo.description),

                priority: ko.observable(todo.priority),

                status: ko.observable(todo.status),

                due_date: ko.observable(todo.due_date),

                creator_name: ko.observable(todo.creator_name)

            };

        })

    );
    self.editTodoId = ko.observable(null);
    self.newTodoTitle = ko.observable("");
    self.newTodoDescription = ko.observable("");
    self.newTodoPriority = ko.observable(2);
    self.newTodoStatus = ko.observable(0);
    self.newTodoDueDate = ko.observable("");

    self.getPriorityLabel = function (priority) {

        priority = ko.unwrap(priority);

        return priorityLabels[priority] || priority;

    };

    self.getStatusLabel = function (status) {

        status = ko.unwrap(status);

        return statusLabels[status] || status;

    };

    self.getPriorityClass = function (priority) {

        priority = ko.unwrap(priority);

        if (priority === 1) {
            return 'todo-badge--low';
        }

        if (priority === 2) {
            return 'todo-badge--medium';
        }

        if (priority === 3) {
            return 'todo-badge--high';
        }

        return '';

    };

    self.getStatusClass = function (status) {

        status = ko.unwrap(status);

        return Number(status) === 1
            ? 'todo-badge--done'
            : 'todo-badge--undone';

    };

    self.addTodo = async function (formElement, event) {

        event.preventDefault();

        const title = self.newTodoTitle().trim();

        if (!title) {
            return;
        }

        try {

            const response = await fetch('/todo/ajax_create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    title: title,
                    description: self.newTodoDescription(),
                    priority: self.newTodoPriority(),
                    status: self.newTodoStatus(),
                    due_date: self.newTodoDueDate(),
                    group_id: groupId
                })
            });

            const data = await response.json();

            if (!data.success) {

                alert(data.message);

                return;

            }

            self.todos.push(
                createTodo(data.todo)
            );

            self.newTodoTitle("");
            self.newTodoDescription("");
            self.newTodoPriority(2);
            self.newTodoStatus(0);
            self.newTodoDueDate("");

            createModal.classList.add('hidden');

        } catch (error) {

            console.error(error);

            alert('ToDo追加に失敗しました');

        }

    };

    self.deleteTodo = async function (todo) {

        if (!confirm('削除しますか？')) {
            return;
        }

        try {

            const response = await fetch('/todo/ajax_delete/' + todo.id, {
                method: 'POST'
            });

            const data = await response.json();

            if (!data.success) {

                alert(data.message);

                return;

            }

            self.todos.remove(todo);

        } catch (error) {

            console.error(error);

            alert('削除に失敗しました');

        }

    };
    self.openEditModal = function (todo) {

        self.editTodoId(todo.id);

        self.editTodoTitle(ko.unwrap(todo.title));
        self.editTodoDescription(ko.unwrap(todo.description));

        self.editTodoPriority(
            ko.unwrap(todo.priority)
        );

        self.editTodoStatus(
            ko.unwrap(todo.status)
        );

        self.editTodoDueDate(
            ko.unwrap(todo.due_date)
        );

        document
            .getElementById('edit-modal')
            .classList
            .remove('hidden');

    };

    const editModal = document.getElementById('edit-modal');

    if (editModal) {

        editModal.addEventListener('click', (event) => {

            if (event.target === editModal) {

                editModal.classList.add('hidden');

            }

        });

    }

    self.toggleStatus = async function (todo) {

        const newStatus = Number(todo.status()) === 1 ? 0 : 1;

        try {

            const response = await fetch('/todo/ajax_update_status/' + todo.id, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            });

            const data = await response.json();

            if (!data.success) {

                alert(data.message);

                return;

            }

            todo.status(newStatus);

        } catch (error) {

            console.error(error);

            alert('ステータス更新に失敗しました');

        }

    };

    self.updateTodo = async function () {

        try {
    
            const response = await fetch(
                '/todo/ajax_update/' + self.editTodoId(),
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        title: self.editTodoTitle(),
                        description: self.editTodoDescription(),
                        priority: self.editTodoPriority(),
                        status: self.editTodoStatus(),
                        due_date: self.editTodoDueDate()
                    })
                }
            );
    
            const data = await response.json();
    
            if (!data.success) {
    
                alert(data.message);
    
                return;
    
            }
    
            const todo = self.todos().find(
                (item) => item.id === self.editTodoId()
            );
    
            if (todo) {
    
                todo.title(self.editTodoTitle());
                todo.description(self.editTodoDescription());
                todo.priority(self.editTodoPriority());
                todo.status(self.editTodoStatus());
                todo.due_date(self.editTodoDueDate());
    
            }
    
            document
                .getElementById('edit-modal')
                .classList
                .add('hidden');
    
        } catch (error) {
    
            console.error(error);
    
            alert('更新に失敗しました');
    
        }
    
    };

}

ko.applyBindings(new TodoViewModel());