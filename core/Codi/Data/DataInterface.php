<?php namespace Codi\Data;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author fiko
 */
interface DataInterface {

  /**
   * Fetching all data.
   *
   * @return array
   */
  public function getData();

  /**
   * Returning current record.
   *
   * @return array
   */
  public function getRecord();

  /**
   * Adding record on the end of the data.
   *
   * @return array $ARecord
   */
  public function addRecord(array $ARecord);

  /**
   * Delete current record.
   */
  public function deleteRecord();

  /**
   * Moving pointer to next record.
   */
  public function nextRecord();

  /**
   * Moving pointer to previous record.
   */
  public function previousRecord();

}

?>
