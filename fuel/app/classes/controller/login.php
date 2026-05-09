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
            $mode = Input::post('mode');
            if ($mode === 'register')
            {
                $exists = DB::select()
                    ->from('users')
                    ->where('username', '=', $username)
                    ->execute()
                    ->count();
            
                if ($exists > 0)
                {
                    exit('既に存在するユーザーです');
                }
            
                list($user_id, $rows) = DB::insert('users')
                    ->set([
                        'username' => $username,
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                    ])
                    ->execute();
            
                Session::set('user_id', $user_id);
            
                return Response::redirect('/groups');
            }

            if ($user && password_verify($password, $user['password'])) {

                Session::set('user_id', $user['id']);

                return Response::redirect('/groups');

            } else {

                echo e('login failed');

            }

            exit;
        }

        return View::forge('login/index');
    }
}