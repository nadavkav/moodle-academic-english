<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
    'local/ouil:ouil_lib' => array(
        'riskbitmask' => RISK_CONFIG,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW
        ),
    ),
    'local/ouil:config' => array(
        'riskbitmask' => RISK_CONFIG,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW
        ),
    ),
		
);

