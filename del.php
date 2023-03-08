<?php
#Delete setzt Status auf - ... 
include('lib.php');
  #print_r($_POST);

  $table='qso';
  $id=$_POST['id'];
  $sql="update $table set status='-' where id=$id";
	  #print $sql;
  logAction('Delete: '.$sql);
  if($mysqli->query($sql)) {
   echo "Success";
  } else {
   echo "Cannot Delete";
  }     
      
?>