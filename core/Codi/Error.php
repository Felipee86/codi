<?php namespace Codi;

/**
 * Class Error of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Exception;
use Codi\Log;

class Error
{

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

  public static function throwError($msg, array $AParams = [])
  {
    if ($msg !== '') {
      self::addMsg($msg);
    }

    if (!empty(self::$AMsgs)) {
      header('Content-type: text/html');
      if ($_ENV['APPLICATION_ENV'] == 'development') {
        foreach(self::$AMsgs as $_msg) {
          throw new Exception(Langus::pr('errors.' . $_msg, 'core', $AParams));
        }
      }
      else {
        foreach (self::$AMsgs as $msg) {
          Log::addEntry($msg);
        }
        echo 'RENDUS FAILED';

        exit();
      }
    }
  }
}