<?php
include('lib.php');
#header('Content-Type: application/json; charset=utf-8');
#einige Funktionen (Filter für Suche werden noch nicht genutzt)
$table=$_POST['table'];
$sqlAtts = $_POST['sqlAtts'];
$order = $_POST['order'];
if (isset($_POST['suche'])) $suche=$_POST['suche'];
if (isset($_POST['filter'])) $filter=$_POST['filter'];
$lid=$_SESSION['lid'];

logAction('quelle.php-Suche: '.$suche);

if (isset($_POST['suche'])) {
//print_r($_POST);
  $alist=preg_split('/,/',$sqlAtts);
  foreach ($alist as $an) $where.="$an like '%$suche%' or ";
  $where="where ($where lid>$lid)";
}

#hier prüfen ob Log noch leer - wenn ja erstes QSO erzeugen
$anz=sql2val("select count(*) from qso where status='+' and lid='$lid'");
if ($anz==0) $mysqli->query("insert into qso (lid,txnr) values($lid,1)");

if (isset($_POST['filter'])) {
  if ($where=='') $where="where $filter";
  else $where=$where." and $filter";
}

$sql="select $sqlAtts from $table where status='+' and lid='$lid' $order";

logAction('Quelle: '.$sql);
$result = $mysqli->query($sql);
#echo("Error description: " . $mysqli -> error);
while ($row = $result->fetch_assoc()) {
  $output[]=$row;
}
print(json_encode($output,JSON_UNESCAPED_UNICODE));
?>
