<?php
    require_once "php/ve.php";
    require_once "php/utils.php";
    $ve = new Ve;
    $info = JSON::getJson();
    $state = $info["state"] ?? "";
    if($state){
        JSON::sendJson($ve->citiesByState($state));
    }
?>