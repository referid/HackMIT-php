<?php
echo <<<_END
<html>
    <head>
        <link href="layout/css/core.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="main" class="center">
_END;
    include('layout/content.php');
echo <<<_END
        </div>
    </body>
</html>
_END;
