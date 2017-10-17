<?php
#$seed_url = "https://tusharagey.github.io/Test";
$seed_url = "http://www.coep.org.in";

$links = array();	#Empty array to hold all links to return
$crawling_depth = 100; #manual definition of crawling depth

$myfile = fopen("urls.txt", "w");


function get_links($url, $links) {
    #Create a new DOM Document to hold our webpage structure
    $xml = new DOMDocument();
    #Load the url's contents into the DOM
    $xml->loadHTMLFile($url);
    
    #Loop through each <a> tag in the dom and add it to the link array
    foreach($xml->getElementsByTagName('a') as $link) {
    	$urlname = $link->getAttribute('href');
    	#echo $urlname;
    	if(!in_array($urlname, $links)) {
    	    
    	    global $seed_url;
            $absolute_url = "";
            if (strpos($urlname, 'http') !== false) { #if URL contains http or https, its absolute url. don't modify
                $absolute_url = $urlname;
            }
            else{
            	/*if($urlname[strlen($urlname) - 1] == '/'){		# If the relative url ends with a '/', don't add that '/'
								# This probably caused some double '/'s in url which we saw that day in lab
			$absolute_url = $seed_url . '/' . rtrim($urlname, '/');
            	}
            	else{
			$absolute_url = $seed_url . '/' . $urlname;
            	}*/
                $absolute_url = $seed_url . '/' . $urlname;
            }

        
            if($absolute_url[0] != '#' and strpos($absolute_url, 'coep.org.in') !== false and strpos($absolute_url, 'foss.coep.org.in') == false) { 
		# if URL is anchor tag, then ignore because it eventually refers the same page.
		# if URL is inside COEP website, then only push it to array
                array_push($links, $absolute_url);
                echo $absolute_url;
		echo "\n";
		
		# Writing to output file :
		global $myfile;
		fwrite($myfile, $absolute_url);
		fwrite($myfile, "\n");
            }
    	    #$links[] = array('url' => $link->getAttribute('href'), 'text' => $link->nodeValue);
            #Just ignore the above commented line
    	}
    }
    //Return the links
    return $links;
}

$links = get_links($seed_url, $links);

#print_r($links);

# Iteration - 2 (Depth - 2) crawling :-
for($x = 0; $x < count($links); $x++) {
	#echo $links[$x];
	$links = get_links($links[$x], $links);
}
#print_r($links);

# Writing to output file :-
/*$arrlength = count($links);
$myfile = fopen("urls.txt", "w");
for($x = 0; $x < $arrlength && $x < $crawling_depth; $x++) {
    #echo $links[$x];
    fwrite($myfile, $links[$x]);
    fwrite($myfile, "\n");
} */

fclose($myfile);

#here, we have base links, add these to queue and go more deep, collect all the possible links that we can grab
?>
