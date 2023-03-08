<?php 
include("lib.php");
head();
?>
    <main role="main" class="container">

      <div class="row">
	   <div class="col-lg-6">
        <form class=bg-success action=neuesLog.php>
		 <input  style='border:10px;width:50%' type=submit value='Neues Log'></form>
		</div>
	   <div class="col-lg-6">
		  <form class=bg-primary action=loggrid.php method=POST>
		    LogId <input style='margin:10px;width:10%' size=5 name=lid>
			Passcode <input style='border:10px;width:10%' name=pss type=password>
			<input style='border:10px;width:10%' type=submit value="Öffen">
		  </form>
		</div>
		
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
