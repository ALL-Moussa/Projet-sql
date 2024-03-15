<!DOCTYPE HTML>
<html>
    <head>
        <title>Contrôle de la base de données</title>
        <link rel="stylesheet" type="text/css" href="index.css" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@200;400&display=swap" rel="stylesheet"> 
    </head>
    <body>
        <h1 class="pg_title">Interface de contrôle de la base de données: Covoiturage campus</h1>
        <?php

            $login = 'ybenmoh' ;
            $db_pwd = 'yasserbenmoh' ;

            /* Creation de l'objet qui gere la connexion: */
            $connection_string = "host=zzz.bordeaux-inp.fr port=5432 dbname=".$login." user=".$login." password=".$db_pwd;

            $connection = pg_connect($connection_string);
            if(!$connection) {
                echo '<div style="background-color:red;; text-align:center; color:black; padding:5px;">non connecté à la base de données<br>
                        <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/alter.php">Actualiser la page</a></div><br>';
            }else{
                echo '<div style="background-color:#90ee90;; text-align:center; color:black; padding:5px;">connecté à la base de données<br>
                        <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/alter.php">Actualiser la page</a> | <a href="https://ybenmoh.zzz.bordeaux-inp.fr/sgbd/">Retour à l\'acceuil</a></div><br>';
            }
        ?>
        <div class="choix" style="text-align:center;">
            <p style="padding: 5px; background-color:white; border-color:black; border-style: groove; border-right:none; border-left:none;">Choisissez la table que vous voulez modifier:</p>
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
                echo '<form method="POST" action="alter.php">';
                while ($row = pg_fetch_assoc($res)) {
                    if($f != 0){
                        foreach ($columnNames as $columnName) {
                            echo '<input type="checkbox" name="table" value="'.$row[$columnName].'" >'.$row[$columnName].'  '; 
                        }  
                    }   
                    $f = $f+1;
                }
                pg_free_result($res);
            }
        ?>
            <p style="padding: 5px; background-color:white; border-color:black; border-style: groove;">Choisissez l'operation: </p>
            <input type="checkbox" name="op" value="ajout" checked>Ajout
            <input type="checkbox" name="op" value="suppression">Suppression
            <input type="checkbox" name="op" value="modification">Modification<br><br>
            <input class="req_submit" type="submit" value="Exécuter" name="Result">
        </form>
        </div><br>
        <?php
            function getInputTypeForColumnType($columnType){
                switch ($columnType) {
                    case 'varchar':
                    case 'text':
                    case 'char':
                    case 'bpchar':
                    case 'name':
                    case 'citext':
                        return 'text';
                    case 'int':
                    case 'int4':
                    case 'integer':
                    case 'smallint':
                    case 'bigint':
                    case 'serial':
                    case 'bigserial':
                        return 'number';
                    case 'numeric':
                    case 'decimal':
                        return 'number';
                    case 'real':
                    case 'float4':
                    case 'double precision':
                    case 'float8':
                        return 'number';
                    case 'date':
                        return 'date';
                    case 'time':
                        return 'time';
                    case 'timestamp':
                        return 'datetime-local';
                    case 'boolean':
                        return 'checkbox';
                    // Add more cases for other data types as needed
                    default:
                        return 'text'; // Default to text for unknown types
                }
            }
            function isColumnPrimaryKey($table, $column, $connection) {
                $query = "SELECT kcu.column_name
                          FROM information_schema.key_column_usage kcu
                          JOIN information_schema.table_constraints tc
                          ON kcu.constraint_name = tc.constraint_name
                          WHERE tc.table_name = '$table'
                            AND kcu.column_name = '$column'
                            AND tc.constraint_type = 'PRIMARY KEY'";
                
                $result = pg_query($connection, $query);
            
                if ($result) {
                    $numRows = pg_num_rows($result);
                    return $numRows > 0;
                } else {
                    return false; // Error in the query
                }
            }
            if(isset($_POST["ligne_supp"])){
                $indexes = $_POST["ligne_supp"];
                $table = $_GET["table"];
                $res = pg_query($connection, "SELECT * FROM $table");
                $id_col = pg_field_name($res, 0);
                foreach($indexes as $index){
                    $query = "DELETE FROM $table WHERE $id_col=$index";
                    $res = pg_query($connection, $query);
                    if(!$res) {
                        echo pg_last_error($connection);
                    }
                }
            }
            if(isset($_POST["ligne_ajout"])){
                $values = $_POST["ligne_ajout"];
                $table = $_GET["table"];
                $res = pg_query($connection, "SELECT * FROM $table"); 
                if($res) {
                    $numFields = pg_num_fields($res);
                    $columnNames = array();
                    for ($i = 0; $i < $numFields; $i++) {
                        if(!isColumnPrimaryKey($table, pg_field_name($res,$i),$connection)){
                            $columnNames[] = pg_field_name($res, $i);
                        }
                    }
                }
                $query = "INSERT INTO $table (";
                $tst = 0;
                foreach($columnNames as $name){
                    if($tst == 0){
                        $query .= "$name";
                    }else{
                        $query .= ",$name";
                    }
                    $tst = $tst +1;
                }
                $query .= ") VALUES (";
                $tst = 0;
                foreach($values as $value){
                    if($tst == 0){
                        $query .= "'$value'";
                    }else{
                        $query .= ",'$value'";
                    }
                    $tst = $tst+1;
                }
                $query = $query.");";
                $res = pg_query($connection, $query);
                if(!$res) {
                    echo pg_last_error($connection);
                }
            }
            if(isset($_POST["ligne_for_modif"])){
                $values = $_POST["ligne_for_modif"];
                $table = $_GET["table"];
                $id = $_GET["id"];
                $res = pg_query($connection, "SELECT * FROM $table"); 
                if($res) {
                    $numFields = pg_num_fields($res);
                    $columnNames = array();
                    for ($i = 0; $i < $numFields; $i++) {
                        if(!isColumnPrimaryKey($table, pg_field_name($res,$i),$connection)){
                            $columnNames[] = pg_field_name($res, $i);
                        }else{
                            $id_col = pg_field_name($res, $i);
                        }
                    }
                }
                foreach($columnNames as $name){
                    $query = "UPDATE $table SET $name='$values[$name]' WHERE $id_col=$id";
                    $res = pg_query($connection, $query);
                    if(!$res) {
                        echo pg_last_error($connection);
                    }
                }
            }
            
            if(isset($_POST["ligne_modif"])){
                $id_to_modify = $_POST["ligne_modif"];
                $table = $_GET["table"];
                $query = "SELECT * FROM $table";
                $res = pg_query($connection, $query);
                if($res){
                    $id_column = pg_field_name($res, 0);
                }
                $query = "SELECT * FROM $table WHERE $id_column=$id_to_modify";
                $res = pg_query($connection, $query);
                if ($res) {
                    $numFields = pg_num_fields($res);
                    $columnNames = array();
                    $columnTypes = array();
                    for ($i = 0; $i < $numFields; $i++) {
                        $columnNames[] = pg_field_name($res, $i);
                        $columnTypes[] = pg_field_type($res, $i);
                    }
                    echo '<div style="text-align:center;"><form method="POST" action="alter.php?table=' . $table . '&id='.$id_to_modify.'"><table border="1" class="req_table"><tr>';
                    foreach ($columnNames as $index => $columnName) {
                        echo '<th>' . $columnName . '</th>';
                    }
                    echo '</tr>';
                    echo '<tr>';
                    while ($row = pg_fetch_assoc($res)) {
                        foreach ($columnNames as $index => $columnName) {
                            $type = $columnTypes[$index];

                            if (isColumnPrimaryKey($table, $columnName,$connection)) {
                                echo '<td><input type="text" value="'.$row[$columnNames[$index]].'" disabled></td>';
                            } else {
                                echo '<td><input type="' . getInputTypeForColumnType($type) . '" name="ligne_for_modif[' . $columnName . ']" value="'.$row[$columnNames[$index]].'"></td>';
                            }
                        }
                    }
                    echo '</tr>';
                    echo '</table><br><input class="req_submit" type="submit" value="Modifier" name="ajout"></form></div><br>';
                    pg_free_result($res);
                }
            }


            if(isset($_POST["op"]) && isset($_POST["table"])){
                $op = $_POST["op"];
                $table = $_POST["table"];
                if($op == "suppression"){
                    $query = "SELECT * FROM $table";
                    $res = pg_query($connection, $query);
                    if($res) {
                        $numFields = pg_num_fields($res);
                        $columnNames = array();
                        for ($i = 0; $i < $numFields; $i++) {
                            $columnNames[] = pg_field_name($res, $i);
                        }
                        echo '<div style="text-align:center;"><form method="POST" action="alter.php?table='.$table.'"><table border="1" class="req_table"><tr>';
                        foreach ($columnNames as $columnName) {
                            echo '<th>' . $columnName . '</th>';
                        }
                        echo '<th>supprimer</th>';
                        echo '</tr>';
                        while ($row = pg_fetch_assoc($res)) {
                            echo '<tr>';
                            foreach ($columnNames as $columnName) {
                                echo '<td>' . $row[$columnName] . '</td>';
                            }
                            echo '<td><input type="checkbox" name="ligne_supp[]" value='.$row[$columnNames[0]].'></td>';
                            echo '</tr>';
                        }
                        echo '</table><br><input class="req_submit" type="submit" value="Supprimer" name="Result"></form></div><br>';
                        pg_free_result($res);
                    }
                }elseif($op == "ajout"){
                    $query = "SELECT * FROM $table";
                    $res = pg_query($connection, $query);

                    if ($res) {
                        $numFields = pg_num_fields($res);
                        $columnNames = array();
                        $columnTypes = array();
                        for ($i = 0; $i < $numFields; $i++) {
                            $columnNames[] = pg_field_name($res, $i);
                            $columnTypes[] = pg_field_type($res, $i);
                        }
                        echo '<div style="text-align:center;"><form method="POST" action="alter.php?table=' . $table . '"><table border="1" class="req_table"><tr>';
                        foreach ($columnNames as $index => $columnName) {
                            echo '<th>' . $columnName . '</th>';
                        }
                        echo '</tr>';
                        echo '<tr>';
                        foreach ($columnNames as $index => $columnName) {
                            $type = $columnTypes[$index];

                            if (isColumnPrimaryKey($table, $columnName,$connection)) {
                                echo '<td><input type="text" disabled></td>';
                            } else {
                                echo '<td><input type="' . getInputTypeForColumnType($type) . '" name="ligne_ajout[' . $columnName . ']"></td>';
                            }
                        }
                        echo '</tr>';
                        echo '</table><br><input class="req_submit" type="submit" value="Ajouter" name="ajout"></form></div><br>';
                        pg_free_result($res);
                    }
                }elseif($op == "modification"){
                    $query = "SELECT * FROM $table";
                    $res = pg_query($connection, $query);
                    if($res) {
                        $numFields = pg_num_fields($res);
                        $columnNames = array();
                        for ($i = 0; $i < $numFields; $i++) {
                            $columnNames[] = pg_field_name($res, $i);
                        }
                        echo '<div style="text-align:center;"><form method="POST" action="alter.php?table='.$table.'"><table border="1" class="req_table"><tr>';
                        foreach ($columnNames as $columnName) {
                            echo '<th>' . $columnName . '</th>';
                        }
                        echo '<th>Modifier</th>';
                        echo '</tr>';
                        while ($row = pg_fetch_assoc($res)) {
                            echo '<tr>';
                            foreach ($columnNames as $columnName) {
                                echo '<td>' . $row[$columnName] . '</td>';
                            }
                            echo '<td><input type="radio" name="ligne_modif" value='.$row[$columnNames[0]].'></td>';
                            echo '</tr>';
                        }
                        echo '</table><br><input class="req_submit" type="submit" value="Modifier" name="Result"></form></div><br>';
                        pg_free_result($res);
                    }
                }
            }
        ?>
        <form action="alter.php" method="post" class="req_form">
            <p>Modification par requête libre</p>
            <textarea class="req_input" name="requete" placeholder="Ici écrire n'importe quelle requête"></textarea><br>
            <input type="submit" class="req_submit" value="Exécuter">
        </form><br>
        <?php
        if(isset($_POST['requete'])){
            $query = $_POST['requete'];
            $res = pg_query($connection, $query);
            if(!$res) {
                echo pg_last_error($connection);
            }
            pg_free_result($res);
        }?>
        <?php pg_close($connection); ?>
    </body>
</html>