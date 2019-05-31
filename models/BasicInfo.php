<?php

class BasicInfo {
    private $id;
    private $name;
    private $address;
    private $phone;

    function __construct($id, $name, $address, $phone) {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function setName($name) {
        $this->name = $name;
    }

    function getAddress() {
        return $this->address;
    }

    function setAddress($address) {
        $this->address = $address;
    }

    function getPhone() {
        return $this->phone;
    }

    function setPhone($phone) {
        $this->phone = $phone;
    }
}

?>