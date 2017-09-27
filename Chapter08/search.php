<?php
header('Content-Type: application/json');
// header('AMP-Access-Control-Allow-Source-Origin: http://localhost');

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: https://theampbook-com.cdn.ampproject.org');
header('Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin');
header('AMP-Access-Control-Allow-Source-Origin: https://theampbook.com');


//keywords used for actual search
$keywords = isset($_REQUEST["keywords"])?$_REQUEST["keywords"]:"";
$keywords = strtolower(filter_var($keywords, FILTER_SANITIZE_STRING));

//q used for autosuggest
$q = isset($_REQUEST["q"])?$_REQUEST["q"]:"";
$sort = isset($_REQUEST["sort"])?$_REQUEST["sort"]:"";
$filter = isset($_REQUEST["filter"])?$_REQUEST["filter"]:"";


$products = array(
              array(
                "name"=>"T-shirt: Super Pouvoir",
                "price"=>9.99,
                "color"=>"red",
                "description"=>"Super cool super power t-shirt",
                "image"=>"img/tshirt-0-f-red-320.png"
                ),           
              array(
                "name"=>"T-shirt: La Biere",
                "price"=>10.99,
                "color"=>"black",
                "description"=>"Beer t-shirt",
                "image"=>"img/tshirt-10-f-black-320.png"
              ),
              array(
                "name"=>"T-shirt: Les Pizzas",
                "price"=>7.99,
                "color"=>"blue",
                "description"=>"Je deteste courier, mais j'aime trop pizza t-shirt",
                "image"=>"img/tshirt-5-f-blue-320.png"
              ),
              array(
                "name"=>"T-shirt: Ma Tete",
                "price"=>6.99,
                "color"=>"green",
                "description"=>"Don\'t judge me when I run t-shirt",
                "image"=>"img/tshirt-6-f-green-320.png"
              ),
              array(
                "name"=>"T-shirt: Les Pizzas",
                "price"=>6.99,
                "color"=>"yellow",
                "description"=>"Je deteste courier, mais j'aime trop pizza t-shirt",
                "image"=>"img/tshirt-5-f-yellow-320.png"
              ),
              array(
                "name"=>"T-shirt: Super Pouvoir",
                "price"=>10.99,
                "color"=>"red",
                "description"=>"Super cool super power t-shirt",
                "image"=>"img/tshirt-0-m-red-320.png"
                ) ,
              array(
                "name"=>"T-shirt: Super Pouvoir",
                "price"=>9.99,
                "color"=>"blue",
                "description"=>"Super cool super power t-shirt",
                "image"=>"img/tshirt-0-f-blue-320.png"
                ),
              array(
                "name"=>"T-shirt: Super Pouvoir",
                "price"=>11.99,
                "color"=>"blue",
                "description"=>"Super cool super power t-shirt",
                "image"=>"img/tshirt-0-m-blue-320.png"
                ),                                        

            );

//Autosuggest
if(!empty($q) && !in_array($q, array('null','none'))) {
  $q = preg_replace("/[^A-Za-z0-9 ]/", '', $q);
  foreach ($products as $product) {
    $names[] = $product['name'];
  }

  $names = array_unique($names);
  $results = array_values(array_filter($names, function($el) use ($q) {
    return (stripos(preg_replace("/[^A-Za-z0-9 ]/", '', $el), $q) !== false);
  }));

  $content = '{"items": [{"results":'.json_encode($results).'}]}';
  header("HTTP/1.0 200 Ok");
  echo $content;
  exit;
}

// Sort by price
if(!empty($sort) && !in_array($sort, array('null','none'))) {
  usort($products, function ($item1, $item2) use($sort) {
    if ($item1['price'] == $item2['price']) return 0;
    if($sort=='up') return $item1['price'] < $item2['price'] ? -1 : 1;
    else return $item1['price'] < $item2['price'] ? 1 : -1;
  });
}


// Filter by color
if(!empty($filter) && !in_array($filter, array('null','none'))) {
  $filtered = array();
  foreach ($products as $product) {
    if($product['color']==$filter) {
      $filtered[] = $product;
    }
  }
  $products = $filtered;
}

// Perform search
if(!empty($keywords) && !in_array($keywords, array('null','none'))) {
  $keywords = preg_replace("/[^A-Za-z0-9 ]/", '', $keywords);
  $results = array();
  foreach ($products as $product) {
    if(strripos(preg_replace("/[^A-Za-z0-9 ]/", '', $product['name']), $keywords)!==false) {
      $results[] = $product;
    }
  }
  $content = '{"items": [{"keywords":"'.$keywords .'", "results":'.json_encode($results).'}]}';
}

// Nothing to show
else {
  $content = '{"items": [{"keywords":"'.$keywords .'","empty":"true"}]}';
}

header("HTTP/1.0 200 Ok");
echo $content;
