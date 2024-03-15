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

<h1 class="pg_title">Les trajets proposés dans un intervalle de jours donné, la liste des villes renseignées entre
                        le campus et une ville donnée</h1>


<?php
$login = 'ybenmoh';
$db_pwd = 'yasserbenmoh';

$connection_string = "host=zzz.bordeaux-inp.fr port=5432 dbname=" . $login . " user=" . $login . " password=" . $db_pwd;

$connection = pg_connect($connection_string);
if (!$connection) {
    echo '<div style="background-color:red; text-align:center; color:black; padding:5px;">non connecté à la base de données<br>
            <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/">Actualiser la page</a></div><br>';
} else {
    echo '<div style="background-color:#90ee90; text-align:center; color:black; padding:5px;">connecté à la base de données<br>
            <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/">Acceuil</a></div><br>';
        
     echo'       <div class="centered-form">
    <form action="recherche1.php" method="POST">
        <label for="ville_depart">Ville de départ :</label>
        <input type="text" id="ville_depart" name="ville_depart"><br>

        <label for="ville_arrivee">Ville d\'arrivée :</label>
        <input type="text" id="ville_arrivee" name="ville_arrivee"><br>

        <label for="date1">Intervalle de dates :</label>
        <input type="date" id="date1" name="date1">
        <input type="date" id="date2" name="date2"><br>

        <input type="submit" value="Rechercher">
    </form>
</div>';

        
        if (isset($_POST['ville_depart'],$_POST['ville_arrivee'], $_POST['date1'], $_POST['date2'])) {
            $ville_depart = $_POST['ville_depart'];
            $ville_arrivee = $_POST['ville_arrivee'];
            $date1 = date('Y-m-d', strtotime( $_POST['date1']));
            $date2 =  date('Y-m-d', strtotime( $_POST['date2']));
            


            $query = "SELECT
            P.numero_proposition,
            T.numero_trajet,
            T.ville_depart,
            T.ville_arrive,
            P.date_trajet,
            P.heure_trajet,
            A.ville AS ville_intermediaire
        FROM
            propositions_trajet P
        JOIN
            trajets T ON P.numero_trajet = T.numero_trajet
        LEFT JOIN
            propositions_arret PA ON P.numero_proposition = PA.numero_proposition
        LEFT JOIN
            arrets A ON PA.numero_arret = A.numero_arret
        WHERE
            P.date_trajet BETWEEN '$date1' AND '$date2' 
            AND T.ville_depart = '$ville_depart' 
            AND T.ville_arrive = '$ville_arrivee' 
        ";
            $res = pg_query($connection, $query);
            if ($res) {
                $numFields = pg_num_fields($res);
                $columnNames = array();
                for ($i = 0; $i < $numFields; $i++) {
                    $columnNames[] = pg_field_name($res, $i);
                }
                echo '<div class="req_table"><table border="1"><tr>';
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
                echo pg_last_error($connection);
            }
        } else {
            echo "Veuillez remplir tous les champs du formulaire.";
        }
    }
    pg_close($connection);
?>
</body>
</html>
