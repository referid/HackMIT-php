<?php
echo <<<_END
<html>
    <head>
        <link href="layout/css/core.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
    </head>
    <body>
        <div id="main" class="center">
_END;

//global param
$name = "";

// Retreive GET parameters
if (isset($_GET['uid']) && isset($_GET['company'])) {
    $id = sanitizeString($_GET['uid']); //!!!! need to regex to check input
    $company = sanitizeString($_GET['company']); //!!!! need to regex to check input
}

// Include library files
require_once 'lib/PHP-on-Couch/lib/couch.php';
require_once 'lib/PHP-on-Couch/lib/couchClient.php';
require_once 'lib/PHP-on-Couch/lib/couchDocument.php';

printf("<div id='banner' class='bar-left'>
        <img src='layout/images/logo-300.png' alt='referid'/>
        </div>
        <div class='clear blueLine' style='height:15px;'></div>");


// Connect with Database
try {
    $client = new couchClient ('http://localhost:5984', 'db_' . $company);

    // Fetch document by id
    try {
        $doc = $client->getDoc($id);
        $warranty_exp = $doc->purchase_date + $doc->warranty_length;
        printf("<div class='bar-right'>");


        $file = "images/" . $id . ".JPG";
        if (file_exists($file)) {
          printf("<img src='%s' width='200' />", $file);
        }

        printf("<h1 class='center'> %s </h1>
                <h3 id='model'> %s Model: %s</h3><h3 id='price'> %s</h3>",
                $doc->label, $doc->company, $doc->model, $doc->msrp);

        //set name
        $name = $doc->label;

        printf("<div class='clear' id='dates'>
                    <h3>Purchased: %s </h3>
                    <h3>Warranty Expires: %s </h3>
               </div>",
                date('d M, Y', $doc->purchase_date), date('d M, Y', $warranty_exp));

        printf("<h3><a href=%s>User Manual</a></h3>
                <h3>Service Number: %s</h3>
                </div>",
                $doc->manual,$doc->phone);

    } catch ( Exception $e ) {
        if ( $e->getCode() == 404 ) {
            echo "We apologize, but the document does not exist\n";
        }
    }


    // Build Reference
    if (isset($_GET['userid']) && isset($_SERVER['REMOTE_ADDR'])) {
        $userid = sanitizeString($_GET['userid']); //!!!! need to regex to check input
        $ip = sanitizeString($_SERVER['REMOTE_ADDR']); //!!!! need to regex to check input

        //store json object as location
        $location = file_get_contents("http://api.hostip.info/get_json.php?ip=" . $ip);

        $new_doc = new stdClass();
        $new_doc->userid = $userid;
        $new_doc->link = $id;
        $new_doc->time = time();
        $new_doc->location = $location;


        try {
          $response = $client->storeDoc($new_doc);

          // Connect with User Database
          try {
            $userClient = new couchClient ('http://localhost:5984', 'db_users');

            // Fetch document by userid
            try {
                $userDocToAdd = $userClient->getDoc($userid);

                //add history
                $historyArray = $userDocToAdd->history;
                $historyArray[] = $name . "/" . $company . "/" . $id;
                $userDocToAdd->history = $historyArray;

                try {  $userResponse = $userClient->storeDoc($userDocToAdd); }
                    catch (Exception $e) {
                        echo "ERROR: ".$e->getMessage()." (".$e->getCode().")<br>\n";
                    }
            } catch (Exception $e) {
                echo "We apologize, but the history could not be updated";
            }
          } catch (Exception $e) {
              echo "We apologize, but the client db connection could not be made";
          }

        } catch (Exception $e) {
          echo "We apologize, but the document could not be saved\n";
        }
    }

} catch (Exception $e) {
    printf("<div class='bar-right'>
                <h2> We apologize, but the server could not connect</h2>
            </div>");
}



 /* sanitizeString is a function that is intended to sanitize input gathered
 * from forms in order to prevent injection and cross site scripting
 * $str_input is the string retrieved*/
function sanitizeString($str_input) {
    $str_input = strip_tags($str_input);
    $str_input = htmlentities($str_input);
    $str_input = stripslashes($str_input);
    return $str_input;
}



echo <<<_END
        </div>
    </body>
</html>
_END;
