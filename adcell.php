<?php

global $wpdb, $table_prefix;
class Adcell
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
    
		public function shortinsertData()
		{
				/** Insert data from xml to database
        * @param
        * @return
        */
				global $wpdb;
						@$xml = simplexml_load_file("http://www.adcell.de/affiliate-gutscheine-xml.php?uname=104232&pass=20adcell10");
            //@$xml = simplexml_load_file("http://www2.schwottenalarm.de/cron/adcell.xml");
						if (is_object($xml)) {
								
                foreach ($xml->gutschein as $gutscheine) {
                    
									if ($this->insertExists((int) $gutscheine->gid)) {
												//$q = "UPDATE wp_posts SET post_title = '".$gutscheine->pname."',post_content = '".$gutscheine->ginfo."' WHERE gid = ".(int) $gutscheine->gid.";";
												$q0 = "<b>".$gutscheine->pname."</b>";
                        //echo $q0 = "UPDATE wp_posts SET gvon = '".date('Y-m-d H:m:s',strtotime($gutscheine->gvon))."' WHERE gid = ".(int) $gutscheine->gid.";";
                        //echo $q0."<br />";
                        
                        //$wpdb->query($q0);
										}
										else {											
												/* INSERTING NEW POSTS*/
                        $q = "INSERT INTO wp_posts (id,gid,post_title,post_content,post_date,post_date_gmt,post_author,post_type,post_status,
                        comment_status,ping_status,post_name,gvon,gbis,gcat,gcode,glink,plogo)";
												$q .= "VALUES(
                        '".$this->getlastinsertId()."',
                        '".(int) $gutscheine->gid."',
                        '".$gutscheine->pname."',
                        '".$gutscheine->ginfo."',
                        '".date("Y-m-d H:m:s")."',
                        '".date("Y-m-d H:m:s")."',
                        '1',
                        'post',
                        'publish',
                        'open',
                        'open',
                        '".$this->createMap($gutscheine->pname.$gutscheine->gid)."',
                        '".date('Y-m-d H:m:s',strtotime($gutscheine->gvon))."',
                        '".date('Y-m-d H:m:s',strtotime($gutscheine->gbis))."',
                        '".$gutscheine->gcat."',
                        '".$gutscheine->gcode."',
                        '".$gutscheine->glink."',
                        '".$gutscheine->plogo."' 
                        );";
                        //echo $q."<br /><br />";
												//MW_query($q);
                        $wpdb->query($q);
                        
                        /* INSERTING POSTS TO CATEGORY GUTSCHEIN*/
                        $q1 = "INSERT INTO wp_term_relationships (object_id,term_taxonomy_id) VALUES (".$this->getlastId($gutscheine->gid).",5);";
                        //echo $q1."<br /><br />";
                        //MW_query($q1);
                        $wpdb->query($q1);
                        
                        /* INSERTING Terms*/
                        $q2 = "INSERT INTO wp_terms (name,slug) VALUES";
                        $q2 .= " ('".$gutscheine->pname."','".$this->createMap($gutscheine->pname)."'),";
                        $q2 .= " ('".$gutscheine->pname." Gutschein','".$this->createMap($gutscheine->pname." Gutschein")."'),";
                        $q2 .= " ('".$gutscheine->pname." Gutschein ".date('Y')."','".$this->createMap($gutscheine->pname." Gutschein ".date('Y')."")."'),";
                        $q2 .= " ('".$gutscheine->pname." ".$gutscheine->gcode."','".$this->createMap($gutscheine->pname." ".$gutscheine->gcode.date('dmY'))."')";
                        $q2 .= ";"; 
                        //echo $q2."<br /><br />";
                        //MW_query($q2);
                        $wpdb->query($q2);
                        
                        
                        /* INSERTING Terms*/
                        $q3 = "INSERT INTO wp_term_taxonomy (term_id,taxonomy) VALUES";
                        $q3 .= " ('".$this->gettermsSlug($this->createMap($gutscheine->pname))."','post_tag'),";
                        $q3 .= " ('".$this->gettermsSlug($this->createMap($gutscheine->pname." Gutschein"))."','post_tag'),";
                        $q3 .= " ('".$this->gettermsSlug($this->createMap($gutscheine->pname." Gutschein ".date('Y')))."','post_tag'),";
                        $q3 .= " ('".$this->gettermsSlug($this->createMap($gutscheine->pname." ".$gutscheine->gcode.date('dmY')))."','post_tag')";
                        $q3 .= ";";  
                        //echo $q3."<br /><br />";
                        //MW_query($q3);
                        $wpdb->query($q3);
                        
                        
                        /* INSERTING Terms*/
                        $q4 = "INSERT INTO wp_term_relationships (object_id,term_taxonomy_id) VALUES";
                        $q4 .= " ('".$this->getlastId($gutscheine->gid)."','".$this->gettermstaxonomyId($this->gettermsSlug($gutscheine->pname))."'),";
                        $q4 .= " ('".$this->getlastId($gutscheine->gid)."','".$this->gettermstaxonomyId($this->gettermsSlug($this->createMap($gutscheine->pname." Gutschein")))."'),";
                        $q4 .= " ('".$this->getlastId($gutscheine->gid)."','".$this->gettermstaxonomyId($this->gettermsSlug($this->createMap($gutscheine->pname." Gutschein ".date('Y'))))."'),";
                        $q4 .= " ('".$this->getlastId($gutscheine->gid)."','".$this->gettermstaxonomyId($this->gettermsSlug($this->createMap($gutscheine->pname." ".$gutscheine->gcode.date('dmY'))))."'),";
                        $q4 .= " ('".$this->getlastId($gutscheine->gid)."','9'),";
                        $q4 .= " ('".$this->getlastId($gutscheine->gid)."','10')";
                        $q4 .= ";";  
                        //echo $q4."<br /><br />";
                        //MW_query($q3);
                        $wpdb->query($q4);
                        
                         //$wpdb->show_errors(); 
                        
                        //echo "<hr />";
                        
										}
								}
						}
						else {
								//echo "chyba";
						}
            
            $qqq = "DELETE bad_rows.*
            from wp_posts as bad_rows
            inner join (
            select post_title, MIN(id) as min_id
            from wp_posts
            group by post_name
            having count(*) > 1
            ) as good_rows on good_rows.post_title = bad_rows.post_title
            and good_rows.min_id <> bad_rows.id and post_type = 'post'";
             $wpdb->query($qqq);
          
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
		 
    
}               