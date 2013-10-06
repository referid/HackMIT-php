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
if (isset($_GET['username'])) {
    $id = sanitizeString($_GET['uid']); //!!!! need to regex to check input
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
        $view_fn = "function(doc) { emit(doc.username); }";
        $design_doc->_id = '_design/history';
        $design_doc->language = 'javascript';
        $design_doc->views = array ( 'username'=> array ('map' => $view_fn ) );
        $client->storeDoc($design_doc);
        $response = $client->key($username)->limit(100)->include_docs(TRUE)->getView('history','username');

        var_dump($response);




        /*
        $doc = $client->getDoc($id);
        $warranty_exp = $doc->purchase_date + $doc->warranty_length;
        printf("<div class='bar-right'>");

        if ($doc->_attachments) {
            $doc2 = couchDocument::getInstance($client, $id);
            foreach($doc2->_attachments as $name => $values) {
                printf("<img src='%s' width='200' />",
                $doc2->getAttachmentURI($name));
            }
        }

        printf("<h1 class='center'> %s </h1>
                <h3 id='model'> %s Model: %s</h3><h3 id='price'> %s</h3>",
                $doc->label, $doc->company, $doc->model, $doc->msrp);

        printf("<div class='clear' id='dates'>
                    <h3>Purchased: %s </h3>
                    <h3>Warranty Expires: %s </h3>
               </div>",
                date('d M, Y', $doc->purchase_date), date('d M, Y', $warranty_exp));

        printf("<h3><a href=%s>User Manual</a></h3>
                </div>",
                $doc->manual);
                         */
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
