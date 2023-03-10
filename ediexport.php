<?php
#header('Content-Type: application/json; charset=utf-8');
header('Content-Type: text/plain; charset=utf-8');
header('Content-Disposition: attachment; filename="edilog.edi"');
session_start();
$lid=$_SESSION['lid'];
$passcode=$_SESSION['passcode'];
$mysqli = new mysqli("localhost", "edilog", "o2vmK6cHWikX1KgF", "edilog");
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
#testen ob logid/passcode matchen
$sql="select txcall, loc from log where lid=$lid and passcode='$passcode'";
#print $sql;
$result=$mysqli->query($sql);
#print_r($result);
if ($row=$result->fetch_row()) {
		//print "Call $row[0] xx";	
} else {
	print "<h3>Logid/Passwort passen nicht .... Exit</h3>";
	exit;
}



#print_r($_SESSION);
	$txcall=sql2val('select txcall from log where lid='.$lid);
	$txlocator=sql2val('select loc from log where lid='.$lid);
	$email=sql2val('select email from log where lid='.$lid);
	$qsozahl=sql2val('select count(*) from qso where lid='.$lid);
	$qrg=sql2val('select max(qrg) from qso where lid='.$lid);
$datum1='230304';
$datum2='230305';
print "[REG1TEST;1]
TName=DARC-UKW
TDate=$datum1;$datum2
PCall=$txcall
RCall=$txcall
PWWLo=$txlocator
PSect=SINGLE
PBand=$qrg MHz
PClub=W37
MOpe1=
MOpe2=
RHBBS=$email
[Remarks]
Test-EDI von DL6MHW erzeugt ... falls es Probleme macht sofort 
Beischeid sagen. Ich höre dann auf.
[QSORecords;$qsozahl]\n";

$sql="select qrg, utc, rxcall, rxrst, rxnr, rxloc, txrst, txnr, mode from qso where lid=$lid and status='+' order by txnr";
#print $sql;
$result = $mysqli->query($sql);
#echo("Error description: " . $mysqli -> error);
$anfang='';
#950304;1445;OZ9SIG;1;59;001;59;006;;JO65ER;6;;N;N;
#230305;1039;DO2PSW;1;59;6;59;19;;JO52TG;57;;N;N;
while ($row = $result->fetch_row()) {
   # print_r($row);
   $qrg=$row[0];
   $utc=$row[1];
   $utc=intval(str_replace(":","",$utc)); 		
   #Datum aus Zeit nur für 14 UTC Contest
   if ($utc>1399) $datum=$datum1; else $datum=$datum2;
   if ($utc<1000) $utc="0$utc";
   $rxcall=$row[2];
   $rxrst=$row[3];
   $rxnr=$row[4];
   $rxloc=$row[5];
   $txrst=$row[6];
   $txnr=$row[7];
   $mode=$row[8];
   $modeflag='1'; #SSB-SSB-QSO   
   #print_r($row);
   print "$datum;$utc;$rxcall;$modeflag;$txrst;$txnr;$rxrst;$rxnr;;$rxloc;57;;N;N;\n";
}
function sql2val($sql) {
	global $mysqli;
	#print $sql;
	$result = $mysqli->query($sql); 
    if ($row = $result->fetch_array(MYSQLI_NUM)) return $row[0];
	return '';
}	

?>
