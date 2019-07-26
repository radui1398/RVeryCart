//Pentru notificare este necesar plugin-ul https://ned.im/noty/

$ = jQuery;

function notify($msg, $type = 'success') {
	new Noty({
		text: $msg,
		type: $type,
		timeout: 1000,
		progressBar: false,
	}).show()
}

function refreshCartDetails($item = 'all') {
	var $newTotal = 0.00;
	var $nrOfProducts = 0;
	$('.shop-items-holder .item').each(function () {
		$nrOfProducts = $nrOfProducts + 1;
		$quantity = parseFloat($('.quantity input', this).val()).toFixed(2);
		$price = parseFloat($(this).find('.price .amount').html()).toFixed(2);
		$productTotal = $quantity * $price;
		$newTotal = $newTotal + $productTotal;
	});
	if ($nrOfProducts > 0) {
		if ($('.shop-details .total .amount').length > 0)
			$(".shop-details .total .amount").html($(".shop-details .total .amount").html().replace(/([0-9,])+/g, $newTotal.toFixed(2).replace('.', ',')));
		else
			$('.shop-details .total').html('<span>Total:</span><span><span class="woocommerce-Price-amount amount">' + $newTotal.toFixed(2).replace('.', ',') + '&nbsp;<span class="woocommerce-Price-currencySymbol">' + priceCurrency + '</span></span></span>')
	}
	return $nrOfProducts;
}



$.fn.quantityChange = function (e) {
	//check if quantity is set and quantity is in stock
	$availableQuantity = $(this).data('quantity-limit');
	$input = $(this).find('input');

	if ($availableQuantity && $availableQuantity < $input.val()) {
		notify('Momentan sunt doar ' + $availableQuantity + ' produse in stoc', 'error');
		$input.val($availableQuantity);
	}
	refreshCartDetails();
	var formData = new FormData();
	formData.append('action', "edit_cart_quantity");
	formData.append('id', $(this).attr('prod_id'));
	formData.append('quantity', $input.val());
	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		url: ajaxUrl,
		data: formData,
		processData: false,
		contentType: false,
		success: function (data) {
			if (data['ok'] == false) {
				notify(data['msg'], 'error');
			}
		},
		error: function (data) {
			notify('Site-ul intampina o eroare la schimbarea de produse.', 'error');
		}
	});
};

$.fn.triggerDelete = function (e) {
	e.preventDefault();
	container = $(this).parent().parent();
	container.slideUp("fast", function () {
		$(this).remove();
		if (refreshCartDetails() == 0) {
			$('.shop-items-holder .items').slideUp("fast", function () {
				$(this).remove();
				$('.shop-items-holder').append('<div class="cart-detail"><p class="no-products">Nu aveti niciun produs in cos!</p></div>');
			});
		}
	});

	var formData = new FormData();
	formData.append('action', "delete_cart_item");
	formData.append('key', $(this).attr('key'));
	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		url: ajaxUrl,
		data: formData,
		processData: false,
		contentType: false,
		success: function (data) {
			if (data['ok'] == false) {
				notify('Nu am reușit să șterg produsul.', 'error');
			}
		},
		error: function (data) {
			notify('Site-ul intampina o eroare la schimbarea de produse.');
		}
	});
}

$.fn.addToCart = function (e) {
	if (!$(this).hasClass('product_type_variable') && !$(this).hasClass('product_type_grouped') && !$(this).hasClass('product_type_external')) {
		e.preventDefault();
		var formData = new FormData();
		formData.append('action', "update_cart_quantity");;
		formData.append('quantity', $(this).attr('data-quantity'));
		$new_product = false;
		$prod_id = $(this).attr('data-product_id');
		formData.append('product_id', $prod_id);
		$prod = $('#' + $prod_id + ' input');
		$prod_quantity = $(this).attr('data-quantity-limit');
		$first_product = false;
		if ($('.shop-details').length == '0') {
			$new_product = true;
			$first_product = true;
			notify('Produsul a fost adăugat!');
		}
		else {
			$first_product = false;
			if ($prod.length) {
				$old_quantity = $prod.val();
				notify('Produsul a fost adăugat!');
				$prod.val(parseInt($old_quantity) + 1).trigger('change');
				$new_product = false;
				return;
			}
			else {
				$new_product = true;
				notify('Produsul a fost adăugat!');
			}

		}

		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajaxUrl,
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {

				if ($first_product == true) {
					$('.shop-items-holder').html('').append('<div class="items"><div class="shop-details"><div class="info-block livrare"><span>Livrare:</span> <span>15 Lei</span></div><div class="info-block total"><span>Total:</span> <span>0</span></div></div><a href="' + data['checkout'] + '" class="btn btn-arrow btn-orange buy"><span>Plaseaza</span> comanda</a></div>');
				}
				if ($new_product == true) {
					$delete_link = data['delete_link'];
					$prod_key = data['prod_key'];
					$prod_link = data['prod_link'];
					$prod_name = data['prod_name'];
					$prod_price = data['prod_price'];
					$prod_thumbnail = data['prod_thumbnail'];

					$('<div class="item mini_cart_item"><div class="item-delete">' + $delete_link + '</div><div class="item-image">' + $prod_thumbnail + '</div><div class="item-description"><div class="item-title"> <a href="' + $prod_link + '"><h6>' + $prod_name + '</h6> </a></div>' + $prod_price + '</div></div>').insertBefore('.items .shop-details');
					$('.shop-details').prev().find('.quantity').on('change', function (e) {
						$(this).quantityChange(e);
					});
					$('.shop-details').prev().find('.item-delete a').on('click', function (e) {
						$(this).triggerDelete(e)
					});
					refreshCartDetails();
				}

			},
			error: function (data) {
				alert('Site-ul intampina o eroare la schimbarea de produse.')
			}
		});
	}
}


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