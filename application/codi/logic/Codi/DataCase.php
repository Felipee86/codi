<?php

/**
 * Class DataCase of
 * CoDI Framework
 *
 * @author Filip Koblsnski
 */
class Codi_DataCase
{
  protected $id = null;

  private $_ACase = [];

  private $_AValidInfo = [];

  public function __construct($id)
  {
    $this->id = $id;

    $this->_loadDataCase($id);
  }

  public final function getValue($key)
  {
    if (isset($this->_ACase[$key])) {
      return $this->_ACase[$key];
    }
    return false;
  }

  public final function getData()
  {
    $AValueArray = [];
    foreach($this->_ACase as $key => $AValue) {
      $AValueArray[$key] = $AValue['value'];
    }

    return $AValueArray;
  }

  public final function setValues(array $AValues) {
    $ret = true;
    foreach ($AValues as $key => $value) {
      if (array_key_exists($key, $this->_ACase)) {
        $ret = $this->setValue($key, $value) && $ret;
      }
    }

    return $ret;
  }

  public final function setValue($key, $value)
  {
    $ret = false;

    if (isset($this->_ACase[$key])) {
      if ($this->validateValue($key, $value)) {
        $this->_ACase[$key]['value'] = $value;
        $ret = true;
      }
    }

    return $ret;
  }

  public final function validateValue($key, $value)
  {
    $ret = false;

    if (isset($this->_ACase[$key])) {
      if (empty($value) && $this->_ACase[$key]['required']) {
        $this->_AValidInfo[] = sprintf('Wartość wymagana nie została przekazana (%s).', $key);
        return false;
      }
      else if (empty($value) && !$this->_ACase[$key]['required']) {
        $ret = true;
      }
      else {
        switch ($type) {
          case 'int'    : if (is_numeric($value)) $ret = true;
            break;
          case 'float'  : if (is_float($value)) $ret = true;
            break;
          case 'bool'   : if ($value === 1 || $value === true || $value == false) $ret = true;
          case 'string' : if (!empty($this->_ACase[$key]['lenght']) && $this->_ACase[$key]['lenght'] >= count_chars($value))
                            $ret = true;
          default : $ret = true;
        }
        if (!$ret) {
          $this->_AValidInfo[] = sprintf('Klucz %s jest niewłaściwego formatu.', $key);
        }
      }
    }
    else {
      $this->_AValidInfo[] = sprintf('Klucz %s nie istnieje', $key);
    }

    return $ret;
  }

  public final function getValidInfo()
  {
    if (!empty($this->_AValidInfo)) {
      return implode('<br>', $this->_AValidInfo);
    }
    return null;
  }

  private function _loadDataCase($id)
  {
    $db = Dbi::factory();

    $q = "SELECT
            id,
            name,
            required,
            type,
            default_value,
            lenght
          FROM
            codi_datacase_param
          WHERE
            id_codi_datacase = ?";
    $AParams = $db->fetchAll($q, array($id));

    foreach($AParams as $AParam) {
      $ATmp = [
          'id'       => $AParam['id'],
          'value'    => $AParam['default_value'],
          'type'     => $AParam['type'],
          'required' => (boolean) $AParam['required'],
          'lenght'   => $AParam['lenght'],
      ];
      $this->_ACase[$AParam['name']] = $ATmp;
    }
  }

  public static function factory($identifier)
  {
    $ret = true;
    $db = Dbi::factory();

    if (is_numeric($identifier)) {
      $q = "SELECT
              classname
            FROM
              codi_datacase
            WHERE
              id = ?";
      $id = $identifier;
      $className = $db->fetchOne($q, array($id));
    }
    else {
      $q = "SELECT
              id
            FROM
              codi_datacase
            WHERE
              classname = ?";
      $id  = $db->fetchOne($q, array($identifier));
      $className = $identifier;
    }

    if (is_null($className)) {
      return new Codi_DataCase($id);
    }
    else {
      if (Codi_Class::loadClass($$className)) {
        return new $className($id);
      }
      else {
        Error::throwError('Klasa ' . $className . ' nie istnieje.');
      }
    }
  }
}