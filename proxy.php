<?php

//$func = $_POST["func"];
$func = "bank";


$call_az_Api = call_az_Api($func);
echo "<PRE>";
print_r($call_az_Api);
echo "</PRE>";


function call_az_Api($func)
{
    $data = array(
        //"func" => "position",
        "func" => $func,
        "id" => 9183468117
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_URL, 'http://localhost/api_cvs/');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $jsonData = json_decode(curl_exec($curl), true);

    //curl_close($curl);

    $result = curl_exec($curl);

    return $result;
}
