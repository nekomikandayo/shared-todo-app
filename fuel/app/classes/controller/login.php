<?php

class Controller_Login extends Controller_Base
{
    public function before()
    {
        parent::before();

        if (Session::get('user_id'))
        {
            Response::redirect('/groups');
            exit;
        }
    }

    public function action_index()
    {
        if (Input::method() === 'POST')
        {
            $this->require_csrf();

            $username = trim(Input::post('username'));
            $password = Input::post('password');
            $mode = Input::post('mode');

            if ($username === '' || $password === '')
            {
                exit('ユーザー名とパスワードを入力してください');
            }

            $user = DB::select()
                ->from('users')
                ->where('username', '=', $username)
                ->execute()
                ->current();

            if ($mode === 'register')
            {
                if ($user)
                {
                    exit('既に存在するユーザーです');
                }

                list($user_id, $rows) = DB::insert('users')
                    ->set([
                        'username' => $username,
                        'password' => password_hash(
                            $password,
                            PASSWORD_DEFAULT
                        ),
                    ])
                    ->execute();

                Session::set('user_id', $user_id);

                return Response::redirect('/groups');
            }

            if (
                $user &&
                password_verify($password, $user['password'])
            )
            {
                Session::set('user_id', $user['id']);

                return Response::redirect('/groups');
            }

            exit('ログインに失敗しました');
        }

        return View::forge('login/index');
    }
}