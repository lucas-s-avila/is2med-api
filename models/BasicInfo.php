<?php

class BasicInfo implements JsonSerializable {
    private $id;
    private $name;
    private $address;
    private $phone;
    private $email;

    function __construct($id, $name, $address, $phone, $email) {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
        $this->email = $email;
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

    function getEmail() {
        return $this->email;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    public function jsonSerialize() {
        return array(
            "id" => $this->getId(),
            "name" => $this->getName(),
            "address" => $this->getAddress(),
            "phone" => $this->getPhone(),
            "email" => $this->getEmail()
        );
    }
}

?>