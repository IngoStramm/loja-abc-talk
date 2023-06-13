<?php

function son_fabricante_cpt()
{
    $fabricante = new LABCT_Post_Type(
        'Fabricante', // Nome (Singular) do Post Type.
        'fabricante' // Slug do Post Type.
    );

    $fabricante->set_labels(
        array(
            'menu_name' => __('Fabricantes', 'odin')
        )
    );

    $fabricante->set_arguments(
        array(
            'supports' => array('title')
        )
    );
}

add_action('init', 'son_fabricante_cpt', 1);
