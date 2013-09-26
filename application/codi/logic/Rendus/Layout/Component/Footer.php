<?php

/**
 * Class Footer of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Rendus/Layout/Component.php';

class Rendus_Layout_Component_Footer extends Rendus_Layout_Component
{
  protected function onRenderHtml($AData = array()) {
    $AHtml = [
        'div#bottom_slat' => [
            'content' => [
                'p' => 'WebAdvice.pl - Powerd by Filip Koblański 2012'
            ]
        ]
    ];

    return $AHtml;
  }
}