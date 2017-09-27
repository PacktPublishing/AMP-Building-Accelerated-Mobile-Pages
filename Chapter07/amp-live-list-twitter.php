<?php 
  require_once 'inc/codebird/src/codebird.php';
  \Codebird\Codebird::setConsumerKey('', '');
  $cb = \Codebird\Codebird::getInstance();
  $cb->setToken('', '');

  $query = isset($_GET['q'])?$_GET['q']:'';

  $params = array(
      'q' => $query,
      'count' => 10,
      'lang' => 'en',
      'result_type' => 'recent'
  );


  if(!empty($query)) {
    $used_ids = array();
    $response = (array) $cb->search_tweets($params);
    $data = (array) $response['statuses'];
    $total = count($data);
  }

?><!doctype html>
<html ⚡>
  <head>
    <meta charset="utf-8">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <link rel="canonical" href="https://theampbook.com/ch7/amp-live-list-twitter.php" />
    <title>The AMP Book</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
      <script async custom-element="amp-live-list" src="https://cdn.ampproject.org/v0/amp-live-list-0.1.js"></script>
      <script async custom-element="amp-twitter" src="https://cdn.ampproject.org/v0/amp-twitter-0.1.js"></script>
      <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>

    <style amp-custom>
      .main { 
        width:100%;
        margin: 0 auto;
        text-align: center;
        font-family: Helvetica;
      }

      h3 {
        color: #253b48;  
        margin: 10px 0 0 0;
      }

      button, input{
        width:140px;
        line-height: 40px;
        vertical-align: middle;
        color: white;
        font-weight: bold;
        font-size: 16px;
        background: none;
        border: 2px solid #333;
        color: #253b48;      
        margin:10px auto;  
        padding:0;
      }

      input {
        padding: 0 5px;
        box-sizing: content-box;
        -webkit-appearance: none;
        border-radius:0;
      }

      input[type="submit"] {
        color:#fff;
        background-color: #253b48;
      }

      .update {
        width:270px;
        text-transform: uppercase;
      }

    </style>

  </head>
  <body class="main">

    <form action="amp-live-list-twitter.php" method="get" target="_top">
      <h3>Search Twitter</h3>
       <input type="text" name="q" placeholder="KEYWORD" <?=empty($query)?'':'value="'.$query.'"'?>>
      <input type="submit" value="SEARCH">
    </form>

    <amp-live-list id="tweet-list" data-poll-interval="15000" data-max-items-per-page="20">
      <button update on="tap:tweet-list.update" class="update">New tweets available</button>
      <div items>
      <?php
        if(!empty($query)) {
          for($i=0;$i<$total && $i<5;$i++) {
            // Get 5 tweets, and filter out retweets
            if (isset($data[$i]->retweeted_status)) {
              $id = $data[$i]->retweeted_status->id;
              $posted = $data[$i]->retweeted_status->created_at;
            }
            else {
              $id = $data[$i]->id;
              $posted = $data[$i]->created_at;
            }

            if(!array_key_exists($id, $used_ids)) {
              $timestamp = strtotime($posted);
              $used[$id]  = array('timestamp'=>$timestamp, 'index'=>$i);
            }
          }

          foreach ($used as $item) {
            $index = $item['index'];
            $timestamp = $item['timestamp'];
            ?>
            <amp-twitter id="item-<?=$data[$index]->id?>" data-sort-time="<?=$timestamp?>" 
               width="640" height="480" layout="responsive" data-tweetid="<?=$data[$index]->id?>">
            </amp-twitter>
        <?php } 
          }
        ?>
      </div>

    </amp-live-list>


  </body>
</html>