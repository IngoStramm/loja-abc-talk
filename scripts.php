<?php

add_action('wp_enqueue_scripts', 'labct_frontend_scripts');

function labct_frontend_scripts()
{

    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.0.0.3'))) ? '' : '.min';

    if (empty($min)) :
        wp_enqueue_script('labct-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true);
    endif;

    wp_register_script('labct-script', LABCT_URL . 'assets/js/loja-abc-talk' . $min . '.js', array('jquery'), '1.0.0', true);

    wp_enqueue_script('labct-script');

    wp_localize_script('labct-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_style('labct-style', LABCT_URL . 'assets/css/loja-abc-talk.css', array(), '1.0.2', 'all');
}
