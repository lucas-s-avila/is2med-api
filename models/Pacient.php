<?php
require_once("BasicInfo.php");

class Pacient extends BasicInfo {
    private $gender;
    private $birthday;
    private $cpf;

    function __construct($id, $name, $address, $phone, $gender, $birthday, $cpf) {
        parent::__construct($id, $name, $address, $phone);
        $this->gender = $gender;
        $this->birthday = $birthday;
        $this->cpf = $cpf;
    }

    function getGender() {
        return $this->gender;
    }

    function setGender($gender) {
        $this->gender = $gender;
    }

    function getBirthday() {
        return $this->birthday;
    }

    function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    function getCpf() {
        return $this->cpf;
    }

    function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function jsonSerialize() {
        $array = parent::jsonSerialize();
        $array['gender'] = $this->getGender();
        $array['birthday'] = $this->getBirthday();
        $array['cpf'] = $this->getCpf();
        return $array;
    }

}

?>