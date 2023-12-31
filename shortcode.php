<?php

function labct_tabela_tamanhos_shortcode($atts)
{
    $post_id = get_the_ID();
    $tamanho_id = get_post_meta($post_id, 'labct_tamanhos', true);
    if (!$tamanho_id) {
        return;
    }
    $colunas = get_post_meta($tamanho_id, 'labct_colunas', true);
    $linhas = get_post_meta($tamanho_id, 'labct_group_tamanho', true);
    $output = '';
    $output .= '<h4 class="tabela-tamanhos-title toggle-medidas" id="toggle-medidas">' . __('Medidas', 'labct') . '<i class="labct-arrow"></i></h4>';
    $output .= '<div class="tabela-tamanhos-wrapper">';
    $output .= '<table class="tabela-tamanhos">';
    $output .= '<thead>';
    $output .= '<tr>';
    foreach ($colunas as $coluna) {
        $output .= '<th>' . $coluna . '</th>';
    }
    $output .= '</tr>';
    $output .= '</thead>';
    $output .= '<tbody>';
    foreach ($linhas as $linha) {
        foreach ($linha as $celulas) {
            $output .= '<tr>';
            foreach ($celulas as $celula) {
                // labct_debug($celula);
                $output .= '<td>' . $celula . '</td>';
            }
            $output .= '</tr>';
        }
    }
    $output .= '</tbody>';
    $output .= '</table>';
    $output .= '</div>';
    return $output;
}

add_shortcode('tabela-tamanhos', 'labct_tabela_tamanhos_shortcode');
