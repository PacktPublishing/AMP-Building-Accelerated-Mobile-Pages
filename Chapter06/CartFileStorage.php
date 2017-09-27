<?php

class CartFileStorage {
    // Implements a simple file-based shopping cart
  

    public static function addToCart($clientId, $productId, $productName, $price, $quantity=1, $image='') {
      $clientId = filter_var($clientId, FILTER_SANITIZE_STRING);
      $productId = filter_var($productId, FILTER_SANITIZE_STRING);
      $productName = filter_var($productName, FILTER_SANITIZE_STRING);
      $price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT);
      $image = filter_var($image, FILTER_SANITIZE_STRING);

      $filename = self::buildFilename($clientId);
      if(file_exists($filename)) {
        $cartJson = file_get_contents($filename);
      }
      else $cartJson=FALSE;

      //If cart exists
      if($cartJson===FALSE) {
        $cart = array(
          'cart_total_price' => round($price * $quantity, 2),
          'cart_items' => array(
              $productId => array(
                'product_name' => $productName,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $image
              )
          )
        );
      } 
      else {
        $cart = json_decode($cartJson, true);
        $cart['cart_total_price'] += round($price * $quantity, 2);

        //Check if we have this product already
        if(array_key_exists($productId, $cart['cart_items'])) {
          $cart['cart_items'][$productId]['quantity'] += $quantity;
        }
        else {
          $cart['cart_items'][$productId] = array(
            'product_name' => $productName,
            'price' => $price,
            'quantity' => $quantity,
            'image' => $image);
        }
      }

      file_put_contents($filename, json_encode($cart));

      return json_encode(self::convertData($cart));
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
        'cart_items' => $cartItems
      );

      return $cartData;
    }


}