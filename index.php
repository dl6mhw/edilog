<?php 
include("lib.php");
head();
?>
    <main role="main" class="container">

      <div class="row">
    	  <div class="col-md-6"> 
        <div class="h-100 p-5 bg-light border rounded-3">
          <h2>Neues Log</h2>
          <p>Ein neues leeres Log wird angelegt und kann auch später mit Logid und Passcode genutzt werden. 
		  Alle Daten werden in der Datenbank gespeichert.</p>
          <form id=myForm1 action=neuesLog.php>
		   <button class="btn  btn-primary" type="button" onclick="neuesLog()">Neues Log</button>
  		  </form>    
        </div>
	 </div>
     <div class="col-md-6">	 
        <div class="h-100 p-5 bg-light border rounded-3">
          <h2>Log öffnen</h2>
          <p>Vorhandenes Log öffen.</p>
          <form id=myForm1 class="border border-success" action=loggrid.php method=POST>
		  		    LogId <input style='border-width:1px;border-color:red;width:30%' size=10 name=lid>
					Passcode <input type=password style='border-width:1px;border-color:red;;width:30%' size=10 name=pss>

		   <button class="btn  btn-primary" type="button" onclick="submit()">Öffnen</button>
  		  </form>    
		  <p>Logid/Passcode vergessen? dl6mhw@darc.de fragen.</p>
        </div>
	   </div>	
      </div>
	  <p></p>
      <div class="row">
    	  <div class="col-md-12"> 
        <div class="h-100 p-5 bg-light border rounded-3">
		<h3> Micro-Doku</h3>
           <pre>
(7.3.2023/DL6MHW)
Motivationen
- Beim Einstieg ein Contestprogramm einzurichten und zu lernen macht extra Aufwand ... 
eigentlich will man erstmal nur funken (Papierlog)
- EDI erzeugen ist nicht ganz einfach

Idee
- Einfachstes Online-Loggen ohne Installation
- Generierung von EDI-Datei für DARC
- Minimaler Aufwand - sofort Loggen
- Datensicherheit - sofortiges Speichern in einem DBMS
- Beschränkung auf wenige QSOs (max 50) 
- Zunächst ein Band pro Log und nur SSB

ToDo/Features
- Loggrid (++-) (Sicherung im Input-Feld)
- automatische Sicherung + Status
- neue Logs anlegen (++-)
- Kopfdatenpflege (+++) (Kopfbereich aufräumen)
- Summe berechnen (+)
- statt löschen "unsichtbar" machen (-)
- Admin-Funktionen (---) (Remove Empty Logs)
- EDI-Generierung (+++) - Einrechung ok, bislang nur 145 und 432

Kleinigkeiten 
- neue leere Zeile wenn Abschluss
- Speichern auch wenn man noch im Input ist
- Nach unten scrollen


Demotivation
- sehr frühe Version - keine Grantie 
		   </pre>
          </div>
          </div>
	  </div>

    </main><!-- /.container -->
<script>
function neuesLog() {
  if (confirm('Wirklich ein neues Log anlegen')) $("#myForm1").submit();	  
}	
</script>

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
