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
        $todos = Model_Todo::get_all_todos();

        return Response::forge(
            View::forge('todo/index', [
                'todos' => $todos,
                'group_id' => 0,
                'group_name' => null,
            ], false)
        );
    }
    public function action_store()
    {
        return $this->post_store();
    }
    public function action_group($group_id)
    {
        $group_id = (int) $group_id;

        $group_row = Model_Group::find_group($group_id);

        $group_name = $group_row
            ? $group_row['name']
            : null;

        $todos = Model_Todo::get_group_todos($group_id);

        return Response::forge(
            View::forge('todo/index', [
                'todos' => $todos,
                'group_id' => $group_id,
                'group_name' => $group_name,
            ], false)
        );
    }
    public function action_create()
    {
        $group_id = (int) Input::get('group_id');
        if ($group_id <= 0) {
            exit('Invalid group');
        }

        return Response::forge(
            View::forge('todo/create', [
                'group_id' => $group_id,
            ], false)
        );
    }
    public function post_store()
    {
        $this->require_csrf();

        $data = Model_Todo::validate_todo(Input::post());

        Model_Todo::create_todo($data);

        return Response::redirect('/todo/group/' . $data['group_id']);
    }
    public function action_edit($id)
    {
        $todo = Model_Todo::find_todo($id);

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

        $data = Model_Todo::validate_todo(Input::post());

        Model_Todo::update_todo($id, $data);

        return Response::redirect('/todo/group/' . $data['group_id']);
    }
    public function action_delete($id)
    {
        Model_Todo::delete_todo($id);

        return Response::redirect('/todo');
    }
    public function post_ajax_create()
    {
        $this->require_csrf();

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $data = Model_Todo::validate_todo($data);

        $todo_id = Model_Todo::create_todo($data);

        $todo = Model_Todo::find_todo($todo_id);

        return Response::forge(
            json_encode([
                'success' => true,
                'todo' => $todo
            ]),
            200,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    public function post_ajax_delete($id)
    {
        $this->require_csrf();
        Model_Todo::delete_todo($id);

        return Response::forge(
            json_encode([
                'success' => true
            ]),
            200,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    public function post_ajax_update_status($id)
    {
        $this->require_csrf();
        $data = json_decode(file_get_contents('php://input'), true);

        $status = (int) ($data['status'] ?? 0);

        Model_Todo::update_status($id, $status);

        return Response::forge(
            json_encode([
                'success' => true
            ]),
            200,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    public function post_ajax_update($id = null)
    {
        $this->require_csrf();
        if ($id === null) {

            return $this->response([
                'success' => false,
                'message' => 'ToDo IDがありません'
            ]);
        }

        $input = json_decode(
            file_get_contents('php://input'),
            true
        );

        $data = Model_Todo::validate_todo($input);

        Model_Todo::update_todo($id, $data);

        return Response::forge(
            json_encode([
                'success' => true,
            ]),
            200,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }
}
