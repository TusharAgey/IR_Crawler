Simple php crawler and simple drupal plugin that acts like a search portal
1)important files: index.php
				   elasticsearch/elasticsearch.module
2)After configuring everything as mentioned in both instructions and elasticsearch/readme file, please go through the code and make some changes .
Changes are:

elasticsearch/elasticsearch.module:  
	1)if you are accessing site using localhost/drupal/?q=elasticsearch/search:
		i)Line number 40: change form action value to http://localhost/drupal/?q=elasticsearch/search
		ii)add another tab: <input type=\"hidden\" name=\"q\" value=\"elasticsearch/search\"></input>
		i.e. line number 40 will look like:
		$vars['items'][0]="<form action = \"http://localhost/drupal/?q=elasticsearch/search\"><input type=\"hidden\" name=\"q\" value=\"elasticsearch/search\"></input><input name = \"query\" type = \"Text\" placeholder=\"Enter the query\"> </input><br/><input type = \"submit\"></input></form> <div id = \"search_results\"></div>"

	2)if you are able to access site using localhost/drupal/elasticsearch/search:
		i)Line number 40: change form action value to http://localhost/drupal/elasticsearch/search
Done.