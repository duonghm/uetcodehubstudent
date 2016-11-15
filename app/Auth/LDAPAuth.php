<?php
/**
 * Created by PhpStorm.
 * User: hmduong
 * Date: 11/16/2016
 * Time: 3:14 AM
 */

namespace App\Auth;

class LDAPAuth
{
    function getLDAP_DN($username){
        return "uid=$username,dc=example,dc=com";
    }

    function loginLDAP($username, $password, $ldap_server){
        $ldaprdn  = $this->getLDAP_DN($username);
        $ldappass = $password;
        $ldapconn = ldap_connect($ldap_server)
        or die("Could not connect to LDAP server.");
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
        if ($ldapconn) {
            $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
            if ($ldapbind) {
                $results = ldap_search(
                    $ldapconn,
                    $ldaprdn,
                    "(uid=$username)",
                    array("cn","sn","mail")
                );
                $entries = ldap_get_entries($ldapconn, $results);
                if($entries['count'] <= 0){
                    return null;
                }else{
                    $obj = new \stdClass();
                    $obj->username = $username;
                    $obj->password = $password;
                    $obj->firstname = $entries[0]['cn'][0];
                    $obj->lastname = $entries[0]['sn'][0];
                    $obj->email = $entries[0]['mail'][0];
                    return $obj;
                }
            } else {
                return null;
            }
        }
    }
}