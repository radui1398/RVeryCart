<?php
/**
* Plugin Name: RVeryCart
* Plugin URI: https://www.verycreative.ro
* Description: Cart Plugin created by VeryCreative. To use this plugin enable your theme support - add_theme_support('woocommerce');
* Version: 1.0
* Author: Radu Ionut
* Author URI: #
**/

// add_theme_support('woocommerce'); -- doar daca nu a fost deja adaugat
// ajax load content slider - gallery page

/* add_localize_scriptR - Daca l-ai declarat deja ar fi bine sa il stergi si sa il pastrezi pe acesta :) */

if(function_exists('add_localize_scriptR')){
   remove_action('wp_head', 'add_localize_scriptR', 999);
}
function add_localize_script_R()
{
?>
<script type="text/javascript">
var jsHomeUrl = '<?php echo home_url(); ?>';
var ajaxUrl = "<?php echo admin_url('admin-ajax.php') ?>";
var priceCurrency = "<?php echo get_woocommerce_currency_symbol() ?>";
</script>
<?php
}
add_action('wp_head', 'add_localize_scriptR', 999);


// register jquery and style on initialization
add_action('init', 'register_plugin_script');
function register_plugin_script() {
    wp_register_script( 'noty', plugins_url('/noty.min.js', __FILE__), array('jquery'), '2.5.1' );
    wp_register_script( 'rVeryCartJS', plugins_url('/cart_js.js', __FILE__), array('jquery'), '2.5.1' );

    wp_register_style( 'notyCSS', plugins_url('/noty.css', __FILE__), false, '1.0.0', 'all');
    wp_register_style( 'rVeryCaryCSS', plugins_url('/cart_style.css', __FILE__), false, '1.0.0', 'all');
}

// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'enqueue_plugin_style');

function enqueue_plugin_style(){
   wp_enqueue_script('rVeryCartJS');
   wp_enqueue_script('noty');

   wp_enqueue_style( 'rVeryCaryCSS' );
   wp_enqueue_style( 'notyCSS' );
}


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
   
   $nrOfItems = WC()->cart->get_cart_contents_count();?>
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
<?php
}

//admin page





add_action('admin_menu', 'cart_setup_menu');
 
function cart_setup_menu(){
        add_submenu_page( 'options-general.php','RVeryCart', 'Cart Options', 'manage_options', 'cart-options', 'cart_init' );
}
 
function cart_init(){
   $cssStyle = fopen(plugins_url('/cart_style.css', __FILE__), "r") or die("Unable to open file!");
   $cssContent = fread($cssStyle,10000);
   fclose($cssStyle);
?>
<div>
   <?php screen_icon(); ?>
   <h2>RVeryCart</h2>
   <form method="post" action="<?php echo plugins_url('/style_updater.php', __FILE__)?>">
      <h3>Cart Style</h3>
      <p>Edit the cart style.</p>
      <input type="hidden" name="fileLocation" value="<?php echo plugin_dir_path( __FILE__ ).'/cart_style.css';?>" />
      <textarea rows="20" id="cssEditor" style="width: 40%" name="cssEditor"><?php echo $cssContent; ?></textarea>

      <?php  submit_button(); ?>
   </form>
</div>
<?php }