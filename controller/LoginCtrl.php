<?php

require_once("../models/User.php");
require_once("UserDB.php");

function login($data) {
    $users = loadUsers();
    foreach($users as $user) {
        if($user->getUsername() == $data["username"]) {
            if($user->getPassword() == $data["password"]) {
                return $user;
            }
        }
    }
    return "HTTP/1.0 403 Forbidden";
}

?>