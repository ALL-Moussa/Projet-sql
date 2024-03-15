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

<h1 class="pg_title">Inscription</h1>


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
    }        
?>
    <div class="choix" style="text-align:center;">
        <p style="padding: 5px; background-color:white; border-color:black; border-style: groove;">Choisissez votre type d'inscription:</p>

        <!-- Formulaire pour les choix -->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="checkbox" name="op[]" value="conducteur" checked>Conducteur
    <input type="checkbox" name="op[]" value="passager">Passager<br><br>
    <input class="req_submit" type="submit" value="Continuer" name="Result">
</form>

    </div>
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
            ?>
    <?php
            if(isset($_POST["ligne_ajout"])){
                $values = $_POST["ligne_ajout"];
                $table = $_GET["table"];
                print_r($table);
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
                if ($table == 'voitures'){
                    $ID = "SELECT MAX(numero_voiture) FROM voitures";
                    $newID = pg_query($connection, $ID);
                    $row = pg_fetch_assoc($newID);
                    $idd = $row['max'];
                    $values2 = $_POST['ligne_ajout_conducteurs'];
                    $m = $values2['numero_etudiant'];
                    $query = "INSERT INTO conducteurs (numero_voiture,numero_etudiant) VALUES ($idd,$m)";
                    $res = pg_query($connection, $query);
                if(!$res) {
                    echo pg_last_error($connection);
                }

            }
        }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST['op'])) {
            $choix = $_POST['op']; // Récupération des valeurs sélectionnées dans un tableau
            foreach ($choix as $valeur) {
                if ($valeur == 'conducteur'){
                    $query = "SELECT * FROM conducteurs";
                    $res = pg_query($connection, $query);

                    if ($res) {
                        $numFields = pg_num_fields($res);
                        $columnNames = array();
                        $columnTypes = array();
                        for ($i = 0; $i < $numFields; $i++) {
                            $columnNames[] = pg_field_name($res, $i);
                            $columnTypes[] = pg_field_type($res, $i);
                        }
                        echo '<div style="text-align:center;"><form method="POST" action="inscription.php?table=voitures"><table border="1" class="req_table"><tr>';
                        foreach ($columnNames as $index => $columnName) {
                            echo '<th>' . $columnName . '</th>';
                        }
                        echo '</tr>';
                        echo '<tr>';
                        foreach ($columnNames as $index => $columnName) {
                            $type = $columnTypes[$index];

                            if (isColumnPrimaryKey('conducteurs', $columnName,$connection)) {
                                echo '<td><input type="text" disabled></td>';
                            }
                            else if ($columnName != 'numero_voiture') {
                                echo '<td><input type="' . getInputTypeForColumnType($type) . '" name="ligne_ajout_conducteurs[' . $columnName . ']"></td>';
                            }
                        }
                        echo '</tr>';
                        echo '</table><br></div><br>';
                        pg_free_result($res);
                    }
                    $query = "SELECT * FROM voitures";
                    $res = pg_query($connection, $query);

                    if ($res) {
                        $numFields = pg_num_fields($res);
                        $columnNames = array();
                        $columnTypes = array();
                        for ($i = 0; $i < $numFields; $i++) {
                            $columnNames[] = pg_field_name($res, $i);
                            $columnTypes[] = pg_field_type($res, $i);
                        }
                        echo '<div style="text-align:center;"><table border="1" class="req_table"><tr>';
                        foreach ($columnNames as $index => $columnName) {
                            echo '<th>' . $columnName . '</th>';
                        }
                        echo '</tr>';
                        echo '<tr>';
                        foreach ($columnNames as $index => $columnName) {
                            $type = $columnTypes[$index];

                            if (isColumnPrimaryKey('voitures', $columnName,$connection)) {
                                echo '<td><input type="text" disabled></td>';
                            } else {
                                echo '<td><input type="' . getInputTypeForColumnType($type) . '" name="ligne_ajout[' . $columnName . ']"></td>';
                            }
                        }
                        echo '</tr>';
                        echo '</table><br><input class="req_submit" type="submit" value="Ajouter" name="ajout"></form></div><br>';
                        pg_free_result($res);
                    }
                } if ($valeur == 'passager'){
                    $query = "SELECT * FROM passagers";
                    $res = pg_query($connection, $query);

                    if ($res) {
                        $numFields = pg_num_fields($res);
                        $columnNames = array();
                        $columnTypes = array();
                        for ($i = 0; $i < $numFields; $i++) {
                            $columnNames[] = pg_field_name($res, $i);
                            $columnTypes[] = pg_field_type($res, $i);
                        }
                        echo '<div style="text-align:center;"><form method="POST" action="inscription.php?table=passagers"><table border="1" class="req_table"><tr>';
                        foreach ($columnNames as $index => $columnName) {
                            echo '<th>' . $columnName . '</th>';
                        }
                        echo '</tr>';
                        echo '<tr>';
                        foreach ($columnNames as $index => $columnName) {
                            $type = $columnTypes[$index];

                            if (isColumnPrimaryKey('passagers', $columnName,$connection)) {
                                echo '<td><input type="text" disabled></td>';
                            } else {
                                echo '<td><input type="' . getInputTypeForColumnType($type) . '" name="ligne_ajout[' . $columnName . ']"></td>';
                            }
                        }
                        echo '</tr>';
                        echo '</table><br><input class="req_submit" type="submit" value="Ajouter" name="ajout"></form></div><br>';
                        pg_free_result($res);
                    }
                } 
            }
        } else {
            echo "Aucun choix effectué.";
        }
    }
    pg_close($connection);
    ?>
    
    
</body>
</html>