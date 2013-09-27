<?php namespace Codi;

/**
 * Class User of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Activation;
use Codi\Date;
use Codi\Error;
use Codi\Msg;
use Codi\User;
use Zend\Authentication\AuthenticationService as Auth;
use Zend\SessionManager as Session;

class User
{
  private static $_instance;

  private $_OAuth = null;

  private $_AUserInfo = [];

  protected function __construct()
  {
    $this->_OAuth = Auth::getInstance();

    $ASess_Options = Session::getOptions();
    if (!empty($ASess_Options['user_name'])) {
      $db = Db::factory();
      $q = $db->select()->from('acl_user')->where('name = ?', $ASess_Options['user_name']);
      $this->_AUserInfo = $db->fetchAll($q);
    }
  }

  /**
   *
   * @return Coid_User
   */
  public static function getInstance()
  {
    if (!(self::$_instance instanceof User)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function hasIdentity()
  {
    return $this->_OAuth->hasIdentity();
  }

  public function authenticate($name, $password)
  {
    $db = Db::factory();
    $OAdapter = new Zend_Auth_Adapter_DbTable($db, 'acl_user', 'name', 'password');
    $OAdapter->setIdentity($name);
    $OAdapter->setCredential($password);
    if (!$this->_OAuth->authenticate($OAdapter)) {
      Codi_Msg::addMsg('Niewłaściwa nazwa użytkownika lub hasło');
      return false;
    }
    return true;
  }

  public final function getId()
  {
    if (!empty($this->_AUserInfo['id'])) {
      return $this->_AUserInfo['id'];
    }
    return false;
  }

  public static function validateCredentials($name, $password)
  {
    if (!preg_match("/^[\_A-Za-z0-9]{3,}$/", $name) || !preg_match("/^[\_\-\+\=\.A-Za-z0-9]{6,}$/", $password)) {
      Msg::addMsg('Niepoprawna nazwa użytkownika bądź hasło');
      return false;
    }

    $db = Db::factory();

    $sql = $db->select()
              ->from('acl_user', 'COUNT(id)')
              ->where('name = ?', array($name));
    $userExists = $db->fetchOne($sql);

    if ($userExists) {
      Msg::addMsg('Użytkownik o podanej nazwie już istnieje w systemie.');
      return false;
    }

    return true;
  }

  public static function addUser($name, $password)
  {
    require_once 'Codi/Date.php';
    require_once 'Codi/Activation.php';
    require_once 'Codi/Msg.php';
    require_once 'Zend/Db/Exception.php';

    if (!self::validateCredentials($name, $password)) {
      return false;
    }

    $act_code = Activation::generate();

    $OUser = self::getInstance();
    $AInput = [
        'create_date' => Date::getDate(),
        'mod_date' => Date::getDate(),
        'create_user' => $OUser->getID(),
        'mod_user' => $OUser->getId(),
        'name' => $name,
        'password' => md5($password),
        'act_code' => $act_code
    ];

    $db = Db::factory();
    try {
      $db->insert('acl_user', $AInput);
    }
    catch (Zend_Db_Exception $e) {
      Error::throwError('Nie powiodło się dodawanie użytkownika do bazy. ' . $e->getMessage());
    }
  }

  public final function getName()
  {
    return $this->_AUserInfo['name'];
  }
}