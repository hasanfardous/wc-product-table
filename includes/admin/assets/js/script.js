(function ($) {
    $(document).ready(function () {
        $('#tabs').tabs();
        // $('.table-design-form tr:not(:first-child)').hide();
        $('input:radio[name="wptcd-table-design-type"]').change(
            function () {
                if ($(this).is(':checked') && $(this).val() == 'custom') {
                    var tableTbody = $(this).closest('tbody tr');
                    tableTbody.siblings().fadeIn();
                } else {
                    var tableTbody = $(this).closest('tbody tr');
                    tableTbody.siblings().fadeOut();
                }
            }
        );

        // Admin notice
        function wptcd_show_admin_notice(message, type) {
            let html = `
                <div class='wptcd_show_admin_notice notice notice-${type} is-dismissible'>
                    <p>${message}</p>
                </div>
                <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
            `;
            return html;
        }

        // Initialize the wpColorPicker
        $('.wptcd_add_to_cart_button_color, .wptcd_table_border_color, .wptcd_table_header_bg_color, .wptcd_table_cell_bg_color, .wptcd_table_header_color, .wptcd_table_cell_text_color').wpColorPicker();

        // Form handling
        $('form.wptcd-feature-request-form').submit(function (e) {
            e.preventDefault();
            // Table Design Options
            var wptcdTableDesignType = $('input[name="wptcd-table-design-type"]:checked').val();
            var wptcdTableBorderColor = $('input[name="wptcd_table_border_color"]').val();
            var wptcdTableHeadBgColor = $('input[name="wptcd_table_head_bg_color"]').val();
            var wptcdTableCellBgColor = $('input[name="wptcd_table_cell_bg_color"]').val();
            var wptcdTableHeaderTxtColor = $('input[name="wptcd_header_txt_color"]').val();
            var wptcdTableCellTxtColor = $('input[name="wptcd_table_cell_txt_color"]').val();

            // Table content
            var show_quick_view = $('input[name="show_quick_view"]').prop('checked') == true ? 'checked' : 'no';
            var show_review_column = $('input[name="show_review_column"]').prop('checked') == true ? 'checked' : 'no';

            // Table controls
            var rows_per_page = $('input[name="rows_per_page"]').val();
            var add_to_cart_btn_title = $('input[name="add_to_cart_btn_title"]').val();
            var add_to_cart_btn_color = $('input[name="add_to_cart_btn_color"]').val();
            var show_search_box = $('input[name="show_search_box"]').prop('checked') == true ? 'checked' : 'no';
            var show_reset_btn = $('input[name="show_reset_btn"]').prop('checked') == true ? 'checked' : 'no';
            var show_mini_cart = $('input[name="show_mini_cart"]').prop('checked') == true ? 'checked' : 'no';

            // Assigning datas to the object
            var data = {
                action: 'wptcd_admin_datas',
                // Table design
                tbl_design_type: wptcdTableDesignType,
                tbl_border_color: wptcdTableBorderColor,
                tbl_header_bg_color: wptcdTableHeadBgColor,
                tbl_cell_bg_color: wptcdTableCellBgColor,
                tbl_header_txt_color: wptcdTableHeaderTxtColor,
                tbl_cell_txt_color: wptcdTableCellTxtColor,

                // Table content
                show_quick_view: show_quick_view,
                show_review_column: show_review_column,

                // Table controls
                rows_per_page: rows_per_page,
                add_to_cart_btn_title: add_to_cart_btn_title,
                add_to_cart_btn_color: add_to_cart_btn_color,
                show_search_box: show_search_box,
                show_reset_btn: show_reset_btn,
                show_mini_cart: show_mini_cart,
            };
            $.ajax({
                dataType: 'json',
                type: 'post',
                url: wptcd_admin_datas.ajax_url,
                data: data,
                beforeSend: function (response) {
                    $('.woocommerce-product-table').addClass('adding-to-cart');
                    console.log('before send');
                },
                complete: function (response) {
                    $('.woocommerce-product-table').removeClass('adding-to-cart');
                    console.log('completed');
                },
                success: function (response) {
                    if (response.status == 'success') {
                        $('.wptcd-admin-wrapper').find('h1').after(wptcd_show_admin_notice(response.message, 'success'));
                    }
                },
            });
        });
    });
})(jQuery);