<?php
echo "start of index.php\n";
echo <<<_END
<html>
    <head>
        <link href="layout/css/core.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="main" class="center">
_END;

 if (isset($_GET['uid']) && isset($_GET['company'])) {
    $id = sanitizeString($_GET['uid']); //!!!! need to regex to check input
    echo "received id: " . $id . "\n";
    $company = sanitizeString($_GET['company']); //!!!! need to regex to check input
    echo "received company: " . $company . "\n";
 }

require_once 'lib/PHP-on-Couch/lib/couch.php';
require_once 'lib/PHP-on-Couch/lib/couchClient.php';
require_once 'lib/PHP-on-Couch/lib/couchDocument.php';

echo "after required statements\n";

try {
    $client = new couchClient ('http://localhost:5984', '"' . $company . '"');
} catch (Exception $e) {
    echo "exception caught\n";
}

// document fetching by ID
try {
    $doc = $client->getDoc('"' . $id . '"');
        echo "trying to get doc";
} catch ( Exception $e ) {
    if ( $e->getCode() == 404 ) {
       echo "Document does not exist !\n";
    }
    exit(1);
}
echo "exit try statement\n";



 /* sanitizeString is a function that is intended to sanitize input gathered
 * from forms in order to prevent injection and cross site scripting
 * $str_input is the string retrieved*/
function sanitizeString($str_input) {
    $str_input = strip_tags($str_input);
    $str_input = htmlentities($str_input);
    $str_input = stripslashes($str_input);
    return $str_input;
}

echo "end of index.php";
echo <<<_END
        </div>
    </body>
</html>
_END;
