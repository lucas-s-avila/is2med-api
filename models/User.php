<?php

class User implements JsonSerializable {
    private $id;
    private $username;
    private $password;
    private $profileId;
    private $group;

    function __construct($id, $username, $password, $profileId, $group) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->profileId = $profileId;
        $this->group = $group;
    }

    function getId() {
        return $this->id;
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
        return $this->profileId;
    }

    function setProfileId($profileId) {
        $this->profileId = $profileId;
    }

    function getGroup() {
        return $this->group;
    }

    function setGroup($group) {
        $this->group = $group;
    }

    public function jsonSerialize() {
        return array(
            "ID" => $this->getId(),
            "Username" => $this->getUsername(),
            "Password" => $this->getPassword(),
            "ProfileID" => $this->getProfileId(),
            "Group" => $this->getGroup()
        );
    }
}

?>