<?php
require "bddConnection.php";

if (isset($_GET["ville_id"])) {
    $ville_id=$_GET["ville_id"];
}
if (isset($_GET["ok"])) {
    $ok=$_GET["ok"];
}else{
    $ok="";
}
if ($ok==="Mise à jour"){
    $ville_code_postal=$_GET["ville_code_postal"];
    $sql="UPDATE villes_france_free SET ville_code_postal='".$ville_code_postal."' WHERE ville_id=:ville_id";
    $query=$bdd->prepare($sql);
    $query->bindValue(':ville_id', $ville_id, PDO::PARAM_INT);
    $nb=$query->execute();
    if ($nb = 1){
        echo "<h1>Le code postal a été mis à jour</h1><br/>";
    }
}

$sql="SELECT ville_nom, ville_departement, ville_code_postal FROM villes_france_free WHERE ville_id=:ville_id";
$query=$bdd->prepare($sql);
$query->bindValue(':ville_id', $ville_id, PDO::PARAM_INT);
$query->execute();
$result=$query->fetch(PDO::FETCH_ASSOC);

$nb=count($result);
if ($nb>0){
    $ville_nom=$result["ville_nom"];
    $ville_departement=$result["ville_departement"];
    $ville_code_postal=$result["ville_code_postal"];
}else{
    echo "cet enregistrement n'existe plus.<br/>";
}
?>

<form action="#" method="get">
    <?php
    if ($ok==""){
    ?>
        <h1>Modification du code postal</h1>
    <?php
    }
    ?>
    <div>
        <input type="hidden" id="ville_id" name="ville_id" value="<?php echo $ville_id ?>">
        <label for="ville_nom">Nom de la ville</label>
        <input type="text" id="ville_nom" name="ville_nom" readonly="readonly" value="<?php echo $ville_nom ?>">
    </div>
    <div>
        <label for="ville_departement">Département</label>
        <input type="text" id="ville_departement" name="ville_departement" readonly="readonly"  value="<?php echo $ville_departement ?>">
    </div>
    <div>
        <label for="ville_departement">Code postal</label>
        <input type="text" id="ville_code_postal" name="ville_code_postal" value="<?php echo $ville_code_postal ?>">
    </div>
    <?php
    if ($ok==""){
    ?>
        <input type="submit" name="ok" value="Mise à jour">
    <?php
    }
    ?>
</form>