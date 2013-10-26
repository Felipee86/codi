<?php namespace Rendus\Layout;

/**
 * Class Content of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\Request;
use Rendus\Layout\Content;
use Rendus\Layout\LayoutAbstract;

class Content extends LayoutAbstract
{
  private $_OContent = null;

  protected function onRenderHtml($AData = array())
  {
    return [];
  }

  public final function setContent(Content $AContent)
  {
    if (!empty($AContent)) {
      $this->_OContent = $AContent;
    }
  }

  protected final function getContent()
  {
    return $this->_OContent->render();
  }

  public final function setAppPath($appPath)
  {
    $this->_appPath = (string)$appPath;
  }

  protected final function getAppPath()
  {
    if ($this->_appPath !== '') {
      return $this->_appPath;
    }

    $appPath = strtoupper(Request::getModule())
               . '<b> > </b>' .  strtoupper(Request::getController())
               . '<b> > ' . strtoupper(Request::getAction()) . '</b>';
    return $appPath;
  }

  protected function loadConfig()
  {

  }
}