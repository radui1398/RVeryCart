jQuery(document).ready(function ($) {
   //shop update

   $('a.add-to-cart').on('click', function (e) {
      $(this).addToCart(e);
   });

   $('.quantity').on('change', function (e) {
      $(this).quantityChange(e);
   });

   $('.item-delete a').on('click', function (e) {
      $(this).triggerDelete(e);
   });

   $('nav li.dropdown').hover(
      function () {
         $('a', this).next().css('display', 'block');
      },


      function () {
         $('a', this).next().css('display', 'none');
      });
});