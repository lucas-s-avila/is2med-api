<?php
require_once("BasicInfo.php");

class Doctor extends BasicInfo {
    private $specialization;
    private $crm;

    function __construct($id, $name, $address, $phone, $specialization, $crm) {
        parent::__construct($id, $name, $address, $phone);
        $this->specialization = $specialization;
        $this->crm = $crm;
    }

    function getSpecialization() {
        return $this->specialization;
    }

    function setSpecialization($specialization) {
        $this->specialization = $specialization;
    }

    function getCrm() {
        return $this->crm;
    }

    function setCrm($crm) {
        $this->crm = $crm;
    }

    public function jsonSerialize() {
        $array = parent::jsonSerialize();
        $array['specialization'] = $this->getSpecialization();
        $array['crm'] = $this->getCrm();
        return $array;
    }

}

?>