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
        $bi_array = parent::jsonSerialize();
        $doc_array = array(
            'specialization' => $this->getSpecialization(),
            'crm' => $this->getCrm()
        );
        return $bi_array;
        //return array_values(array_merge($bi_array, $doc_array));
    }

}

?>