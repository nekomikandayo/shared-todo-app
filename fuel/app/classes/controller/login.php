<?php

class Controller_Login extends Controller_Base
{
    public function before()
    {
        parent::before();

        if (Session::get('user_id')) {
            Response::redirect('/groups');
            exit;
        }
    }

    public function action_index()
    {
        if (Input::method() === 'POST') {
            $this->require_csrf();

            $username = trim(Input::post('username'));
            $password = Input::post('password');
            $mode = Input::post('mode');

            if ($username === '' || $password === '') {
                Session::set_flash(
                    'error',
                    'ユーザー名とパスワードを入力してください'
                );

                return Response::redirect('/login');
            }

            $user = DB::select()
                ->from('users')
                ->where('username', '=', $username)
                ->execute()
                ->current();

            if ($mode === 'register') {
                if ($user) {
                    Session::set_flash(
                        'error',
                        '既に存在するユーザーです'
                    );

                    return Response::redirect('/login');
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
            ) {
                Session::set('user_id', $user['id']);

                return Response::redirect('/groups');
            }

            Session::set_flash(
                'error',
                'ログインに失敗しました'
            );


            return Response::redirect('/login');
        }

        return View::forge('login/index');
    }
}
