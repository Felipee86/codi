<?php namespace Codi;

/**
 * Class Controller of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Rendus\Html;
use Codi\Controller\ControllerAbstract;
use Codi\DataBase as Db;
use Codi\DataCase;
use Codi\Error;
use Codi\Loader;
use Codi\User;

class Controller extends ControllerAbstract {

  /**
   * Name of the controller action
   * @var string
   */
  protected $action = 'index';

  /**
   * Name of the controller
   * @var string
   */
  protected $name = '';

  /**
   * Name of the controller module
   * @var string
   */
  protected $module = '';

  /**
   * Rendus object
   * @var Rendus_Layout
   */
  protected $ORendus = null;

  /**
   * Is controller in ajax mode
   * @var boolean
   */
  private $ajax = false;

  /**
   * Does controller gonna have view
   * @var boolean
   */
  private $isRendus = true;

  public function __construct($action = 'index')
  {
    $this->db = Db::factory();

    $this->_loadUser();

    if (strpos($action, 'ajax') === 0) {
      $this->ajax = true;
    }
    $this->action = $action;
    $this->module = Request::getModule();
    $this->name   = Request::getController();

    $this->_loadConfig();
    $this->_loadDataCase();
  }

  protected final function init()
  {
    $this->onInit();
  }

  protected function onInit() {}

  public final function run()
  {
    if ($this->isRendus && Loader::loadLayout($this->AConfig['layout_class'], Request::getModule())) {
      $this->ORendus = new $this->AConfig['layout_class'];
    }

    $method = $this->action . 'Action';
    if (method_exists($this, $method)) {
      $this->init();

      $this->$method();
    }
    else {
      Error::throwError('Akcja ' . $this->action . ' nie istnieje!' );
    }
  }

  protected function indexAction() {}

  public final function render()
  {
    echo "<!DOCTYPE html>";
    echo Html::parseHtmlArray($this->ORendus->render($this->ODataCase->getData()));
  }

  public final function isValidOptions(Array $AOptions)
  {
    $AOptions = $this->onValidOptions($AOptions);
    return $AOptions;
  }

  protected function onValidOptions(Array $AOptions)
  {
    return $AOptions;
  }

  protected final function setContent($MContent)
  {
    $OContent = $this->_getContent($MContent);

    $this->ORendus->setContent($OContent);
  }

  private function _getContent($MContent)
  {
    if ($MContent instanceof Content) {
      $OContent = $MContent;
    }
    else {
      $OContent = Loader::loadContent((string)$MContent);
      if (!$OContent) {
        Error::throwError('Niewlasciwa nazwa klasy');
      }
    }

    return $OContent;
  }

  public function setNoRendus()
  {
    $this->isRendus = false;
    $this->ORendus = null;
  }

  private function _loadUser()
  {
     $OUser = User::getInstance();
     if (!$OUser->hasIdentity()) {
       $OUser = null;
     }

    $this->OUser = $OUser;
  }

  private function _loadConfig()
  {
    $q = "SELECT
            rll.classname AS layout_class,
            rlc.classname AS content_class,
            cc.id_codi_datacase,
            cc.comment AS controller_comment,
            cca.comment AS action_comment
          FROM
            " . self::CONTROLLER_TABLE . " cc
          JOIN
            " . self::CONTROLLER_ACTION_TABLE . " cca ON
              cc.id = cca.id_codi_controller
          JOIN
            " . Rendus_Layout_Abstract::LAYOUT_TABLE . " rll ON
              rll.id = default_layout_id_rendus_layout
          JOIN
            " . Rendus_Layout_Abstract::LAYOUT_TABLE . " rlc ON
              rlc.id = default_content_id_rendus_layout
          WHERE
            cc.module = ?
            AND cc.name = ?
            AND cca.name = ?
          ";

    $this->AConfig = $this->db->fetchRow($q, array(
        $this->module,
        $this->name,
        $this->action
    ));

    if (empty($this->AConfig)) {
      Error::throwError('Nie zaladowano danych kontrolera');
    }
  }

  private function _loadDataCase()
  {
    $this->ODataCase = DataCase::factory($this->AConfig['id_codi_datacase']);
  }
}