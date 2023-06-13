<?php

function labct_getAmount($money)
{
    $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
    $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

    $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

    $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
    $removedThousandSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

    return (float) str_replace(',', '.', $removedThousandSeparator);
}


add_action('woocommerce_order_status_changed', 'lacbt_complete_for_status');

function lacbt_complete_for_status($order_id)
{
    $order = new WC_Order($order_id);
    $text_align = is_rtl() ? 'right' : 'left';
    $margin_side = is_rtl() ? 'left' : 'right';
    $line_subtotal = 0;

    if ($order->status !== 'processing') {
        return;
    }
    $order = wc_get_order($order_id);
    $items = $order->get_items();
    $fabricante_email_arr = [];
    $fabricante_products_arr = [];

    foreach ($items as $item) {
        $product_id = $item->get_product_id();
        $fabricante_id = get_post_meta($product_id, 'labct_fabricante', true);
        $fabricante_email = get_post_meta($fabricante_id, 'labct_email', true);
        if (!in_array($fabricante_email, $fabricante_email_arr)) {
            $fabricante_email_arr[] = $fabricante_email;
        }
        $fabricante_products_arr[] = array(
            'email'         => $fabricante_email,
            'product'       => $item
        );
    }

    $unique_fabricante_emails = array_unique($fabricante_email_arr);
    // $unique_fabricante_products = $fabricante_products_arr;

    foreach ($unique_fabricante_emails as $email) {
        $to = $email;
        $subject = sprintf(__('Novo pedido', 'labct') . ' #%s', $order_id);
        $body = '';
        $body .= '
            <!DOCTYPE html>
    <html ' . get_language_attributes() . '>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=' . get_bloginfo('charset') . '" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>' . get_bloginfo('name', 'display') . '</title>';

        $bg = get_option('woocommerce_email_background_color');
        $body_color = get_option('woocommerce_email_body_background_color');
        $base = get_option('woocommerce_email_base_color');
        $base_text = wc_light_or_dark($base, '#202020', '#ffffff');
        $text = get_option('woocommerce_email_text_color');

        // Pick a contrasting color for links.
        $link_color = wc_hex_is_light($base) ? $base : $base_text;

        if (wc_hex_is_light($body_color)) {
            $link_color = wc_hex_is_light($base) ? $base_text : $base;
        }

        $bg_darker_10 = wc_hex_darker($bg, 10);
        $body_darker_10 = wc_hex_darker($body_color, 10);
        $base_lighter_20 = wc_hex_lighter($base, 20);
        $base_lighter_40 = wc_hex_lighter($base, 40);
        $text_lighter_20 = wc_hex_lighter($text, 20);
        $text_lighter_40 = wc_hex_lighter($text, 40);

        $body .= '
        <style>
        body {
background-color: ' . esc_attr($bg) . '
padding: 0;
text-align: center;
}

#outer_wrapper {
background-color: ' . esc_attr($bg) . '
}

#wrapper {
margin: 0 auto;
padding: 70px 0;
-webkit-text-size-adjust: none !important;
width: 100%;
max-width: 600px;
}

#template_container {
box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1) !important;
background-color: ' . esc_attr($body) . '
border: 1px solid ' . esc_attr($bg_darker_10) . '
border-radius: 3px !important;
}

#template_header {
background-color: ' . esc_attr($base) . '
border-radius: 3px 3px 0 0 !important;
color: ' . esc_attr($base_text) . '
border-bottom: 0;
font-weight: bold;
line-height: 100%;
vertical-align: middle;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

#template_header h1,
#template_header h1 a {
color: ' . esc_attr($base_text) . '
background-color: inherit;
}

#template_header_image img {
margin-left: 0;
margin-right: 0;
}

#template_footer td {
padding: 0;
border-radius: 6px;
}

#template_footer #credit {
border: 0;
color: ' . esc_attr($text_lighter_40) . '
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 12px;
line-height: 150%;
text-align: center;
padding: 24px 0;
}

#template_footer #credit p {
margin: 0 0 16px;
}

#body_content {
background-color: ' . esc_attr($body) . '
}

#body_content table td {
padding: 48px 48px 32px;
}

#body_content table td td {
padding: 12px;
}

#body_content table td th {
padding: 12px;
}

#body_content td ul.wc-item-meta {
font-size: small;
margin: 1em 0 0;
padding: 0;
list-style: none;
}

#body_content td ul.wc-item-meta li {
margin: 0.5em 0 0;
padding: 0;
}

#body_content td ul.wc-item-meta li p {
margin: 0;
}

#body_content p {
margin: 0 0 16px;
}

#body_content_inner {
color: ' . esc_attr($text_lighter_20) . '
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 14px;
line-height: 150%;
text-align: ' . (is_rtl() ? 'right' : 'left') . '
}

.td {
color: ' . esc_attr($text_lighter_20) . '
border: 1px solid ' . esc_attr($body_darker_10) . '
vertical-align: middle;
}

.address {
padding: 12px;
color: ' . esc_attr($text_lighter_20) . '
border: 1px solid ' . esc_attr($body_darker_10) . '
}

.text {
color: ' . esc_attr($text) . '
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

.link {
color: ' . esc_attr($link_color) . '
}

#header_wrapper {
padding: 36px 48px;
display: block;
}

h1 {
color: ' . esc_attr($base) . '
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 30px;
font-weight: 300;
line-height: 150%;
margin: 0;
text-align: ' . (is_rtl() ? 'right' : 'left') . '
text-shadow: 0 1px 0 ' . esc_attr($base_lighter_20) . '
}

h2 {
color: ' . esc_attr($base) . '
display: block;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 18px;
font-weight: bold;
line-height: 130%;
margin: 0 0 18px;
text-align: ' . (is_rtl() ? 'right' : 'left') . '
}

h3 {
color: ' . esc_attr($base) . '
display: block;
font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
font-size: 16px;
font-weight: bold;
line-height: 130%;
margin: 16px 0 8px;
text-align: ' . (is_rtl() ? 'right' : 'left') . '
}

a {
color: ' . esc_attr($link_color) . '
font-weight: normal;
text-decoration: underline;
}

img {
border: none;
display: inline-block;
font-size: 14px;
font-weight: bold;
height: auto;
outline: none;
text-decoration: none;
text-transform: capitalize;
vertical-align: middle;
margin-' . (is_rtl() ? 'left' : 'right') . ': 10px;
max-width: 100%;
}

/**
* Media queries are not supported by all email clients, however they do work on modern mobile
* Gmail clients and can help us achieve better consistency there.
*/
@media screen and (max-width: 600px) {
#header_wrapper {
padding: 27px 36px !important;
font-size: 24px;
}

#body_content table > tbody > tr > td {
padding: 10px !important;
}

#body_content_inner {
font-size: 10px !important;
}
}

        </style>';
        $body .= '</head>

    <body ' . (is_rtl() ? 'rightmargin' : 'leftmargin') . '="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <table width="100%" id="outer_wrapper">
            <tr>
                <td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
                <td width="600">
                    <div id="wrapper" dir="' . (is_rtl() ? 'rtl' : 'ltr') . '">
                        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                                <td align="center" valign="top">
                                    <div id="template_header_image">';

        $img = get_option('woocommerce_email_header_image');

        if ($img) {
            $body .= '<p style="margin-top:0;"><img src="' . esc_url($img) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" /></p>';
        }

        $body .=
            '</div>
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_container">
                                        <tr>
                                            <td align="center" valign="top">
                                                <!-- Header -->
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header">
                                                    <tr>
                                                        <td id="header_wrapper">
                                                            <h1>' . __('Novo pedido!', 'labct') . '</h1>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- End Header -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top">
                                                <!-- Body -->
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_body">
                                                    <tr>
                                                        <td valign="top" id="body_content">
                                                            <!-- Content -->
                                                            <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                                <tr>
                                                                    <td valign="top">
                                                                        <div id="body_content_inner">
                                                                        <?php } ?>
        ';
        $body .= '
<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:' . esc_attr($text_align) . ';">' . __('Produto', 'labct') . '</th>
				<th class="td" scope="col" style="text-align:' . esc_attr($text_align) . ';">' . __('Quantidade', 'labct') . '</th>
				<th class="td" scope="col" style="text-align:' . esc_attr($text_align) . ';">' . __('Preço', 'labct') . '</th>
			</tr>
		</thead>
		<tbody>
			';
        $line_subtotal = 0;
        foreach ($fabricante_products_arr as $item) {
            $fabricante_email = $item['email'];
            if ($email == $fabricante_email) {

                $product = $item['product'];

                $product_id = $product->get_id();

                $body .= '
                <tr class="' . esc_attr(apply_filters('woocommerce_order_item_class', 'order_item', $product, $order)) . '">

                    <td class="td" style="text-align:' . esc_attr($text_align) . '; vertical-align: middle; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;">
                    ' . wp_kses_post(apply_filters('woocommerce_order_item_name', $product->get_name(), $product, false));

                // allow other plugins to add additional product information here.
                do_action('woocommerce_order_item_meta_start', $product_id, $product, $order, false);

                wc_display_item_meta(
                    $product,
                    array(
                        'label_before' => '<strong class="wc-item-meta-label" style="float: ' . esc_attr($text_align) . '; margin-' . esc_attr($margin_side) . ': .25em; clear: both">',
                    )
                );

                // allow other plugins to add additional product information here.
                do_action('woocommerce_order_item_meta_end', $product_id, $product, $order, false);

                $body .= '</td>

                    <td class="td" style="text-align:' . esc_attr($text_align) . '; vertical-align:middle; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;">
                        ' .
                    wp_kses_post($product->get_quantity(), $product)
                    . '
                    </td>

                    <td class="td" style="text-align:' . esc_attr($text_align) . '; vertical-align:middle; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;">
                        ' . wp_kses_post($order->get_formatted_line_subtotal($product)) . '
                    </td>

                </tr>
            ';
                $line_subtotal += $order->get_line_subtotal($product);
            }
        }
        $body .= '
		</tbody>
		<tfoot>';


        $item_totals = $order->get_order_item_totals();

        if ($item_totals) {
            $i = 0;
            foreach ($item_totals as $total) {
                $i++;
                $total_difference = labct_getAmount($total['value']) > $line_subtotal ? (labct_getAmount($total['value']) - $line_subtotal) : 0;
                $new_total = (labct_getAmount($total['value']) - $total_difference);
                $display_total = wp_kses_post($total['label']) === 'Subtotal:' || wp_kses_post($total['label']) === 'Total:' ? wc_price($new_total) : wp_kses_post($total['value']);

                $body .= '<tr>
						<th class="td" scope="row" colspan="2" style="text-align:' . esc_attr($text_align) . 'border-top-width: 4px;">' . wp_kses_post($total['label']) . '</th>
						<td class="td" style="text-align: ' . esc_attr($text_align) . 'border-top-width: 4px;">' . $display_total . '</td>
					</tr>';
            }
        }
        $body .= '</tfoot>
	</table>
</div>';

        $body .= '
        </div>
    </td>
    </tr>
    </table>
    <!-- End Content -->
    </td>
    </tr>
    </table>
    <!-- End Body -->
    </td>
    </tr>
    </table>
    </td>
    </tr>
    <tr>
        <td align="center" valign="top">
            <!-- Footer -->
            <table border="0" cellpadding="10" cellspacing="0" width="100%" id="template_footer">
                <tr>
                    <td valign="top">
                        <table border="0" cellpadding="10" cellspacing="0" width="100%">
                            <tr>
                                <td colspan="2" valign="middle" id="credit">'
            . wp_kses_post(
                wpautop(
                    wptexturize(
                        /**
                         * Provides control over the email footer text used for most order emails.
                         *
                         * @since 4.0.0
                         *
                         * @param string $email_footer_text
                         */
                        apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'))
                    )
                )
            );

        $body .= '</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!-- End Footer -->
        </td>
    </tr>
    </table>
    </div>
    </td>
    <td><!-- Deliberately empty to support consistent sizing and layout across multiple email clients. --></td>
    </tr>
    </table>
    </body>

    </html>
        ';

        // get WordPress site name
        $site_name = get_bloginfo('name');

        // get WordPress site domain without slashes and protocoll
        $site_domain = str_replace(
            array('http://', 'https://', '/', '\\'),
            '',
            get_site_url()
        );

        // Set email headers
        $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . $site_name . '<noreply@' . $site_domain . '>');

        wp_mail(
            $to,
            $subject,
            $body,
            $headers
        );
    }
}
