<?php

function bm_social_customizer($wp_customize) {
    // Settings
    $wp_customize->add_setting('bm_facebook', array('default' =>''));
    $wp_customize->add_setting('bm_googleplus', array('default' =>''));
    $wp_customize->add_setting('bm_instagram', array('default' =>''));
    $wp_customize->add_setting('bm_twitter', array('default' =>''));
    $wp_customize->add_setting('bm_youtube', array('default' =>''));

    // Sections
    $wp_customize->add_section('bm_social_section', array(
        'title' => 'Redes Sociais',
        'priority' => '1'
    ));

    // Controllers
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'bm_facebook',
            array(
                'label' =>'Link do Facebook',
                'section' => 'bm_social_section',
                'settings' => 'bm_facebook',
                'type' =>'text'
            )
        )
    );
}