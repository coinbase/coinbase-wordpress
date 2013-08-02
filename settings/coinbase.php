<?php

global $wpsf_settings;

// General Settings section
$wpsf_settings[] = array(
    'section_id' => 'general',
    'section_title' => 'OAuth Credentials',
    'section_description' => '',
    'section_order' => 5,
    'fields' => array(
        array(
            'id' => 'client_id',
            'title' => 'Client ID',
            'desc' => "You can find this on the <a href='https://coinbase.com/oauth/applications'>OAuth applications page</a>.",
            'type' => 'text',
            'std' => ''
        ),
        array(
            'id' => 'client_secret',
            'title' => 'Client Secret',
            'desc' => "You can find this on the <a href='https://coinbase.com/oauth/applications'>OAuth applications page</a>.",
            'type' => 'text',
            'std' => ''
        ),
    )
);

?>