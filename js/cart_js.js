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


$.fn.addToCart = function (t) {
	if (!$(this).hasClass("product_type_variable") && !$(this).hasClass("product_type_grouped") && !$(this).hasClass("product_type_external")) {
		t.preventDefault();
		var a = new FormData;
		if (a.append("action", "update_cart_quantity"), a.append("quantity", $(this).attr("data-quantity")), $new_product = !1, $prod_id = $(this).attr("data-product_id"), a.append("product_id", $prod_id), $prod = $("#" + $prod_id + " input"), $prod_quantity = $(this).attr("data-quantity-limit"), -1 == $prod_quantity && ($prod_quantity = 99999), $actual_quantity = $(this).attr("data-quantity"), $first_product = !1, "0" == $(".shop-details").length) {
			if ($new_product = !0, $first_product = !0, parseInt($actual_quantity) > parseInt($prod_quantity)) return void notify("Momentan sunt doar " + $prod_quantity + " produse in stoc.", "error");
			notify("Produsul a fost adăugat!")
		} else {
			if ($first_product = !1, $prod.length) return $old_quantity = $prod.val(), parseInt($old_quantity) + parseInt($actual_quantity) > parseInt($prod_quantity) ? void notify("Momentan sunt doar " + $prod_quantity + " produse in stoc.", "error") : (notify("Produsul a fost adăugat!"), $prod.val(parseInt($old_quantity) + parseInt($actual_quantity)).trigger("change"), void ($new_product = !1));
			if ($new_product = !0, parseInt($actual_quantity) > parseInt($prod_quantity)) return void notify("Momentan sunt doar " + $prod_quantity + " produse in stoc.", "error");
			notify("Produsul a fost adăugat!")
		}
		jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: ajaxUrl,
			data: a,
			processData: !1,
			contentType: !1,
			success: function (t) {
				1 == $first_product && $(".shop-items-holder").html("").append('<div class="items"><div class="shop-details"><div class="info-block livrare"><span>Livrare:</span> <span>15 Lei</span></div><div class="info-block total"><span>Total:</span> <span>0</span></div></div><a href="' + t.checkout + '" class="btn btn-arrow btn-orange buy"><span>Plaseaza</span> comanda</a></div>'), 1 == $new_product && ($delete_link = t.delete_link, $prod_key = t.prod_key, $prod_link = t.prod_link, $prod_name = t.prod_name, $prod_price = t.prod_price, $prod_thumbnail = t.prod_thumbnail, $('<div class="item mini_cart_item"><div class="item-delete">' + $delete_link + '</div><div class="item-image">' + $prod_thumbnail + '</div><div class="item-description"><div class="item-title"> <a href="' + $prod_link + '"><h6>' + $prod_name + "</h6> </a></div>" + $prod_price + "</div></div>").insertBefore(".items .shop-details"), $(".shop-details").prev().find(".quantity").on("change", function (t) {
					$(this).quantityChange(t)
				}), $(".shop-details").prev().find(".item-delete a").on("click", function (t) {
					$(this).triggerDelete(t)
				}), refreshCartDetails())
			},
			error: function (t) {
				alert("Site-ul intampina o eroare la schimbarea de produse.")
			}
		})
	}
};

$.fn.addToCartSingle = function (e){
	e.preventDefault();
	const form = $("form.variations_form");
	const prodVar =  $('input[name="variation_id"]', form).val() || false;
	let prodID = $('input[name="product_id"]', form).val();
	const prodCart = $('#' + prodID + '[data-variation="'+ prodVar + '"] input');
	const formData = new FormData();
	const prodQty = $('input[name="quantity"]', form).val();
	let prodQtyLimit = $('input[name="quantity"]', form).attr('max') || 99999;
	let firstProduct = false;

	//check if variation is on
	if (!$('input[name="variation_id"]', form).val())
		return;
	//check if products selected
	if (!$('input[name="quantity"]', form).val()) {
		notify("Trebuie să cumperi cel puțin un produs.", 'error');
		return;
	}
	//check if there are enough products
	if (parseInt(prodQty) > parseInt(prodQtyLimit)) {
		notify('Momentan sunt doar ' + prodQtyLimit + ' produse in stoc.', 'error')
		return;
	}
	//check if cart has any elements
	if (!$('.shop-details').length) {
		firstProduct = true;
	}
	//check if products already exist
	if (!firstProduct && prodCart.length) {
		//check if combined with cart there are enough products
		const oldQty = prodCart.val();
		console.log(form.serialize())
		if (parseInt(oldQty) + parseInt(prodQty) > parseInt(prodQtyLimit)) {
			notify('Momentan sunt doar ' + prodQtyLimit + ' produse in stoc.', 'error')
			return;
		}
		notify('Produsul a fost adăugat!');
		console.log(oldQty + prodQty);
		prodCart.val(parseInt(oldQty) + parseInt(prodQty)).trigger('change');
		return;
	}

	//if this product is var then we can insert with that id
	formData.append('action', "update_cart_quantity");
	formData.append('quantity', prodQty);
	formData.append('product_id_variation', prodID);
	if(prodVar) prodID = prodVar;
	formData.append('product_id', prodVar);
	notify('Produsul a fost adăugat!');

	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		url: ajaxUrl,
		data: formData,
		processData: false,
		contentType: false,
		success: function (data) {
			if (firstProduct)
				$('.shop-items-holder').html('').append('<div class="items"><div class="shop-details"><div class="info-block livrare"><span>Livrare:</span> <span>15 Lei</span></div><div class="info-block total"><span>Total:</span> <span>0</span></div></div><a href="' + data['checkout'] + '" class="btn btn-arrow btn-orange buy"><span>Plaseaza</span> comanda</a></div>');

			const deleteLink = data['delete_link'];
			const prodLink = data['prod_link'];
			const prodName = data['prod_name'];
			const prodPrice = data['prod_price'];
			const prodThmb = data['prod_thumbnail'];
			const cartDetails = $(".shop-details");
			$('<div class="item mini_cart_item"><div class="item-delete">' + deleteLink + '</div><div class="item-image">' + prodThmb + '</div><div class="item-description"><div class="item-title"> <a href="' + prodLink + '"><h6>' + prodName + '</h6> </a></div>' + prodPrice + '</div></div>').insertBefore('.items .shop-details');
			cartDetails.prev().find('.quantity').on('change', function (e) {
				$(this).quantityChange(e);
			});
			cartDetails.prev().find('.item-delete a').on('click', function (e) {
				$(this).triggerDelete(e)
			});

			refreshCartDetails();
		},
		error: function (data) {
			notify('Site-ul intampina o eroare la schimbarea de produse.','error')
		}
	});
}










