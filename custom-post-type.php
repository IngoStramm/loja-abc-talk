<?php

function labct_fabricante_cpt()
{
    $fabricante = new LABCT_Post_Type(
        'Fabricante', // Nome (Singular) do Post Type.
        'fabricante' // Slug do Post Type.
    );

    $fabricante->set_labels(
        array(
            'menu_name' => __('Fabricantes', 'labct')
        )
    );

    $fabricante->set_arguments(
        array(
            'supports' => array('title')
        )
    );
}

add_action('init', 'labct_fabricante_cpt', 1);


function lacbt_tamanho_cpt()
{
    $fabricante = new LABCT_Post_Type(
        'Tamanho', // Nome (Singular) do Post Type.
        'tamanho' // Slug do Post Type.
    );

    $fabricante->set_labels(
        array(
            'menu_name' => __('Tamanhos', 'labct')
        )
    );

    $fabricante->set_arguments(
        array(
            'supports' => array('title')
        )
    );
}

add_action('init', 'lacbt_tamanho_cpt', 1);
