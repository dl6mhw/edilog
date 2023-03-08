<?php 
include("lib.php");
function get_randomcode($max)
{
  $newcode = '';
  for($i=0;$i <= $max;$i++)
  {
    $temp = rand(35,111);
	if ($temp==39) $temp=38;
	if ($temp==60) $temp=61;
	$newcode .= chr($temp);
  }
  return $newcode;
}

$xcode=get_randomcode(6);

$sql="insert into log (txcall,passcode) value('$txcall','$xcode')";
if ($mysqli->query($sql) === TRUE) {
  $last_id = $mysqli->insert_id;
  #echo "New record created successfully. Last inserted ID is: " . $last_id;
} else {
  echo "Error: " . $sql . "<br>" . $mysqli->error;
}
head();
?>
    <main role="main" class="container">


      <div class="starter-template">
<?php print "Ein Log mit der Nummer<br>
<h3 style='background-color:#ee1'>$last_id</h3> <br>
und dem Passcode<br><h3 style='background-color:#ee1'>$xcode</h3><br> wurde angelegt<br>
<b>Bitte Beides notieren!</b>";
$_SESSION['lid']=$last_id;
$_SESSION['passcode']=$xcode;
print "<h3><a href=loggrid.php>Zum Loggen</a></h3>\n";

#print "nix";
#print_r($_SESSION);
?> 
		
      </div>


    </main><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
    <script src="locator.js"></script>
  </body>
</html>
