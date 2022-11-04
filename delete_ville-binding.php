<?php
require "bddConnection.php";
if(isset($_GET["ville_id"])){
    $ville_id=$_GET["ville_id"];
}else{
    $ville_id=0;
}
if (isset($_GET["ok"])) {
    $ok=$_GET["ok"];
}else{
    $ok="";
}

if($ok==""){
    $sql="SELECT ville_nom, ville_code_postal FROM villes_france_free WHERE ville_id=:ville_id";
    $query=$bdd->prepare($sql);
    $query->bindValue(':ville_id', $ville_id, PDO::PARAM_INT);
    $query->execute();

    $result=$query->fetch(PDO::FETCH_ASSOC);
    $nb=count($result);
    if ($nb>0){
        $ville_nom=$result["ville_nom"];
        $ville_code_postal=$result["ville_code_postal"];
    }else{
        echo "L'enregistrement demandé n'existe pas!<br/>";
    }
    ?>
    <form action="" method="get">
    <h1>Confirmer la suppression de la ville</h1>
    <div>
        <input type="hidden" name="ville_id" value="<?php echo $ville_id?>">
        <label for="ville_nom">Ville</label>
        <input id="ville_nom" type="text" value="<?php echo $ville_nom?>" readonly="readonly">
    </div>
    <div>
        <label for="ville_code_postal" type="texte" value="">Code postal</label>
        <input id="ville_nom" type="text" value="<?php echo $ville_code_postal?>" readonly="readonly">
    </div>
    <input type="submit" name="ok" value="Supprimer">
    </form>
    <?php
}else{
    $sql="DELETE FROM villes_france_free WHERE ville_id=:ville_id";
    $query=$bdd->prepare($sql);
    $query->bindValue(':ville_id', $ville_id, PDO::PARAM_INT);
    $nb_delete=$query->execute();
    if($nb_delete>0){
        echo "$nb_delete lignes supprimée(s).<br/>";
        echo '<button><a href="index.php">Retour à la liste des villes</a></button><br>';
    }
}

?>

