<?php

/**
 * Class Error of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

class Error {

  private static $AMsgs = [];

  public static function addMsg($msg)
  {
    if (is_array($msg)) {
      self::$AMsgs = array_merge(self::$AMsgs, $msg);
    }
    else {
      if (!empty($msg)) {
        self::$AMsgs[] = $msg;
      }
      else {
        self::$AMsgs = [];
      }
    }

  }

  public static function throwError($msg)
  {
    if ($msg !== '') {
      self::addMsg($msg);
    }

    if (!empty(self::$AMsgs)) {
      header('Content-type: text/html');
      if ($_ENV['APPLICATION_ENV'] == 'development') {
        require_once 'Zend/Exception.php';
        echo '<pre>';
        throw new Zend_Exception(implode('<br />', self::$AMsgs));
      }
      else {
        foreach (self::$AMsgs as $msg) {
          echo $msg . '<br />';
        }
        echo 'RENDUS FAILED';

        exit();
      }
    }

  }

}