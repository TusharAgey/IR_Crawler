<?php
$seed_url = "https://tusharagey.github.io/Test/";

function get_links($url) {
    #Create a new DOM Document to hold our webpage structure
    $xml = new DOMDocument();
    #Load the url's contents into the DOM
    $xml->loadHTMLFile($url);
    #Empty array to hold all links to return
    $links = array();

    #Loop through each <a> tag in the dom and add it to the link array
    foreach($xml->getElementsByTagName('a') as $link) {
	if(!in_array($link->getAttribute('href'), $links)) {

        	array_push($links, $link->getAttribute('href'));
	        #$links[] = array('url' => $link->getAttribute('href'), 'text' => $link->nodeValue);
        	#Just ignore the above commented line
	}
    }

    //Return the links
    return $links;
}

$links = get_links($seed_url);
print_r($links);

$arrlength = count($links);

$myfile = fopen("urls.txt", "w");

for($x = 0; $x < $arrlength; $x++) {
    echo $links[$x];
    fwrite($myfile, $links[$x]);
    fwrite($myfile, "\n");
    echo "<br>";
}


#here, we have base links
#add these to queue and go more deep
#collect all the possible links that we can grab
?>
