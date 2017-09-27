<?php

header('Content-Type: application/json');
//header('AMP-Access-Control-Allow-Source-Origin: http://localhost');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: https://theampbook-com.cdn.ampproject.org');
header('Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin');
header('AMP-Access-Control-Allow-Source-Origin: https://theampbook.com');
?>{
  "items":[
    {"results":[
      {
        "product_id":"tshirt-2",
        "name":"T-Shirt: La Bierre",
        "price":"€9.99",
        "description":"French running T-Shirt, with beer graphic",
        "image":"img/tshirt-10-f-black-320.png",
        "url":"..."
      },
      {
        "product_id":"tshirt-3",
        "name":"T-Shirt: La Bierre",
        "price":"€9.99",
        "description":"French running T-Shirt, with beer graphic",
        "image":"img/tshirt-10-f-green-320.png",
        "url":"..."
      },
      {
        "product_id":"tshirt-4",
        "name":"T-shirt: Ma Tete",
        "price":"€7.99",
        "description":"Don't judge me when I run t-shirt",
        "image":"img/tshirt-6-f-blue-320.png",
        "url":"..."
      },
      {
        "product_id":"tshirt-5",
        "name":"T-shirt: Les Pizzas",
        "price":"€7.99",
        "description":"Je deteste courier, mais j'aime trop pizza t-shirt",
        "image":"img/tshirt-5-f-yellow-320.png",
        "url":"..."
      }

    ]
    }
  ]
}