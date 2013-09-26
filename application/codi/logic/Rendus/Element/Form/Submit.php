<?php

/**
 * Class Input of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Rendus/Element/Abstract.php';

class Rendus_Element_Form_Submit extends Rendus_Element_Abstract
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