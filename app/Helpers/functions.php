<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;




if (!function_exists('check')) {

    function check($permission_name, $action)
    {
        $user = Auth::user();
        if (!$user) return false;

        $q = DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role_id', $user->role_id)
            ->where('permissions.name', $permission_name);

        switch ($action) {
            case 'insert':
                $q->where('role_permissions.insert', 1);
                break;
            case 'update':
                $q->where('role_permissions.update', 1);
                break;
            case 'delete':
                $q->where('role_permissions.delete', 1);
                break;
            case 'list':
                $q->where('role_permissions.list', 1);
                break;
        }

        return $q->exists();
    }
}

if (!function_exists('check')) {

    function check($permission_name, $action)
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        $q = DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role_id', $user->role_id)
            ->where('permissions.name', $permission_name);

        switch ($action) {
            case 'insert':
                $q->where('role_permissions.insert', 1);
                break;

            case 'update':
                $q->where('role_permissions.update', 1);
                break;

            case 'delete':
                $q->where('role_permissions.delete', 1);
                break;

            case 'list':
                $q->where('role_permissions.list', 1);
                break;
        }

        return $q->exists();
    }
}