<?php

require_once("../models/User.php");

function loadUsers() {
    $xmlusers = simplexml_load_file("../xml/users.xml");
    $users = array();
    foreach($xmlusers as $user) {
        $users[] = new User((string) $user->id, (string) $user->username, (string) $user->password, (string) $user->profileId, (string) $user->group);
    }
    return $users;
}

function loadUser($id) {
    $users = loadUsers();
    foreach($users as $user) {
        if($user->getId() == $id) {
            return $user;
        }
    }
    return false;
}

function writeNewUser($data) {
    $id = time();
    $user = new User((string) $id, (string) $data["username"], (string) $data["password"], (string) $data["profileId"], (string) $data["group"]);
    $xmlusers = simplexml_load_file("../xml/users.xml");
    $userNode = $xmlusers->addChild("user");
    
    if(!array_key_exists("username", $data) || !array_key_exists("password", $data) || !array_key_exists("profileId", $data) || !array_key_exists("group", $data)) {
        return "HTTP/1.0 400 Bad Request";
    }

    $userNode->addChild("id", $user->getId());
    $userNode->addChild("username", $user->getUsername());
    $userNode->addChild("password", $user->getPassword());
    $userNode->addChild("profileId", $user->getProfileId());
    $userNode->addChild("group",  $user->getgroup());

    $write = simplexml_import_dom($xmlusers);
    $write->saveXML("../xml/users.xml");

    return $user;
}

function writeUser($id, $data) {
    $user = loadUser($id);
    if(gettype($user) == "object") {

        if(!array_key_exists("username", $data) || !array_key_exists("password", $data) || !array_key_exists("profileId", $data) || !array_key_exists("group", $data)) {
            return "HTTP/1.0 400 Bad Request";
        }

        $user->setUsername((string) $data["username"]);
        $user->setPassword((string) $data["password"]);
        $user->setProfileId((string) $data["profileId"]);
        $user->setGroup((string) $data["group"]);

        $xmlusers = simplexml_load_file("../xml/users.xml");
        foreach($xmlusers as $userNode) {
            if($userNode->id == $user->getId()) {
                $userNode->username = $user->getUsername();
                $userNode->password = $user->getPassword();
                $userNode->profileId = $user->getProfileId();
                $userNode->group = $user->getGroup();
            }
        }

        $write = simplexml_import_dom($xmlusers);
        $write->saveXML("../xml/users.xml");

        return $user;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function writeAttributeUser($id, $data) {
    $user = loadUser($id);
    if(gettype($user) == "object") {
        foreach($data as $key => $value) {
            switch ($key) {
                case 'username':
                    $user->setUsername((string) $value);
                    break;
                case 'password':
                    $user->setPassword((string) $value);
                    break;
                case 'profileId':
                    $user->setProfileId((string) $value);
                    break;
                case 'group':
                    $user->setGroup((string) $value);
                    break;
                default:
                    return "HTTP/1.0 200 OK";
                    break;
            }
        }

        $xmlusers = simplexml_load_file("../xml/users.xml");
        foreach($xmlusers as $userNode) {
            if($userNode->id == $user->getId()) {
                $userNode->username = $user->getUsername();
                $userNode->password = $user->getPassword();
                $userNode->profileId = $user->getProfileId();
                $userNode->group = $user->getGroup();
            }
        }

        $write = simplexml_import_dom($xmlusers);
        $write->saveXML("../xml/users.xml");

        return $user;
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

function removeUser($id) {
    $user = loadUser($id);
    if(gettype($user) == "object") {
        $xmlusers = simplexml_load_file("../xml/users.xml");
        foreach($xmlusers as $userNode) {
            if($userNode->id == $user->getId()) {
                unset($userNode[0]);
                $write = simplexml_import_dom($xmlusers);
                $write->saveXML("../xml/users.xml");
                return "HTTP/1.0 200 OK";
            }
        }
    } else {
        return "HTTP/1.0 404 Not Found";
    }
}

?>