<?php
include 'audit-log-function.php';

include 'audit-log-action.php';

function audit_log_manager() {

	$ci =& get_instance();

	$audit_log_temp = get_audit_log();

	$audit_log = [];

	$count_log = count($audit_log_temp);

	for ($i = $count_log -1; $i >= 0 ; $i--) {

		$key = date('d/m/Y', $audit_log_temp[$i]['time']);

		$audit_log[$key][] = $audit_log_temp[$i];
	}

	include_once 'audit-log-manager-view.php';

}