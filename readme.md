//To use this with the "buy product" button use this structure:
<a data-product_id="[POST_ID]" class="[add-to-cart] [product_type] [Any other class but not add_to_cart_button]" data-quantity="[quantity]" [*]>[*]</a>

//Works only with simple products / ELSE: It's just a link to the product


Paste this in header
-----
                 <?php $nrOfItems = WC()->cart->get_cart_contents_count();?>

                 <li class="dropdown shop-dropdown">

                    <a href="<?php echo wc_get_cart_url(); ?>">

                       <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"

                          class="svg replaced-svg">

                          <g transform="translate(-1804 -37)">

                             <circle cx="24" cy="24" r="24" transform="translate(1804 37)" fill="#f49f85"></circle>

                             <g transform="translate(1817.586 48)">

                                <g transform="translate(0)">

                                   <path

                                      d="M73.187,20.987,72.05,6.666A1.79,1.79,0,0,0,70.274,5.02H68.029v-.1a4.919,4.919,0,0,0-9.839,0v.1H55.945a1.789,1.789,0,0,0-1.776,1.644L53.032,20.989a4.144,4.144,0,0,0,4.132,4.461H69.055a4.144,4.144,0,0,0,4.132-4.463ZM59.681,4.919a3.429,3.429,0,0,1,6.857,0v.1H59.681ZM71,23.109a2.626,2.626,0,0,1-1.947.85H57.164a2.653,2.653,0,0,1-2.646-2.854L55.655,6.78a.292.292,0,0,1,.29-.269H58.19V8.347a.745.745,0,1,0,1.491,0V6.511h6.857V8.347a.745.745,0,0,0,1.491,0V6.511h2.245a.293.293,0,0,1,.29.271L71.7,21.1A2.626,2.626,0,0,1,71,23.109Z"

                                      transform="translate(-53.02)" fill="#fff"></path>

                                </g>

                             </g><text

                                transform="<?=((int) $nrOfItems > 9) ? 'translate(1823 67)' : 'translate(1825 67)'?>"

                                fill="#fff" font-size="8" font-family="Montserrat-Medium, Montserrat" font-weight="500"

                                letter-spacing="0.01em">

                                <tspan x="0" y="0"><?php echo $nrOfItems; ?></tspan>

                             </text>

                          </g>

                       </svg>

                    </a>

                    <div class="dropdown-wrapper-shop">

                        <?php generate_cart()?>

                    </div>

                 </li>
