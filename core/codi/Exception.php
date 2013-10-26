<?php namespace Codi;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Exception
 *
 * @author fiko
 */
class Exception extends \Exception {

  public function __toString() {
    $AResult = [];

    $cssStyle = "
        border: solid 1px red;
        padding: 20px;
        font-family: Courier;
        font-size: 12px;
        background: pink;
      ";

    echo '<pre>';
    echo "<div style=\"$cssStyle\">";
    echo 'Error message: <b>';
    echo $this->getMessage();
    echo  '</b>';
    echo '<br>';

    $i = 0;
    foreach ($this->getTrace() as $ATrack) {
      if ($i == 0) {
        echo $ATrack['file'];
        echo '<br /><br /><b>';
      }
      else {
        echo "[$i] " . $ATrack['file'] . "(Line: " . $ATrack['line'] . ") - " . $ATrack['class'] . $ATrack['type'] . $ATrack['function'];
        if (!empty($ATrack['args'])) {
          echo "<br />Args: ";
          print_r($ATrack['args']);
        }
        echo '<br />';
      }
      $i++;
    }
    echo '</div>';
    echo '</b></pre>';

    die;
  }
}

?>
