<?php
use CRM_Funding_ExtensionUtil as E;

class CRM_Funding_BAO_FundingProgramContactType extends CRM_Funding_DAO_FundingProgramContactType {

  /**
   * Create a new FundingProgramContactType based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Funding_DAO_FundingProgramContactType|NULL
   *
  public static function create($params) {
    $className = 'CRM_Funding_DAO_FundingProgramContactType';
    $entityName = 'FundingProgramContactType';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
