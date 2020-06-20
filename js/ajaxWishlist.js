$(document).ready(function(){
	$('.clsWishlist').on('click', addToWishList);
});
function addToWishList(){
	var productVal = $(this).data('id');
	var add = $('#addedWishlist');
	var currentButton = $(this);
	$.ajax({
		url: BASE_URL + "/ajaxWishlistAdd.php",
		data:{product_id:productVal},
		type:"GET",
		success: function(response){
				var response = JSON.parse(response);
				if(response!=""){
					if(response!=""){
						// alert(response);
						// if(add){
							// add.html("<i class='fa fa-heart-o'></i>"+response);
						// }
						currentButton.closest('.iwishlist').addClass('active');
						// showNotification("Product added to wishlist");
						showNotification(response);
						$(".wishlistAdded").html('<a href="javascript:;" class="add_to_cart_bottom-wish added" title="Already Added to wishlist"><i class="fa fa-heart" aria-hidden="true"></i></a>');
						
					}else{
						// showNotification("Product added to wishlist");
						showNotification(response);
					}
				}else{
					// showNotification("Product added to wishlist");
					showNotification(response);
				}
		},
		error: function(){
			alert("Unable to get list, Please try again")
		}
	});
}