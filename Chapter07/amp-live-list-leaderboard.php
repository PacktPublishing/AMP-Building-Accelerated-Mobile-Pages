<!-- Server version of amp-live-list-leaderboard.html -->
<!-- This script simulates server updates based on the number of requests -->
<!-- Clear cookies to reset -->
<!doctype html>
<html ⚡>
  <head>
    <meta charset="utf-8">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <link rel="canonical" href="https://theampbook.com/ch7/amp-live-list-leaderboard.php" />
    <title>The AMP Book</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
      <script async custom-element="amp-live-list" src="https://cdn.ampproject.org/v0/amp-live-list-0.1.js"></script>

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

      div[items] div {
        width:300px;
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
        text-align: left;
      }
      div[items] span {
        width: 40px;
        text-align: center;
        display: inline-block;
        height:100%;
        color: #fff;
        background-color: #253b48; 
      }

      #item-6 {
        border:0;
        text-align: center;
      }

    </style>

  </head>
  <body class="main">
    <h3>AMP Grand Prix Leaderboard</h3>


    <amp-live-list id="leaderboard" data-poll-interval="15000" data-max-items-per-page="10">
      <div update on="tap:leaderboard.update" role="" tabindex=""></div>
        <div items>
          <?php if(!empty($_COOKIE['amp_live_list_request']) && $_COOKIE['amp_live_list_request']==='1') {
              //Update the cookie 
              setcookie('amp_live_list_request', '2', time()+60, '/');
              ?>
              <!-- 2nd request -->
              <div id="item-1" data-sort-time="1" data-update-time="1"><span>1</span> &#x1F1E9;&#x1F1EA; Sebastian Vettel</div>
              <div id="item-2" data-sort-time="2" data-update-time="1"><span>2</span> &#x1F1EB;&#x1F1F7; Esteban Ocon</div>
              <div id="item-3" data-sort-time="3" data-update-time="1"><span>3</span> &#x1F1EC;&#x1F1E7; Lewis Hamilton</div>
              <div id="item-4" data-sort-time="4" data-update-time="2"><span>4</span> &#x1F1EE;&#x1F1EA; Ruadhan O'Donoghue</div>
              <div id="item-5" data-sort-time="5" data-update-time="2"><span>5</span> &#x1F1EB;&#x1F1EE; Kimi Räikkönen</div>
              <div id="item-6" data-sort-time="6" data-update-time="1"> </div>
          <?php exit;
          } else if(!empty($_COOKIE['amp_live_list_request']) && $_COOKIE['amp_live_list_request']==='2') {
              //Update the cookie 
              setcookie('amp_live_list_request', '3', time()+60, '/');
              ?>
              <!-- 3rd request -->
              <div id="item-1" data-sort-time="1" data-update-time="3"><span>1</span> &#x1F1EE;&#x1F1EA; Ruadhan O'Donoghue</div>
              <div id="item-2" data-sort-time="2" data-update-time="3"><span>2</span> &#x1F1E9;&#x1F1EA; Sebastian Vettel</div>
              <div id="item-3" data-sort-time="3" data-update-time="3"><span>3</span> &#x1F1EB;&#x1F1F7; Esteban Ocon</div>
              <div id="item-4" data-sort-time="4" data-update-time="3"><span>4</span> &#x1F1EC;&#x1F1E7; Lewis Hamilton</div>
              <div id="item-5" data-sort-time="5" data-update-time="2"><span>5</span> &#x1F1EB;&#x1F1EE; Kimi Räikkönen</div>
              <div id="item-6" data-sort-time="6" data-update-time="3"> FINAL RESULT</div>
          <?php } else {
              //Set the cookie 
              setcookie('amp_live_list_request', '1', time()+60, '/');
              ?>         
              <!-- First request -->
              <div id="item-1" data-sort-time="1" data-update-time="1"><span>1</span> &#x1F1E9;&#x1F1EA; Sebastian Vettel</div>
              <div id="item-2" data-sort-time="2" data-update-time="1"><span>2</span> &#x1F1EB;&#x1F1F7; Esteban Ocon</div>
              <div id="item-3" data-sort-time="3" data-update-time="1"><span>3</span> &#x1F1EC;&#x1F1E7; Lewis Hamilton</div>
              <div id="item-4" data-sort-time="4" data-update-time="1"><span>4</span> &#x1F1EB;&#x1F1EE; Kimi Räikkönen</div>
              <div id="item-5" data-sort-time="5" data-update-time="1"><span>5</span> &#x1F1EE;&#x1F1EA; Ruadhan O'Donoghue</div>
              <div id="item-6" data-sort-time="6" data-update-time="1"> </div>            
          <?php } ?>

        </div>
      </div>
    </amp-live-list>

</body>
</html>