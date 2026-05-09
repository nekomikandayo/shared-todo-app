<?php

class Controller_Groups extends Controller_Base
{
    public function before()
    {
        parent::before();
    
        if (! Session::get('user_id')) {
            return Response::redirect('/login');
        }
    }

    public function action_index()
    {
        $user_id = Session::get('user_id');
    
        $groups = DB::select('groups.id', 'groups.name')
            ->from('groups')
            ->join('group_users', 'INNER')
            ->on('groups.id', '=', 'group_users.group_id')
            ->where('group_users.user_id', '=', $user_id)
            ->where('groups.deleted_at', 'IS', DB::expr('NULL'))
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
        return Response::redirect('/groups');
    }
    public function action_delete($group_id)
    {
        return $this->post_delete($group_id);
    }

    public function post_delete($group_id)
    {
        $this->require_csrf();

        $user_id = Session::get('user_id');
        $is_member = DB::select()
            ->from('group_users')
            ->where('group_id', '=', $group_id)
            ->where('user_id', '=', $user_id)
            ->execute()
            ->count();

        if ($is_member > 0) {
            DB::update('groups')
                ->set(array('deleted_at' => date('Y-m-d H:i:s')))
                ->where('id', '=', $group_id)
                ->execute();

            Session::set_flash('success', 'グループを削除しました。');
        } else {
            Session::set_flash('error', '削除権限がありません。');
        }

        return Response::redirect('/groups');
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

        return Response::redirect('/groups');
    }
    public function action_join($token = null)
    {
        if (!$token) {
            return Response::redirect('/groups');
        }
    
        $invite = DB::select(
                'invite_tokens.*',
                ['groups.name', 'group_name']
            )
            ->from('invite_tokens')
            ->join('groups', 'INNER')
            ->on('invite_tokens.group_id', '=', 'groups.id')
            ->where('invite_tokens.token', '=', $token)
            ->where('invite_tokens.used_at', 'IS', DB::expr('NULL'))
            ->where('invite_tokens.expires_at', '>', date('Y-m-d H:i:s'))
            ->execute()
            ->current();
    
        if (!$invite) {
            Session::set_flash('error', '招待リンクが無効か、期限切れです。');
            return Response::redirect('/groups');
        }
    
        return Response::forge(
            View::forge('groups/join_confirm', [
                'invite' => $invite,
                'token' => $token,
            ], false)
        );
    }
    public function post_join($token = null)
    {
        $this->require_csrf();
    
        if (!$token) {
            return Response::redirect('/groups');
        }
    
        $invite = DB::select()
            ->from('invite_tokens')
            ->where('token', '=', $token)
            ->where('used_at', 'IS', DB::expr('NULL'))
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->execute()
            ->current();
    
        if (!$invite) {
            Session::set_flash('error', '招待リンクが無効か、期限切れです。');
            return Response::redirect('/groups');
        }
    
        $user_id = Session::get('user_id');
        $group_id = $invite['group_id'];
    
        $exists = DB::select()
            ->from('group_users')
            ->where('user_id', '=', $user_id)
            ->where('group_id', '=', $group_id)
            ->execute()
            ->count();
    
        if ($exists == 0) {
            DB::insert('group_users')->set([
                'user_id' => $user_id,
                'group_id' => $group_id,
            ])->execute();
        }
    
        DB::update('invite_tokens')
            ->set([
                'used_at' => date('Y-m-d H:i:s')
            ])
            ->where('token', '=', $token)
            ->execute();
    
        Session::set_flash('success', 'グループに参加しました！');
    
        return Response::redirect('/groups');
    }
}