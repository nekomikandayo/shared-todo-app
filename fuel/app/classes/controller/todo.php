<?php

class Controller_Todo extends Controller_Base
{
    public function before()
    {
        parent::before();
    
        Config::load('todo', true);
    }
    public function action_index()
    {
        $todos = DB::select(
                'todos.*',
                ['users.username', 'creator_name']
            )
            ->from('todos')
            ->join('users', 'LEFT')
            ->on('todos.created_by', '=', 'users.id')
            ->where('todos.deleted_at', 'IS', DB::expr('NULL'))
            ->execute()
            ->as_array();
    
            return Response::forge(
                View::forge('todo/index', [
                    'todos' => $todos,
                    'group_id' => 0,
                ], false)
            );
    }
    public function action_group($group_id)
    {
        $todos = DB::select(
            'todos.*', 
            ['users.username', 'creator_name'] 
        )
        ->from('todos')
        ->join('users', 'LEFT')
        ->on('todos.created_by', '=', 'users.id')
        ->where('todos.group_id', '=', $group_id)
        ->where('todos.deleted_at', 'IS', DB::expr('NULL'))
        ->execute()
        ->as_array();
    
        return Response::forge(
            View::forge('todo/index', [
                'todos' => $todos,
                'group_id' => $group_id,
            ], false)
        );
    }
    public function action_create()
    {
        $group_id = (int) Input::get('group_id');
        if ($group_id <= 0)
        {
            exit('Invalid group');
        }
    
        return Response::forge(
            View::forge('todo/create', [
                'group_id' => $group_id,
            ], false)
        );
    }
    public function action_store()
    {
        return $this->post_store();
    }

    public function post_store()
    {
        $this->require_csrf();

        $title = trim(Input::post('title'));

        if ($title === '')
        {
            exit('Title is required');
        }
        $description = Input::post('description');
        $priority = (int) Input::post('priority');
        $allowed_priority = array_keys(Config::get('todo.priority_labels', []));
        if (!in_array($priority, $allowed_priority, true))
        {
            exit('Invalid priority');
        }
        $status = (int) Input::post('status');
        $group_id = (int) Input::post('group_id');
        $allowed_status = array_keys(Config::get('todo.status_labels', []));
        if (!in_array($status, $allowed_status, true))
        {
            exit('Invalid status');
        }
        $due_date = Input::post('due_date');
        if ($due_date !== '')
        {
            $date = DateTime::createFromFormat('Y-m-d', $due_date);
        
            if (
                !$date ||
                $date->format('Y-m-d') !== $due_date
            )
            {
                exit('Invalid due date');
            }
            if ($due_date < date('Y-m-d'))
            {
                exit('Past dates are not allowed');
            }
        }

        DB::insert('todos')
            ->set([
                'title' => $title,
                'description' => $description,
                'priority' => $priority,
                'status' => $status,
                'due_date' => $due_date ?: null,
                'created_by' => 1,
                'group_id' => $group_id,
            ])
            ->execute();
    
        return Response::redirect('/todo');
    }
    public function action_edit($id)
    {
        $todo = DB::select()
            ->from('todos')
            ->where('id', $id)
            ->execute()
            ->current();
    
        return Response::forge(
            View::forge('todo/edit', [
                'todo' => $todo
            ], false)
        );
    }

    public function action_update($id)
    {
        return $this->post_update($id);
    }

    public function post_update($id)
    {
        $this->require_csrf();

        $title = trim(Input::post('title'));
        if ($title === '')
        {
            exit('Title is required');
        }
        $description = Input::post('description');
    
        $priority = (int) Input::post('priority');
        $allowed_priority = array_keys(Config::get('todo.priority_labels', []));
        if (!in_array($priority, $allowed_priority, true))
        {
            exit('Invalid priority');
        }
        $status = (int) Input::post('status');
        $group_id = (int) Input::post('group_id');

        $allowed_status = array_keys(Config::get('todo.status_labels', []));
        if (!in_array($status, $allowed_status, true))
        {
            exit('Invalid status');
        }
        $due_date = Input::post('due_date');
        if ($due_date !== '')
        {
            $date = DateTime::createFromFormat('Y-m-d', $due_date);
        
            if (
                !$date ||
                $date->format('Y-m-d') !== $due_date
            )
            {
                exit('Invalid due date');
            }
            if ($due_date < date('Y-m-d'))
            {
                exit('Past dates are not allowed');
            }
        }

        DB::update('todos')
            ->set([
                'title' => $title,
                'description' => $description,
                'priority' => $priority,
                'status' => $status,
                'due_date' => $due_date ?: null,
            ])
            ->where('id', $id)
            ->execute();
    
        return Response::redirect('/todo');
    }
    public function action_delete($id)
    {
        DB::update('todos')
            ->set([
                'deleted_at' => date('Y-m-d H:i:s')
            ])
            ->where('id', $id)
            ->execute();
    
        return Response::redirect('/todo');
    }
}