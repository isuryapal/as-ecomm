<?php
	/*
	 * Cart.class.php
	 * v1 - adds product to cart and saves cart to session,
			updates (increment/decrement) quantity cart in session,
			remove product from cart in session
	 */
	class Cart{
		public static $QUANTITY_UPDATE = 1;
		public static $QUANTITY_INCREMENT = 2;

		private $maxQuantityAllowedInCart = 10;

		function addProductToCart($productId, $quantity, $incrementType = 2){
			// $incrementType = (isset($incrementType) ? $incrementType : 2); // DEPRECATED
			$errorArr = array();
			if(isset($productId) && !empty($productId)){
				$productId = strip_tags($productId);
			} else {
				$errorArr[] = "ENTERPRODUCTID";
			}
			if(isset($quantity) && !empty($quantity)){
				$quantity = strip_tags($quantity);
			} else {
				$errorArr[] = "ENTERQUANTITY";
			}

			$statusMessage = '';
			if(count($errorArr)>0){
				$errorStr = implode("|", $errorArr);
				return array(
						"response" => false,
						"responseMsg" => "An error occurred while updating cart",
						"error" => $errorStr
						);
			} else {
				// prepare product to add in session
				$productArr = array(
						"productId" => $productId,
						"quantity" => $quantity,
					);

				if(isset($_SESSION[SITE_NAME]['cart'])){ // update cart session
					// $productIndex = array_search($productId, array_column($_SESSION[SITE_NAME]['cart'], 'productId')); // DEPRECATED
					$productIndex = $this->getCartIndex($productId);
					if($productIndex===false){ // product not in cart, add product to cart
						$_SESSION[SITE_NAME]['cart'][] = $productArr;
						$statusMessage = "Product added to cart";
					} else if($incrementType == self::$QUANTITY_UPDATE){
						if(($_SESSION[SITE_NAME]['cart'][$productIndex]['quantity']+$quantity)<=$this->maxQuantityAllowedInCart){
							$_SESSION[SITE_NAME]['cart'][$productIndex]['quantity']+=$quantity;
							$statusMessage = "Product quantity updated in cart";
						} else {
							$statusMessage = "You have already added ".$_SESSION[SITE_NAME]['cart'][$productIndex]['quantity']." quantity of this product to cart. You can add a maximum of ".$this->maxQuantityAllowedInCart." quantity. You can add ".($this->maxQuantityAllowedInCart - $_SESSION[SITE_NAME]['cart'][$productIndex]['quantity'])." more quantity of this product to cart.";
						}
					} else if($incrementType == self::$QUANTITY_INCREMENT){
						if(($_SESSION[SITE_NAME]['cart'][$productIndex]['quantity'] + 1)<=$this->maxQuantityAllowedInCart){
							$_SESSION[SITE_NAME]['cart'][$productIndex]['quantity']++;
							$statusMessage = "Product quantity updated in cart";
						} else {
							$statusMessage = "You have already added ".$_SESSION[SITE_NAME]['cart'][$productIndex]['quantity']." quantity of this product in cart. You can add a maximum of ".$this->maxQuantityAllowedInCart." quantity. You can add ".($this->maxQuantityAllowedInCart - $_SESSION[SITE_NAME]['cart'][$productIndex]['quantity'])." more quantity of this product to cart.";
						}
					}
				} else { // create cart session
					$_SESSION[SITE_NAME]['cart'] = array($productArr);
					$statusMessage = "Product added to cart";
				}

				return array(
						"response" => true,
						"responseMsg" => $statusMessage,
						"productArr" => $_SESSION[SITE_NAME]['cart'],
						);
			}
		}

		/*
		 * NOT YET PROPERLY TESTED
		 */
		function decrementProductFromCart($productId, $quantity, $incrementType = 2){
			$errorArr = array();
			if(isset($productId) && !empty($productId)){
				$productId = strip_tags($productId);
			} else {
				$errorArr[] = "ENTERPRODUCTID";
			}
			if(isset($quantity) && !empty($quantity)){
				$quantity = strip_tags($quantity);
			} else {
				$errorArr[] = "ENTERQUANTITY";
			}

			$statusMessage = '';
			if(count($errorArr)>0){
				$errorStr = implode("|", $errorArr);
				return array(
						"response" => false,
						"responseMsg" => "An error occurred while updating cart",
						"error" => $errorStr
						);
			} else {
				// prepare product to add in session
				$productArr = array(
						"productId" => $productId,
						"quantity" => $quantity,
					);

				if(isset($_SESSION[SITE_NAME]['cart'])){ // update cart session
					// $productIndex = array_search($productId, array_column($_SESSION[SITE_NAME]['cart'], 'productId')); // DEPRECATED
					$productIndex = $this->getCartIndex($productId);
					if($productIndex===false){ // product not in cart, add product to cart
						$statusMessage = "Product is not in cart";
					} else if($incrementType == self::$QUANTITY_UPDATE){
						if(($_SESSION[SITE_NAME]['cart'][$productIndex]['quantity'] - $quantity) >= 0){
							$_SESSION[SITE_NAME]['cart'][$productIndex]['quantity'] -= $quantity;
							$statusMessage = "Product quantity updated in cart";
						} else {
							$_SESSION[SITE_NAME]['cart'][$productIndex]['quantity'] = 0;
							$statusMessage = "Product removed from cart";
						}
					} else if($incrementType == self::$QUANTITY_INCREMENT){
						if(($_SESSION[SITE_NAME]['cart'][$productIndex]['quantity'] - 1) >= 0){
							$_SESSION[SITE_NAME]['cart'][$productIndex]['quantity']--;
							$statusMessage = "Product quantity updated in cart";
						} else {
							$_SESSION[SITE_NAME]['cart'][$productIndex]['quantity'] = 0;
							$statusMessage = "Product removed from cart";
						}
					}
				} else { // cart does not exists, session expired
					$statusMessage = "Your session has expired";
				}

				return array(
						"response" => true,
						"responseMsg" => $statusMessage,
						"productArr" => $_SESSION[SITE_NAME]['cart'],
						);
			}
		}

		function removeProductFromCart($productId){
			$errorArr = array();
			if(isset($productId) && !empty($productId)){
				$productId = strip_tags($productId);
			} else {
				$errorArr[] = "ENTERPRODUCTID";
			}

			$statusMessage = '';
			if(count($errorArr)>0){
				$errorStr = implode("|", $errorArr);
				return array(
						"response" => false,
						"responseMsg" => "An error occurred while updating cart",
						"error" => $errorStr
						);
			} else {
				if(isset($_SESSION[SITE_NAME]['cart'])){ // update cart session
					// $productIndex = array_search($productId, array_column($_SESSION[SITE_NAME]['cart'], 'productId')); // DEPRECATED
					$productIndex = $this->getCartIndex($productId);
					if($productIndex===false){ // product not in cart
						$statusMessage = "Product is not in cart";
					} else {
						unset($_SESSION[SITE_NAME]['cart'][$productIndex]);
						if(count($_SESSION[SITE_NAME]['cart'])<=0){
							unset($_SESSION[SITE_NAME]['cart']);
						}
						$statusMessage = "Product removed from cart";
					}
				} else { // cart does not exists, session expired
					$statusMessage = "Your session has expired";
				}

				return array(
						"response" => true,
						"responseMsg" => $statusMessage
						// "productArr" => $_SESSION[SITE_NAME]['cart'],
						);
			}
		}

		function setProductQuantityInCart($productId, $quantity){
			$errorArr = array();
			if(isset($productId) && !empty($productId)){
				$productId = strip_tags($productId);
			} else {
				$errorArr[] = "ENTERPRODUCTID";
			}
			if(isset($quantity) && !empty($quantity)){
				$quantity = strip_tags($quantity);
			} else {
				$errorArr[] = "ENTERQUANTITY";
			}
			/*if(isset($price_id) && !empty($price_id)){
				$price_id = strip_tags($price_id);
			} else {
				$errorArr[] = "ENTERPRICEID";
			}*/

			$statusMessage = '';
			if(count($errorArr)>0){
				$errorStr = implode("|", $errorArr);
				return array(
						"response" => false,
						"responseMsg" => "An error occurred while updating cart",
						"error" => $errorStr
						);
			} else {
				// prepare product to add in session
				$productArr = array(
					"productId" => $productId,
					"quantity" => $quantity,
					//"price_id" => $price_id,
				);

				if(isset($_SESSION[SITE_NAME]['cart'])){ // update cart session
					// print_r(array_column($_SESSION[SITE_NAME]['cart'], 'productId')); // TEST

					// $productIndex = array_search($productId, array_column($_SESSION[SITE_NAME]['cart'], 'productId')); // DEPRECATED
					$productIndex = $this->getCartIndex($productId);

					if($productIndex===false){ // product not in cart, add product to cart
						$_SESSION[SITE_NAME]['cart'][] = $productArr;
						$statusMessage = "Product added to cart";
					} else {
						$_SESSION[SITE_NAME]['cart'][$productIndex]['quantity'] = $quantity;
						$statusMessage = "Product quantity updated in cart";
					}
				} else { // create cart session
					$_SESSION[SITE_NAME]['cart'] = array($productArr);
					$statusMessage = "Product added to cart";
				}

				return array(
						"response" => true,
						"responseMsg" => $statusMessage,
						"productArr" => $_SESSION[SITE_NAME]['cart'],
						);
			}
		}

		function getCart(){
			if(isset($_SESSION[SITE_NAME]['cart'])){
				return $_SESSION[SITE_NAME]['cart'];
			} else {
				return false;
			}
		}
		function getCartProductCount(){
			if(isset($_SESSION[SITE_NAME]['cart'])){
				return count($_SESSION[SITE_NAME]['cart']);
			} else {
				return 0;
			}
		}

		function getCartIndex($productId){
			if(isset($_SESSION[SITE_NAME]['cart'])){
				foreach($_SESSION[SITE_NAME]['cart'] as $key => $oneProduct){

					if($oneProduct['productId'] == $productId){
						return $key;
					}

					/*if(!empty($prod_color)){
						if($oneProduct['productId'] == $productId and $oneProduct['prod_color']==$prod_color){
							return $key;
						}
					} else {

						if($oneProduct['productId'] == $productId ){
							return $key;
						}
					}*/					
				}
			}
			return false;
		}

		function getCartIndexByProductId($productId){
			if(isset($_SESSION[SITE_NAME]['cart'])){
				foreach($_SESSION[SITE_NAME]['cart'] as $key => $oneProduct){
					if($oneProduct['productId'] == $productId){
						return $key;
					}
				}
			}
			return false;
		}
		
		function getProductByProductId($productId){
			$cartProductIndex = $this->getCartIndexByProductId($productId);
			if($cartProductIndex!==false){
				return $_SESSION[SITE_NAME]['cart'][$cartProductIndex];
			} else {
				return false;
			}
		}

		function getProductQuantity($productId){
			if(isset($_SESSION[SITE_NAME]['cart'])){
				foreach($_SESSION[SITE_NAME]['cart'] as $key => $oneProduct){
					if($oneProduct['productId'] == $productId){
						return $oneProduct['quantity'];
					}
				}
			}
			return false;
		}

		function clearEntireCart(){
			if(isset($_SESSION[SITE_NAME]['cart'])){
				unset($_SESSION[SITE_NAME]['cart']);
			}
		}
	}
?>