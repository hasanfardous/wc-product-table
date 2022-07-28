<?php

// Woo Product Table content shortcode
add_action( 'init', 'wptcd_content_shortcode_callback' );

function wptcd_get_wc_product_cats() {
	$args = array( 'taxonomy' => "product_cat" );
	$product_cats = get_terms($args);
    $html = '<select class="wptcd-cat-filter"><option value="">'.__('Category', 'wc-product-table' ).'</option>';
    foreach( $product_cats as $product_cat ) {
        $html .= '<option value="'.esc_attr($product_cat->slug).'">'.esc_html($product_cat->name).'</option>';
    }
    $html .= '</select>';

    return $html;
}

function wptcd_content_shortcode_callback() {
    add_shortcode( 'wc-product-table', 'wptcd_content_shortcode' );

    if ( ! WC()->cart ) {
        return;
    }

    add_filter( 'woocommerce_add_to_cart_fragments', function($fragments) {
        ob_start();
        ?>

        <span class="cart-btn"><?php echo is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : ''; ?></span>


        <?php $fragments['span.cart-btn'] = ob_get_clean();

        return $fragments;

    } );

    add_filter( 'woocommerce_add_to_cart_fragments', function($fragments) {

        ob_start();
        ?>

        <div class="mini-cart-con">
            <?php woocommerce_mini_cart(); ?>
        </div>

        <?php $fragments['div.mini-cart-con'] = ob_get_clean();

        return $fragments;

    } );
}
function wptcd_content_shortcode() {
    // Plugin default options
    $wptcd_settings_datas = get_option('wptcd_settings_datas');

    $tbl_design_type = isset($wptcd_settings_datas['tbl_design_type']) ? sanitize_text_field($wptcd_settings_datas['tbl_design_type']) : '';
    $tbl_border_color = isset($wptcd_settings_datas['tbl_border_color']) ? sanitize_text_field($wptcd_settings_datas['tbl_border_color']) : '';
    $tbl_header_bg_color = isset($wptcd_settings_datas['tbl_header_bg_color']) ? sanitize_text_field($wptcd_settings_datas['tbl_header_bg_color']) : '';
    $tbl_cell_bg_color = isset($wptcd_settings_datas['tbl_cell_bg_color']) ? sanitize_text_field($wptcd_settings_datas['tbl_cell_bg_color']) : '';
    $tbl_header_txt_color = isset($wptcd_settings_datas['tbl_header_txt_color']) ? sanitize_text_field($wptcd_settings_datas['tbl_header_txt_color']) : '';
    $tbl_cell_txt_color = isset($wptcd_settings_datas['tbl_cell_txt_color']) ? sanitize_text_field($wptcd_settings_datas['tbl_cell_txt_color']) : '';

    // Table content
    $show_review_column = isset($wptcd_settings_datas['show_review_column']) ? sanitize_text_field($wptcd_settings_datas['show_review_column']) : '';

    // Table controls
    $show_search_box = isset($wptcd_settings_datas['show_search_box']) ? sanitize_text_field($wptcd_settings_datas['show_search_box']) : '';
    $show_reset_btn = isset($wptcd_settings_datas['show_reset_btn']) ? sanitize_text_field($wptcd_settings_datas['show_reset_btn']) : '';
    $show_mini_cart = isset($wptcd_settings_datas['show_mini_cart']) ? sanitize_text_field($wptcd_settings_datas['show_mini_cart']) : '';

    global $woocommerce;

    $wc_activated = false;

    // Checking the Woocommerce plugin is active or not
    if ( class_exists( 'WooCommerce' ) ) {
        $wc_activated = true;
    }
	?>
	<div class="woocommerce-product-table">
        <?php
            if ( ! WC()->cart ) {
                return;
            }
            if ( $show_mini_cart == 'checked' ) {
                ?>
                <div class="mini-cart">
                    <div class="cart-btn-wrap">
                        <span class="cart-btn"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    </div>
                
                    <div class="mini-cart-con">
                        <?php
                        if ( $wc_activated ) {
                            woocommerce_mini_cart();
                        } else { 
                            _e('Sorry! Woocommerce not activated.', 'wc-product-table' );
                        } 
                        ?>
                    </div>
                </div>
                <?php
            }
        ?>

        <div class="wptcd-products-table-wrap">
            <div class="container">
                <div class="row">
                    <div class="wptcd-filtering-area">
                        <?php 
                            echo __('Filter', 'wc-product-table' ) . ': '.wptcd_get_wc_product_cats();
                            if ( $show_reset_btn == 'checked' ) {
                                echo '<span class="wptcd-reset-btn"> '.__('Reset', 'wc-product-table' ).'</span>';
                            }
                        ?>
                    </div>
                    <style>
                        #wptcd-product-table thead tr th {
                            color: <?php echo ($tbl_header_txt_color!='')?$tbl_header_txt_color:''?>;
                        }
                        <?php
                            if ( $show_search_box == 'checked' ) {
                                ?>
                                #wptcd-product-table_filter {
                                    opacity: 1;
                                }
                                <?php
                            }
                        ?>
                    </style>
                    <table id="wptcd-product-table" class="display" style="width:100%; min-height: 100px;<?php echo ($tbl_border_color!='')?'border: 1px solid '.$tbl_border_color:''?>">
                        <thead style="<?php echo ($tbl_header_bg_color!='')?'background: '.$tbl_header_bg_color:''?>">
                            <tr>
                                <th class="d-none"><?php _e('Category slug', 'wc-product-table' )?></th>
                                <th><?php _e('Image', 'wc-product-table' )?></th>
                                <th><?php _e('Name', 'wc-product-table' )?></th>
                                <th><?php _e('Summary', 'wc-product-table' )?></th>
                                <?php echo ($show_review_column=='checked'?'<th>'.__('Reviews', 'wc-product-table' ).'</th>':'')?>
                                <th><?php _e('Price', 'wc-product-table' )?></th>
                                <th><?php _e('Action', 'wc-product-table' )?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
	<?php
	require plugin_dir_path( __FILE__ ) . 'all-products.php';
	$form_html = ob_get_clean();
	return $form_html;
}