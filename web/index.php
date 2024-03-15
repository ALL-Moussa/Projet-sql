<!DOCTYPE HTML>
<html>
    <head>
        <title>Résultats Projet</title>
        <link rel="stylesheet" type="text/css" href="index.css" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <h1 class="pg_title">Interface de la base de données: Covoiturage campus</h1>

        <?php
        $login = 'alogin';
        $db_pwd = 'apassword';

        // Connexion à la base de données PostgreSQL
        $connection_string = "host=zzz.bordeaux-inp.fr port=5432 dbname=" . $login . " user=" . $login . " password=" . $db_pwd;

        $connection = pg_connect($connection_string);

        if (!$connection) {
            echo '<div style="background-color:red;; text-align:center; color:black; padding:5px;">non connecté à la base de données<br>
                        <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/">Actualiser la page</a></div><br>';
        } else {
            echo '<div style="background-color:#90ee90;; text-align:center; color:black; padding:5px;">connecté à la base de données<br>
                        <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/">Acceuil</a> | <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/alter.php">  Page de contrôle</a> | <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/index2.php">Selection par requête  </a>| <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/inscription.php">inscription</a> </div><br>';

            try {
                $tableau_chaines = array("Informations sur les passagers", "Informations sur les conducteurs","La liste des véhicules disponibles pour un jour donné pour une ville donnée", "Les trajets proposés dans un intervalle de jours donné,la liste des villes renseignées entre le campus et une ville donnée.","Les trajets pouvant desservir une ville donnée dans un intervalle de temps","Moyenne des passagers sur l’ensemble des trajets effectués","moyenne des distances parcourues en covoiturage par jour","classement des meilleurs conducteurs d’aprés les avis","classement des villes selon le nombre de trajets qui les dessert");
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Exécution des requêtes lors de la soumission du formulaire
                    $sqlFile = 'select.sql';
                    $sqlQueries = file_get_contents($sqlFile);
                    $queries = explode(';', $sqlQueries);

                    foreach ($queries as $index => $query) {
                        if (trim($query) !== '') {
                            if (isset($_POST['execute_query_' . $index])) {
                                $res = pg_query($connection, $query);
                                if ($res) {
                                    $numFields = pg_num_fields($res);
                                    $columnNames = array();
                                    for ($i = 0; $i < $numFields; $i++) {
                                        $columnNames[] = pg_field_name($res, $i);
                                    }
                                    echo '<div class="req_table"><table border="1"><tr>';
                                    echo "<h2>Résultat de la requête $tableau_chaines[$index] :</h2>";
                                    foreach ($columnNames as $columnName) {
                                        echo '<th>' . $columnName . '</th>';
                                    }
                                    echo '</tr>';
                                    while ($row = pg_fetch_assoc($res)) {
                                        echo '<tr>';
                                        foreach ($columnNames as $columnName) {
                                            echo '<td>' . $row[$columnName] . '</td>';
                                        }
                                        echo '</tr>';
                                    }
                                    echo '</table></div><br>';
                                    pg_free_result($res);
                                } else {
                                    echo "Aucun résultat trouvé pour la requête $index.<br><br>";
                                }
                            }
                        }
                    }
                }

                // Affichage des formulaires pour chaque requête
                $sqlFile = 'select.sql';
                $sqlQueries = file_get_contents($sqlFile);
                $queries = explode(';', $sqlQueries);

                foreach ($queries as $index => $query) {
                    if ($index == 2){
                        echo '<a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/recherche.php" class= "req_submit centered-form" style="margin-left:35%; text-decoration:None;">La liste des véhicules disponibles pour un jour donné pour une ville donnée</a></div><br><br>';

                    }
                    else if ($index == 3){
                        echo '<a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/recherche1.php" class= "req_submit centered-form" style="margin-left:26%; text-decoration:None;">Les trajets proposés dans un intervalle de jours donné, la liste des villes renseignées entre
                        le campus et une ville donnée</a></div><br><br>';

                    }
                    else if ($index == 4){
                        echo '<a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/recherche2.php" class= "req_submit centered-form" style="margin-left:35%; text-decoration:None;">Les trajets pouvant desservir une ville donnée dans un intervalle de temps</a></div><br><br>';

                    }
                    else if (trim($query) !== '') {
                        echo "<form action='' method='post' class='req_form'>";
                        echo "<input type='submit' class='req_submit' name='execute_query_$index' value='" . htmlspecialchars($tableau_chaines[$index]) . "'>";

                        echo "</form><br>";
                    }
                }
            } catch (Exception $e) {
                echo "Erreur : " . $e->getMessage();
            } finally {
                pg_close($connection);
            }
        }
        ?>
    </body>
</html>
