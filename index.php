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





//Retreive GET parameters
 if (isset($_GET['uid']) && isset($_GET['company'])) {
    $id = sanitizeString($_GET['uid']); //!!!! need to regex to check input
    $company = sanitizeString($_GET['company']); //!!!! need to regex to check input
 }

 //Include library files
require_once 'lib/PHP-on-Couch/lib/couch.php';
require_once 'lib/PHP-on-Couch/lib/couchClient.php';
require_once 'lib/PHP-on-Couch/lib/couchDocument.php';

printf("<div id='banner' class='bar-left'>
            <img src='layout/images/logo-300.png' alt='referid'/>
        </div>
        <div class='clear blueLine' style='height:15px;'></div>");



//Connect with Database
try {
    $client = new couchClient ('http://localhost:5984', 'db_' . $company);

    // Fetch document by id
    try {
        $doc = $client->getDoc($id);
        $warranty_exp = $doc->purchase_date + $doc->warranty_length;
        printf("<div class='bar-right'>
                    <h1 class='center'> %s </h1>
                    <h3 id='model'> %s Model: %s</h3><h3 id='price'> %s</h3>",
                    $doc->label, $doc->company, $doc->model, $doc->msrp);

        printf("<div class='clear' id='dates'>
                    <h3>Purchased: %s </h3>
                    <h3>Warranty Expires: %s </h3>
               </div>",
                date('d m, Y', $doc->purchase_date), date('d m, Y', $warranty_exp));

        printf("<h3>User Manual:");

    } catch ( Exception $e ) {
        if ( $e->getCode() == 404 ) {
            echo "We apologise, but the document does not exist\n";
        }
    }
} catch (Exception $e) {
    printf("<div class='bar-right'>
                <h2> We apologise, but the server could not connect</h2>
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

/*
function viewFile($file) {
      $fileRef = realpath(p['']);

}
        $file = realpath('data/uploads/' . $type . '/' . $fileRow[0]['file_name']);

        if (file_exists($file)) {
            $response = new \Zend\Http\Response\Stream();
            $response->setStream(fopen($file, 'r'));
            $response->setStatusCode(\Zend\Http\Response::STATUS_CODE_200);
            $response->setStreamName(basename($file));

            $headers = new Headers();
            $headers->addHeaders(array('Content-Description'       => 'File Transfer',
                                       'Content-Type'              => 'application/oct-stream',
                                       'Content-Disposition'       => 'attachment; filename=' . basename($file),
                                       'Content-Transfer-Encoding' => 'binary',
                                       'Content-Length'            => filesize($file),));
            $response->setHeaders($headers);
            return $response;
            */
echo <<<_END
        </div>
    </body>
</html>
_END;
