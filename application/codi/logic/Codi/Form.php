<?php

/**
 * Class Form of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */
class Codi_Form
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
    $db = Dbi::factory();

    $q = "SELECT
            name,
            id_codi_form_element,
            default_value
          FROM
            codi_form_param
          WHERE
            id_codi_form = ?";

    $AParams = $db->fetchRow($q, array($id));

    foreach ($AParams as $AParam) {
      $this->_AParams[$AParam['name']] = new Codi_Form_Param($AParam);
    }

    if (!empty($AForm) && Codi_Class::loadClass($AForm['classname'])) {
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
    $db = Dbi::factory();

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

    $AForm = $db->fetchRow($q, array($identifier));

    if (!empty($AForm['classname']) && Codi_Class::loadClass($AForm['classname'])) {
      return new $AForm['classname']($AForm);
    }
    else {
      Error::throwError('Klasa formularza ' . $AForm['classname'] . ' (id: ' . $AForm['id'] . ') nie istnieje.');
    }
  }
}