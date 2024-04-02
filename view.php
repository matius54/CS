<?php
    if(!isset($_SESSION)) session_start();
    if($data = json_decode($_SESSION["view"] ?? "{}", true)){
        $title = $data["dname"] ?? "visor";
    }
    include "components/header.php";
    require_once "php/utils.php";
    if($data){
        echo "<h2>$title</h2>";
        echo HTML::matrix2table($data["items"]["items"] ?? [[]]);
        echo $data["items"]["navigator"] ?? "";
    }else{
        echo "nada para mostrar";
    }
    include "components/footer.php";
?>