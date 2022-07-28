<?php

// Admin menu page
add_action( 'admin_menu', 'wptcd_adding_admin_menu_page' );
if ( ! function_exists( 'wptcd_adding_admin_menu_page' ) ) {
	function wptcd_adding_admin_menu_page() {
		add_menu_page(
			__( 'WC Product Table Settings', 'wc-product-table' ),
			__( 'WC Product Table Settings', 'wc-product-table' ),
			'manage_options',
			'product-table-settings',
			'wptcd_product_table_settings_callback',
			'dashicons-editor-table',
			6
		);
	}
}

// Admin notice function
if ( ! function_exists( 'wptcd_show_admin_notice' ) ) {
	function wptcd_show_admin_notice( $message, $type )  {
		echo "
			<div class='wptcd_show_admin_notice notice notice-{$type} is-dismissible'>
				<p>{$message}</p>
			</div>
		";
	}
}

// Settings page callback function
if ( ! function_exists( 'wptcd_product_table_settings_callback' ) ) {
	function wptcd_product_table_settings_callback() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Plugin settings array
		$wptcd_settings_datas = get_option('wptcd_settings_datas');
		$item_per_page = isset($wptcd_settings_datas['item_per_page']) ? intval($wptcd_settings_datas['item_per_page']) : 10;
		$button_title = isset($wptcd_settings_datas['button_title']) ? sanitize_text_field($wptcd_settings_datas['button_title']) : __( 'Add to cart', 'wc-product-table' );

		// Table design
		$tbl_design_type = isset($wptcd_settings_datas['tbl_design_type']) ? sanitize_text_field($wptcd_settings_datas['tbl_design_type']) : '';
		$tbl_border_color = isset($wptcd_settings_datas['tbl_border_color']) ? sanitize_text_field($wptcd_settings_datas['tbl_border_color']) : '';
		$tbl_header_bg_color = isset($wptcd_settings_datas['tbl_header_bg_color']) ? sanitize_text_field($wptcd_settings_datas['tbl_header_bg_color']) : '';
		$tbl_cell_bg_color = isset($wptcd_settings_datas['tbl_cell_bg_color']) ? $wptcd_settings_datas['tbl_cell_bg_color'] : '';
		$tbl_header_txt_color = isset($wptcd_settings_datas['tbl_header_txt_color']) ? $wptcd_settings_datas['tbl_header_txt_color'] : '';
		$tbl_cell_txt_color = isset($wptcd_settings_datas['tbl_cell_txt_color']) ? $wptcd_settings_datas['tbl_cell_txt_color'] : '';

		// Table content
		$show_quick_view = isset($wptcd_settings_datas['show_quick_view']) ? $wptcd_settings_datas['show_quick_view'] : '';
		$show_review_column = isset($wptcd_settings_datas['show_review_column']) ? $wptcd_settings_datas['show_review_column'] : '';

		// Table controls
		$rows_per_page = isset($wptcd_settings_datas['rows_per_page']) ? $wptcd_settings_datas['rows_per_page'] : '';
		$add_to_cart_btn_title = isset($wptcd_settings_datas['add_to_cart_btn_title']) ? $wptcd_settings_datas['add_to_cart_btn_title'] : '';
		$add_to_cart_btn_color = isset($wptcd_settings_datas['add_to_cart_btn_color']) ? $wptcd_settings_datas['add_to_cart_btn_color'] : '';
		$show_search_box = isset($wptcd_settings_datas['show_search_box']) ? $wptcd_settings_datas['show_search_box'] : '';
		$show_reset_btn = isset($wptcd_settings_datas['show_reset_btn']) ? $wptcd_settings_datas['show_reset_btn'] : '';
		$show_mini_cart = isset($wptcd_settings_datas['show_mini_cart']) ? $wptcd_settings_datas['show_mini_cart'] : '';
		?>
		<div class="wrap wptcd-admin-wrapper">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<div class="wptcd-add-product-feature-requests-wrapper">
				<div class="wptcd-section-content">
					<form method="post" class="wptcd-feature-request-form">
						<div class="tab-content">
							<div id="tabs">
								<ul>
									<li><a href="#tabs-1"><?php _e('Table Design', 'wc-product-table')?></a></li>
									<li><a href="#tabs-2"><?php _e('Table Content', 'wc-product-table')?></a></li>
									<li><a href="#tabs-4"><?php _e('Table Controls', 'wc-product-table')?></a></li>
								</ul>
								<div id="tabs-1">
									<h2 class="title"><?php _e('Customize your Produt Table', 'wc-product-table')?></h2>
									
									<p><?php _e('Use the shortcode <kbd>[wc-product-table-woobuddies]</kbd> for displaying the Amazing Product Table with all of your products together. User can order products here easily without visiting individual product.', 'wc-product-table')?></p>
									<table class="form-table table-design-form">
										<?php 
										if ($tbl_design_type == 'default') {
											?>
											<script>
												(function ($) {
													$(document).ready(function () {
														$('.table-design-form tr:not(:first-child)').hide();
													});
												})(jQuery);
											</script>
											<?php
										}
										?>
										<tbody>
											<tr>
												<th scope="row">
													<?php _e('Design', 'wc-product-table')?>
												</th>
												<td>
													<fieldset>
														<label>
															<input name="wptcd-table-design-type" type="radio" value="default" id="wptcd-table-design-type" class="wptcd-table-design-type"<?php echo ($tbl_design_type=='default')?' checked':''?>> <?php _e('Default', 'wc-product-table')?>
															</label>
														<br>
														<label>
															<input name="wptcd-table-design-type" type="radio" value="custom" id="wptcd-table-design-type" class="wptcd-table-design-type"<?php echo ($tbl_design_type=='custom')?' checked':''?> disabled> <?php _e('Custom', 'wc-product-table')?>
														</label>
													</fieldset>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label for="wptcd_table_border_color"><?php _e('Border', 'wc-product-table')?></label>
												</th>
												<td>
													<input type="text" value="<?php echo esc_attr( $tbl_border_color )?>" name="wptcd_table_border_color" class="wptcd_table_border_color"/>
													<p class="description"><?php _e('Change the table border Color here.', 'wc-product-table')?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label for="wptcd_table_header_bg_color"><?php _e('Header Background', 'wc-product-table')?></label>
												</th>
												<td>
													<input type="text" value="<?php echo esc_attr( $tbl_header_bg_color )?>" name="wptcd_table_head_bg_color" class="wptcd_table_header_bg_color"/>
													<p class="description"><?php _e('Change the table header background Color here.', 'wc-product-table')?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label for="wptcd_table_cell_bg_color"><?php _e('Cell Background', 'wc-product-table')?></label>
												</th>
												<td>
													<input type="text" value="<?php echo esc_attr( $tbl_cell_bg_color )?>" name="wptcd_table_cell_bg_color" class="wptcd_table_cell_bg_color"/>
													<p class="description"><?php _e('Change the table cell background Color here.', 'wc-product-table')?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label for="wptcd_table_header_color"><?php _e('Header Text Color', 'wc-product-table')?></label>
												</th>
												<td>
													<input type="text" value="<?php echo esc_attr( $tbl_header_txt_color )?>" name="wptcd_header_txt_color" class="wptcd_table_header_color"/>
													<p class="description"><?php _e('Change the table header text Color here.', 'wc-product-table')?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label for="wptcd_table_cell_text_color"><?php _e('Cell Text Color', 'wc-product-table')?></label>
												</th>
												<td>
													<input type="text" value="<?php echo esc_attr( $tbl_cell_txt_color )?>" name="wptcd_table_cell_txt_color" class="wptcd_table_cell_text_color"/>
													<p class="description"><?php _e('Change the table cell text Color here.', 'wc-product-table')?></p>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div id="tabs-2">
									<h2 class="title"><?php _e('Table Content Options', 'wc-product-table')?></h2>
									<table class="form-table">
										<tbody>
											<tr>
												<th scope="row">
												<?php _e('Quick View?', 'wc-product-table')?>
												</th>
												<td>
													<label for="wptcd-quick-view">
														<input name="show_quick_view" type="checkbox" id="wptcd-quick-view" <?php echo esc_attr($show_quick_view=='checked'?'checked':'')?> disabled>
														<?php _e('Enable Quick View', 'wc-product-table')?>
													</label>
												</td>
											</tr>
											<tr>
												<th scope="row">
												<?php _e('Show the Review Column?', 'wc-product-table')?>
												</th>
												<td>
													<label for="wptcd-show-review-column">
														<input name="show_review_column" type="checkbox" id="wptcd-show-review-column" <?php echo esc_attr($show_review_column=='checked'?'checked':'')?> disabled>
														<?php _e('Show the Review Column', 'wc-product-table')?>
													</label>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div id="tabs-4">
									<h2 class="title"><?php _e('Table Controls', 'wc-product-table')?></h2>
									<table class="form-table">
										<tbody>
											<tr>
												<th scope="row">
													<label for="wptcd-items-per-page"><?php _e('Rows Per Page', 'wc-product-table')?></label>
												</th>
												<td>
													<input name="rows_per_page" type="number" id="wptcd-items-per-page" min="5" value="<?php echo esc_attr($rows_per_page)?>" class="small-text" disabled>
													<p class="description"><?php _e('Product Items Per Page for the Pagination.', 'wc-product-table')?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label for="button_title"><?php _e('Button Title', 'wc-product-table')?></label>
												</th>
												<td>
													<input name="add_to_cart_btn_title" type="text" id="button_title" class="regular-text" value="<?php echo esc_attr($add_to_cart_btn_title)?>">
													<p class="description"><?php _e('Change the default \'Add to cart\' button title here.', 'wc-product-table')?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
													<label for="wptcd_add_to_cart_button_color"><?php _e('Button Color', 'wc-product-table')?></label>
												</th>
												<td>
													<input type="text" value="<?php echo esc_attr( $add_to_cart_btn_color )?>" name="add_to_cart_btn_color" class="wptcd_add_to_cart_button_color" data-default-color="#17a2b8" />
													<p class="description"><?php _e('Change the default Color for the \'Add to cart\' button here.', 'wc-product-table')?></p>
												</td>
											</tr>
											<tr>
												<th scope="row">
												<?php _e('Show the Search Box?', 'wc-product-table')?>
												</th>
												<td>
													<label for="wptcd-show-search-box">
														<input name="show_search_box" type="checkbox" id="wptcd-show-search-box" <?php echo esc_attr($show_search_box=='checked'?'checked':'')?> disabled>
														<?php _e('Show the Search Box.', 'wc-product-table')?>
													</label>
												</td>
											</tr>
											<tr>
												<th scope="row">
												<?php _e('Show the Reset Button?', 'wc-product-table')?>
												</th>
												<td>
													<label for="wptcd-show-reset-btn">
														<input name="show_reset_btn" type="checkbox" id="wptcd-show-reset-btn" <?php echo esc_attr($show_reset_btn=='checked'?'checked':'')?> disabled>
														<?php _e('Show the Reset Button.', 'wc-product-table')?>
													</label>
												</td>
											</tr>
											<tr>
												<th scope="row">
												<?php _e('Show the Mini Cart?', 'wc-product-table')?>
												</th>
												<td>
													<label for="wptcd-show-mini-cart">
														<input name="show_mini_cart" type="checkbox" id="wptcd-show-mini-cart" <?php echo esc_attr($show_mini_cart=='checked'?'checked':'')?> disabled>
														<?php _e('Show the Mini Cart.', 'wc-product-table')?>
													</label>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<p class="submit"><input type="submit" name="settingsSubmitBtn" id="submit" class="button button-primary" value="<?php _e('Save Changes', 'wc-product-table')?>"></p>
					</form>
				</div>
			</div>
		</div>
		<?php
	}
}