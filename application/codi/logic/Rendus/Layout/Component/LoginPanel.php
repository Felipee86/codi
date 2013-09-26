<?php

/**
 * Class LoginPanel of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

require_once 'Rendus/Layout/Component.php';
require_once 'Rendus/Element/Form.php';

class Rendus_Layout_Component_LoginPanel extends Rendus_Layout_Component
{
  private $_loginInput;
  private $_passwordInput;
  private $_submitButton;
  private $_action;

  public function onInit()
  {
    $this->_loginInput    = Rendus_Elemnt_Form::factory('input', 'Login');
    $this->_passwordInput = Rendus_Elemnt_Form::factory('password', 'Hasło');
    $this->_submitButton  = Rendus_Elemnt_Form::factory('submit', 'Zaloguj');

    $this->setLabel('Panel logowania');
  }

  protected function onRenderHtml($AData = [])
  {
    $AContent = [];

    if ($AData['isLogged']) {
      if (!empty($AData['user_name'])) {
        $AContent = [
            'div' => [
                'content' => $AData['user_name']
            ]
        ];
      }
      else {
        Error::throwError('Problem z zalogowanym uzytkownikiem.');
      }
    }
    else {
      $AContent = [
          'form' => [
              'attr' => [
                  'method' => 'post',
                  //TODO:
                  'action' => '$this->getAction()'
              ],
              'content' => [
                  $this->_loginInput->render(),
                  $this->_passwordInput->render(),
                  $this->_submitButton->render()
              ]
          ]
      ];
    }

    $AHtml = [
        'div#socket_LoginPanel' => [
           'div#1' => [
              'attr' => [
                  'class' => 'component_label'
              ],
              'content' => 'Panel logowania'
          ],
          'div#2' => [
              'attr' => [
                  'class' => 'component_content'
              ],
              'contnet' => $AContent
          ]
        ]
    ];

    return $AHtml;
  }

  protected function onRender(array $AData) {
    $user = Codi_User::getInstance();
    $AData['is_logged_in'] = $user->hasIdentity();

    $AData['login_panel_label'] = 'Panel logowania';
    if ($AData['is_logged_in']) {
      $AData['login_panel_label'] = 'Użytkownik';
      $AData['user_name'] = $user->getName();
    }

    return $AData;
  }

  public final function setAction($module, $controller, $action)
  {
    $this->action = $module . '/' . $controller . '/' . $action;
  }

}