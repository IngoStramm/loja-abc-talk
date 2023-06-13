<?php

// Product 
function labct_register_product_metabox()
{
    $cmb_demo = new_cmb2_box(array(
        'id'            => 'labct_demo_metabox',
        'title'         => esc_html__('Opções', 'cmb2'),
        'object_types'  => array('product'), // Post type
        // 'show_on_cb' => 'labct_show_if_front_page', // function should return a bool value
        'context'    => 'side',
    ));

    $cmb_demo->add_field(array(
        'name'       => esc_html__('Selecione o fabricante', 'labct'),
        'id'         => 'labct_fabricante',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => function () {
            $fabricantes = get_posts(array(
                'numberposts'   => -1,
                'post_type'     => 'fabricante',
                'orderby'       => 'title',
                'order'         => 'ASC'
            ));
            wp_reset_postdata();
            $fabricantes_options = [];
            foreach ($fabricantes as $fabricante) {
                $fabricante_email = get_post_meta($fabricante->ID, 'labct_email', true);
                $fabricantes_options[$fabricante->ID] = $fabricante->post_title .  ' (' . $fabricante_email . ')';
            }
            return $fabricantes_options;
        },
    ));
}

add_action('cmb2_admin_init', 'labct_register_product_metabox');

// Fabricante

function labct_register_fabricante_metabox()
{
    $cmb_demo = new_cmb2_box(array(
        'id'            => 'labct_fabricante_metabox',
        'title'         => esc_html__('Opções', 'cmb2'),
        'object_types'  => array('fabricante'), // Post type
    ));

    $cmb_demo->add_field(array(
        'name'       => esc_html__('E-mail', 'cmb2'),
        'desc'       => esc_html__('E-mail do Fabricante', 'cmb2'),
        'id'         => 'labct_email',
        'type'       => 'text_email',
        'attributes' => array(
            'required' => true
        )
    ));
}

add_action('cmb2_admin_init', 'labct_register_fabricante_metabox');
