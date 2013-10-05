<?php namespace Rendus\Layout\Component;

/**
 * Class LoginPanel of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\User;
use Rendus\Layout\Component;

class LoginPanel extends Component
{
  private $_action;

  public function onInit()
  {

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
      //TODO: Formularz
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
    $user = User::getInstance();
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