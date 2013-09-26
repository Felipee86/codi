<?php

/**
 * Class Content of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

class Rendus_Layout_Content extends Rendus_Layout_Abstract
{
  private $_OContent = null;

  protected function onRenderHtml($AData = array())
  {
    return [];
  }

  public final function setContent(Rendus_Layout_Content $AContent)
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

    $appPath = strtoupper(Codi_Request::getModule())
               . '<b> > </b>' .  strtoupper(Codi_Request::getController())
               . '<b> > ' . strtoupper(Codi_Request::getAction()) . '</b>';
    return $appPath;
  }

  protected function loadConfig()
  {

  }
}