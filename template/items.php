<?php
	
   if (sizeof(WC()->cart->get_cart()) > 0): ?>

<div class="items">

   <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item):

      $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

      $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

      if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key)):

      $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

      $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('cart-image'), $cart_item, $cart_item_key);

      $product_price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);

      $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);?>
   <div
      class="item <?php echo esc_attr(apply_filters('woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key)); ?>">
      <?php rverycart_item($_product,$product_id,$product_name,$thumbnail,$product_price,$product_permalink,$cart_item_key,$cart_item); ?>
   </div>

   <?php endif;endforeach;?>

   <?php rverycart_shop_details();?>

   <?php else: ?>

   <?php rverycart_no_items(); ?>

   <?php endif;?>