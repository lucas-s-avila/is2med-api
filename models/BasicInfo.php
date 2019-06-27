<?php

class BasicInfo implements JsonSerializable {
    private $id;
    private $username;
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

    public function jsonSerialize() {
        return array(
            "id" => $this->getId(),
            "name" => $this->getName(),
            "address" => $this->getAddress(),
            "phone" => $this->getPhone()
        );
    }
}

?>