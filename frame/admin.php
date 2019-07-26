<?php

add_action('admin_menu', 'cart_setup_menu');

function cart_setup_menu(){

   add_submenu_page( 'options-general.php','RVeryCart', 'Cart Options', 'manage_options', 'cart-options', 'cart_init' );

}



function cart_init(){

   $cssStyle = fopen(plugins_url('/style.css', __FILE__), "r") or die("Unable to open file!");

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
   <p> New <a> must respect this structure: </p>
   <p>
      <pre><a data-product_id="[POST_ID]" class="[add-to-cart] [product_type] [Any other class but not add_to_cart_button]" data-quantity="[quantity]" [*]>[*]</a></pre>
   </p>


   <form method="post" action="<?php echo plugins_url('/utils/style_updater.php', __FILE__)?>">

      <h3>Cart Style</h3>

      <p>Edit the cart style.</p>

      <input type="hidden" name="fileLocation" value="<?php echo plugin_dir_path( __FILE__ ).'/style.css';?>" />

      <textarea rows="20" id="cssEditor" style="width: 40%" name="cssEditor"><?php echo $cssContent; ?></textarea>



      <?php  submit_button(); ?>

   </form>

</div>
<?php
}