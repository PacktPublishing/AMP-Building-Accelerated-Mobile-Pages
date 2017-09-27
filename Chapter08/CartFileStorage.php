<?php

//CartFileStorage::buildFilename('amp-kpuIh2IWilIOEy7Yo8V2OX7rtd0WE-qHbctOcMBoj_fi3gMXaBN2vNcWlxyasgdh');

// $cart = CartFileStorage::addToCart('amp-kpuIh2IWilIOEy7Yo8V2OX7rtd0WE-qHbctOcMBoj_fi3gMXaBN2vNcWlxyasgdh', 'tshirt-2', 'T-Shirt: Super Pouvoir', '1.99', 2);

// var_dump($cart);


class CartFileStorage {
  

    public static function addToCart($clientId, $productId, $productName, $price, $quantity=1, $image='', $size='') {
      $clientId = filter_var($clientId, FILTER_SANITIZE_STRING);
      $productId = filter_var($productId, FILTER_SANITIZE_STRING);
      $productName = filter_var($productName, FILTER_SANITIZE_STRING);
      $price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT);
      $size = filter_var($size, FILTER_SANITIZE_STRING);
      $image = filter_var($image, FILTER_SANITIZE_STRING);


      if(empty($image)) {
        $image = 'img/'.preg_replace('/-'.$size.'$/', '', $productId).'-120.png';
      }
      if(!empty($size)) $productName .= ' ('.strtoupper($size).')';

      $filename = self::buildFilename($clientId);
      if(file_exists($filename)) {
        $cartJson = file_get_contents($filename);
      }
      else $cartJson=FALSE;

      //If cart doesnt exist
      if($cartJson===FALSE) {
        $cart = array(
          'cart_total_price' => round($price * $quantity, 2),
          'cart_total_items' => $quantity,
          'cart_items' => array(
              $productId => array(
                'product_name' => $productName,
                'price' => $price,
                'size' => $size,
                'quantity' => $quantity,
                'image' => $image
              )
          )
        );
      } 
      else {
        $cart = json_decode($cartJson, true);
        $cart['cart_total_price'] += round($price * $quantity, 2);
        $cart['cart_total_items'] += $quantity;

        //Check if we have this product already
        if(array_key_exists($productId, $cart['cart_items'])) {
          $cart['cart_items'][$productId]['quantity'] += $quantity;

          //Move to front
          $cartItems = $cart['cart_items'];
          $cartItems = array($productId => $cartItems[$productId]) + $cartItems;
          $cart['cart_items'] = $cartItems;
        }
        else {
          // $cart['cart_items'][$productId] = array(
          //   'product_name' => $productName,
          //   'price' => $price,
          //   'quantity' => $quantity,
          //   'image' => $image);
          //Prepend newest item
          $cart['cart_items'] = array($productId => array(
            'product_name' => $productName,
            'price' => $price,
            'quantity' => $quantity,
            'size'  => $size,
            'image' => $image)) + $cart['cart_items'];
        }
      }

      file_put_contents($filename, json_encode($cart));

      return json_encode(self::convertData($cart));
    }

    public static function removeFromCart($clientId, $productId) {
      $clientId = filter_var($clientId, FILTER_SANITIZE_STRING);
      $productId = filter_var($productId, FILTER_SANITIZE_STRING);

      $filename = self::buildFilename($clientId);
      if(file_exists($filename)) {
        $cartJson = file_get_contents($filename);
      }
      else $cartJson=FALSE;

      //If cart exists
      if($cartJson===FALSE) {
        return '{"cart":[{"cart_total_price":0,"cart_total_items":0,"cart_items":[]}]}';
      } 

      $cart = json_decode($cartJson, true);

      //Do we have this product in cart?
      if(isset($cart['cart_items'][$productId])) {
        //Calc the price of quantity*item to remove & subtract from total
        $price_change = $cart['cart_items'][$productId]['price'] * $cart['cart_items'][$productId]['quantity'];

        $cart['cart_total_price'] -= $price_change;
        $cart['cart_total_items'] -= $cart['cart_items'][$productId]['quantity'];

        //Remove the product
        unset($cart['cart_items'][$productId]);

        //Write back to storage (save the cart state)
        file_put_contents($filename, json_encode($cart));
      }

      return json_encode(self::wrapCart(self::convertData($cart)));
    }

    public static function emptyCart($clientId) {
      $clientId = filter_var($clientId, FILTER_SANITIZE_STRING);
      $filename = self::buildFilename($clientId);
      file_put_contents($filename, '{"cart":[{"cart_total_price":0,"cart_total_items":0,"cart_items":[]}]}');
    }

    // Deprecated
    public static function getCartItems($clientId){
      $filename = self::buildFilename($clientId);
      if(file_exists($filename)) {
        $cartJson = file_get_contents($filename);
      }
      else $cartJson=FALSE;

      //If cart doesn't exists
      if($cartJson===FALSE) {
        return '{"cart_items":[]}';
      } 
      else {
        $cart = json_decode($cartJson, true);
      }

      return json_encode(self::convertData($cart));
    }

    public static function getCart($clientId){
      $filename = self::buildFilename($clientId);
      if(file_exists($filename)) {
        $cartJson = file_get_contents($filename);
      }
      else $cartJson=FALSE;

      //If cart doesn't exist
      if($cartJson===FALSE) {
        return '{"cart":[{"cart_total_price":0,"cart_total_items":0,"cart_items":[]}]}';
      } 
      else {
        $cart = json_decode($cartJson, true);
      }

      return json_encode(self::wrapCart(self::convertData($cart)));
    }


    private static function buildFilename($clientId) {
      $filename = filter_var($clientId, FILTER_SANITIZE_STRING);
      if(strpos($filename, 'amp-')===0) $filename = substr($filename, 4);
      $filename = md5($filename);      
      $dir = '/tmp/amp/'.$filename[0];
      if(!is_dir($dir)) mkdir($dir, 0777, true);
      return $dir.'/'.$filename;
    }

    private static function convertData($cart) {
      $cartItems = array();
      foreach($cart['cart_items'] as $productId => $itemData) {
        $cartItems[] = array_merge(array('product_id' => $productId), $itemData);
      }

      $cartData = array(
        'cart_total_price' => $cart['cart_total_price'],
        'cart_total_items' => $cart['cart_total_items'],
        'cart_items' => $cartItems
      );

      return $cartData;
    }

    private static function wrapCart($cartData) {
      return array('cart' => array($cartData));
    }


}