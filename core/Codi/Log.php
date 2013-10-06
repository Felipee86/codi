<?php namespace Codi;

/**
 * Class Log of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Conf;

class Log {
  const LOG_LEVEL_DEBUG   = 0;
  const LOG_LEVEL_INFO    = 1;
  const LOG_LEVEL_NOTICE  = 2;
  const LOG_LEVEL_WARNING = 3;
  const LOG_LEVEL_ERROR   = 4;


  public static function addEntry($msg, $level)
  {
    $filePath = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR
                . '_files' . DIRECTORY_SEPARATOR
                . 'logs' .DIRECTORY_SEPARATOR;

    $fileName = date('Y-m-d') . '.log';

    if (!file_exists($filePath . $fileName)) {
      //TODO: TWORZENIE + PLIKU
    }

    //TODO: OTWIERANIE PLIKU DO ZAPISU

    if ($level == self::LOG_LEVEL_DEBUG && !Conf::getOption('logs.debug')) {
      return;
    }

    //TODO: Dodawanie wpisu.
  }

}
