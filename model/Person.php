<?php

class Person{

    private $personId;
    private $firstName;
    private $lastName;
    private $gender;
    private $maidenName;
    private $email;
    private $postalCode;

    function __construct( $firstName, $lastName, $gender, $maidenName, $email, $postalCode) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;
        $this->maidenName = $maidenName;
        $this->email = $email;
        $this->postalCode = $postalCode;
    }

    public function getPersonId() {
        return $this->personId;
    }

    public function setPersonId($personId) {
        $this->personId = $personId;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function getMaidenName() {
        return $this->maidenName;
    }

    public function setMaidenName($maidenName) {
        $this->maidenName = $maidenName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPostalCode() {
        return $this->postalCode;
    }

    public function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;
    }


}
?>
