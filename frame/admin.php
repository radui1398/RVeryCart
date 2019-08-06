<?php

add_action('admin_menu', 'cart_setup_menu');

function cart_setup_menu(){

   add_submenu_page( 'options-general.php','RVeryCart', 'Cart Options', 'manage_options', 'cart-options', 'cart_init' );

}


function rverycart_register_settings() {
   add_option( 'rverycart_option_li_class', 'dropdown shop-dropdown');
   add_option( 'rverycart_option_link', 'SHOP');
   register_setting( 'rverycart_options_group', 'rverycart_option_li_class', 'rverycart_callback' );
   register_setting( 'rverycart_options_group', 'rverycart_option_link', 'rverycart_callback' );

}
add_action( 'admin_init', 'rverycart_register_settings' );



function cart_init(){

   $cssStyle = fopen(plugins_url('../style.css', __FILE__), "r");

   $cssContent = fread($cssStyle,10000);

   fclose($cssStyle);

   ?>

<div>

   <?php screen_icon(); ?>

   <h2>RVeryCart</h2>

   <h3>Info:</h3>

   <p> To use this plugin enable your theme support - add_theme_support('woocommerce'); </p>
   <p> To use the "add to cart" button with RVeryCart you need to edit your button. You can do this in the
      add-to-cart.php file from the woocommerce templates.</p>
   <p> New button must respect this structure: </p>
   <p><code><pre><?php echo htmlspecialchars('<a data-product_id="[POST_ID]" class="[add-to-cart] [product_type] [Any other class but not add_to_cart_button]" data-quantity="[quantity]" data-quantity-limit="[limit or -1 for no limit] [*]>[*]</a>') ?></pre></code>
   </p>

   <div style="width: 30%; float: left;">

      <form method="post" action="<?php echo plugins_url('../utils/style_updater.php', __FILE__)?>">

         <h3>Cart Style</h3>

         <p>Edit the cart style.</p>

         <input type="hidden" name="fileLocation" value="<?php echo plugin_dir_path( __FILE__ ).'../style.css';?>" />

         <textarea rows="20" style="width:100%" id="cssEditor" name="cssEditor"><?php echo $cssContent; ?></textarea>



         <?php  submit_button(); ?>

      </form>
   </div>

   <div style="width: 30%; margin-left: 4%; float: left;">
      <?php
      $bufferFile = file_get_contents(plugins_url('../template/cart.tpl', __FILE__), "r");

      ?>
      <form method="post" action="<?php echo plugins_url('../utils/style_updater.php', __FILE__)?>">

         <h3>Shop Holder Structure</h3>

         <p>Don't edit the php code!</p>

         <input type="hidden" name="fileLocation"
            value="<?php echo plugin_dir_path( __FILE__ ).'../template/cart.tpl';?>" />

         <textarea rows="20" style="width:100%" id="cssEditor"
            name="cssEditor"><?php echo htmlspecialchars($bufferFile); ?></textarea>



         <?php  submit_button(); ?>

      </form>
   </div>


   <div style="width: 30%; margin-left: 4%;  float: left;">
      <?php
      $bufferFile = file_get_contents(plugins_url('../template/cart_item.tpl', __FILE__), "r");

      ?>
      <form method="post" action="<?php echo plugins_url('../utils/style_updater.php', __FILE__)?>">

         <h3>Shop Holder Structure</h3>

         <p>Don't edit the php code!</p>

         <input type="hidden" name="fileLocation"
            value="<?php echo plugin_dir_path( __FILE__ ).'../template/cart_item.tpl';?>" />

         <textarea rows="20" style="width:100%" id="cssEditor"
            name="cssEditor"><?php echo htmlspecialchars($bufferFile); ?></textarea>



         <?php  submit_button(); ?>

      </form>
   </div>


   <div style="width: 30%; float: left;">
      <?php
      $bufferFile = file_get_contents(plugins_url('../template/no_items.tpl', __FILE__), "r");

      ?>
      <form method="post" action="<?php echo plugins_url('../utils/style_updater.php', __FILE__)?>">

         <h3>No items structure</h3>

         <p>Don't edit the php code!</p>

         <input type="hidden" name="fileLocation"
            value="<?php echo plugin_dir_path( __FILE__ ).'../template/no_items.tpl';?>" />

         <textarea rows="20" style="width:100%" id="cssEditor"
            name="cssEditor"><?php echo htmlspecialchars($bufferFile); ?></textarea>



         <?php  submit_button(); ?>

      </form>
   </div>

   <div style="width: 30%; margin-left: 4%; float: left;">
      <?php
      $bufferFile = file_get_contents(plugins_url('../template/shop_details.tpl', __FILE__), "r");

      ?>
      <form method="post" action="<?php echo plugins_url('../utils/style_updater.php', __FILE__)?>">

         <h3>Shop details structure</h3>

         <p>Don't edit the php code!</p>

         <input type="hidden" name="fileLocation"
            value="<?php echo plugin_dir_path( __FILE__ ).'../template/shop_details.tpl';?>" />

         <textarea rows="20" style="width:100%" id="cssEditor"
            name="cssEditor"><?php echo htmlspecialchars($bufferFile); ?></textarea>



         <?php  submit_button(); ?>

      </form>
   </div>

   <div style="width: 30%; margin-left: 4%; float: left;">
      <?php
      $bufferFile = file_get_contents(plugins_url('../js/cart_init.js', __FILE__), "r");

      ?>
      <form method="post" action="<?php echo plugins_url('../utils/style_updater.php', __FILE__)?>">

         <h3>Cart Initialization</h3>

         <p>JavaScript code, usefull to recall after filtering with an ajax plugin.</p>

         <input type="hidden" name="fileLocation"
            value="<?php echo plugin_dir_path( __FILE__ ).'../js/cart_init.js';?>" />

         <textarea rows="20" style="width:100%" id="cssEditor"
            name="cssEditor"><?php echo htmlspecialchars($bufferFile); ?></textarea>



         <?php  submit_button(); ?>

      </form>
   </div>
   <?php
}