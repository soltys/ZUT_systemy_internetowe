<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserSession
 *
 * @author Soltys
 */
class UserSession {

    private $userId;
    private $sessionId;
    function __construct($userId, $sessionId) {
        $this->userId = $userId;
        $this->sessionId = $sessionId;
    }
    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getSessionId() {
        return $this->sessionId;
    }

    public function setSessionId($sessionId) {
        $this->sessionId = $sessionId;
    }



}

?>
