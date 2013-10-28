<?php namespace Codi;

/**
 * Class Session of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

class Session
{
  const SESSION_LVL_INFO = 0;
  const SESSION_LVL_SUCC = 1;
  const SESSION_LVL_WARN = 2;
  const SESSION_LVL_ERRO = 3;

  public static function getId()
  {
    return session_id();
  }

  public static function setId($id)
  {
    if (self::exists()) {
      Error::throwError('Sesja juz istnieje i nie mozna nadpisac jej ID');
    }
    session_id($id);
  }

  public static function exists()
  {
    if (self::getId()) {
      return true;
    }
    return false;
  }

  public static function setTimeExpiry($time)
  {

  }

  public static function start()
  {
    if (!self::exists()) {
      session_start();
    }
  }

  public static function addValue($key, $value)
  {
    if (self::exists()) {
      $_SESSION[$key] = $value;
      return true;
    }
    return false;
  }

  public static function getValue($key)
  {
    if (self::exists() && isset($_SESSION[$key])) {
      return $_SESSION[$key];
    }
    return false;
  }

  /**
   * Adding a flashed message to session varible.
   *
   * @param string  $msg      Message in text format or Codi\Langus key.
   * @param int     $level    (optional) Level of message priority.
   * @param string  $context  (optional) Context (GLOBAL or component key) for placing the message.
   */
  public static function flash($msg, $level = self::SESSION_LVL_INFO, $context = 'GLOBAL')
  {
    if (self::_checkSession() && $context) {
      $key =  $context  . '.' . $level;

      $AMsg = [
          'level'   => $level,
          'context' => $context,
          'msg'     => $msg
      ];

      $_SESSION['FLASH'][] = $AMsg;
    }
  }

  /**
   * Returns an array with grouped flash messages.
   *
   * @return Array
   */
  public static function getFlash()
  {
    if (self::exists() && isset($_SESSION['FLASH'])) {
      $_AMsgs = [];

      foreach ($_SESSION['FLASH'] as $AMsg) {
        if (!isset($_AMsgs[$AMsg['context']])) {
          $_AMsgs[$AMsg['context']] = [];
          $_AMsgs[$AMsg['context']][$AMsg['level']] = [];
          $_AMsgs[$AMsg['context']][$AMsg['level']][] = $AMsg['msg'];
        }
      }

      return $_AMsgs;
    }

    return null;
  }
}