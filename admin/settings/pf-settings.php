<?php

global $pf_settings;

// General Settings section
$pf_settings[] = array(
    'section_id' => 'setup',
    'section_title' => 'General Settings',
    'section_description' => '',
    'section_order' => 1,
    'fields' => array(
        array(
            'id' => 'pf_enable',
            'title' => 'Activate "Post Feedback"',
            'desc' => '',
            'type' => 'checkbox',
            'std' => 1
        ),
        array(
            'id' => 'pf_preview_length',
            'title' => 'General Preview Length',
            'desc' => 'Can be overidden by post settings',
            'type' => 'range',
            'std' => '100',
            'min' => '100',
            'max'  => '3000'
        ),
        array(
            'id' => 'pf_show_imgs',
            'title' => 'Show number of hidden images',
            'desc' => '',
            'type' => 'checkbox',
            'std' => 1
        ),
        array(
            'id' => 'pf_show_code',
            'title' => 'Show number of hidden code tags',
            'desc' => '',
            'type' => 'checkbox',
            'std' => 1
        ),
        array(
            'id' => 'pf_html_intro',
            'title' => 'HTML intro text',
            'type' => 'textarea',
        ),
    )
);


$pf_settings[] = array(
    'section_id' => 'pf-headwords',
    'section_title' => 'Headwords Settings',
    'section_description' => '',
    'section_order' => 3,
    'fields' => array(
        array(
            'id' => 'pf_headwords_html',
            'title' => 'HTML intro text',
            'type' => 'textarea',
        ),
    )
);

$pf_settings[] = array(
    'section_id' => 'pf-social',
    'section_title' => 'Social Settings',
    'section_description' => '',
    'section_order' => 2,
    'fields' => array(
        array(
            'id' => 'pf_social_services',
            'title' => 'Social Networks',
            'desc' => 'Click to enable a Social Network.',
            'type' => 'checkboxes',
            'std' => array('false'),
            'choices' => array(
                'facebook' => 'Facebook',
                'twitter' => 'Twitter',
                'gplus' => 'Google Plus'           
            )
        ),
        array(
            'id' => 'pf_twitter_u',
            'title' => 'Twitter Username (Via @)',
            'type' => 'text',
        ),
        array(
            'id' => 'pf_social_html',
            'title' => 'HTML before the like buttons',
            'type' => 'textarea',
        ),
        array(
            'id' => 'pf_fb_summary',
            'title' => 'Facebook Share Summary',
            'type' => 'textarea',
        ),
    )
);


$pf_settings[] = array(
    'section_id' => 'styling',
    'section_title' => 'Styling',
    'section_description' => '',
    'section_order' => 3,
    'fields' => array(  
        array(
            'id' => 'pf_accent1',
            'title' => 'First Accent Color',
            'desc' => '(optional)',
            'type' => 'color',
            'std' => '#34495E'
        ),
        array(
            'id' => 'pf_accent2',
            'title' => 'Second Accent Color',
            'desc' => '(optional)',
            'type' => 'color',
            'std' => '#1abc9c'
        )
    )
);



?>