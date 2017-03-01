<?php

/**
 * A custom contact search
 */
class CRM_Testing_Form_Search_webmaster extends CRM_Contact_Form_Search_Custom_Base implements CRM_Contact_Form_Search_Interface {
  function __construct(&$formValues) {
    parent::__construct($formValues);
  }

  /**
   * Prepare a set of search fields
   *
   * @param CRM_Core_Form $form modifiable
   * @return void
   */
  function buildForm(&$form) {
    CRM_Utils_System::setTitle(ts('Webmaster on server'));

    $serverId = array('' => ts('- any server -'), 1 => 'britiac2', 2 => 'britiac3');
    $form->addElement('select', 'server_id', ts('Server'), $serverId);

    // Optionally define default search values
    $form->setDefaults(array(
      'server_id' => NULL,
    ));

    /**
     * if you are using the standard template, this array tells the template what elements
     * are part of the search criteria
     */
    $form->assign('elements', array('server_id'));
  }

  /**
   * Get a list of summary data points
   *
   * @return mixed; NULL or array with keys:
   *  - summary: string
   *  - total: numeric
   */
  function summary() {
    return NULL;
    // return array(
    //   'summary' => 'This is a summary',
    //   'total' => 50.0,
    // );
  }

  /**
   * Get a list of displayable columns
   *
   * @return array, keys are printable column headers and values are SQL column names
   */
  function &columns() {
    // return by reference
    $columns = array(
      ts('Contact Id') => 'contact_id',
      ts('Name') => 'sort_name',
      ts('Email') => 'email',
    );
    return $columns;
  }

  /**
   * Construct a full SQL query which returns one page worth of results
   *
   * @param int $offset
   * @param int $rowcount
   * @param null $sort
   * @param bool $includeContactIDs
   * @param bool $justIDs
   * @return string, sql
   */
  function all($offset = 0, $rowcount = 0, $sort = NULL, $includeContactIDs = FALSE, $justIDs = FALSE) {
    // delegate to $this->sql(), $this->select(), $this->from(), $this->where(), etc.
    return $this->sql($this->select(), $offset, $rowcount, $sort, $includeContactIDs, NULL);
  }

  /* Get email addresses of all webmasters who have a hosting package on server X or no server listed
SELECT c1.id,e1.email from `civicrm_contact` c1
LEFT JOIN `civicrm_email` e1 ON c1.id=e1.contact_id

where e1.is_primary=1 AND c1.id in (
SELECT w.webmaster_30
FROM  `civicrm_contact` AS c
LEFT JOIN  `civicrm_membership` m ON m.contact_id = c.id
LEFT JOIN `civicrm_value_bca_webservices_hosting_6` h on h.entity_id=m.id
LEFT JOIN  `civicrm_value_bca_webservices_domain_5` w ON w.entity_id=m.id
WHERE m.contact_id IS NOT NULL
AND (m.membership_type_id =3 OR m.membership_type_id=1)
AND (h.server_27 IS NULL OR h.server_27=1)
)
  */

  /**
   * Construct a SQL SELECT clause
   *
   * @return string, sql fragment with SELECT arguments
   */
  function select() {
    return "
      contact_a.id           as contact_id,
      contact_a.sort_name    as sort_name,
      email_a.email          as email
    ";
  }

  /**
   * Construct a SQL FROM clause
   *
   * @return string, sql fragment with FROM and JOIN clauses
   */
  function from() {
    return "
      FROM      civicrm_contact contact_a
      LEFT JOIN civicrm_email email_a ON contact_a.id=email_a.contact_id
    ";
  }

  /**
   * Construct a SQL WHERE clause
   *
   * @param bool $includeContactIDs
   * @return string, sql fragment with conditional expressions
   */
  function where($includeContactIDs = FALSE) {
/*    where e1.is_primary=1 AND c1.id in (
      SELECT w.webmaster_30
FROM  `civicrm_contact` AS c
LEFT JOIN  `civicrm_membership` m ON m.contact_id = c.id
LEFT JOIN `civicrm_value_bca_webservices_hosting_6` h on h.entity_id=m.id
LEFT JOIN  `civicrm_value_bca_webservices_domain_5` w ON w.entity_id=m.id
WHERE m.contact_id IS NOT NULL
    AND (m.membership_type_id =3 OR m.membership_type_id=1)
    AND (h.server_27 IS NULL OR h.server_27=1)
)*/

    $serverId = CRM_Utils_Array::value('server_id',
      $this->_formValues
    );
    if (!$serverId &&
      $this->_serverId
    ) {
      $serverId = $this->_serverId;
    }

    if ($serverId) {
      if ($serverId == 0) {
        $serverIdClause = '';
      } else {
        $serverIdClause = "AND (ws_host.server_27 IS NULL OR ws_host.server_27={$serverId})";
      }
    }

    $where = "email_a.is_primary = 1
              AND contact_a.id IN (";

    $select2 = "SELECT ws_domain.webmaster_30
                FROM  civicrm_contact AS contact_b
                LEFT JOIN  civicrm_membership member ON member.contact_id = contact_b.id
                LEFT JOIN civicrm_value_bca_webservices_hosting_6 ws_host on ws_host.entity_id=member.id
                LEFT JOIN  civicrm_value_bca_webservices_domain_5 ws_domain ON ws_domain.entity_id=member.id
                WHERE member.contact_id IS NOT NULL
                AND (member.membership_type_id =3 OR member.membership_type_id=1)
                $serverIdClause";

    $where = $where . $select2 .")";

    return $this->whereClause($where, $params);
  }

  /**
   * Determine the Smarty template for the search screen
   *
   * @return string, template path (findable through Smarty template path)
   */
  function templateFile() {
    return 'CRM/Contact/Form/Search/Custom.tpl';
  }

  /**
   * Modify the content of each row
   *
   * @param array $row modifiable SQL result row
   * @return void
   */
  function alterRow(&$row) {
    $row['sort_name'] .= ' ( altered )';
  }
}
