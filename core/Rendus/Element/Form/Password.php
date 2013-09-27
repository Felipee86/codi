<?php namespace Rendus\Element\Form;

/**
 * Class Input of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Rendus/Element/ElementAbstract.php';

use Rendus\Element\ElementAbstract;

class Password extends ElementAbstract
{
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
        'type'  => 'password'
    ];
    if ($this->readonly) {
      $AAttr['readonly'] = 'readonly';
    }

    return $AAttr;
  }

  public function setEnable($enable = true)
  {
    $this->readonly = !$enable;
  }
}