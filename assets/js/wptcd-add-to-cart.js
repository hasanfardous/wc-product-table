(function ($) {
    $(document).ready(function () {
        $(document).on('click', '.wptcd-add-to-cart', function (e) {
            e.preventDefault();
            console.log('add to cart js');
            $thisbutton = $(this),
                product_qty = $thisbutton.prev('.wptcd-product-quantity').val() || 1,
                product_id = $thisbutton.prev().data('product-id'),
                variation_id = $thisbutton.prev().data('variation-id') || 0;

            var data = {
                action: 'wptcd_ajax_datas',
                product_id: product_id,
                product_sku: '',
                quantity: product_qty,
                variation_id: variation_id,
            };
            $.ajax({
                dataType: 'json',
                type: 'post',
                url: wptcd_ajax_datas.ajax_url,
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
                    console.log(response);
                    $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                },
            });
        });
    });
})(jQuery);