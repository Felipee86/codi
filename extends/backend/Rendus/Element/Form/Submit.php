<?php

namespace Rendus\Element\Form;

/**
 * Class Input of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Rendus\ElementAbstract;

class Submit extends ElementAbstract
{
  protected function onRenderHtml($AData = [])
  {
    $AHtml = [
        'div' => [
            'attr' => [
                'class' => 'form_element'
            ],
            'content' => [
                'div' => [
                    'attr' => [
                        'class' => 'content'
                    ],
                    'content' => [
                        'input' => [
                            'attr' => $this->getInputAttr()
                        ]
                    ]
                ]
            ]
        ]
    ];

    return $AHtml;
  }

  protected function getInputAttr()
  {
    $AAttr = [
        'value' => $this->getValue(),
        'type'  => 'submit'
    ];


    return $AAttr;
  }
}