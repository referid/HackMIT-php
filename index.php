<?php
echo "boo";
require '/lib/PHP-on-Couch/lib/couch.php';
require '/lib/PHP-on-Couch/lib/couchClient.php';
require '/lib/PHP-on-Couch/lib/couchDocument.php';
echo "boo";
echo <<<_END
<html>
    <head>
        <link href="layout/css/core.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="main" class="center">
_END;

 if (isset($_GET['uid']) && isset($_GET['company'])) {
    $id = sanitizeString($_GET['uid']);
    echo "received id: " . $id;         //!!!! need to regex to check input
    $database = sanitizeString($_GET['company']);    //!!!! need to regex to check input
    echo "received company: " . $company;
 }

    try {
        $client = new couchClient ('http://localhost:5984', 'company');
    } catch (Exception $e) {
        echo "exception caught";
    }
    echo "connected to client";
    // document fetching by ID
    try {
        $doc = $client->getDoc('7b668553');
            echo "trying to get doc";
    } catch ( Exception $e ) {
        if ( $e->getCode() == 404 ) {
           echo "Document does not exist !";
        }
        exit(1);
    }
    echo "exit try statement";

echo <<<_END
        </div>
    </body>
</html>
_END;
