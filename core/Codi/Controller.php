<?php

namespace Codi;

/**
 * Class Controller of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Rendus\Layout\LayoutAbstract;
use Codi\Conf;
use Codi\Controller\ControllerAbstract;
use Codi\DataBase as DDb;
use Codi\DataCase;
use Codi\Error;

class Controller extends ControllerAbstract {

  /**
   * Rendus object
   * @var Rendus_Layout
   */
  protected $ORendus = null;

  public final function run()
  {
    $this->ORendus = new $this->AConfig['layout_class'];

    if (method_exists($this, $this->action)) {
      return call_user_func_array([$this, $this->action], Request::getOptions());
    }
    else {
      Error::throwError('action_dosnt_exist', array($this->action));
    }
  }

  protected function index() {
    return;
  }

  protected final function setContent($content)
  {
    $class = "\\" . $content;
    $OContent = new $class();

    $this->ORendus->setContent($OContent);
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
            " . LayoutAbstract::LAYOUT_TABLE . " rll ON
              rll.id = cca.default_layout_id_rendus_layout
          JOIN
            " . LayoutAbstract::LAYOUT_TABLE . " rlc ON
              rlc.id = cca.default_content_id_rendus_layout
          WHERE
            cc.module = ?
            AND cc.name = ?
            AND cca.name = ?
          ";

    $db = DDb::factory();
    $this->AConfig = $db->getQueryRow($q, array(
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