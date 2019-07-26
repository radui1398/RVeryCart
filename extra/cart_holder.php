 <?php $nrOfItems = WC()->cart->get_cart_contents_count();?>
               <li class="dropdown shop-dropdown">
                  <a href="#">
                     SHOP
                  </a>
                  <div class="dropdown-wrapper-shop">
                     <div class=" shop-items-holder">
                        <?php if (sizeof(WC()->cart->get_cart()) > 0): ?>
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
                              <div class="item-delete">
                                 <?php
        echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
            '<a href="#" class="remove" key="%s" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa fa-times-circle" aria-hidden="true"></i></a>',
            $cart_item_key,
            __('', 'woocommerce'),
            esc_attr($product_id),
            esc_attr($_product->get_sku())
        ), $cart_item_key);
        ?>
                              </div>
                              <div class="item-image">
                                 <?php if (empty($product_permalink)): ?>
                                 <?php echo $thumbnail; ?>
                                 <?php else: ?>
                                 <a href="<?php echo esc_url($product_permalink); ?>">
                                    <?php echo $thumbnail; ?>
                                 </a>
                                 <?php endif;?>
                              </div>

                              <div class="item-description">
                                 <div class="item-title">
                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                       <h6><?php echo $product_name; ?></h6>
                                    </a>
                                 </div>
                                 <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                                 <?php echo apply_filters('woocommerce_widget_cart_item_quantity', '<div class="item-info">'
    . sprintf('<span class="quantity" prod_id="%s" id="%s" data-quantity-limit="%s">x<input type="number" min="1"
                                 value="%s"></span><span class="price">%s</span>', $cart_item_key,$product_id,  $_product->get_stock_quantity(), $cart_item['quantity'],
        $product_price) . '
                           </div>', $cart_item, $cart_item_key); ?>
                              </div>

                           </div>
                           <?php endif;endforeach;?>
                           <div class="shop-details">
                              <div class="info-block livrare">
                                 <span>Livrare:</span>
                                 <span>15 Lei</span>
                              </div>
                              <div class="info-block total">
                                 <span>Total:</span>
                                 <span><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                              </div>
                           </div>
                           <a href="<?php echo WC()->cart->get_checkout_url(); ?>"
                              class="btn btn-arrow btn-orange buy"><span>Plaseaza</span>
                              comanda</a>
                           <?php else: ?>
                           <div class="cart-detail">
                              <p class="no-products"><?php _e('Nu aveti niciun produs in cos!', THEME_TEXT_DOMAIN);?>
                              </p>
                           </div>
                           <?php endif;?>

                        </div>
                     </div>
               </li>