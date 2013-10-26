<?php namespace Rendus\Layout;

/**
 * Class Abstract of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\DataBase as DDb;
use Codi\Error;
use Codi\Request;
use Rendus\RendusInterface;
use Rendus\Layout\Component;

abstract class LayoutAbstract implements RendusInterface
{

  const LAYOUT_TABLE = 'rendus_layout';

  protected $id = null;

  protected $name = '';

  protected $moduleOwner = '';

  /**
   * An array of the extra data that is going to be past to the view.
   * @var array
   */
  protected $AData = [];

  /**
   * An array with the list of javascript filepaths
   * @var array
   */
  protected $AJs = [];

  /**
   * An array with the list of css filenames
   * @var array
   */
  protected $ACss = [];

  /**
   * An array of sockets of components
   * @var array
   */
  protected $ASocket = [];

  /**
   * Configurates layout around bounds.
   * @var boolean
   */
  protected $bounds = true;

  /**
   * Does layout accepts the flash messages.
   * @var Boolean
   */
  protected $flashMsg = false;

  public function __construct($id)
  {
    $this->loadConfig($id);
    $this->loadSockets();
  }

  /**
   * Main method to render the view.
   *
   * @param array $AData data array that key is a name of a varible in the view
   */
  public final function render(array $AData = [])
  {
    $AData = $this->onRenderHtml($AData);
    if (!is_array($AData)) {
      Error::throwError('method_onRenderHtml_have_to_return_array');
    }

    return $AData;
  }

  private function loadConfig($id)
  {
    $db = DDb::factory();

    $q = "SELECT
            id,
            template_path,
            module,
            css_files,
            js_files
          FROM
            rendus_layout
          WHERE
            id = ?";
    $AConfig = $db->getQueryRow($q, array($id));

    $this->id = $AConfig['id'];
    $this->template_path = str_replace('.', DIRECTORY_SEPARATOR, $AConfig['template_path']);
    $this->module = $AConfig['module'];
    if (!empty($AConfig['css_files'])) {
      $ACss = explode(';', $AConfig['css_files']);
      foreach($ACss as $css_file) {
        $this->addCss($css_file);
      }
    }
    if (!empty($AConfig['js_files'])) {
      $AJs = explode(';', $AConfig['js_files']);
      foreach($AJs as $js_file) {
        $this->addJs($js_file);
      }
    }
  }

  private function loadSockets()
  {
    $db = DDb::factory();

    $q = "
          SELECT
            rs.name,
            source_rl.classname AS component_class
          FROM
            rendus_socket rs
          JOIN
            rendus_layout source_rl ON
            source_rl.id = rs.source_id_rendus_layout
          WHERE
            rs.id_rendus_layout = ?
          ";
    $ASockets = $db->getQueryAll($q, array($this->id));

    foreach ($ASockets as $ASocket) {
      $ASocket['component_class'] = "\\" . $ASocket['component_class'];
      $this->setComponent($ASocket['name'], new $ASocket['component_class']);
    }
  }

  /**
   * In here you can create an Html array that gonna be parse.
   *
   * @return array This method has to return an HTML preperd array
   */
  abstract protected function onRenderHtml($AData = []);

  /**
   * Setting layout data.
   *
   * @param array $AData data array that key is a name of a varible in the view
   */
  public function setData(Array $AData)
  {
    if (is_array($AData)) {
      $this->AData = array_merge($AData, $this->AData);
    }
  }

  /**
   * Setting the socket component of the view or it part.
   *
   * @param string $socket_key Socket name that component is being put to
   * @param Rendus_Layout_Component $OSocket Slready set component to add to the view
   */
  public final function setComponent($socket_key, Component $OSocket)
  {
    $key = $socket_key;
    if (strpos($socket_key, 'socket_') !== 0) {
      $key = 'socket_' . $socket_key;
    }

    $this->ASocket[$key] = $OSocket;
  }

  /**
   * Adds a js file to controll the view
   *
   * @param string $jsFilename Filepath to js file
   */
  public function addJS($jsFilename)
  {
    if (preg_match('/^[\.\/\-\_[0-9a-zA-Z]+$/', $jsFilename)) {
      $this->AJs[] = $jsFilename;
    }
    else {
      Error::addMsg('Niewlasciwa nazwa pliku JS.');
    }
  }

  /**
   * Adds an extra css file that view is requiring
   *
   * @param string $cssFilename filename of the css file
   */
  public function addCss($cssFilename)
  {
    if (preg_match('/^[\.\/\-\_[0-9a-zA-Z]+$/', $cssFilename)) {
      $this->ACss[] = $cssFilename;
    }
    else {
      Error::addMsg('Niewlasciwa nazwa pliku CSS.');
    }
  }

  /**
   * Getting all js files in html format
   *
   * @return string
   */
  public final function getJS(&$index = 1)
  {
    $AJs = [];
    foreach ($this->AJs as $jsfile) {
      $jsfilepath = 'js/' . $jsfile . '.js';
      if (file_exists($jsfile)) {
        $AJs += [
            'script#' . $index => [
                'attr' => [
                    'type' => 'text/javascript',
                    'src'  => $jsfilepath
                ]
            ]
        ];

        $index++;
      }
      else {
        Error::addMsg('Nie powiodlo sie zaladowac pliku JS: '. $jsfile);
      }
    }
    foreach ($this->ASocket as $OSocket) {
      $AJs += $OSocket->getJs($index);
    }

    return $AJs;
  }

  /**
   * Getting all extra css files in html format
   *
   * @return string
   */
  public final function getCss(&$index = 1)
  {

    $ACss = [];
    foreach ($this->ACss as $cssFilename) {
      $cssFilePath = Request::getModule() . '/css/' . $cssFilename;
      $ACss += [
          'link#' . $index => [
              'attr' => [
                  'rel'  => 'stylesheet',
                  'type' => 'text/css',
                  'href' => $cssFilePath
              ]
          ]
      ];
      $index++;
    }

    foreach ($this->ASocket as $OSocket) {
      $ACss += $OSocket->getCss($index);
    }

    return $ACss;
  }

  /**
   * Removing the specify component.
   *
   * @param string $socketSymbol Socket symbol that component is added on
   * @return boolean
   */
  public final function removeComponent($socketSymbol)
  {
    if (strpos('socket_', $socketSymbol) !== 0) {
      $socketSymbol = 'socket_' . strtolower($socketSymbol);
    }
    if (array_key_exists($socketSymbol, $this->ASocket)) {
      unset($this->ASocket[$socketSymbol]);
      return true;
    }
    return false;
  }

  /**
   * Reciving the specify component object from socket list if exists.
   *
   * @param string $socketSymbol Socket name key
   * @return mixed
   */
  public final function getSocket($socketSymbol)
  {
    if (strpos('socket_', $socketSymbol) !== 0) {
      $socketSymbol = 'socket_' . strtolower($socketSymbol);
    }

    if (array_key_exists($socketSymbol, $this->ASocket)) {
      return $this->ASocket[$socketSymbol];
    }

    Error::throwError(sprintf('Zadane gniazdo (%s) nie istnieje.', $socketSymbol));
  }

  /**
   * Reciving all components from socket list.
   *
   * @return array
   */
  public final function getAllSockets()
  {
    return $this->ASocket;
  }

}