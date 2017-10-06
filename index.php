<?php
$seed_url = "https://tusharagey.github.io/Test";

#Empty array to hold all links to return
    $links = array();

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
		$absolute_url = $seed_url . '/' . $urlname;
		#echo $absolute_url;
        	array_push($links, $absolute_url);
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
$arrlength = count($links);

$myfile = fopen("urls.txt", "w");

for($x = 0; $x < $arrlength; $x++) {
    #echo $links[$x];
    fwrite($myfile, $links[$x]);
    fwrite($myfile, "\n");
}
fclose($myfile);
#here, we have base links
#add these to queue and go more deep
#collect all the possible links that we can grab
?>
