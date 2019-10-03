<?php
require_once("BasicInfo.php");

class Doctor extends BasicInfo {
    private $specialty;
    private $crm;

    function __construct($id, $name, $address, $phone, $email, $specialty, $crm) {
        parent::__construct($id, $name, $address, $phone, $email);
        $this->specialty = $specialty;
        $this->crm = $crm;
    }

    function getSpecialty() {
        return $this->specialty;
    }

    function setSpecialty($specialty) {
        $this->specialty = $specialty;
    }

    function getCrm() {
        return $this->crm;
    }

    function setCrm($crm) {
        $this->crm = $crm;
    }

    public function jsonSerialize() {
        $array = parent::jsonSerialize();
        $array['Specialty'] = $this->getSpecialty();
        $array['CRM'] = $this->getCrm();
        return $array;
    }

}

?>