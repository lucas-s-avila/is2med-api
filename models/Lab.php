<?php
require_once("BasicInfo.php");

class Lab extends BasicInfo {
    private $examType;
    private $cnpj;

    function __construct($id, $name, $address, $phone, $examType, $cnpj) {
        parent::__construct($id, $name, $address, $phone);
        $this->examType = $examType;
        $this->cnpj = $cnpj;
    }

    function getExamType() {
        return $this->examType;
    }

    function setExamType($examType) {
        $this->examType = $examType;
    }

    function getCnpj() {
        return $this->cnpj;
    }

    function setCrm($cnpj) {
        $this->cnpj = $cnpj;
    }

    public function jsonSerialize() {
        $array = parent::jsonSerialize();
        $array['examType'] = $this->getExamType();
        $array['cnpj'] = $this->getCnpj();
        return $array;
    }

}

?>