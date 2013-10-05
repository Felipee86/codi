<?php namespace Codi;

/**
 * Class Lang of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Request;

class Langus {

  private static $_ATrans = [];

  private static function _getLang($module)
  {
    if (!isset(self::$_ATrans[$module])) {
      //TODO: Obsługa języków z poziomu strony.
      $lang = Conf::getOption('default.lang');
      $filename = TRANSLATION_PATH . DIRECTORY_SEPARATOR . $lang . DIRECTORY_SEPARATOR . $module . '.php';
      if (file_exists($filename)) {
        self::$_ATrans[$module] = include $filename;
      }
      else {
        Error::throwError('Nie istnieja tlumaczenia dla danego jezyka.');
      }
    }

    return self::$_ATrans[$module];
  }

  public static function pr($value, $modul = '', $context = '')
  {
    if (empty($modul)) {
      $modul = Request::getModule();
    }

    $ALang = self::_getLang($module);

    $ARecursiveValue = explode('.', $value);
    if (count($ARecursiveValue) > 1) {
      if (isset($ALang[$ARecursiveValue[0]][$ARecursiveValue[1]])) {
        return $ALang[$ARecursiveValue[0]][$ARecursiveValue[1]];
      }
      else {
        return "^" . $value . "$";
      }

    }
    if (isset($ALang[$value])) {
      return $ALang[$value];
    }
    else {
      return "^" . $value . "$";
    }
  }



}