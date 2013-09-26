<?php

/**
 * Class Menu of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Rendus/Layout/Component.php';

class Rendus_Layout_Component_Menu extends Rendus_Layout_Component
{
  const ORIENT_HORIZONTAL = 0;
  const ORIENT_VERTICAL   = 1;
  const MENU_DEFAULT_NAME = 'menu';

  /**
   * Type of orientation of the menu.
   * @var int
   */
  private $orientation = self::ORIENT_HORIZONTAL;

  protected function onInit()
  {
  }

  protected function onRenderHtml($AData = array()) {
    $AHtml = [
        'div#1' => [
            'attr' => [
                'class' => 'left_corner'
            ]
        ],
        'div#2' => [
            'attr' => [
                'class' => 'right_corner'
            ]
        ],
        'div#3' => [
            'attr' => [
                'class' => 'content'
            ]
        ]
    ];

    return $AHtml;
  }

  /**
   * Returning all menu elements with all options.
   *
   * @param string $menu (optional) Name of the menu.
   * @return array
   */
  private function _getMenuElements($menu = 'adminMenu')
  {
    //TODO: zapytanie do wyciagania elementow menu
    return $AMenu;
  }
}