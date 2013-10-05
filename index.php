<?php
echo "boo";
require_once '/lib/PHP-on-Couch/lib/couch.php';
require_once '/lib/PHP-on-Couch/lib/couchClient.php';
require_once '/lib/PHP-on-Couch/lib/couchDocument.php';
echo "boo";
echo <<<_END
<html>
    <head>
        <link href="layout/css/core.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="main" class="center">
_END;

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
