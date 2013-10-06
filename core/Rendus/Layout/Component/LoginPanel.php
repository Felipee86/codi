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
}