<?php

/**
 * Perform and initial setup and check for updates
 *
 * @return void
 */
function hj_framework_upgrade() {
	run_function_once('hj_framework_upgrade_187');
}

