<?php

    require_once('includes/data.php');

    $data = new Data();

    echo json_encode($data->getAllRows());



?>