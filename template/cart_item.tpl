<div class="item-delete">
   <?php rverycart_item_delete('remove',$productKey,$productID,$product)?>
</div>


<div class="item-image">
   <?php rverycart_item_image($productLink,$productImage);?>
</div>

<div class="item-description">
   <div class="item-title">
      <a href="<?php rverycart_item_link($productLink); ?>">
         <h6><?php rverycart_item_title($productName); ?></h6>
      </a>
   </div>
   <?php rverycart_item_price('input',$productCart,$productKey,$productID,$product,$productPrice);?>
</div>