<?php

require_once dirname(dirname(__FILE__)) . "/config.php";
require_once ABSPATH . 'DAL/Database.php';
require_once ABSPATH . 'DAL/SessionManager.php';
require_once ABSPATH . 'klogger/klogger.php';

/**
 * Description of Authentication
 *
 * @author Soltys
 */
class Authentication {

    private $log;
    private $sm;
    private $db;
    private static $oInstance = false;
/**
 * @return Authentication
 */
    public static function getInstance() {
        if (self::$oInstance == false) {
            self::$oInstance = new Authentication();
        }
        return self::$oInstance;
    }

    private function __construct() {
        $this->log = KLogger::instance(LOGPATH . "Authentication", KLOGGER_ERROR_LEVEL);
        $this->sm = SessionManager::getInstance();
        $this->db = new Database();
    }

    public function isUserLoggedIn() {
        $userId = $this->sm->getLoggedUserId();
        return isset($userId);
    }

    public function isLoginExits($login) {
        return $this->db->isLoginExists($login);
    }

    public function checkCredentials($login, $password) {
        return $this->db->checkCredentials($login, $password);
    }

    public function isUserHaveRights($rights) {
        return $this->sm->getUserRights() >= $rights;
    }

    /**    
     * @return User
     */
    public function getCurrentUser() {
        $userId = $this->sm->getLoggedUserId();
        $user = $this->db->getUser($userId);
        return $user;
    }

    public function getUserFirstName() {
        $userId = $this->sm->getLoggedUserId();
        if (isset($userId)) {
            $user = $this->db->getUser($userId);
            return $user->getFirstName();
        }
        return "";
    }

    public function addUser($user) {
        $this->db->addUser($user);
        $this->login($user->getLogin());
    }
    /**     
     * @param User $user
     */
public function updateUser($user)
{
    $this->db->updateUser($user);
}
    public function login($login) {
        $user = $this->db->getUserByLogin($login);
        $this->sm->setLoggedUserId($user->getUserId());
        $this->sm->setUserRights($user->getRights());
    }

    public function logout() {
        $this->sm->setLoggedUserId(NULL);
        $this->sm->setUserRights(NULL);
    }

}

?>
