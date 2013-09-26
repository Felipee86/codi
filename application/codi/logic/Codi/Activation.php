<?php

/**
 * Class Activation of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */
class Codi_Activation
{
  public static function generate($length = 12)
  {
    if ($lenght > 64) {
      // maximum of string lenght
      $lenght = 64;
    }
    if ($lenght < 4) {
      // minimum of string lenght
      $lenght = 4;
    }

    $AHashTab = ['0', '1', '2', '3', '4', '5', '6' ,'7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];

    $strOut = '';

    while ($lenght > 0) {
      $index = rand(0, 15);

      $strOut .= $AHashTab[$index];

      $lenght--;
    }

    return $strOut;
  }
}