<?php

global $wpdb, $table_prefix;
class Affili
{
		private static $instance;
		public $file;
		public $xml;
		public $files;
		public function __construct()
		{
      
		}
		public function update_items($test = false)
		{
		}
    
    public function createMap($str){

    	$str = strip_tags($str);
    
    	$XFilter = array(
    		"А" => "а", "Б" => "б", "В" => "в", "Г" => "г", "Д" => "д", "Е" => "е", "Ж" => "ж", "З" => "з", "И" => "и", "Й" => "и", 
    		"К" => "к", "Л" => "л", "М" => "м", "Н" => "н", "О" => "о", "П" => "п", "Р" => "р", "С" => "с", "Т" => "т", "У" => "у", 
    		"Ф" => "ф", "Х" => "х", "Ц" => "ц", "Ч" => "ч", "Ш" => "ш", "Щ" => "щ", "Ъ" => "ъ", "Ы" => "ы", "Ь" => "ь", "Э" => "э", 
    		"Ю" => "ю", "Я" => "я", "й" => "и",
    
    		"ě" => "e", "š" => "s", "č" => "c","ř" => "r", "ž" => "z","ý" => "y","á" => "a","í" => "i","é" => "e","ů" => "u","ü" => "u",
    		"ú" => "u","ó" => "o","ö" => "o","ň" => "n","ń" => "n","ć" => "c", "ë" => "e","ä" => "a","ď" => "d","ľ" => "l", "ť" => "t",
    		"ß" => "ss",
    		"Ě" => "E", "Š" => "S", "Č" => "C","Ř" => "R", "Ž" => "Z","Ý" => "Y","Á" => "A","Í" => "I","É" => "E","Ů" => "U","Ü" => "U",
    		"Ú" => "U","Ó" => "O","Ö" => "O","Ň" => "N","Ń" => "N","Ć" => "C", "Ë" => "E","Ä" => "A","Ď" => "D","Ľ" => "L", "Ť" => "T"
    		) ;
    
    	$str = strtr($str,$XFilter);
    	
    	if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') ){
    		$str = mb_convert_encoding($str, 'UTF-8');
    	}
    	$str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
    	$str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\\1', $str);
    	$str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
    	$str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
    	$str = mb_strtolower( trim($str, '-') );
    	
    	return $str;
}
    
		public function insertExists($gid)
		{
				/** If insert Exists returns true
        * @param gid
        * @return true
        */
        global $wpdb;
				$ie = $wpdb->get_results("SELECT gid FROM wp_posts WHERE gid = ".$gid."");
				if (count($ie) != 0) {
						return true;
				}
		}
    public function getlastinsertId()
		{
				/** If insert Exists returns true
        * @param gid
        * @return true
        */
        global $wpdb;
				$ie = $wpdb->get_results("SELECT ID FROM `wp_posts` ORDER BY `ID` DESC LIMIT 1");
        foreach ( $ie as $_ie ){        
          return $_ie->ID + 1;
        }
		}
    public function getlastId($gid)
		{
				/** If insert Exists returns true
        * @param gid
        * @return true
        */
        global $wpdb;
				$ie = $wpdb->get_results("SELECT ID,gid FROM `wp_posts` WHERE gid = ".$gid."");
        
        foreach ( $ie as $_ie ){        
          return $_ie->ID;
        }
		}
    public function gettermsSlug($name)
		{
				/** If insert Exists returns true
        * @param gid
        * @return true
        */
        global $wpdb;
				$ie = $wpdb->get_results("SELECT name,term_id,slug FROM `wp_terms` WHERE slug='".$name."'");
        foreach ( $ie as $_ie ){        
          return $_ie->term_id;
        }
		}
    
    public function gettermstaxonomyId($id)
		{
				/** If insert Exists returns true
        * @param gid
        * @return true
        */
        global $wpdb;
				$ie = $wpdb->get_results("SELECT term_taxonomy_id,term_id FROM `wp_term_taxonomy` WHERE term_id='".$id."'");
        foreach ( $ie as $_ie ){        
          return $_ie->term_taxonomy_id;
        }
		}
    
    public function test($feedurl)
    {
        	global $wpdb;
						@$xml = simplexml_load_file($feedurl);
						if (is_object($xml)) {            								
                foreach ($xml->Products->Product as $product) {                    
									echo $product->ProductName."<br />";                        
								}
            } 
    }
    
    
		public function shortinsertData($feedurl,$mode)
		{
				/** Insert data from xml to database
        * @param
        * @return
        */
				global $wpdb;
						@$xml = simplexml_load_file($feedurl);
						if (is_object($xml)) {
								
                foreach ($xml->Products->Product as $product) {
                    
									if ($this->insertExists((int) $product->ProductId)) {
												//echo $q0 = "<b>".$product->ProductName."</b><br /><hr />";                        
										}
										else {											
												/* INSERTING NEW POSTS*/
                        $q = "INSERT INTO wp_posts (id,gid,post_title,post_content,post_date,post_date_gmt,post_author,post_type,post_status,
                        comment_status,ping_status,post_name,gvon,gbis,gcat,gcode,glink,plogo)";
												$q .= "VALUES(
                        '".$this->getlastinsertId()."',
                        '".(int) $product->ProductId."',
                        '".$product->ProductName."',
                        '".$product->DescriptionShort."',
                        '".date("Y-m-d H:m:s")."',
                        '".date("Y-m-d H:m:s")."',
                        '1',
                        'post',
                        'publish',
                        'open',
                        'open',
                        '".$this->createMap($product->ProductName.$product->ProductId)."',
                        '',
                        '',
                        '".$product->ShopCategoryId."',
                        '".$product->EAN."',
                        '".$product->Deeplink1."',
                        '' 
                        );";
                        //echo $q."<br /><br />";												
                        $wpdb->query($q);
                        
                        switch ($mode) {
                                case 1:
                                    /*rabatt*/
                                    $cts = 169;
                                    break;
                                case 2:
                                    /*gutschein*/
                                    $cts = 5;
                                    break;
                                case 3:
                                    /*gewinnspiel*/
                                    $cts = 856;
                                    break;                                    
                            }
                        
                        
                        /* INSERTING POSTS TO CATEGORY GUTSCHEIN*/
                        $q1 = "INSERT INTO wp_term_relationships (object_id,term_taxonomy_id) VALUES (".$this->getlastId($product->ProductId).",".$cts.");";
                        //echo $q1."<br /><br />";                        
                        $wpdb->query($q1);
                        
                        /* INSERTING Terms*/
                        $q2 = "INSERT INTO wp_terms (name,slug) VALUES";
                        $q2 .= " ('".$product->ProductName."','".$this->createMap($product->ProductName)."'),";
                        $q2 .= " ('".$product->ProductName." Gutschein','".$this->createMap($product->ProductName." Gutschein")."'),";
                        $q2 .= " ('".$product->ProductName." Gutschein ".date('Y')."','".$this->createMap($product->ProductName." Gutschein ".date('Y')."")."'),";
                        $q2 .= " ('".$product->ProductName." ".$product->EAN."','".$this->createMap($product->ProductName." ".$product->EAN.date('dmY'))."')";
                        $q2 .= ";"; 
                        //echo $q2."<br /><br />";                        
                        $wpdb->query($q2);
                        
                        
                        /* INSERTING Terms*/
                        $q3 = "INSERT INTO wp_term_taxonomy (term_id,taxonomy) VALUES";
                        $q3 .= " ('".$this->gettermsSlug($this->createMap($product->ProductName))."','post_tag'),";
                        $q3 .= " ('".$this->gettermsSlug($this->createMap($product->ProductName." Gutschein"))."','post_tag'),";
                        $q3 .= " ('".$this->gettermsSlug($this->createMap($product->ProductName." Gutschein ".date('Y')))."','post_tag'),";
                        $q3 .= " ('".$this->gettermsSlug($this->createMap($product->ProductName." ".$product->EAN.date('dmY')))."','post_tag')";
                        $q3 .= ";";  
                        //echo $q3."<br /><br />";
                        $wpdb->query($q3);
                        
                        
                        /* INSERTING Terms*/
                        $q4 = "INSERT INTO wp_term_relationships (object_id,term_taxonomy_id) VALUES";
                        $q4 .= " ('".$this->getlastId($product->ProductId)."','".$this->gettermstaxonomyId($this->gettermsSlug($product->ProductName))."'),";
                        $q4 .= " ('".$this->getlastId($product->ProductId)."','".$this->gettermstaxonomyId($this->gettermsSlug($this->createMap($product->ProductName." Gutschein")))."'),";
                        $q4 .= " ('".$this->getlastId($product->ProductId)."','".$this->gettermstaxonomyId($this->gettermsSlug($this->createMap($product->ProductName." Gutschein ".date('Y'))))."'),";
                        $q4 .= " ('".$this->getlastId($product->ProductId)."','".$this->gettermstaxonomyId($this->gettermsSlug($this->createMap($product->ProductName." ".$product->EAN.date('dmY'))))."'),";
                        $q4 .= " ('".$this->getlastId($product->ProductId)."','9'),";
                        $q4 .= " ('".$this->getlastId($product->ProductId)."','10')";
                        $q4 .= ";";  
                        //echo $q4."<br /><br />";
                        $wpdb->query($q4);
                        
                         //$wpdb->show_errors(); 
                        
                        //echo "<hr />";
                        
										}
								}
						}
						else {
								//echo "chyba";
						}
           
          
		}
    
    
		 
    
}               