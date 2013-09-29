<?php namespace Codi;

/**
 * Class User of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Activation;
use Codi\Date;
use Codi\DataBase as Db;
use Codi\Error;
use Codi\Msg;
use Codi\User;
use Codi\User\Authenticator as Auth;

class User
{
  private static $_instance;

  private $_OAuth = null;

  private $_AUserInfo = [];

  private $_ARemoteInfo = [];

  protected function __construct()
  {
    $this->_OAuth = new Auth();

    $this->_ARemoteInfo = $this->_getRemoteInfo();

    if (Session::getValue('user_name')) {
      $db = Db::factory();
      $q = $db->select()->from('acl_user')
                ->where('name                 = ?', Session::getValue('user_name'))
                ->where('session_id           = ?', Session::getId())
                ->where('session_ip           = ?', $this->_ARemoteInfo['ip'])
                ->where('session_hash_browser = ?', $this->_ARemoteInfo['hash_browser']);
      $this->_AUserInfo = $db->fetchAll($q);
    }
  }

  private function _getRemoteInfo()
  {
    $ARemoteInfo = [];

    $ARemoteInfo['ip'] = $_SERVER['REMOTE_ADDR'];
    $ARemoteInfo['hash_browser'] = md5($_SERVER["HTTP_USER_AGENT"]);

    $agent_info = "_".$_SERVER['HTTP_USER_AGENT'];

    // TODO: Uzupelnic info o systemach i przegladarkach.
    $ASystem = [
        'Windows 2000' => 'NT 5.0',
        'Windows XP' => 'NT 5.1',
        'Windows Vista' => 'NT 6.0',
        'Windows 7' => 'NT 6.1',
        'Windows 8' => 'NT 6.2',
        'Linux' => 'Linux'
    ];

    $ABrowser = [
        'Internet Explorer' => 'MSIE',
        'Mozilla Firefox' => 'Firefox',
        'Opera' => 'Opera',
        'Chrome' => 'Chrome'
    ];

    foreach ($ASystem as $name => $ver) {
      if (strpos($agent_info, $id)) {
        $ARemoteInfo['system'];
        break;
      }
    }

    foreach ($ABrowser as $name => $tag) {
      if (strpos($agent_info, $tag)) {
        $ARemoteInfo['browser'] = $name;
        break;
      }
    }

    return $ARemoteInfo;
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
    if (!empty($this->_AUserInfo)) {
      return true;
    }
    return false;
  }

  public function authenticate($name, $password)
  {
    $db = Db::factory();

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
              ->where('name = ?', array($name))
              ->where('password = ?', array($password));
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
    catch (Exception $e) {
      Error::throwError('Nie powiodło się dodawanie użytkownika do bazy. ' . $e->getMessage());
    }
  }

  public final function getName()
  {
    return $this->_AUserInfo['name'];
  }
}