<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="php bdd binding">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Villes de France</title>
        <link rel="stylesheet" href="style/style.css">
        <script src="https://kit.fontawesome.com/16875f3306.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <?php
        require "bddConnection.php";
        if(isset($_GET["page"])){
            if(!empty($_GET["page"])){
                $page=(int) $_GET["page"];
            }else{
                $page=1;
            }
        }else{
            $page=1;
        }
        if(isset($_GET["lnPage"])){
            if(empty($_GET["lnPage"])){
                $lnPage=25;
            }else{
                $lnPage=(int) $_GET["lnPage"];
            }            
        }else{
            $lnPage=25;
        }

        $sql_nb="SELECT COUNT(*) AS nb_villes FROM villes_france_free";
        $query_nb=$bdd->query($sql_nb);
        // fetch: récupère la ligne suivante de ma requête: donc ici, c'est la 1ère ligne
        // le résultat est un tableau associatif
        $result=$query_nb->fetch();

        $totalLignes=$result["nb_villes"];
        $totalPages= ceil($totalLignes / $lnPage);
        $firstRecord=($page * $lnPage) - $lnPage;

        $sql="SELECT ville_id, ville_nom, ville_code_postal, ville_population_2012, ville_surface 
        FROM villes_france_free 
        ORDER BY ville_nom LIMIT :firstRecord, :lnPage";
        $query=$bdd->prepare($sql);
        $query->bindValue(':firstRecord', $firstRecord, PDO::PARAM_INT);
        $query->bindValue(':lnPage', $lnPage, PDO::PARAM_INT);
        $query->execute();

        $pageSuivante=$page+1;
        $pagePrecedente=($page>1)? $page-1: $page;
        ?>

        <h1>Les villes de France</h1>
        <form action="#" method="get">
            <label for="lnPerPlnPageage">Lignes par page</label>
            <select name="lnPage" id="lnPage" value=<?php echo $lnPage?>>
                <option <?php if($lnPage==25){echo "selected";}?>>25</option>
                <option <?php if($lnPage==50){echo "selected";}?>>50</option>
                <option <?php if($lnPage==100){echo "selected";}?>>100</option>
            </select>
            <label for="page">Aller à la page</label>
            <input id="page" name="page">
            <input type="submit" value="Mise à jour">
            <p>Page <?php echo "$page / $totalPages" ?></p>
            <div>
                <nav>
                    <ul class="pagination">
                        <li>
                            <a <?php if ($page==1){echo 'class="disabled"';}else{echo 'class="enable"';}?> href="tp_villes-binding.php?page=1<?php echo "&lnPage=$lnPage"; ?>" title="Première page"><i class="fa-solid fa-backward-step"></i></a>
                        </li>
                        <li>
                            <a <?php if ($page==1){echo 'class="disabled"';}else{echo 'class="enable"';}?> href="tp_villes-binding.php?page=<?php echo "$pagePrecedente&lnPage=$lnPage"; ?>" title="Page précédente"><i class="fa-solid fa-chevron-left"></i></a>
                        </li>
                        <li>
                            <a <?php if ($page==$totalPages){echo 'class="disabled"';}else{echo 'class="enable"';}?> href="tp_villes-binding.php?page=<?php echo "$pageSuivante&lnPage=$lnPage"; ?>" title="Page suivante"><i class="fa-solid fa-chevron-right"></i></a>
                        </li>
                        <li>
                            <a <?php if ($page==$totalPages){echo 'class="disabled"';}else{echo 'class="enable"';}?> href="tp_villes-binding.php?page=<?php echo "$totalPages&lnPage=$lnPage"; ?>" title="Dernière Page"><i class="fa-solid fa-forward-step"></i></a>
                        </li>
                    </ul>
                </nav>
            </div>
        </form>
        <table>
            <caption>Liste des villes</caption>
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Ville</th>
                    <th>Code Postal</th>
                    <th>Population (2012)</th>
                    <th>Surface (km2)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach($query as $ville){
                    $villeNom=$ville["ville_nom"];
                    $codePostal=$ville["ville_code_postal"];
                    $ville_id=$ville["ville_id"];
                    $ville_population_2012=$ville["ville_population_2012"];
                    $ville_surface=$ville["ville_surface"];
                ?>
                <tr>
                    <td>
                        <a href="delete_ville-binding.php?ville_id=<?php echo $ville_id ?>" title="Supprimer"><i class="fa-solid fa-trash-can enable"></i></a>
                        <a href="update-codepostal-binding.php?ville_id=<?php echo $ville_id ?>" title="Modifier le code postal"><i class="fa-solid fa-pen-to-square enable"></i></a>
                    </td>
                    <td>
                        <?php echo $villeNom ?>
                    </td>
                    <td>
                        <?php echo $codePostal ?>
                        
                    </td>
                    <td>
                        <?php echo $ville_population_2012 ?>
                    </td>
                    <td>
                        <?php echo $ville_surface ?>
                    </td>
                </tr>
                <?php 
                }
                ?>
            </tbody>
        </table>
    </body>
</html>
