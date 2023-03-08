<?php 
$mysqli = new mysqli("localhost", "edilog", "o2vmK6cHWikX1KgF", "edilog");
/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
    #printf("Connect OK: %s\n", $mysqli->connect_error);

session_start();





function head() {?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>EDILOG (DL6MHW)</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">
<style>
table {
    border-collapse: collapse;
        margin-top:10px;
}
td, th {
    border: 0.5px solid black;
}

th {
  background-color:#1a1;
  color:white;
  padding-left:0.2em;
  text-transform: capitalize;
}

tr:hover {
    background-color: #aaa;
}
input {
  border: 0px solid red;
  margin:1px;
  width:100%;
}
tr.modified {
  background-color:#ff3333;
}
	
.container{
    max-width:2600px;
    margin:0 auto;/*make it centered*/
}

.odd{color:#000;background-color:#EEEEEE}
input.xhidden {
  visibility: hidden;
}
tr.xhidden {
  visibility: hidden;
}
td.xhidden {
  display: none;
}
th.xhidden {
  display: none;
}

div.xhidden {
  display: none;
}

</style>	
	
<script>

function setKopfdaten() {
  sqlAtts={};
  sqlAtts['txcall']=$("#txcall").val().toUpperCase();
  sqlAtts['loc']=$("#txlocator").val().toUpperCase();
  sqlAtts['email']=$("#email").val();
  logid=$('#logid').text();
  sqlAtts['lid']=logid;       

  sqlJSON = JSON.stringify(sqlAtts);
  //alert(sqlAtts);
  console.log(sqlJSON);
  $.post("save.php", {table:"log",sqlAtts:sqlJSON},
       	function(msg){ 
			console.log(msg);
                if (msg=='Success') {
					alert('Änderung erfolgreich');
				}
				else alert('Speicherproblem - Werte falsch?');
				if (modCalls==0) myQuery();
	           // HIER NOCH TESTEN OB SUCSESS'
	        });
}	
</script>	
  </head>

  <body>
<?php #PBO (Prozess befor Input .... like SAP)
#Prüfugen und diver Kopdaten
if (strpos($_SERVER[PHP_SELF],'index.php')==0  and strpos($_SERVER[PHP_SELF],'neuesLog.php')==0) {
	if (isset($_POST[lid])) $_SESSION['lid']=$_POST['lid'];
	if (isset($_POST[pss])) $_SESSION['passcode']=$_POST['pss'];
	$lid=$_SESSION['lid'];
	$passcode=$_SESSION['passcode'];
	global $mysqli;
	#testen ob logid/passcode matchen
	$sql="select txcall, loc from log where lid='$lid' and passcode='$passcode'";
	#print $sql;
	$result=$mysqli->query($sql);
	#exit;
	#print_r($result);
	if ($row=$result->fetch_row()) {
		//print "Call $row[0] xx";	
	} else {
		print "<h3>Logid/Passwort passen nicht .... Exit</h3>";
		exit;
	}
	$txcall=sql2val('select txcall from log where lid='.$lid);
	$txlocator=sql2val('select loc from log where lid='.$lid);
	$email=sql2val('select email from log where lid='.$lid);
}
else {
  unset($_SESSION['lid']);	
  unset($_SESSION['passcode']);	
}	
#print "Ende Prüfung";
?>

 <!-- Navigation -->
  
<nav class="navbar navbar-expand-lg navbar-light bg-success">
  <a class="navbar-brand" href="index.php">
    <img src="warne.png" width="60" height="60" class="d-inline-block align-top" alt="" >
    <span  style="color:#FFF;font-size:200%">Edilog Mar-23</span>
  </a>
<?php
  
  #Bereich nicht für Index
  
if (strpos($_SERVER['PHP_SELF'],'index.php')==0) {
	
  	
  print '<label class="text-white">Call:</label><input size=8 id=txcall style="width:100px" value="'.$txcall.'" onchange="setKopfdaten()"></input> _
  <label class="text-white">Locator:</label><input size=8 style="width:100px" id=txlocator value="'.$txlocator.'" onchange="setKopfdaten()"></input> _
  <label class="text-white">Email:</label><input type=email size=20 style="width:200px" id=email value="'.$email.'"  onchange="setKopfdaten()"></input>
  <label class="text-white" id=logid>'.$lid.'</label>';
  #print '<label class=bg-white style="margin-right:0px" id=clock></label>';
}
?>  
</nav>
<?php }

function sql2val($sql) {
	global $mysqli;
	#print $sql;
	logAction("sql2val:$sql");
	$result = $mysqli->query($sql); 
    if ($row = $result->fetch_array(MYSQLI_NUM)) return $row[0];
	return '';
}	

function logAction($info) {
  global $mysqli;
  $sql="insert into log (info) values ('$info')";
  $query = sprintf("insert into action (info) values ('%s')", mysqli_real_escape_string($mysqli, $info));
  $result = mysqli_query($mysqli, $query);
}

?>
