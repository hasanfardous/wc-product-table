<?php

add_action('wp_ajax_wptcd_ajax_datas', 'wptcd_add_to_cart_datas_callabck');
add_action('wp_ajax_nopriv_wptcd_ajax_datas', 'wptcd_add_to_cart_datas_callabck');

// Request form data handling
if ( ! function_exists( 'wptcd_add_to_cart_datas_callabck' ) ) {
	function wptcd_add_to_cart_datas_callabck() {
		$product_id = apply_filters('wptcd_add_to_cart_product_id', absint($_POST['product_id']));
		$quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount(absint($_POST['quantity']));
		$variation_id = absint($_POST['variation_id']);
		$passed_validation = apply_filters('wptcd_add_to_cart_validation', true, $product_id, $quantity);
		$product_status = get_post_status($product_id); 
		if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) { 
			do_action('wptcd_ajax_added_to_cart', $product_id);
			$data = array( 
			'error' => false,
			'message' => __('Product added to cart successfully', 'wc-product-table' )
			);
			WC_AJAX :: get_refreshed_fragments(); 

			echo wp_send_json($data);
		}
		wp_die();

	}
}

// Saving admin datas
add_action('wp_ajax_wptcd_admin_datas', 'wptcd_save_admin_datas_callabck');
// Request form data handling
if ( ! function_exists( 'wptcd_save_admin_datas_callabck' ) ) {
	function wptcd_save_admin_datas_callabck() {
		$response = [];
		// Table design
		$tbl_design_type = isset($_POST['tbl_design_type']) ? sanitize_text_field($_POST['tbl_design_type']) : '';
		$tbl_border_color = isset($_POST['tbl_border_color']) ? sanitize_text_field($_POST['tbl_border_color']) : '';
		$tbl_header_bg_color = isset($_POST['tbl_header_bg_color']) ? sanitize_text_field($_POST['tbl_header_bg_color']) : '';
		$tbl_cell_bg_color = isset($_POST['tbl_cell_bg_color']) ? sanitize_text_field($_POST['tbl_cell_bg_color']) : '';
		$tbl_header_txt_color = isset($_POST['tbl_header_txt_color']) ? sanitize_text_field($_POST['tbl_header_txt_color']) : '';
		$tbl_cell_txt_color = isset($_POST['tbl_cell_txt_color']) ? sanitize_text_field($_POST['tbl_cell_txt_color']) : '';

		// Table content
		$show_quick_view = isset($_POST['show_quick_view']) ? sanitize_text_field($_POST['show_quick_view']) : '';
		$show_review_column = isset($_POST['show_review_column']) ? sanitize_text_field($_POST['show_review_column']) : '';

		// Table controls
		$rows_per_page = isset($_POST['rows_per_page']) ? sanitize_text_field($_POST['rows_per_page']) : '';
		$add_to_cart_btn_title = isset($_POST['add_to_cart_btn_title']) ? sanitize_text_field($_POST['add_to_cart_btn_title']) : '';
		$add_to_cart_btn_color = isset($_POST['add_to_cart_btn_color']) ? sanitize_text_field($_POST['add_to_cart_btn_color']) : '';
		$show_search_box = isset($_POST['show_search_box']) ? sanitize_text_field($_POST['show_search_box']) : '';
		$show_reset_btn = isset($_POST['show_reset_btn']) ? sanitize_text_field($_POST['show_reset_btn']) : '';
		$show_mini_cart = isset($_POST['show_mini_cart']) ? sanitize_text_field($_POST['show_mini_cart']) : '';

		// Settings array
		$wptcd_settings_array = [];
		if ($tbl_design_type == 'default') {
			$wptcd_settings_array = [
				'tbl_design_type' => $tbl_design_type,
			];
		} else {
			$wptcd_settings_array = [
				'tbl_design_type' => $tbl_design_type,
				'tbl_border_color' => $tbl_border_color,
				'tbl_header_bg_color' => $tbl_header_bg_color,
				'tbl_cell_bg_color' => $tbl_cell_bg_color,
				'tbl_header_txt_color' => $tbl_header_txt_color,
				'tbl_cell_txt_color' => $tbl_cell_txt_color,
			];
		}
		// Table content
		$wptcd_settings_array['show_quick_view'] = $show_quick_view;
		$wptcd_settings_array['show_review_column'] = $show_review_column;
		// Table controls
		$wptcd_settings_array['rows_per_page'] = $rows_per_page;
		$wptcd_settings_array['add_to_cart_btn_title'] = $add_to_cart_btn_title;
		$wptcd_settings_array['add_to_cart_btn_color'] = $add_to_cart_btn_color;
		$wptcd_settings_array['show_search_box'] = $show_search_box;
		$wptcd_settings_array['show_reset_btn'] = $show_reset_btn;
		$wptcd_settings_array['show_mini_cart'] = $show_mini_cart;

		// Saving the settings
		if (update_option( 'wptcd_settings_datas', $wptcd_settings_array )) {
			$response['status'] = 'success';
			$response['message'] = __('Settings has been updated!', 'wc-product-table' );
		} else {
			$response['status'] = 'warning';
			$response['message'] = __('Nothing to save!', 'wc-product-table');
		}
		echo wp_send_json( $response );
		wp_die();
	}
}
