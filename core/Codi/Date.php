<?php namespace Codi;

/**
 * Class Codi_Date of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */
class Date
{
  public static function getDate($format = 'Y-m-d h:i:s')
  {
    return date($format);
  }

  public static function getDateFromTimestamp($timestamp, $format = 'Y-m-d h:i:s')
  {
    return date($format, $timestamp);
  }
}