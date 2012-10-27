<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Soltys
 */
class User {
    private $userId;
    private $login;
    private $password;
    private $rights;
    private $firstName;
    private $lastName;
    
    function __construct($login, $password, $rights, $firstName, $lastName,$userId =0) {
        $this->userId = $userId;
        $this->login = $login;
        $this->password = $password;
        $this->rights = $rights;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    
    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getRights() {
        return $this->rights;
    }

    public function setRights($rights) {
        $this->rights = $rights;
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


            
}

?>
