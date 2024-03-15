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

<h1 class="pg_title">La liste des véhicules disponibles pour un jour donné pour une ville donnée</h1>


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
    <form action="recherche.php" method="POST">
        <label for="ville_depart">Ville de départ :</label>
        <input type="text" id="ville_depart" name="ville_depart"><br>

        <label for="date_depart">Date de départ :</label>
        <input type="date" id="date_depart" name="date_depart"><br>

        <input type="submit" value="Rechercher">
    </form>
</div>';

        
        if (isset($_POST['ville_depart'], $_POST['date_depart'])) {
            $ville_depart = $_POST['ville_depart'];
            $date_depart = $_POST['date_depart'];
            $date_sql = date('Y-m-d', strtotime($date_depart));


            $query = "SELECT
            T.ville_depart,
            P.date_trajet,
            V.numero_voiture,
            V.type_voiture,
            V.couleur,
            V.nombres_places,
            P.heure_trajet,
            T.ville_depart
            FROM
            propositions_trajet P
            JOIN
            conducteurs C ON P.numero_conducteur = C.numero_conducteur
            JOIN
            voitures V ON C.numero_voiture = V.numero_voiture
            JOIN
            trajets T ON P.numero_trajet = T.numero_trajet
            WHERE
            T.ville_depart = '$ville_depart'
            AND P.date_trajet = '$date_sql'";

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

