<?php

/**
 * Class Msg of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */
class Codi_Msg
{
  const MSG_LVL_DEBUG = 0;
  const MSG_LVL_INFO  = 1;
  const MSG_LVL_WARN  = 2;
  const MSG_LVL_ERROR = 3;

  const MSG_CON_DEFAULT = 'main';

  public static $AMsg = [];

  public static function addMsg($msg, $level = self::MSG_LVL_INFO, $context = self::MSG_CON_DEFAULT)
  {
    if (!array_key_exists($context, self::$AMsg)) {
      self::$AMsg[$context] = [];
    }
    self::$AMsg[$context][] = $msg;
  }

}