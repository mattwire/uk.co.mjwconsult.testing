<?php

require_once 'testing.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function testing_civicrm_config(&$config) {
  _testing_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function testing_civicrm_xmlMenu(&$files) {
  _testing_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function testing_civicrm_install() {
  _testing_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function testing_civicrm_postInstall() {
  _testing_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function testing_civicrm_uninstall() {
  _testing_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function testing_civicrm_enable() {
  _testing_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function testing_civicrm_disable() {
  _testing_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function testing_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _testing_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function testing_civicrm_managed(&$entities) {
  _testing_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function testing_civicrm_caseTypes(&$caseTypes) {
  _testing_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function testing_civicrm_angularModules(&$angularModules) {
  _testing_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function testing_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _testing_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function testing_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function testing_civicrm_navigationMenu(&$menu) {
  _testing_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'uk.co.mjwconsult.testing')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _testing_civix_navigationMenu($menu);
} // */

/**
 * Implements hook_coreResourceList
 *
 * @param array $list
 * @param string $region
 */
function testing_civicrm_coreResourceList(&$list, $region) {
  Civi::resources()->addScriptFile('uk.co.mjwconsult.testing', 'js/navigation_search_default.js');
}

/**
 * Implements hook_civicrm_emailProcessor
 * 
  * @param string $type type of mail processed: 'activity' OR 'mailing'
  * @param array &$params the params that were sent to the CiviCRM API function
  * @param object $mail the mail object which is an ezcMail class
  * @param array &$result the result returned by the api call
  * @param string $action (optional ) the requested action to be performed if the types was 'mailing'
  *
  * @return null
 */
function testing_civicrm_emailProcessor($type, &$params, $mail, &$result, $action = null)
{
  // Set inbound email activity to "New"
  $params['status_id'] = 9;

  foreach ($mail->to as $mAddress) {
    switch ($mAddress->email) {
      case 'webservices@british-caving.org.uk':
      case 'support@webservices.british-caving.org.uk':
      case 'test@webservices.british-caving.org.uk':
        // Set assigned to noone, with contact to sender
        $result = civicrm_api3('Activity', 'create', array(
          'sequential' => 1,
          'id' => $result['id'],
          'status_id' => 9,
          'assignee_id' => '',
          'target_id' => $params['assignee_contact_id'],
        ));
        break;
    }
  }
}
