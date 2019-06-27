<?php

require_once("../models/User.php");

function loadUsers() {
    $xmlUsers = simplexml_load_file("../xml/users.xml");
    $users = array();
    foreach($xmlUsers->Children() as $user) {
        $users[] = new User((string) $user->username, (string) $user->password, (string) $user->profile_id, (string) $user->group);
    }
    return $users;
}

function login($data) {
    $users = loadUsers();
    foreach($users as $user) {
        if($user->getUsername() == $data["username"]) {
            if($user->getPassword() == $data["password"]) {
                return "HTTP/1.0 200 OK";
            }
        }
    }
    return "HTTP/1.0 403 Forbidden";
}

?>