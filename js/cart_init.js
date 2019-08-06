$ = jQuery;

jQuery(document).ready(function ($) {
   //shop update

   $('a.add-to-cart').on('click', function (e) {
      $(this).addToCart(e);
   });

   $('.dropdown-wrapper-shop .quantity').on('change', function (e) {
      $(this).quantityChange(e);
   });

   $('.dropdown-wrapper-shop .item-delete a').on('click', function (e) {
      $(this).triggerDelete(e);
   });

   $('form.variations_form button[type="submit"]').on('click', (e) => {
      $(this).addToCartSingle(e);
   })

   $('nav li.dropdown').hover(
      function () {
         $('a', this).next().css('display', 'block');
      },


      function () {
         $('a', this).next().css('display', 'none');
      });
});