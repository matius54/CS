<?php
    require_once "ve.php";
    require_once "utils.php";
    $ve = new Ve;
    $info = JSON::getJson();
    $state = $info["state"] ?? "";
    if($state){
        JSON::sendJson($ve->citiesByState($state));
    }
?>