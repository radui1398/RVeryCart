<?php

function edit_cart_quantity()

{

    $new_quantity = $_POST['quantity'];

    

    WC()->cart->set_quantity($_POST['id'], $new_quantity);

    echo json_encode(array('ok' => true, 'total' => WC()->cart->get_cart_subtotal(), 'nrOfProducts' => WC()->cart->get_cart_contents_count()));

        

    wp_die();



}



add_action('wp_ajax_edit_cart_quantity', 'edit_cart_quantity');

add_action('wp_ajax_nopriv_edit_cart_quantity', 'edit_cart_quantity');



function update_cart_quantity()

{

    include_once "../../../wp-load.php";

    global $wpdb;



    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));

    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);

    WC()->cart->add_to_cart($product_id, $quantity);



    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {



        if ($cart_item['product_id'] == $_POST['product_id']) {

            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('cart-image'), $cart_item, $cart_item_key);

            $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);

            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);



            $delete_link = apply_filters('woocommerce_cart_item_remove_link', sprintf(

                '<a href="#" class="remove" key="%s" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-times-circle" aria-hidden="true"></i></a>',

                $cart_item_key,

                __('', 'woocommerce'),

                esc_attr($product_id),

                esc_attr($_product->get_sku())

            ), $cart_item_key);



            if (empty($product_permalink)) {

                $prod_thumbnail = $thumbnail;

            } else {

                $prod_thumbnail = '<a href="' . esc_url($product_permalink) . '">' . $thumbnail . '</a>';

            }

            



            $prod_price = wc_get_formatted_cart_item_data($cart_item) . apply_filters('woocommerce_widget_cart_item_quantity', '<div class="item-info">'

                . sprintf('<span class="quantity" prod_id="%s" id="%s" data-quantity-limit="%s" >x<input type="number" min="1"

                                         value="%s"></span><span class="price">%s</span>', $cart_item_key, $_POST['product_id'], $_product->get_stock_quantity(),$cart_item['quantity'],

                    $product_price) . ' </div>', $cart_item, $cart_item_key);



            $product_link = esc_url($product_permalink);

            echo json_encode(array('ok' => true,'total' => WC()->cart->get_cart_subtotal(), 'nrOfProducts' => WC()->cart->get_cart_contents_count(), 'delete_link' => $delete_link,

                'prod_key' => $cart_item_key, 'prod_name' => $product_name, 'prod_thumbnail' => $prod_thumbnail, 'prod_price' => $prod_price, 'prod_link' => $product_link, 'checkout' => WC()->cart->get_checkout_url()));

            die();

        }

    }



}



add_action('wp_ajax_update_cart_quantity', 'update_cart_quantity');

add_action('wp_ajax_nopriv_update_cart_quantity', 'update_cart_quantity');



function delete_cart_item()

{

    include_once "../../../wp-load.php";

    global $wpdb;

    WC()->cart->remove_cart_item($_POST['key']);

    echo json_encode(array('total' => WC()->cart->get_cart_subtotal(), 'nrOfProducts' => WC()->cart->get_cart_contents_count()));

    die();

}



add_action('wp_ajax_delete_cart_item', 'delete_cart_item');

add_action('wp_ajax_nopriv_delete_cart_item', 'delete_cart_item');



function generate_cart(){

   

   $nrOfItems = WC()->cart->get_cart_contents_count();

   include( plugin_dir_path( __FILE__ ) . 'template/cart.php');

}