<?php

if (function_exists('current_permission_value') === false) {

    function current_permission_value($model, $permissionTitle, $permissionAction) {
        $value = array_get($model->permissions, "$permissionTitle.$permissionAction");
        if ($value === true) {
            return 1;
        }
        if ($value === false) {
            return 0;
        }

        return 0;
    }

}


if (function_exists('count_manga') === false) {

    function count_manga($user_id) {
        $stmt = DB::select("select count(id) total from `manga` where `manga`.`user_id` = $user_id");
        return $stmt[0]->total;
    }

}

if (function_exists('count_chapters') === false) {

    function count_chapters($user_id) {
        $stmt = DB::select("select count(id) total from `chapter` where `chapter`.`user_id` = $user_id");
        return $stmt[0]->total;
    }

}
