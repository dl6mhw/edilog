<?php
include('lib.php');
  #print_r($_POST);

  $table=$_POST['table'];
  $sqlAtts = json_decode($_POST['sqlAtts'],true);
  #print_r($sqlAtts);
  
  $id=$sqlAtts['id'];
  if ($table=='log') $id=$sqlAtts['lid'];

  if ($id=='neu' or $id=='') {
      foreach($sqlAtts as $n=>$v) { 
         if ($n!='id') {
           $as.="$n,";
           $vs.="'$v',";
         }  
	  };
      $vs=substr($vs, 0, -1);
      $as=substr($as, 0, -1);
      $sql="insert into $table ($as) values ($vs)";
	  #print $sql;
	logAction('Save: '.$sql);
  if($mysqli->query($sql)) {
   echo "Success";
  } else {
   echo "Cannot Update... $sql";
  }
      
	  exit;
      
  }	  
	  
  foreach($sqlAtts as $n=>$v) {
    if ($n!='id') $set.="$n='$v',";
  }	
  $set=substr($set, 0, -1);
  $sql="update $table set $set where id='$id'";
  if ($table=='log') $sql="update $table set $set where lid='$id'";
  #echo $sql;
  logAction('Save: '.$sql);
  if($mysqli->query($sql)) {
   echo "Success";
  } else {
   echo "Cannot Update";
  }
?>