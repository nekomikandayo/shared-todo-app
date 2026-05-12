<?php

class Model_Todo extends Model
{
    public static function get_group_todos($group_id)
    {
        return self::base_query()
            ->where('todos.group_id', '=', $group_id)
            ->order_by('todos.priority', 'DESC')
            ->order_by(DB::expr('todos.due_date IS NULL'), 'ASC')
            ->order_by('todos.due_date', 'ASC')
            ->execute()
            ->as_array();
    }
    public static function find_todo($id)
    {
        return DB::select()
            ->from('todos')
            ->where('id', '=', $id)
            ->execute()
            ->current();
    }
    private static function base_query()
    {
        return DB::select(
            'todos.*',
            ['users.username', 'creator_name']
        )
            ->from('todos')
            ->join('users', 'LEFT')
            ->on('todos.created_by', '=', 'users.id')
            ->where('todos.deleted_at', 'IS', DB::expr('NULL'));
    }
    public static function get_all_todos()
    {
        return self::base_query()
            ->order_by('todos.priority', 'DESC')
            ->order_by(DB::expr('todos.due_date IS NULL'), 'ASC')
            ->order_by('todos.due_date', 'ASC')
            ->execute()
            ->as_array();
    }

    public static function create_todo($data)
    {
        list($todo_id) = DB::insert('todos')
            ->set($data)
            ->execute();

        return $todo_id;
    }

    public static function delete_todo($id)
    {
        return DB::update('todos')
            ->set([
                'deleted_at' => date('Y-m-d H:i:s')
            ])
            ->where('id', '=', $id)
            ->execute();
    }


    public static function update_status($id, $status)
    {
        return DB::update('todos')
            ->set([
                'status' => $status
            ])
            ->where('id', '=', $id)
            ->execute();
    }
    public static function update_todo($id, $data)
    {
        return DB::update('todos')
            ->set([
                'title' => $data['title'] ?? '',
                'description' => $data['description'] ?? '',
                'priority' => $data['priority'] ?? 2,
                'status' => $data['status'] ?? 0,
                'due_date' => $data['due_date'] ?? null,
            ])
            ->where('id', '=', $id)
            ->execute();
    }
    public static function validate_todo($data)
    {
        $user = Auth::get_user_id();

        if (!$user) {
            exit('Login required');
        }

        list(, $user_id) = Auth::get_user_id();

        $title = trim($data['title'] ?? '');

        if ($title === '') {
            exit('Title is required');
        }

        $priority = (int) ($data['priority'] ?? 2);

        $allowed_priority = array_keys(
            Config::get('todo.priority_labels', [])
        );

        if (!in_array($priority, $allowed_priority, true)) {
            exit('Invalid priority');
        }

        $status = (int) ($data['status'] ?? 0);

        $allowed_status = array_keys(
            Config::get('todo.status_labels', [])
        );

        if (!in_array($status, $allowed_status, true)) {
            exit('Invalid status');
        }

        $due_date = !empty($data['due_date'])
            ? $data['due_date']
            : null;

        if ($due_date !== null) {

            $date = DateTime::createFromFormat(
                'Y-m-d',
                $due_date
            );

            if (
                !$date ||
                $date->format('Y-m-d') !== $due_date
            ) {
                exit('Invalid due date');
            }

            if ($due_date < date('Y-m-d')) {
                throw new Exception('過去の日付は設定できません');
            }
        }

        return [
            'title' => $title,
            'description' => $data['description'] ?? '',
            'priority' => $priority,
            'status' => $status,
            'due_date' => $due_date,
            'group_id' => (int) ($data['group_id'] ?? 0),
        ];
    }
}
