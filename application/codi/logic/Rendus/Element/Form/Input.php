<?php

/**
 * Class Input of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Rendus/Element/Abstract.php';

class Rendus_Element_Form_Input extends Rendus_Element_Abstract
{
  private $inputType = 'text';
  private $readonly = false;

  protected function onRenderHtml($AData = [])
  {
    $AHtml = [
        'div' => [
            'attr' => [
                'class' => 'form_element'
            ],
            'content' => [
                'div#1' => [
                    'attr' => [
                        'class' => 'label'
                    ],
                    'content' => $this->getLabel()
                ],
                'div#2' => [
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
        'type'  => (string)$this->inputType
    ];
    if ($this->readonly) {
      $AAttr['readonly'] = 'readonly';
    }

    return $AAttr;
  }

  public final function setInputType($type)
  {
    $this->inputType = (string)$type;
  }

  public function setEnable($enable = true)
  {
    $this->readonly = !$enable;
  }
}