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

            $login = 'ybenmoh' ;
            $db_pwd = 'yasserbenmoh' ;

            /* Creation de l'objet qui gere la connexion: */
            $connection_string = "host=zzz.bordeaux-inp.fr port=5432 dbname=".$login." user=".$login." password=".$db_pwd;

            $connection = pg_connect($connection_string);
            if(!$connection) {
                echo '<div style="background-color:red;; text-align:center; color:black; padding:5px;">non connecté à la base de données<br>
                        <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/">Actualiser la page</a></div><br>';
            }else{
                echo '<div style="background-color:#90ee90;; text-align:center; color:black; padding:5px;">connecté à la base de données<br>
                        <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/">Retour en acceuil</a></div><br>';
            }

        ?>
        <form action="index2.php" method="post" class="req_form">
            <p>Requête libre</p>
            <textarea class="req_input" name="requete" placeholder="Ici écrire n'importe quelle requête"></textarea><br>
            <input type="submit" class="req_submit" value="Exécuter">
        </form><br>
        <?php
        if(isset($_POST['requete'])){
            $query = $_POST['requete'];
            $res = pg_query($connection, $query);
            if($res) {
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
            }
        }?>
        <form action="index2.php" method="post" class="req_form">
        <p>Informations sur les passagers</p>
            <textarea class="req_input" name="requete1">SELECT 
    E.numero_etudiant AS numero_etudiant_passager,
    E.nom_etudiant AS nom_passager,
    E.prenom_etudiant AS prenom_passager
    FROM passagers P
    JOIN Etudiants E ON P.numero_etudiant = E.numero_etudiant;</textarea><br>
            <input type="submit" class="req_submit" value="Exécuter">
        </form><br>
        <?php
        if(isset($_POST['requete1'])){
            $query = $_POST['requete1'];
            $res = pg_query($connection, $query);
            if($res) {
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
            }
        }
        ?>
        <form action="index2.php" method="post" class="req_form">
        <p>Informations sur les conducteurs</p>
            <textarea class="req_input" name="requete2">SELECT 
    E.numero_etudiant AS numero_etudiant_conducteur,
    E.nom_etudiant AS nom_conducteur,
    E.prenom_etudiant AS prenom_conducteur,
    V.numero_voiture AS numero_voiture,
    V.type_voiture AS type_voiture,
    V.couleur AS couleur_voiture
    FROM conducteurs C
    JOIN Etudiants E ON C.numero_etudiant = E.numero_etudiant
    JOIN voitures V ON C.numero_voiture = V.numero_voiture;</textarea><br>
            <input type="submit" class="req_submit" value="Exécuter">
        </form><br>
        <?php
        if(isset($_POST['requete2'])){
            $query = $_POST['requete2'];
            $res = pg_query($connection, $query);
            if($res) {
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
            }
        }
        ?>
        <form action="index2.php" method="post" class="req_form">
        <p>La liste des véhicules disponibles pour un jour donné pour une ville donnée</p>
            <textarea class="req_input" name="requete3">SELECT
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
LEFT JOIN propositions_arret PA ON P.numero_proposition = PA.numero_proposition
LEFT JOIN arrets A ON PA.numero_arret = A.numero_arret 
WHERE
    T.ville_depart = 'City A' 
    AND P.date_trajet = '2023-01-01';</textarea><br>
            <input type="submit" class="req_submit" value="Exécuter">
        </form><br>
        <?php
        if(isset($_POST['requete3'])){
            $query = $_POST['requete3'];
            $res = pg_query($connection, $query);
            if($res) {
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
            }
        }
        ?>
        <form action="index2.php" method="post" class="req_form">
        <p>Les trajets proposés dans un intervalle de jours donné, la liste des villes renseignées entre
le campus et une ville donnée.</p>
            <textarea class="req_input" name="requete4">SELECT
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
    P.date_trajet BETWEEN '2023-01-01' AND '2023-01-10' 
    AND T.ville_depart = 'City A' 
    AND T.ville_arrive = 'City B';</textarea><br>
            <input type="submit" class="req_submit" value="Exécuter">
        </form><br>
        <?php
        if(isset($_POST['requete4'])){
            $query = $_POST['requete4'];
            $res = pg_query($connection, $query);
            if($res) {
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
            }
        }
        ?>
        <form action="index2.php" method="post" class="req_form">
        <p>Les trajets pouvant desservir une ville donnée dans un intervalle de temps</p>
            <textarea class="req_input" name="requete5">SELECT
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
    T.ville_arrive = 'City A'
    AND P.heure_trajet BETWEEN 10 AND 18;</textarea><br>
            <input type="submit" class="req_submit" value="Exécuter">
        </form><br>
        <?php
        if(isset($_POST['requete5'])){
            $query = $_POST['requete5'];
            $res = pg_query($connection, $query);
            if($res) {
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
            }
        }
        ?>
        <form action="index2.php" method="post" class="req_form">
            <p>Moyenne des passagers sur l’ensemble des trajets effectués</p>
            <textarea class="req_input" name="requete6">SELECT
    AVG(nombre_passagers) AS moyenne_passagers
FROM (
    SELECT
        T.numero_trajet,
        COUNT(P.numero_passager) AS nombre_passagers
    FROM
        trajets T
    LEFT JOIN
        passagers P ON T.numero_trajet = P.numero_trajet
    GROUP BY
        T.numero_trajet
) AS passagers_par_trajet;</textarea><br>
            <input type="submit" class="req_submit" value="Exécuter">
        </form><br>
        <?php
        if(isset($_POST['requete6'])){
            $query = $_POST['requete6'];
            $res = pg_query($connection, $query);
            if($res) {
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
            }
        }
        ?>
        <form action="index2.php" method="post" class="req_form">
            <p>Moyenne des distances parcourues en covoiturage par jour.</p>
            <textarea class="req_input" name="requete7">SELECT
    AVG(distance) AS moyenne_distances_parcourues
FROM
    trajets;</textarea><br>
            <input type="submit" class="req_submit" value="Exécuter">
        </form><br>
        <?php
        if(isset($_POST['requete7'])){
            $query = $_POST['requete7'];
            $res = pg_query($connection, $query);
            if($res) {
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
            }
        }
        ?>
        <form action="index2.php" method="post" class="req_form">
            <p>Classement des meilleurs conducteurs d’aprés les avis.</p>
            <textarea class="req_input" name="requete8">SELECT
    C.numero_conducteur,
    E.nom_etudiant || ' ' || E.prenom_etudiant AS nom_prenom,
    AVG(A.note) AS moyenne_notes
FROM
    conducteurs C
JOIN
    Etudiants E ON C.numero_etudiant = E.numero_etudiant
LEFT JOIN
    avis A ON C.numero_conducteur = A.numero_proposition
GROUP BY
    C.numero_conducteur, E.nom_etudiant, E.prenom_etudiant
ORDER BY
    AVG(A.note) DESC;</textarea><br>
            <input type="submit" class="req_submit" value="Exécuter">
        </form><br>
        <?php
        if(isset($_POST['requete8'])){
            $query = $_POST['requete8'];
            $res = pg_query($connection, $query);
            if($res) {
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
            }
        }
        ?>
        <form action="index2.php" method="post" class="req_form">
            <p>Classement des villes selon le nombre de trajets qui les dessert.</p>
            <textarea class="req_input" name="requete9">SELECT
    ville_arrive,
    COUNT(numero_trajet) AS nombre_trajets_desservis
FROM
    trajets
GROUP BY
    ville_arrive
ORDER BY
    COUNT(numero_trajet) DESC;</textarea><br>
            <input type="submit" class="req_submit" value="Exécuter">
        </form><br>
        <?php
        if(isset($_POST['requete9'])){
            $query = $_POST['requete9'];
            $res = pg_query($connection, $query);
            if($res) {
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
            }
        }
        ?>
        <?php
            $query = "SELECT table_name
                        FROM information_schema.tables
                        WHERE table_schema='public'
                        AND table_type='BASE TABLE'";
            $res = pg_query($connection, $query);
            if($res) {
                $numFields = pg_num_fields($res);
                $columnNames = array();
                for ($i = 0; $i < $numFields; $i++) {
                    $columnNames[] = pg_field_name($res, $i);
                }
                $f = 0;
                echo 'Les tables existents: <ul>';
                while ($row = pg_fetch_assoc($res)) {
                    if($f != 0){
                        foreach ($columnNames as $columnName) {
                            echo '<li>'.$row[$columnName].'</li>'; 
                        }  
                    }   
                    $f = $f+1;
                }
                echo '</ul>';
                pg_free_result($res);
            }
        ?>
        <?php pg_close($connection); ?>
        </body>
</html>
