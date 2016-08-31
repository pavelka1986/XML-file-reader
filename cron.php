<?php

global $wpdb, $table_prefix;

  if(!isset($wpdb))
  {
  require_once('../wp-config.php');
  require_once('../wp-includes/wp-db.php');
  }
  include 'adcell.php';
  include 'affili.php';  
 
  
if($_GET['do'] == "1")
{  
  $adcell = new Adcell;
  $adcell->shortinsertData();
  
  $affili = new Affili;
  $affili->shortinsertData("http://product-api.affili.net/V3/productservice.svc/XML/SearchProducts?publisherId=111111&Password=999999999&query=prozent",1);
  $affili->shortinsertData("http://product-api.affili.net/V3/productservice.svc/XML/SearchProducts?publisherId=111111&Password=999999999&query=percent",1);
  $affili->shortinsertData("http://product-api.affili.net/V3/productservice.svc/XML/SearchProducts?publisherId=111111&Password=999999999&query=rabatt",1);
  $affili->shortinsertData("http://product-api.affili.net/V3/productservice.svc/XML/SearchProducts?publisherId=111111&Password=999999999&query=rabatte",1); 
  
  $affili->shortinsertData("http://product-api.affili.net/V3/productservice.svc/XML/SearchProducts?publisherId=111111&Password=999999999&query=gutschein",2);
  $affili->shortinsertData("http://product-api.affili.net/V3/productservice.svc/XML/SearchProducts?publisherId=111111&Password=999999999&query=voucher",2);    
  
  $affili->shortinsertData("http://product-api.affili.net/V3/productservice.svc/XML/SearchProducts?publisherId=111111&Password=999999999&query=gewinnspiele",3);
  $affili->shortinsertData("http://product-api.affili.net/V3/productservice.svc/XML/SearchProducts?publisherId=111111&Password=999999999&query=gewinnspiel",3);
    
  mail('pavelka1986@gmail.com', 'Schwottenalarm', 'Cron erflogreich');
}


?>

      
