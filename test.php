<?php
    include "components/header.php";
    require_once "utils.php";
    /*
    require_once "db.php";
    $cn = DB::getInstance();
    $cn->execute("SELECT * FROM categoria");
    var_dump($cn->rowCount());
    $result = $cn->fetch();
    var_dump($result);
    */

    require_once "ve.php";
    $ve = new Ve;
    $states = $ve->states();
    echo HTML::array2list($states, "states", "selecciona el estado", sameValueName: true);
    $cities = $ve->cities();
    echo HTML::array2list($cities, "cities", "selecciona tu ciudad", sameValueName: true);
    
?>
<script src="ajax.js"></script>
<script src="ve.js"></script>
<script src="list.js"></script>
<script>
    const ve = new Ve(document.querySelector("select[name=\"states\"]"),document.body);
</script>
<?php include "components/footer.php" ?>