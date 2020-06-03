//Script to add parameters to JSON

<?php

//Reading
$js = json_decode(file_get_contents("sites.json"));

foreach($js->sites as $i){

//Adding parameter
$i -> filename = $i -> domain;
}

//Encoding
$js = json_encode($js, JSON_PRETTY_PRINT);

//Writing to file
file_put_contents("sites.json", $js);
?>
