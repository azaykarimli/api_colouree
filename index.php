<?php
$default_id = 715948248;
$actual_link_all = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . "/api_colouree";
$actual_link_position = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . "/api_colouree/?func=position&id=$default_id";
$actual_link_bank = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . "/api_colouree/?func=bank&id=$default_id";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <a href="<?php echo $actual_link_all; ?>"> show all records </a>
    </div>
    <div>
        <a href="<?php echo $actual_link_position; ?>"> position (this link has default random id. You can change it manually in the browser) </a>
    </div>
    <div>
        <a href="<?php echo $actual_link_bank; ?>"> 3 closest Banks (this link has default random id. You can change it manually in the browser) </a>
    </div>

</body>

</html>


<?php

//$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . "proxy.php";

//link to the proxy.php file 
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . "/api_colouree/proxy.php";


$func = "";
//$func = $_POST["func"];
if (isset($_GET["func"])) {

    $func = $_GET["func"];
}
if (isset($_GET["id"])) {

    $id_topass = $_GET["id"];
} else {
    $id_topass = $default_id;
}

//$func = "bank";
//$func = "position";
//$func = "";




/**
 * default will return ll the rrecords
 */
$call_az_Api = call_az_Api($func, $actual_link, $id_topass);
$response = json_decode($call_az_Api, TRUE);
//$call_az_Api = json_encode($call_az_Api, JSON_PRETTY_PRINT);

if (empty($response)) {

    echo "id or func params must be incorrect: FUNC can be 'position' and 'bank' //// id can be not exist on file <BR>";
    echo "<a type='button' href='$actual_link_all'>Click me see all records</a>";
} else {
    echo "<PRE>";
    print_r($response);
    echo "</PRE>";
}


function call_az_Api($func, $actual_link, $id_topass)
{
    $data = array(
        //"func" => "position",
        "func" => $func,
        "id" => $id_topass
    );

    /**
     * link of file in browser
     */

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    //curl_setopt($curl, CURLOPT_URL, 'http://localhost/api_colouree/proxy.php');
    curl_setopt($curl, CURLOPT_URL, $actual_link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $jsonData = json_decode(curl_exec($curl), true);

    //curl_close($curl);

    $result = curl_exec($curl);

    return $result;
}




/* 
function call_az_Api($func)
{
    $data = array(
        //"func" => "position",
        "func" => $func,
        "id" => 715948248
    );

    
     //link of file in browser
     
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"."proxy.php";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    //curl_setopt($curl, CURLOPT_URL, 'http://localhost/api_colouree/proxy.php');
    curl_setopt($curl, CURLOPT_URL, $actual_link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $jsonData = json_decode(curl_exec($curl), true);

    //curl_close($curl);

    $result = curl_exec($curl);

    return $result;
}
 */