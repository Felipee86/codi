<?php namespace Codi;

/**
 * Class Form of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\DataBase as DDb;
use Codi\Error;
use Codi\Loader;

class Form
{
  private $_AFormConfig = [];

  private $_ODataCase = null;

  private $_AParams = null;

  private function __construct(array $AFormConfig)
  {
    $this->_AFormConfig = $AFormConfig;


  }

  private function _loadFormParams($id)
  {
    $db = DDb::factory();

    $q = "SELECT
            name,
            id_rendus_element_form,
            default_value
          FROM
            codi_form_param
          WHERE
            id_codi_form = ?";

    $AParams = $db->getQueryRow($q, array($id));

    foreach ($AParams as $AParam) {
      $this->_AParams[$AParam['name']] = new Codi_Form_Param($AParam);
    }

    if (!empty($AForm) && Loader::loadClass($AForm['classname'])) {
      return new $AForm['classname']();
    }
    else {
      Error::throwError("Nie udalo zaladowac sie formularza (id: $id).");
    }
  }

  private function _populateForm()
  {

  }

  public final function createForm(array $AData = [])
  {

  }

  public static function factory($identifier)
  {
    $db = DDb::factory();

    $q = "SELECT
              id,
              classname,
              id_codi_datacase
            FROM
              codi_form
            WHERE ";

    if (is_numeric($identifier)) {
      $q .= "id = ?";
    }
    else {
      $q .= "classname = ?";
    }

    $AForm = $db->getQueryRow($q, array($identifier));

    if (!empty($AForm['classname']) && Loader::loadClass($AForm['classname'])) {
      return new $AForm['classname']($AForm);
    }
    else {
      Error::throwError('Klasa formularza ' . $AForm['classname'] . ' (id: ' . $AForm['id'] . ') nie istnieje.');
    }
  }
}