<?php
// php function to convert csv to json format

//getcwd();

$stream = getcwd() . "\milano.csv";



/**
 * which function to be called position or bank
 */

//$post = 9183468117;
if (isset($_POST["func"])) {
    $func = $_POST["func"];
    $id = $_POST["id"];
} else {
    $func = "";
    $id = 0;
}

/**
 * only function that will be executed
 */
print_r(decider($func, $id));

function decider($func, $id)
{

    switch ($func) {

        case "position":

            $res = position($id);

            //echo "<PRE>";
            $res = position($id);
            //echo "</PRE>";


            break;
        case "bank":
            $res = position($id);

            $res = json_decode($res, TRUE);

            //$stream = "C:\\folder\\milano.csv";
            $stream = getcwd() . "\milano.csv";

            $returner = csvToJson("$stream");

            //$returner = json_decode($returner, TRUE);


            $res = json_encode(bank($id, $returner, $res));

            break;

        default:

            $stream = getcwd() . "\milano.csv";

            $returner = csvToJson("$stream");
            //$response = json_decode($returner, TRUE);


            //echo "<PRE>";
            //$res = print_r($response);
            $res = $returner;
            //$res = print_r($returner);
            //echo "</PRE>";



            break;
    }
    return $res;
}



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




//$response = json_decode($post, TRUE);



/**
 * return the nearest 3 banks
 */
function bank($id, $returner, $res)
{

    //$ref = array($res["Lat"], $res["Long"]);

    $response = json_decode($returner, TRUE);

    $items = array();

    foreach ($response as $re) {

        if ($re["secondary"] == "bank") {

            $items[] = $re;
        }
    }

    /**
     * unset the itself from list
     */
    unset($items[array_search($res, $items)]);

    $closeest_banks = "";

    /**
     * base location that we are searching closest bank
     */
    if (!empty($res)) {


        $base_location = array(
            'lat' => $res["Lat"],
            'lng' => $res["Long"]
        );



        $distances = array();

        /**
         * for each records calculate the distance with pythagorean algorithm
         */

        foreach ($items as $key => $location) {
            $a = $base_location['lat'] - $location['Lat'];
            $b = $base_location['lng'] - $location['Long'];
            $distance = sqrt(($a ** 2) + ($b ** 2));
            $distances[$key] = $distance;
        }



        $closeest_banks = array();

        for ($i = 1; $i <= 3; $i++) {

            asort($distances);

            $closest = $items[key($distances)];

            $will_unset = key($distances);

            $distance_mile = $distances[key($distances)] * 60 * 1.1515;

            $distance_km = (round($distance_mile * 1.609344, 2));

            //echo "<br> Closest foreach suburb is: " . $closest['id'];

            //$closeest_banks[] = $distance_km;
            $closeest_banks[] =
                [
                    "info" =>  $closest,
                    "km" =>  $distance_km
                ];

            //echo "<br> $i. closest  bank is: " . $closest["name"] . " to  " . $res["name"];
            //echo "<br> .$i. closest is: " .print_r($closest);
            //echo "<br>" . $res["id"];

            /**
             * closest ones are unsetted from array to prevent duplication
             */
            unset($distances[$will_unset]);
        }
    }



    return $closeest_banks;
}



/**
 * position function will return the informations based on id provided
 */


function position($id)
{


    //$stream = "C:\\folder\\milano.csv";
    $stream = getcwd() . "\milano.csv";

    $returner = csvToJson("$stream");

    $response = json_decode($returner, TRUE);

    $resultant = array();

    foreach ($response as $res => $value) {

        /*    foreach($value as $v){

        echo "<PRE>";
        print_r($v);
        echo "</PRE>";

    } */
        if ($value["id"] == $id) {

            //print_r($value);
            /*     echo "<PRE>";
            print_r($value);
            echo "</PRE>"; */
            $resultant = $value;
            //return $value;
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
    return json_encode($resultant);
}
