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

<h1 class="pg_title">Les trajets pouvant desservir une ville donnée dans un intervalle de temps</h1>


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
    <form action="recherche2.php" method="POST">
        <label for="ville_arrivee">Ville d\'arrivée :</label>
        <input type="text" id="ville_arrivee" name="ville_arrivee"><br>

        <label for="date_depart">Date de départ :</label>
        <input type="date" id="date_depart" name="date_depart"><br>

        <label for="intervalle">Intervalle horaire :</label>
        <input type="time" id="heure1" name="heure1">
        <input type="time" id="heure2" name="heure2"><br>

        <input type="submit" value="Rechercher">
    </form>
</div>';

        
        if (isset($_POST['ville_arrivee'], $_POST['heure1'], $_POST['heure2'],$_POST['date_depart'])) {
            $ville_arrivee = $_POST['ville_arrivee'];
            $date_depart= date('Y-m-d', strtotime( $_POST['date_depart']));
            $heure1 = $_POST['heure1'];
            $heure2 = $_POST['heure2'];
            


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
            T.ville_arrive = '$ville_arrivee'
            AND P.date_trajet = '$date_depart'
            AND P.heure_trajet BETWEEN '$heure1'AND '$heure2'
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
