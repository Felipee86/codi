<?php namespace Codi;

/**
 * Class File of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */
class File {

  const TMP_FILEPATH = '_files/tmp';

  public function __construct()
  {
    ;
  }

  public static function factory()
  {

  }

/*
 * SEKCJA FUNKCJI STATYCZNYCH
 */

  /*
   * Returns file handle
   *
   * @param string      file path
   *
   * @return object
   */
  public static function open($filePath)
  {

  }

  /**
   * Cheacks if file exists
   *
   * @param string       file path
   *
   * @return boolean
   */
  public static function exists($filePath)
  {

  }

  /**
   * Retriving the absolute path of the tmp folder
   *
   * @return type
   */
  public static function tmpFile()
  {
    return APPLICATION_PATH . '/../' . slef::TMP_FILEPATH . DIRECTORY_SEPARATOR;
  }

  public static function getModulesDirs()
  {
    $AModules = [];
    $appPath  = APPLICATION_PATH . DIRECTORY_SEPARATOR;

    $handle = opendir(realpath(CONFIG_PATH));
    while (false !== ($moduleDir = readdir($handle))) {
      if (is_dir($moduleDir)) {
        $AModules[$moduleDir] = $appPath . $moduleDir;
      }
    }
    return $AModules;
  }

}