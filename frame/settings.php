<?php

function add_localize_scriptR()

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

    wp_register_script( 'noty', plugins_url('../js/noty.min.js', __FILE__), array('jquery'), '2.5.1' );

    wp_register_script( 'rVeryCartJS', plugins_url('../js/cart_js.js', __FILE__), array('jquery'), '2.5.1' );

    wp_register_script( 'rVeryCartJSInit', plugins_url('../js/cart_init.js', __FILE__), array('jquery'), '2.5.1' );

    wp_register_style( 'notyCSS', plugins_url('../css/noty.css', __FILE__), false, '1.0.0', 'all');

    wp_register_style( 'rVeryCaryCSS', plugins_url('../style.css', __FILE__), false, '1.0.0', 'all');

}



// use the registered jquery and style above

add_action('wp_enqueue_scripts', 'enqueue_plugin_style');



function enqueue_plugin_style(){

   wp_enqueue_script('rVeryCartJS');

   wp_enqueue_script('rVeryCartJSInit');

   wp_enqueue_script('noty');



   wp_enqueue_style( 'rVeryCaryCSS' );

   wp_enqueue_style( 'notyCSS' );

}