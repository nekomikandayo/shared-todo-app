<?php

class Model_Group extends Model
{
    public static function find_group($group_id)
    {
        return DB::select()
            ->from('groups')
            ->where('id', '=', $group_id)
            ->where('deleted_at', 'IS', DB::expr('NULL'))
            ->execute()
            ->current();
    }

    public static function get_user_groups($user_id)
    {
        return DB::select(
            'groups.id',
            'groups.name'
        )
            ->from('groups')
            ->join('group_users', 'LEFT')
            ->on('groups.id', '=', 'group_users.group_id')
            ->where('group_users.user_id', '=', $user_id)
            ->where('groups.deleted_at', 'IS', DB::expr('NULL'))
            ->execute()
            ->as_array();
    }

    public static function create_group($group_name, $user_id)
    {
        DB::start_transaction();

        try {

            list($group_id) = DB::insert('groups')
                ->set([
                    'name' => $group_name,
                ])
                ->execute();

            DB::insert('group_users')
                ->set([
                    'user_id'  => $user_id,
                    'group_id' => $group_id,
                ])
                ->execute();

            DB::commit_transaction();

            return $group_id;
        } catch (Exception $e) {

            DB::rollback_transaction();

            throw $e;
        }
    }

    public static function delete_group($group_id)
    {
        return DB::update('groups')
            ->set([
                'deleted_at' => date('Y-m-d H:i:s')
            ])
            ->where('id', '=', $group_id)
            ->execute();
    }
    public static function is_member($group_id, $user_id)
    {
        return DB::select()
            ->from('group_users')
            ->where('group_id', '=', $group_id)
            ->where('user_id', '=', $user_id)
            ->execute()
            ->count() > 0;
    }
    public static function create_invite($group_id)
    {
        $token = bin2hex(random_bytes(16));

        $expires_at = date(
            'Y-m-d H:i:s',
            strtotime('+24 hours')
        );

        DB::insert('invite_tokens')
            ->set([
                'group_id'   => $group_id,
                'token'      => $token,
                'expires_at' => $expires_at,
                'used_at'    => null,
            ])
            ->execute();

        return $token;
    }
    public static function find_valid_invite($token)
    {
        return DB::select(
            'invite_tokens.id',
            'invite_tokens.group_id',
            'invite_tokens.token',
            'invite_tokens.expires_at',
            ['groups.name', 'group_name']
        )
            ->from('invite_tokens')
            ->join('groups', 'INNER')
            ->on(
                'invite_tokens.group_id',
                '=',
                'groups.id'
            )
            ->where(
                'invite_tokens.token',
                '=',
                $token
            )
            ->where(
                'invite_tokens.used_at',
                'IS',
                DB::expr('NULL')
            )
            ->where(
                'invite_tokens.expires_at',
                '>',
                date('Y-m-d H:i:s')
            )
            ->execute()
            ->current();
    }
    public static function join_group($group_id, $user_id)
    {
        if (! self::is_member($group_id, $user_id)) {

            DB::insert('group_users')
                ->set([
                    'user_id'  => $user_id,
                    'group_id' => $group_id,
                ])
                ->execute();
        }
    }
    public static function use_invite($token)
    {
        return DB::update('invite_tokens')
            ->set([
                'used_at' => date('Y-m-d H:i:s')
            ])
            ->where('token', '=', $token)
            ->execute();
    }
}
