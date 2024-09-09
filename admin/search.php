<?php
require_once('../includes/database-functions.php');
//A list of email.
$services = getServices();

//Get the search term from our "q" GET variable.
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

//Array to hold results so that we can
//return them back to our Ajax function.
$results = array();

//Loop through our array.
foreach($services as $service){

    if ($q=='' || $q==null ||empty($q)){
        $results[]=$service;
    }
    //If the search term is present in our city name.
    elseif(stristr($service['name'], $q)){
        //Add it to the results array.
        $results[] = $service;
    }
}

//Display the results in JSON format so that
//we can parse it with JavaScript.
echo json_encode($results);

?>
