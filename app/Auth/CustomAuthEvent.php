<?php
/**
 * Created by PhpStorm.
 * User: hmduong
 * Date: 11/16/2016
 * Time: 2:23 AM
 */

namespace App\Auth;


use App\Models\User;

class CustomAuthEvent
{
    public $ds = "ldap.forumsys.com";

    public function handle($event){
        $username = $event->credentials['username'];
        $password = $event->credentials['password'];
        if(User::where('username',$username)->count() == 0){
            $ldapAuth = new LDAPAuth();
            $obj = $ldapAuth->loginLDAP($username, $password, $this->ds);
            if($obj != null){
                User::create([
                    'username' => $username,
                    'password' => md5($password),
                    'firstname' => "$obj->firstname",
                    'lastname' => "$obj->lastname",
                    'email' => "$obj->email",
                    'roleId' => 4
                ]);
            }

        }

    }
}