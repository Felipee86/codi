<?php namespace Codi;

/**
 * Class Form of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */

use Codi\DataBase as DDb;
use Codi\DataCase;
use Codi\Form\Param;

class Form
{
  private $_AFormConfig = [];

  /**
   * Represernts the Form DataCase object.
   * @var \Codi\DataCase
   */
  private $_ODataCase = null;

  private $_AParams = [];

  private function __construct(array $AFormConfig)
  {
    $this->_AFormConfig = $AFormConfig;

    $this->_AParams = $this->_loadFormParams($AFormConfig['id_codi_form']);
  }

  private function _loadFormParams($id_codi_form)
  {
    $db = DDb::factory();

    $q = "SELECT
            cfp.name,
            re.name as form_element_name,
            cfp.default_value,
            cfp.row,
            cfp.col
          FROM
            codi_form_param cfp
          JOIN
            rendus_element_form ref ON ref.id = cfp.id_rendus_element_form
          JOIN
            rendus_element re ON re.id = ref.id_rendus_element
          WHERE
            cfp.id_codi_form = ?";

    $AParams = $db->getQueryRow($q, array($id_codi_form));

    foreach ($AParams as $AParam) {
      $this->_AParams[$AParam['name']] = new Param($AParam);

    }
  }

  public final function setDataCase(DataCase $ODataCase)
  {
    $this->_ODataCase = $ODataCase;
  }

  private function _populateForm(array $AData = [])
  {
    if (empty($AData)) {
      $AData = $this->_ODataCase->getData();
    }

    foreach ($this->_AParams as $name => $param) {
      if (empty($AData[$name]) && !empty($param->getValue())) {
        $param->setValue($AData[$name]);
      }
    }
  }

  public final function createForm(array $AData = [])
  {
    if (!is_null($this->_ODataCase) && !empty($AData)) {
      $this->_populateForm($AData);
    }

  }

  private function _getElementMatrix()
  {
    $AMatrix = [];
    foreach($this->_AParams as $param) {
      // TODO:
    }
  }

  public final function render()
  {
    $AForm = [];

    $AForm = [
        'form' => [
            'attr' => [
                'method' => 'post',
                //TODO:
                'action' => '$this->getAction()'
            ],
            'content' => []
        ]
    ];
    foreach($this->_AParams as $name => $param) {
      $AForm['form']['content'][] = $param->render();
    }

    return $AForm;
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
            WHERE
              name = ?";

    if (is_numeric($identifier)) {
      $q .= "id = ?";
    }
    else {
      $q .= "name = ?";
    }

    $AForm = $db->getQueryRow($q, array($identifier));

    if (!empty($AForm['classname'])) {
      return new $AForm['classname']($AForm);
    }
    else {
      return new Form($AForm);
    }
  }
}