<?php namespace Center;

/**
 * Class Default of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Rendus\Layout;

class DefaultLayout extends Layout {

  protected function onRenderHtml($AData = array())
  {
    $AHtml = [
        'html' => [
            'head' => [
                $this->getHeaders(),
                $this->getTitle(),
                $this->getAllCss(),
                $this->getAllJS()
            ],
            'body' => [
                'div' => [
                    'div#top' => [
                        'content' => [
                            'div#header_content' => [
                                'attr' => [
                                    'title' => 'WebAdvice - Filip Koblanski - Vortal wiedzy o projektach webowych'
                                ]
                            ],
                            $this->getSocket('login_panel')->render($AData)
                         ]
                    ],
                    'div#content' => [
                        'div#path_field' => null,
                        'div#page_label' => null,
                        'div#1.strike' => null,
                        //$this->getContent()
                    ],
                    'div#1' => [
                        'attr' => [
                            'style' => 'clear: both;'
                        ]
                    ]
               ]
            ]
        ]
    ];


    return $AHtml;
  }
}