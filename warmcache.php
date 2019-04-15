<?php 

$cachemap = "../cachemap.xml";

$step = 50;

$active_file = './cache_active.txt';
$start_file = './cache_start.txt';
// Open the file to get existing content

$active = file_get_contents($active_file);
$start = file_get_contents($start_file);

if ( $active == 1 ) {
    echo 'Sorry dave I can\'t do that' . PHP_EOL;
    die();
} else {
    file_put_contents($active_file, "1");
}



$xml = simplexml_load_file($cachemap) or die("Error: Cannot create object");
$xml_total = count($xml);

// create a new cURL resource
$ch = curl_init();

for ($i = (integer) $start; $i <= ($start + $step); $i++) {

    if ($i >= $xml_total) {
        break;
    }

    $time_start = microtime(TRUE);

    $url = $xml->url[$i]->loc;

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $url );
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    //curl_setopt($ch, CURLOPT_CONNECT_ONLY, 1 );

    // grab URL and pass it to the browser
    curl_exec($ch);

    // close cURL resource, and free up system resources

    $time_end = microtime(TRUE);
    $time = $time_end - $time_start;

    echo $url . ' taking: ' . $time .  PHP_EOL . '</br>';
} 

curl_close($ch);

if ($xml_total >= ($start + $step)) {
    $new_start = ($start + $step);
} else {
    $new_start = 0;
}

// Write the contents back to the file
file_put_contents($start_file, $new_start);
file_put_contents($active_file, "0");

?>
