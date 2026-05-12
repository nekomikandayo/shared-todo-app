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

        $groups = Model_Group::get_user_groups($user_id);

        $view = View::forge('groups/index', [], false);
        $view->set('groups', $groups);

        return $view;
    }

    public function post_create()
    {
        $this->require_csrf();

        $group_name = Input::post('group_name');

        if ($group_name) {

            try {

                Model_Group::create_group(
                    $group_name,
                    Session::get('user_id')
                );
                Session::set_flash(
                    'success',
                    'グループを作成しました'
                );
            } catch (Exception $e) {

                Session::set_flash(
                    'error',
                    'グループ作成に失敗しました'
                );
            }
        }

        return Response::redirect('/groups');
    }

    public function post_delete($group_id)
    {
        $this->require_csrf();

        $user_id = Session::get('user_id');

        if (Model_Group::is_member($group_id, $user_id)) {

            Model_Group::delete_group($group_id);

            Session::set_flash(
                'success',
                'グループを削除しました。'
            );
        } else {

            Session::set_flash(
                'error',
                '削除権限がありません。'
            );
        }

        return Response::redirect('/groups');
    }

    public function post_invite($group_id)
    {
        $this->require_csrf();

        $token = Model_Group::create_invite($group_id);

        $invite_url =
            "http://localhost/groups/join/" . $token;

        Session::set_flash(
            'invite_url',
            $invite_url
        );

        return Response::redirect('/groups');
    }
    public function action_join($token = null)
    {
        if (!$token) {
            return Response::redirect('/groups');
        }

        $invite = Model_Group::find_valid_invite($token);

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

        $invite = Model_Group::find_valid_invite($token);

        if (!$invite) {
            Session::set_flash('error', '招待リンクが無効か、期限切れです。');
            return Response::redirect('/groups');
        }

        $user_id = Session::get('user_id');
        $group_id = $invite['group_id'];

        Model_Group::join_group(
            $group_id,
            $user_id
        );

        Model_Group::use_invite($token);

        Session::set_flash('success', 'グループに参加しました！');

        return Response::redirect('/groups');
    }
}
