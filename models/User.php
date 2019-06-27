<?php

class User implements JsonSerializable {
    private $username;
    private $password;
    private $profile_id;
    private $group;

    function __construct($username, $password, $profile_id, $group) {
        $this->username = $username;
        $this->password = $password;
        $this->profile_id = $profile_id;
        $this->group = $group;
    }

    function getUsername() {
        return $this->username;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function getPassword() {
        return $this->password;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function getProfileId() {
        return $this->profile_id;
    }

    function setProfileId($profile_id) {
        $this->profile_id = $profile_id;
    }

    function getGroup() {
        return $this->group;
    }

    function setGroup($group) {
        $this->group = $group;
    }

    public function jsonSerialize() {
        return array(
            "username" => $this->getUsername(),
            "password" => $this->getPassword(),
            "id" => $this->getProfileId(),
            "group" => $this->getGroup()
        );
    }
}

?>