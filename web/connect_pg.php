<?php
// these are fake login and password
  $login = 'alogin'   ; 
  $db_pwd = 'apassword'  ;

  /* Creation de l'objet qui gere la connexion: */
  $connection_string = "host=zzz.bordeaux-inp.fr port=5432 dbname=".$login." user=".$login." password=".$db_pwd;

  $connection = pg_connect($connection_string);
  if(!$connection) {
     echo 'error';
  }else{
    echo 'connected';
  }

?>
