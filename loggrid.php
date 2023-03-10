<?php
include('lib.php');
#include('orgStruktur.php');

head();
#$tabelle=$_GET['tabelle'];
$tabelle='qso';


?>
  <!-- Page Content -->
<script>
function myFunction() {
  const element = document.getElementById("content");
  element.scrollIntoView();
  console.log('myFunctionScroll');
}
</script>
  <div class="container">
	
    <div class="row">
      <div class="col-lg-12">
<?php
#print "nux";
#print_r($_SESSION);
#print '<div id="txt"></div>';
#print 'Call: <button id=mycall value=1>DL6MHW</button> 
#		 Locator: <button id=txlocator value="JO52TG">JO52TG</button> 
#		 LogId: <button id=logid value='.$lid.">$lid</button> ";
#	    <a href="#" id=debug class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Debug</a>
		 ?>
	    <a href="#" id=update class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Speichern</a>
	    <a href="#" id=insert class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Neues QSO</a>
	    <a href="#" id=export class="btn btn-primary btn-sm active" role="button" aria-pressed="true" onclick="window.open('ediexport.php')">EDI erzeugen</a>
	    <a href="#" id=test class="btn btn-primary btn-sm active" role="button" aria-pressed="true" 
		onclick="myFunction()">Test Scroll</a>
    	  <label class="text-black">Punkte:</label>
		  <input size=8 style="width:100px" id=punkte value="0"></input>

	  </div>
	</div>  
    <div class="row">
      <div class="col-lg-12">
        <div id="datagrid"></div>
     </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
	    <a href="#" id=insert2 class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Neues QSO</a>
  <div id="content">
  <p>Some text inside an element.</p>
  <p>Some text inside an element.</p>
  <p>Some text inside an element.</p>
  </div>
   </div>
    </div>
	</div>   <!-- Container Page Content -->


  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.slim.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>

<script>

//startTime();

editStatus=false;
schema= {
	"qso":[ 
		{name:"id",width:"2%"}, 
		{name:"qrg",width:"5%"},
		{name:"mode",width:"5%"}, 
		{name:"utc",width:"5%"},
		{name:"txrst",width:"5%"},
		{name:"txnr",width:"5%"},
		{name:"rxcall",width:"20%"},
		{name:"rxrst",width:"10%"},
		{name:"rxnr",width:"10%"},
		{name:"rxloc",width:"10%"}
		]};



$("#context-menu a").on("click", function() {
  $(this).parent().removeClass("show").hide();
});

$(document).ready(function(){
  //selectFB('start');
  //localStorage.setItem('tabelle', '<?php print $tabelle?>');
  myQuery();
});

/*function startTime() {
  const today = new Date(	);
  let h = today.getHours();
  let m = today.getMinutes();
  let s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('clock').innerHTML =  h + ":" + m + ":" + s;
  setTimeout(startTime, 1000);
}

function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
*/
  
function keyNavigation(e,that) {
      //console.log(e.which+' '+$(that).attr('id'));
	  //alert($(that).parent().parent().attr('id'));
	  $(that).parent().parent().addClass("modified");
	  
     
	 if (e.which == 13)
      {
		saveNow();
      }      
	 else if (e.which == 187)
      {
		insertQSO();
		e.preventDefault();	
      }
     //right
	 else if (e.which == 39 & !editStatus )
      {
        cId=$(that).attr('id');
        id_part = cId.split('-');
        next= parseInt(id_part[1])+1;
        nId = '#' + id_part[0] + '-'+next;
		$(nId).focus();
      }
	  //left
      else if (e.which == 37 & !editStatus )
      {
        cId=$(that).attr('id');
        id_part = cId.split('-');
        next= parseInt(id_part[1])-1;
        nId = '#' + id_part[0] + '-'+next;
		$(nId).focus();
      }
      else if (e.which == 40)
      {
        cId=$(that).attr('id');
        id_part = cId.split('-');
        id_part2 = id_part[0].split('z');
        next= parseInt(id_part2[1])+1;
        nId = '#z' + next+'-'+id_part[1];
		$(nId).focus();
        editStatus=false;  
      }
      else if (e.which == 38) {
        cId=$(that).attr('id');
        id_part = cId.split('-');
        id_part2 = id_part[0].split('z');
        next= parseInt(id_part2[1])-1;
        nId = '#z' + next+'-'+id_part[1];
		$(nId).focus();
        editStatus=false;  
      }
	  else {
		//wenn andere Taste im Feld dann werden Links/Rechts im Feld möglich  
        editStatus=true;  
	  }		  
};

function startSuche(e,that) {
  alert('start Suche:'+e.value);
  myQuery(e.value);
}

function myQuery(suche) {
  //alert('start myQuery');
  $('#datagrid').empty();
  console.log('call mysql '+suche);
  $('#datagrid').empty();
  //tabelle='<?php echo $tabelle;?>';

  tabelle='qso';
  console.log('call mysql tabelle '+tabelle);

  //alert(tabelle);
  //$.post( 'quelle.php',{ table:'veranstaltung',  sqlAtts:'id,kurzzeichen,typ,status,bezeichnung,bemerkung', suche: suche})
  order='';


  console.log(schema);
  
  attdef=schema[tabelle];
  atts='';
  for (i in attdef) {
      atts=atts+attdef[i]['name']+',';
  };
  
  console.log('hier '+atts);


  if (tabelle=='qso') {	 
      filter = 'lid=1'; 
	  order= 'order by id';
  }

  atts = atts.substring(0, atts.length-1);
  console.log('tabelle/atts '+tabelle+atts+filter);  
  $.post( 'quelle.php',{ table:tabelle,  sqlAtts:atts, suche: suche, filter:filter,order: order})
    .done(function( data ) {
	    console.log('data done here'+data);
		json = $.parseJSON(data);
		var tData='<table>'; s=1; z=0;
  attdef=schema['qso'];
  atts='';
		//hier die Überschrift	
		$.each( json, function( key, value ) {
    		iId='z'+z;   
			if (key==0) {
				tData+='<tr id='+iId+'>';
				for (i in attdef) {
					an=attdef[i]['name'];
					aw=attdef[i]['width'];
					if (attdef[i]['th'])  an=attdef[i]['th'];
					console.log(aw+'\n');
					
					if (an!='id') tData+='<th style="width:'+aw+'">'+an+'</th>';
					else tData+='<th class=xhidden style="width:'+aw+'">'+an+'</th>';
					s++;
				};
				
				tData+='<th style="width:10%;text-align:center">Dir</th>';
				tData+='<th style="width:10%;text-align:center">Dist</th>';
				tData+='<th style="width:10%;text-align:center">Action</th></tr>';
			    console.log(tData+'\n');
			}
     		z++;s=1; 
			iId='z'+z;   
			tData+='<tr class=xrow id='+iId+' +>';
			dir=0;
			$.each(value,function(an,av) {
				iId='z'+z+'-'+s; 
				if (av==null) av='';
				readonly='';
				if (attdef[s-1]['modus']=='ro') readonly=' readonly';  	
				if (an!='id') tData+='<td><input class=grid id='+iId+' value="'+av+'" name="'+an+'"'+readonly+'></td>';
				else tData+='<td class=xhidden><input class=grid id='+iId+' value="'+av+'" name="'+an+'" readonly></td>';
				s++;
				if (an=='rxloc') {
					loc1=$('#txlocator').attr('value');
					dist=distance(loc1, av);
					dir=azimuth(loc1,av);
					//dir=azimuth('JO97xw', 'KP20lf');					
			tData+="<td class=richtung style=\"width:3%;text-align:center\">"+dir+"°</td>";
			tData+="<td class=distanz style=\"width:3%;text-align:center\">"+dist+" km</td>";
				}	
				//console.log("\n"+s+"###"+tData);        
			});
			tData+="<td class=dup style=\"width:3%;text-align:center\"><img class=neuesqso src='media/icon_plus.png' width=20>";
			tData+="_<img class=delete src='media/icon_delete.png' width=15></td>";
			tData+='</tr>\n';
			//console.log(tData);
		});
        tData+='</table>\n';
       
	   
		$('#datagrid').append(tData);
  rechne();		
  window.scrollTo(0, document.body.scrollHeight);	    
  console.log('srcoll nach tData append');

		tInput=$('<input>').attr({value:'hu',id:'huid'});     
		$('#datagrid2').append(tInput);  
		$('input').keydown(function(e){
			keyNavigation(e,this);
		});
		
		$('input').click(function(e){
			if (document.activeElement.id != this.id) editStatus=false;
			//alert('click:'+document.activeElement.id);
		});
		
		insertZeile();
		$('.delete').click(function(e){
          deleteDB(this);
        });
		
		$('.neuesqso').click(function(e){
          insertQSO();
        });

		$('.duplicate').click(function(e){
			var tr    = $(this).closest('.xrow');
			var clone = tr.clone();	
			clone.find('input[name=id').val('neu');
			//$clone.find(':text').val('');
            clone.addClass("modified");
			tr.after(clone);
			z=1;
			//Zeilen neu nummerieren aber auch alle Felder!!!
			last=0;
			$('.grid').each(function() {
              cId=$(this).attr('id');
		      $(this).keydown(function(e){keyNavigation(e,this);});
              id_part = cId.split('-');
              zeile = id_part[0].split('z');
              id_part2 = id_part[0].split('z');
              spalte= parseInt(id_part[1]);
              zeile= parseInt(id_part2[1]);
		      if (spalte<last) z=z+1;
		      last=spalte;
              nId = 'z' + z+'-'+spalte;
              console.log(nId); 
		      $(this).attr('id',nId);
            });
            z=0;
			$('.xrow').each(function() {z++; $(this).attr('id','z'+z);});
			//$('.xrow').each(function() {console.log('neu:'+ this.id )});

		});	
		
	});
    
};

function rechne() {
  summe=0;	
  $('.distanz').each(function(key,value) {
	summe=summe+parseInt(value.innerHTML);
  });
  $('#punkte').attr('value',summe);
calls=[];
  $(".grid[id$='-7']").each(function(key,value) {
	if (calls.includes(value.value)) $(this).addClass('bg-warning');
	calls.push(value.value);
  });
}	

function deleteDB(that) {
  $tr    = $(that).closest('.xrow');
  cId=$tr.attr('id');
  delId=$('#'+cId+'-1').val();
  if (!confirm('Achtung echtes Löschen:'+delId)) return;
  tabelle=localStorage.getItem('tabelle');
  console.log('delete in tabelle '+tabelle);
  $.post("del.php", {table:tabelle,id:delId},
        	function(msg){ 
                console.log(msg);
                // HIER NOCH TESTEN OB SUCSESS'
    myQuery();
  });
}

function insertZeile() {
	
		$('input.grid').change(function(e){
          $(this).addClass("modified");
 	      modId="#"+this.parentElement.parentElement.id;         
          $(modId).addClass("modified");
          console.log(modId); 
        });	
				$('input').keydown(function(e){
		});

}	

function ersteZeile() {
  //alert('erste Zeile ohne Daten');	
  var tData='<table>'; s=1; z=1;
  tabelle='qso';
  console.log('erste Zeile Überschrift '+tabelle);
  attdef=schema[tabelle];
  atts='';
  iId='z'+z;   
  tData+='<tr id=z0>';
  tData2='<tr id=z1 class=modified>';
  for (i in attdef) {
	an=attdef[i]['name'];
	aw=attdef[i]['width'];
	av='';
	if (an=='fb') av=fb; //hier wird der default wert gesetzt
	if (an=='studiengang') av=sgInfo[sg]; //hier wird der default wert gesetzt
	console.log(an+'  '+av+'\n');
	if (attdef[i]['th'])  an=attdef[i]['th'];
	if (an!='id') tData+='<th style="width:'+aw+'">'+an+'</th>';
		else tData+='<th class=xhidden style="width:'+aw+'">'+an+'</th>';
	
	//tData+='<th style="width:'+aw+'">'+an+'</th>';
	iId='z'+z+'-'+s; 
	an=attdef[i]['name'];
	if (an!='id') tData2+='<td><input class=grid id='+iId+' value="'+av+'" name="'+an+'"></td>';
		else tData2+='<td class=xhidden><input class=grid id='+iId+' value="'+av+'" name="'+an+'" readonly></td>';
	//tData2+='<td><input class=grid id='+iId+' value="'+av+'" name="'+an+'"></td>';
	s++;
  };
  //alert(tData2);
  tData+='<th style="width:3%;text-align:center">Action</th></tr>';
  tData2+="<td class=dup style=\"width:3%;text-align:center\"><img class=duplicate src='icon_duplicate.png' width=20>";
  tData2+="_<img class=delete src='icon_delete.png' width=15></td>";
  tData+=tData2;   
  tData+='</table>\n';  
  $('#datagrid').append(tData); 
  //console.log('\n'+tData);

//das ist eine hässliche Kopie von "oben"  
		$('.duplicate').click(function(e){
			var tr    = $(this).closest('.xrow');
			var clone = tr.clone();	
			clone.find('input[name=id').val('neu');
			//$clone.find(':text').val('');
            clone.addClass("modified");
			tr.after(clone);
			z=1;
			//Zeilen neu nummerieren aber auch alle Felder!!!
			last=0;
			$('.grid').each(function() {
              cId=$(this).attr('id');
		      $(this).keydown(function(e){keyNavigation(e,this);});
              id_part = cId.split('-');
              zeile = id_part[0].split('z');
              id_part2 = id_part[0].split('z');
              spalte= parseInt(id_part[1]);
              zeile= parseInt(id_part2[1]);
		      if (spalte<last) z=z+1;
		      last=spalte;
              nId = 'z' + z+'-'+spalte;
              console.log(nId); 
		      $(this).attr('id',nId);
            });
            z=0;
			$('.xrow').each(function() {z++; $(this).attr('id','z'+z);});
			//$('.xrow').each(function() {console.log('neu:'+ this.id )});

		});	

}

function letzteZeile() {
  alert('leere letzte Zeile ohne Daten');	
  var tData='<table>'; s=1; z=1;
  tabelle='qso';
  console.log('erste Zeile Überschrift '+tabelle);
  attdef=schema[tabelle];
  alert(attdef);
  atts='';
  iId='z'+z;   
  tData+='<tr id=z0>';
  tData2='<tr id=z1 class=modified>';
  for (i in attdef) {
	an=attdef[i]['name'];
	aw=attdef[i]['width'];
	av='';
	console.log(an+'  '+av+'\n');
	if (attdef[i]['th'])  an=attdef[i]['th'];
	if (an!='id') tData+='<th style="width:'+aw+'">'+an+'</th>';
		else tData+='<th class=xhidden style="width:'+aw+'">'+an+'</th>';
	
	//tData+='<th style="width:'+aw+'">'+an+'</th>';
	iId='z'+z+'-'+s; 
	an=attdef[i]['name'];
	if (an!='id') tData2+='<td><input class=grid id='+iId+' value="'+av+'" name="'+an+'"></td>';
		else tData2+='<td class=xhidden><input class=grid id='+iId+' value="'+av+'" name="'+an+'" readonly></td>';
	//tData2+='<td><input class=grid id='+iId+' value="'+av+'" name="'+an+'"></td>';
	s++;
  };
  //alert(tData2);
  
  tData+='<th style="width:3%;text-align:center">Action</th></tr>';
  tData2+="<td class=dup style=\"width:3%;text-align:center\"><img class=duplicate src='icon_duplicate.png' width=20>";
  tData2+="_<img class=delete src='icon_delete.png' width=15></td>";
  tData+=tData2;   
  tData+='</table>\n';  
  $('#datagrid').append(tData); 
  //console.log('\n'+tData);

//das ist eine hässliche Kopie von "oben"  
		$('.duplicate').click(function(e){
			var tr    = $(this).closest('.xrow');
			var clone = tr.clone();	
			clone.find('input[name=id').val('neu');
			//$clone.find(':text').val('');
            clone.addClass("modified");
			tr.after(clone);
			z=1;
			//Zeilen neu nummerieren aber auch alle Felder!!!
			last=0;
			$('.grid').each(function() {
              cId=$(this).attr('id');
		      $(this).keydown(function(e){keyNavigation(e,this);});
              id_part = cId.split('-');
              zeile = id_part[0].split('z');
              id_part2 = id_part[0].split('z');
              spalte= parseInt(id_part[1]);
              zeile= parseInt(id_part2[1]);
		      if (spalte<last) z=z+1;
		      last=spalte;
              nId = 'z' + z+'-'+spalte;
              console.log(nId); 
		      $(this).attr('id',nId);
            });
            z=0;
			$('.xrow').each(function() {z++; $(this).attr('id','z'+z);});
			//$('.xrow').each(function() {console.log('neu:'+ this.id )});

		});	

}

function debugTable() {
  console.log('Demug\n----------------');
  $('.xrow').each(function() {
    console.log('xrow:'+ this.id )
	$(this).find('.grid').each(function() {
      console.log('grid:'+ this.id )
    });                   
  });                   
  
}

$('#debug').click(function (e) {
  debugTable();
  });                   

//neues (3/3/23) neue QSO-Zeile
$('#insert').click(function (e) {
  insertQSO();
});
$('#insert2').click(function (e) {
  insertQSO();
});

function insertQSO() {
  var tr    = $('tr').last();
  var clone = tr.clone();	
  clone.addClass("modified");
  nextId=tr.attr('id');
  nextId=nextId.substr(1);
  nextId=parseInt(nextId)+1;
  nextId='z'+nextId;
  console.log('next:'+nextId);
  $(clone).attr('id',nextId);
  spalte=1;
  $(clone.find('.grid')).each(function() {
      $(this).keydown(function(e){keyNavigation(e,this);});
	  console.log('ins-feld:'+this.id);	  
	  nnId=nextId+'-'+spalte;
	  $(this).attr('id',nnId);
	  spalte++;
	  if ($(this).attr('name')=='qrg') {} //qrg wird übernommen
	  else if ($(this).attr('name')=='utc') {} //utc wird übernommen
	  else if ($(this).attr('name')=='txrst') {	$(this).attr('value','59');} 
	  else if ($(this).attr('name')=='rxrst') {	$(this).attr('value','59');} 
	  else if ($(this).attr('name')=='mode') {} //utc wird übernommen
	  else if ($(this).attr('name')=='txnr') {//txnr wird erhöht
		  txnr=parseInt($(this).attr('value'));
		  txnr=txnr+1;
		  $(this).attr('value',txnr);	
		  
	  } else $(this).attr('value','');
  });
  tr.after(clone);
  //7 ist das Call-Feld
  focusId="#"+nextId+"-7";
  //alert(focusId);
  $(focusId).focus();
  
  
  //const element = document.getElementById("z17-6");
  //element.scrollIntoView();
  //console.log("FocusId"+focusId);
  //alert(document.body.scrollHeight);
  //window.scrollTo(110, 500);	
  const element = document.getElementById("content");
  console.log(element.id);
   
  element.scrollIntoView();
  console.log('scroll nach insert');
  //alert("nux");
  last=0;

};


$('#update').click(function (e) {
	e.preventDefault();
    saveNow();
});	

function saveNow() {	
    //debugTable();
    tabelle='qso';
    console.log('speicher tabelle '+tabelle);
	modCalls=0;
	$('tr.modified').each(function(key,value) {modCalls++;});
	//alert(modCalls);
	$('tr.modified').each(function(key,value) {
	   console.log('sichern val:'+value.id);
       zId=value.id;
       sqlAtts={};
       $("input[id|='"+zId+"']").each(function(key2,value2) {
         if (value2.name=='id') sqlAtts[value2.name]=value2.value;       
       });
       $("input[id|='"+zId+"']").each(function(key2,value2) {
       //$("input[id|='"+zId+"'].modified").each(function(key2,value2) {
         sqlAtts[value2.name]=value2.value.toUpperCase();       
       });
	   
	   logid=$('#logid').text();
       sqlAtts['lid']=logid;       

       sqlJSON = JSON.stringify(sqlAtts);
	   //alert(sqlAtts);
       //console.log(sqlJSON);
       //alert('vor POST');
	   $.post("save.php", {table:"qso",sqlAtts:sqlJSON},
        	function(msg){ 
				console.log(msg);
                if (msg=='Success') {
					$(".modified").removeClass("modified");
				    modCalls--;
				}
				else alert('Speicherproblem - Werte falsch?');
				if (modCalls==0) myQuery();
	           // HIER NOCH TESTEN OB SUCSESS'
	        });
	});
    //wird schon ausgeführt bevor alle updates durch ... baue 1 sek in PHP ein
};
</script>
<script src="locator.js"></script>

<?php ?>
