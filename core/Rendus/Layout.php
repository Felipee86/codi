<?php namespace Rendus;
/**
 * Class Layout of
 * CoDI Framework
 * @author Filip Koblsnski
 */

require_once 'Rendus/Layout/Abstract.php';

use Codi\Loader;
use Codi\Error;
use Rendus\Layout\LayoutAbstract;

class Layout extends LayoutAbstract {

  /**
   * An array of list of headers.
   * @var type
   */
  private $AHeader = [];

  /**
   * Title of the rendering view.
   * @var string
   */
  private $title  = '';

  /**
   * The main content object of the view.
   * @var Rendus_Layout_Content
   */
  private $content = null;

  public function __construct()
  {
    parent::__construct();

    $this->_setContent();

    $this->AHeader = array(
        'content-type' => 'text/plain',
        'charset' => 'UTF-8',
    );

    $ADefaultCss = Conf::getSectionOptions('codi.layout.css');
    foreach ($ADefaultCss as $css) {
      $this->addCss($css);
    }

    $this->title = '<no title>';
    $title = Conf::getValue('codi.default.title');
    if ($title) {
      $this->title = $title;
    }
  }

  protected function onRenderHtml($AData = [])
  {
    return [];
  }

  private function _setContent()
  {
    $q = "
          SELECT
            rl.name
          FROM
            rendus_layout rl
          JOIN
            codi_controller_action cca ON
              rl.id = cca.default_content_id_rendus_layout
          WHERE
            cca.id = ?
          ";

    $content = $this->db->fetchOne($q, array(Codi_Controller::getActionId()));
    if ($content && Loader::loadContent($content)) {
      $this->setContent(new $content);
    }
    else {
      Error::throwError('Kontroler nie ma ustawionej zawartości lub plik z klasą nie istnieje.');
    }
  }

  /**
   * Adding a array of meta headers for view.
   *
   * @param array $AHeader An array of key that is name of meta element and the value that is it content.
   */
  public function setHeader(Array $AHeader)
  {
    $this->AHeader = array_merge($this->AHeader, $AHeader);
  }

  /**
   * Reciving the prepered html meta header tags.
   *
   * @return string
   */
  protected final function getHeaders()
  {
    $AHeaders = [];
    $index = 0;
    foreach ($this->AHeader as $key => $header) {
      $index++;
      if ('charset' == strtolower($key)) {
        $AHeaders += [
            'meta#' . $index => [
                'attr' => [
                    'http-equiv' => 'content-type',
                    'content'    => 'text/html',
                    'charset'    => $header
                ]
            ]
        ];
      }
      else {
        $AHeaders += [
            'meta#' . $index => [
                'attr' => [
                    'name' => $key,
                    'content'    => $header
                ]
            ]
        ];
      }
    }
    return $AHeaders;
  }

  /**
   * Setting the page title value.
   *
   * @param string $title Title value to be set.
   */
  public final function setTitle($title)
  {
    $this->title = (string)$title;
  }

  /**
   * Adding text to the begining of the title
   *
   * @param string $preTitle Text to be add to begining of the title.
   * @param string $separator (optional) Separator between texts.
   */
  public final function prependTitle($preTitle, $separator = ' ')
  {
    $this->title = $preTitle . $separator . $this->title;
  }

  /**
   * Adding text to the end of the title
   *
   * @param string $appTitle Text to be add to end of the title.
   * @param string $separator (optional) Separator between texts.
   */
  public final function appendTitle($appTitle, $separator = ' ')
  {
    $this->title = $this->title . $separator . $appTitle;
  }

  /**
   * Reciving prepared html title tag.
   *
   * @return string
   */
  protected final function getTitle()
  {
    $title = ((empty($this->title)) ? '<no title>' : (string) $this->title);
    $ATitle = ['title' => $title];
    return $ATitle;
  }

  /**
   * Setting the main content object of the view.
   *
   * @param string $content (optional) Name of the specify content view.
   */
  public final function setContent(Rendus_Layout_Content $OContent)
  {
    $this->content = $OContent;
  }

  /**
   * Reciving the main content view object.
   *
   * @return Rendus_Layout_Content
   */
  protected final function getContent()
  {
    return $this->content->render();
  }

  /**
   * Reciving all css files prepered html tags from all sockets, content and layout itself.
   *
   * @return string
   */
  protected final function getAllCss()
  {
    $ACss = [];
    $ACss = $this->getCss();
    $ACss += $this->content->getCss();

    return $ACss;
  }

  /**
   * Reciving all javascipt files prepered html tags from all sockets, content and layout itself.
   *
   * @return string
   */
  protected final function getAllJs()
  {
    $AJs = [];
    $AJs = $this->getJS();
    $AJs += $this->content->getJs();

    return $AJs;
  }
}