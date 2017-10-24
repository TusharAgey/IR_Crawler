<?php
#$seed_url = "https://tusharagey.github.io/Test";
$seed_url = "http://www.coep.org.in";

#$links = array();	#Empty array to hold all links to return

$crawling_depth = 100; #manual definition of crawling depth

$myfile = fopen("urls.txt", "w");

$count = 1;

function fixString($str){ #removing the special characters. Special characters are not allowed in JSON such as (") in a string.
    return preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', ' ', $str);
}
function get_links($url, & $links) {					# '&' indicates pass by reference
    #Create a new DOM Document to hold our webpage structure
    $xml = new DOMDocument();
    #Load the url's contents into the DOM
    $xml->loadHTMLFile($url);
    
    # Get title, content of page and feed them to indexer :-
    $title_list = $xml->getElementsByTagName('title');
    foreach ($title_list as $title) {
    	$title_str = fixString(trim($title->nodeValue)); #, PHP_EOL; trim for removing any whitespaces/unknown characters around the string
    }
    
    $body = $xml->getElementById('main-wrapper'); #ignore the header/footer and only include important and unique section.
	$body_content = fixString(trim($body->nodeValue)); #, PHP_EOL;

    
    # Elastic search code :-
    $ch = curl_init();
    global $count;
    $Curlurl = "http://localhost:9200/url-test/document/". $count. "?pretty=true"; #connection to elasticsearch
    $count++;
    #$param = "{ \"title\" : \"myWebsite\", \"link\" : \"https://tusharagey.github.io/Test\", \"content\" : \"Small with URL https://tusharagey.github.io/Test.\" }";
    $param = '{ "title" : "'.$title_str.'", "link" : "'.$url.'", "content" : "'.$body_content.'" }';
    
    curl_setopt($ch, CURLOPT_URL ,$Curlurl); #curl command set to send query to elasticsearch
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    $result = curl_exec($ch);
    echo $result;
    echo "<br/>";
    curl_close($ch);
    
    #Loop through each <a> tag in the dom and add it to the link array
    foreach($xml->getElementsByTagName('a') as $link) {
    	$urlname = $link->getAttribute('href');
    	#echo $urlname;
    	if(!in_array($urlname, $links) && $urlname[0] != '#' && strcmp($urlname, "/") != 0) {
    		global $seed_url;
        	$absolute_url = "";    	
            if (strpos($urlname, 'http') !== false) { #if URL contains http or https, it is absolute url; don't modify
            	$absolute_url = $urlname;
        	}
        	else{
            	if($urlname[strlen($urlname) - 1] == '/'){		# If the relative url ends with a '/', remove it
				$urlname = rtrim($urlname, '/');					
				#$absolute_url = $seed_url . '/' . rtrim($urlname, '/');
				# This probably caused some double '/'s in url which we saw that day in lab, so this might solve it
        	}
        	if($urlname[0] == '/') {				# If relative url starts with a '/', remove it
    			$urlname = ltrim($urlname, '/');
      		}			
    		$absolute_url = $seed_url . '/' . $urlname;
    	}		
		if( ($absolute_url[0] != '#') && (strpos($absolute_url, 'javascript') == false) && (strpos($absolute_url, 'coep.org.in') !== false) && (strpos($absolute_url, 'foss.coep.org.in') == false) && (!in_array($absolute_url, $links)) && (strcmp($absolute_url, "http://www.coep.org.in/") != 0) )
		{
			# if URL is anchor tag, then ignore because it eventually refers the same page.
			# if URL is inside COEP website, then only push it to array
            array_push($links, $absolute_url);
			# Writing to output file :
			global $myfile;
			fwrite($myfile, $absolute_url);
			fwrite($myfile, "\n");
	 	}
    	}
    }
    //Return the links
    #return $links;		# Modifying the actual argument array itself, so no need of returning (pass by reference)
}
$links = array();
get_links($seed_url, $links);
#print_r($links);


# Iteration - 2 (Depth - 2) crawling :-
for($x = 0; $x < count($links) && $x < $crawling_depth; $x++) {
	#echo $links[$x];
	get_links($links[$x], $links);
}
#print_r($links);
fclose($myfile);
?>
