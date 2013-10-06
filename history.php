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


// Retreive GET parameters
if (isset($_GET['userid'])) {
    $userid = sanitizeString($_GET['userid']); //!!!! need to regex to check input
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
    $client = new couchClient ('http://localhost:5984', 'db_' . 'users');

    // Fetch document by id
    try {
         $doc = $client->getDoc($userid);
         printf("<div>
                 <ul>");
         echo $doc->history;
         $json = $doc->history;
         foreach($json) {
            //$address = explode("/", $id);
            //echo $address;
            printf("<li><a href='%s' >%s</a></li>",
            "http://referid.co/index.php?uid=" . $json[1] . "&company=" . $json[0] . "&username=" . $username,
            $json[0]);
        }
        printf("</ul>
                </div>");

    } catch ( Exception $e ) {
        if ( $e->getCode() == 404 ) {
            echo "We apologize, but the document does not exist\n";
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

    </body>
</html>
_END;
