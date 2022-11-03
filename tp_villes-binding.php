<!DOCTYPE html>
<html>
    <head>
        <script src="https://kit.fontawesome.com/16875f3306.js" crossorigin="anonymous"></script>
        <style>
            .pagination {
                list-style-type: none;
                margin: 8px;
                padding: 6px 28px;
                overflow: hidden;
                display: flex;
                justify-content: space-between;
               
                width: 200px;
                background: #d5e1df;
                border-radius: 4px;
                outline: none;
            }

            .pagination li a {
                font-size: 24px;
                text-decoration: none;
            }

            .pagination a:hover {
                color: #3e4444;
            }

            .disabled {
                pointer-events: none;
                cursor: default;
                color: white;
            }

            .enable {
                color: #86af49;
            }

            table {
                border-collapse: collapse;
                table-layout: fixed;
                width: 50%;
                border: 1px solid #d3d3d3;
            }

            td, th {
                /* border: 1px solid black; */
                padding: 8px;
            }

            tr td {
                text-align: center;
            }

            tbody tr:hover {
                background: #dcdcdc;
            }

            th {
                background: #3e4444;
                color: white;
                height: 32px;
            }

            thead th:nth-child(1) {
                width: 10%;
            }
            thead th:nth-child(2) {
                width: 40%;
            }
            thead th:nth-child(3) {
                width: 20%;
            }
            thead th:nth-child(4) {
                width: 15%;
            }
            thead th:nth-child(5) {
                width: 15%;
            }

            tbody tr:nth-child(even) {
                background: #d5e1df;
            }
        </style>
    </head>
    <body>
        <?php
        require "bddConnection.php";
        if(isset($_GET["page"])){
            if($_GET["page"]!==""){
                $page=(int) $_GET["page"];
            }else{
                $page=1;
            }
        }else{
            $page=1;
        }
        if(isset($_GET["lnPage"])){
            if($_GET["lnPage"]===""){
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
