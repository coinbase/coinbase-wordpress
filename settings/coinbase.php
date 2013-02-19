<?php

global $wpsf_settings;

// General Settings section
$wpsf_settings[] = array(
    'section_id' => 'general',
    'section_title' => '',
    'section_description' => '',
    'section_order' => 5,
    'fields' => array(
        array(
            'id' => 'api_key',
            'title' => 'Coinbase API key',
            'desc' => "You can find this on the <a href='https://coinbase.com/account/integrations'>integrations page</a>.  Note: this allows access to your account, do not share.",
            'type' => 'password',
            'std' => ''
        )
    )
);

?>