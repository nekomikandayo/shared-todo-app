<?php

class Model_Group extends Model
{
    public static function find_group($group_id)
    {
        return DB::select()
            ->from('groups')
            ->where('id', '=', $group_id)
            ->execute()
            ->current();
    }
}