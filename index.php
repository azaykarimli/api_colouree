<?php
// php function to convert csv to json format
function csvToJson($fname)
{
    // open csv file
    if (!($fp = fopen($fname, 'r'))) {
        die("Can't open file...");
    }

    //read csv headers
    $key = fgetcsv($fp, "1024", ",");

    // parse csv rows into array
    $json = array();
    while ($row = fgetcsv($fp, "1024", ",")) {
        $json[] = array_combine($key, $row);
    }

    // release file handle
    fclose($fp);

    // encode array to json
    return json_encode($json);
}

//$post = 9183468117;
$func = $_POST["func"];
$id = $_POST["id"];
//$response = json_decode($post, TRUE);

decider($func, $id);

function decider($func, $id)
{

    switch ($func) {

        case "position":

            $res = position($id);

            break;
        case "bank":
            $res = position($id);

            $stream = "C:\\folder\\milano.csv";

            $returner = csvToJson("$stream");

            $res = bank($id, $returner, $res);

            break;
    }
    return $res;
}


function bank($id, $returner, $res)
{

    $ref = array($res["Lat"], $res["Long"]);

    $response = json_decode($returner, TRUE);

    $items = $response;


    for ($i = 0; $i <= 2; $i++) {

        //$ref = array(49.648881, -103.575312);
        

        $distances = array_map(function ($item) use ($ref) {
            $a = array_slice($item, -2);
            return distance($a, $ref);
        }, $items);

        asort($distances);

        echo '<br>Closest item is: ', print_r($items[key($distances)]["id"]);

        $will_unset = $items[key($distances)]["id"];

        unset($items[array_search($will_unset, $items)]);

    }


    //echo 'Closest item is: ', var_dump($items[key($distances)]);
    //echo 'Closest item is: ', print_r($items[key($distances)]["id"]);
}


function distance($a, $b)
{
    $miles = 0;
    if (isset($lat1) && isset($lat2)) {
        list($lat1, $lon1) = $a;
        list($lat2, $lon2) = $b;
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
    }



    return $miles;
}


function position($id)
{


    $stream = "C:\\folder\\milano.csv";

    $returner = csvToJson("$stream");

    $response = json_decode($returner, TRUE);

    foreach ($response as $res => $value) {

        /*    foreach($value as $v){

        echo "<PRE>";
        print_r($v);
        echo "</PRE>";

    } */
        if ($value["id"] == $id) {

            /* echo "<PRE>";
            print_r($value);
            echo "</PRE>"; */
            return $value;
        }
        //print_r($value["id"]);


        /*     echo "<PRE>";
    print_r($value["id"]);
    echo "</PRE>"; */
    }

    /* echo "<PRE>";
print_r($response);
echo "</PRE>";
 */
}
