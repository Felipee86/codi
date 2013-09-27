<?php namespace Rendus\Layout\Component;

/**
 * Class Footer of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Rendus/Layout/Component.php';

use Rendus\Layout\Component;

class Footer extends Component
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