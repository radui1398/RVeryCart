<?php 
include( plugin_dir_path( __FILE__ ) . '../template/cart.tpl');

function rverycart_items(){
   include( plugin_dir_path( __FILE__ ) . '../template/items.php');
}


function rverycart_shop_details(){
   include( plugin_dir_path( __FILE__ ) . '../template/shop_details.php');
}

function rverycart_subtotal(){
echo WC()->cart->get_cart_subtotal();
}

function rverycart_checkout_url(){
echo WC()->cart->get_checkout_url();
}

function rverycart_no_items(){
   include( plugin_dir_path( __FILE__ ) . '../template/no_items.php');
}

function rverycart_no_items_msg(){
_e('Nu aveti niciun produs in cos!', THEME_TEXT_DOMAIN);
}

function rverycart_item_delete($class = 'remove', $productKey, $productID, $product){
echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
   '<a href="#" class="%s" key="%s" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-times-circle" aria-hidden="true"></i></a>',
   $class,
   $productKey,
   __('', 'woocommerce'),
   esc_attr($productID),
   esc_attr($product->get_sku())), $productKey);
}

function rverycart_item_image($product_permalink,$thumbnail){
if (empty($product_permalink)): ?>
<?php echo $thumbnail; ?>
<?php else: ?>
<a href="<?php echo esc_url($product_permalink); ?>">
   <?php echo $thumbnail; ?>
</a>
<?php endif;
}

function rverycart_item($product,$productID,$productName,$productImage,$productPrice,$productLink,$productKey,$productCart){
   include( plugin_dir_path( __FILE__ ) . '../template/cart_item.tpl');
}

function rverycart_item_price($type = 'input',$cart_item,$cart_item_key,$product_id,$_product,$product_price){
if($type == 'input'){
   echo wc_get_formatted_cart_item_data($cart_item); 
   echo apply_filters('woocommerce_widget_cart_item_quantity', '<div class="item-info">'
   . sprintf('<span class="quantity" prod_id="%s" id="%s" data-quantity-limit="%s">x<input type="number" min="1"
   value="%s"></span><span class="price">%s</span>', $cart_item_key,$product_id,  $_product->get_stock_quantity(), $cart_item['quantity'],
   $product_price) . ' </div>', $cart_item, $cart_item_key); 
}
}

function rverycart_item_link($product_permalink){
echo esc_url($product_permalink);
}

function rverycart_item_title($product_name){
echo $product_name;
}