<?php

namespace App\Ldap;

class Ldap {

    private static function getConnection() {

        $ldap_host= "10.200.0.5";
        $ldap_port = 389;

        $conn = @ldap_connect($ldap_host, $ldap_port)
        or die("Could not connect to $ldap_host");
        @ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        @ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

        return $conn;
    }

    public static function authenticate($username, $password) {
        $dom = "SAUDE";
        $conn = self::getConnection();

        $fullUsername = $dom."\\".$username;

        $bind = @ldap_bind($conn, $fullUsername, $password);

        if ($bind) {
            return true;
        } else {
            return false;
        }
    }
}