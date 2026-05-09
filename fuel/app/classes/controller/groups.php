<?php

class Controller_Groups extends Controller_Base
{
    public function before()
    {
        parent::before();
        if (! Session::get('user_id')) {
            Response::redirect('/login');
        }
    }

    public function action_index()
    {
        $user_id = Session::get('user_id');

        $groups = DB::select('groups.id', 'groups.name')
            ->from('groups')
            ->join('group_users')
            ->on('groups.id', '=', 'group_users.group_id')
            ->where('group_users.user_id', '=', $user_id)
            ->execute()
            ->as_array();

        $view = View::forge('groups/index');
        $view->set('groups', $groups);
        return $view;
    }

    public function action_create()
    {
        return $this->post_create();
    }

    public function post_create()
    {
        $this->require_csrf();

        $group_name = Input::post('group_name');

        if ($group_name) {
            try {
                list($group_id, $rows_affected) = DB::insert('groups')->set(array(
                    'name' => $group_name,
                ))->execute();

                DB::insert('group_users')->set(array(
                    'user_id'  => Session::get('user_id'),
                    'group_id' => $group_id,
                ))->execute();

            } catch (Exception $e) {
                //error
            }
        }
        Response::redirect('/groups');
    }

    public function action_invite($group_id)
    {
        $token = bin2hex(random_bytes(16));
        $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));

        DB::insert('invite_tokens')->set(array(
            'group_id'   => $group_id,
            'token'      => $token,
            'expires_at' => $expires_at,
            'used_at'    => null,
        ))->execute();

        $invite_url = "http://localhost/groups/join/" . $token;
        Session::set_flash('invite_url', $invite_url);

        Response::redirect('/groups');
    }

    public function action_join($token = null)
    {
        if (!$token) {
            Response::redirect('/groups');
        }

        $invite = DB::select()
            ->from('invite_tokens')
            ->where('token', '=', $token)
            ->where('used_at', 'IS', DB::expr('NULL'))
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->execute()
            ->current();

        if ($invite) {
            $user_id = Session::get('user_id');
            $group_id = $invite['group_id'];

            $exists = DB::select()
                ->from('group_users')
                ->where('user_id', '=', $user_id)
                ->where('group_id', '=', $group_id)
                ->execute()
                ->count();

            if ($exists == 0) {
                DB::insert('group_users')->set(array(
                    'user_id'  => $user_id,
                    'group_id' => $group_id,
                ))->execute();
            }

            DB::update('invite_tokens')
                ->set(array('used_at' => date('Y-m-d H:i:s')))
                ->where('token', '=', $token)
                ->execute();

            Session::set_flash('success', 'グループに参加しました！');
        } else {
            Session::set_flash('error', '招待リンクが無効か、期限が切れています。');
        }

        Response::redirect('/groups');
    }
}