CRM.$('#civicrm-menu').ready(function() {
    CRM.$("input:radio[name='quickSearchField']").each(function(i) { this.checked = false; });
    CRM.$("input:radio[value='external_identifier']").each(function(i) { this.checked = true; });
    CRM.$('#sort_name_navigation').focus();
});
