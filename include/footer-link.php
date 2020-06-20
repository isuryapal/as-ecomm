<script>
	var BASE_URL = "<?php echo BASE_URL; ?>";
	var USER = "<?php echo $loggedInUserDetailsArr['user_type']; ?>"

</script>
<script src="<?php echo BASE_URL; ?>/js/jquery-3.3.1.min.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>/js/jquery.nice-select.min.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>/js/slick.min.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>/js/jquery-ui.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>/js/jquery.matchHeight.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>/js/jquery.nice-select.min.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>/js/jquery.elevatezoom.js"></script>
<script src="<?php echo BASE_URL; ?>/js/fancy.js" type="text/javascript"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>  
<script src="<?php echo BASE_URL; ?>/js/jquery.validate.js"></script>
<script src="<?php echo BASE_URL; ?>/js/additional-methods.js"></script>
<script src="<?php echo BASE_URL; ?>/js/wow.min.js"></script>

<script src="<?php echo BASE_URL; ?>/js/jquery.mCustomScrollbar.concat.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
<script src="<?php echo BASE_URL; ?>/js/main.js"></script>
<script src="<?php echo BASE_URL; ?>/js/modernizr.js"></script>
<script src="<?php echo BASE_URL; ?>/js/bootstrap-select.min.js"></script>
<script src="<?php echo BASE_URL; ?>/js/jquery.menu-aim.js"></script>

<script src="<?php echo BASE_URL; ?>/js/index.js" type="text/javascript"></script>
<script>
	$(document).ready(function() {
		incrementSpinner();
	});

	function incrementSpinner(){
		$('.btn-number').off("click");
		$('.input-number').off("click");
		$('.input-number').off("change");
		$('.input-number').off("keydown");

		$('.btn-number').click(function(e){
			// alert('click');
			e.preventDefault();

			fieldName = $(this).attr('data-field');
			type = $(this).attr('data-type');
			var input = $("input[name='"+fieldName+"']");
			var currentVal = parseInt(input.val());
			//alert(currentVal);
			if (!isNaN(currentVal)) {
				if(type == 'minus') {
					if(currentVal > input.attr('min')) {
						input.val(currentVal - 1).change();
					} 
					/* if(parseInt(input.val()) == input.attr('min')) {
						$(this).attr('disabled', true);
					} */

				} else if(type == 'plus') {

					if(currentVal < input.attr('max')) {
						input.val(currentVal + 1).change();
					}

				}
			} else {
				input.val(0);
			}
		});
		$('.input-number').focusin(function(){
			$(this).data('oldValue', $(this).val());
		});
		$('.input-number').change(function() {
			minValue =  parseInt($(this).attr('min'));
			maxValue =  parseInt($(this).attr('max'));
			valueCurrent = parseInt($(this).val());
			
			name = $(this).attr('name');
			if(valueCurrent >= minValue) {
				$(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
			} else {
				alert('Sorry, the minimum value was reached');
				$(this).val($(this).data('oldValue'));
			}
			if(valueCurrent <= maxValue) {
				$(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
			} else {
				alert('Sorry, the maximum value was reached');
				$(this).val($(this).data('oldValue'));
			}
		});
		$(".input-number").keydown(function (e) {
				// Allow: backspace, delete, tab, escape, enter and .
			if ($.inArray(e.keyCode, [46, 8, 9, 17, 13, 190]) !== -1 ||
				 // Allow: Ctrl+A
				(e.keyCode == 65 && e.ctrlKey === true) || 
				 // Allow: home, end, left, right
				(e.keyCode >= 35 && e.keyCode <= 39)) {
					 // let it happen, don't do anything
					 return;	
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});
	}
</script>
<script src="<?php echo BASE_URL; ?>/js/ajax-update-cart.js" type="text/javascript"></script>
<script src="<?php echo BASE_URL; ?>/js/ajaxWishlist.js" type="text/javascript"></script>

<script src="<?php echo BASE_URL ?>/js/bootstrap3-typeahead.min.js"></script>
<script src="<?php echo BASE_URL ?>/js/bootstrap-select.min.js"></script>
<script>
	var search_texts = '';
	var qField = $('#search_text, #mini_search_text, #mobile-search');
	var qFieldTypeBox = qField.typeahead({
		source: function(query, getData){
			setTimeout(function(){ // for fast typing, delay
				var categoty = $('select[name="categories"]').val();
				search_texts = $('input#search_text').val();
				var aTest = $.ajax({
								url:"<?php echo BASE_URL ?>/search_product.php?cat="+categoty,
								type:"get",
								data:{q:query},
								success: function(response){
									console.log(response);
									// alert(response); // TEST
									var response = response.split("D#K");
									getData(response);
								},
								error: function(response){
									getData(["Unable to get results please try again"]);
								},
								complete: function(){
								}
							});
			}, 1000);
		},
		showHintOnFocus:false,
		// delay:100,
		autoSelect:false,
		afterSelect: function(e, f, g) {
			qField.val(e);
			setTimeout(function() {
				$("#searchFrm").submit();
				//alert(search_texts);
				//window.location.href = "<?php echo BASE_URL; ?>/listing-search?product_id="+search_text;
			}, 10);
		}
	});
	$("#news_letterSub").validate({
		ignore: ".ignore",
		rules: {
			email: {
				required: true,
				email:true,
			}
		},
		messages: {
			email: {
				required: 'Please enter your email address',
				email : 'Pleas enter valid email Address'
			},
		}
	});
</script>