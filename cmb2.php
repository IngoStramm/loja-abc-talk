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

    $cmb_demo->add_field(array(
        'name'       => esc_html__('Selecione os tamanhos', 'labct'),
        'id'         => 'labct_tamanhos',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => function () {
            $tamanhos = get_posts(array(
                'numberposts'   => -1,
                'post_type'     => 'tamanho',
                'orderby'       => 'title',
                'order'         => 'ASC'
            ));
            wp_reset_postdata();
            $tamanhos_options = [];
            foreach ($tamanhos as $tamanho) {
                $tamanhos_options[$tamanho->ID] = $tamanho->post_title;
            }
            return $tamanhos_options;
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

function labct_register_meta_tamanho()
{

    /**
     * Repeatable Field Groups
     */
    $cmb = new_cmb2_box(array(
        'id'           => 'labct_coluna_metabox',
        'title'        => esc_html__('Colunas da Tabela de tamanhos', 'cmb2'),
        'object_types' => array('tamanho'),
    ));

    $cmb->add_field(array(
        'name'       => esc_html__('Colunas', 'cmb2'),
        // 'desc'       => esc_html__('E-mail do Fabricante', 'cmb2'),
        'id'         => 'labct_colunas',
        // 'description' => esc_html__('Primeiro defina as colunas e salve o tamnho. Depois que as colunas estiverem criadas e salvas, será exibida a opção de adicionar as linhas.', 'labct'),
        'type'       => 'text',
        'repeatable' => true
    ));

    $cmb_group = new_cmb2_box(array(
        'id'           => 'labct_group_metabox',
        'title'        => esc_html__('Linhas da tabela de Tamanhos', 'labct'),
        'object_types' => array('tamanho'),
    ));
    
    // $group_field_id is the field id string, so in this case: 'labct_group_demo'
    $group_field_id = $cmb_group->add_field(array(
        'id'          => 'labct_group_tamanho',
        'description' => esc_html__('Importante: a quantidade de células das linhas deve ser a mesma que a quantidade de colunas. Por ex: se houverem 3 colunas, consequentemente devem haver 3 células por linha.', 'labct'),
        'type'        => 'group',
        'options'     => array(
            'group_title'    => esc_html__('Linha {#}', 'labct'), // {#} gets replaced by row number
            'add_button'     => esc_html__('Adicionar nova linha', 'labct'),
            'remove_button'  => esc_html__('Remover linha', 'labct'),
            'sortable'       => true,
            // 'closed'      => true, // true to have the groups closed by default
            // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
        ),
    ));

    $cmb_group->add_group_field($group_field_id, array(
        'name'       => 'Célula',
        'id'         => 'celula',
        'type'       => 'text',
        'repeatable' => true
    ));
}

add_action('cmb2_admin_init', 'labct_register_meta_tamanho');

// add_action(
//     'wp_head',
//     function () {
//         $post_id = get_the_ID();
//         $metas = get_post_meta($post_id, '', true);
//         $colunas = get_post_meta($post_id, 'labct_colunas', true);
//         labct_debug($metas);
//         foreach ($colunas as $k => $coluna) {
//         }
//     }
// );
