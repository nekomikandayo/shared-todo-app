<?php

class Controller_Login extends Controller_Base
{
    public function action_index()
    {
        if (Input::method() === 'POST') {

            $this->require_csrf();

            $username = Input::post('username');
            $password = Input::post('password');

            $user = DB::select()
                ->from('users')
                ->where('username', $username)
                ->execute()
                ->current();

            if ($user && password_verify($password, $user['password'])) {

                Session::set('user_id', $user['id']);

                Response::redirect('/groups/index');

            } else {

                echo e('login failed');

            }

            exit;
        }

        return View::forge('login/index');
    }
}