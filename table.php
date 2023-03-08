<?php
include('lib.php');
#include('orgStruktur.php');

head();
#$tabelle=$_GET['tabelle'];
$tabelle='log';


?>
  <!-- Page Content -->
  <div class="container">
    <div class="row">
      <div class="col-lg-2" id=prozessCol>
<?php orgaMenu($tabelle);?>     
  
  </div>
      <div class="col-lg-10">
    <div class="row">
      <div class="col-lg-8">
		Allg. Suche
	    <input id=zsearch style="border: 1px solid #0062cc;" onchange="myQuery(value)">
       	  
	  </div>
      <div class="col-lg-4">
	  <a href="#" id=update class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Speichern</a>
	  <a href="#" id=insert class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Neu</a>
	</div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div id="datagrid"></div>
     </div>
    </div>
    </div>
	</div>   <!-- Container Page Content -->


  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.slim.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>

<script>
editStatus=false;
schema= {
	"gruppe":[ 
		{name:"id",width:"2%"}, 
		{name:"kurzzeichen",width:"10%"},
		{name:"studiengang",width:"10%"},
		{name:"semester",width:"10%"},
		{name:"anzahl",width:"10%"},
		{name:"oberGruppe",width:"10%"}
		],
	"veranstaltung":[ 
		{name:"id",width:"2%"}, 
		{name:"modul",width:"2%",th:"Mod"}, 
		{name:"xkurzzeichen",width:"10%",th:"kurzzeichen"},
		{name:"art",width:"5%"},
		{name:"dauer",width:"5%"},
		{name:"status",width:"5%"},
		{name:"xbezeichnung",width:"20%",th:"Bezeichnung"},
		{name:"bemerkung",width:"20%"},
		{name:"linkMoodle",width:"20%",th:"Moodle Link"}
		],
	"modul":[ 
		{name:"id",width:"2%"}, 
		{name:"kurzzeichen",width:"10%"},
	    {name:"studiengang",width:"5%",modus:"ro"},
		{name:"semester",width:"5%",th:"Sem"},
		{name:"bezeichnung",width:"30%"},
		{name:"linkMK",width:"20%",th:"Link Modulkatalog"}
		],
	"dozent":[ 
		{name:"id",width:"2%"}, 
		{name:"name",width:"15%"},
		{name:"vorname",width:"15%"},
		{name:"status",width:"5%"},
		{name:"titel",width:"6%"},
		{name:"kontakt",width:"15%"},
		{name:"email",width:"15%"},
		{name:"fb",width:"10%"	},
		{name:"bemerkung",width:"20%"},
		],
	"studiengang":[ 
		{name:"id",width:"2%"}, 
		{name:"kurzzeichen",width:"10%"},
		{name:"name",width:"30%"}, 
		{name:"semester",width:"10%"}, 
		{name:"fb",width:"10%",modus:"ro"}, 
		{name:"spo",width:"10%"}
		],
	"raum":[ 
		{name:"id",width:"2%"}, 
		{name:"rid",width:"15%"}, 
		{name:"fb",width:"10%",modus:"ro"}, 
		{name:"plaetze",width:"10%"}, 
		{name:"ausstattung",width:"30%"},
		{name:"verantwortlicher",width:"30%"}, 
		{name:"status",width:"10%"}
		]
};



$("#context-menu a").on("click", function() {
  $(this).parent().removeClass("show").hide();
});

$(document).ready(function(){
  selectFB('start');
  localStorage.setItem('tabelle', '<?php print $tabelle?>');
  //myQuery();
});
  
function keyNavigation(e,that) {
      //console.log(e.which+' '+$(that).attr('id'));
      
     //right
	 if (e.which == 39 & !editStatus )
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

function myxQuery(suche) {
    //alert('start MUX MUX Query'); 
}
function myQuery(suche) {
  //alert('start myQuery');
  $('#datagrid').empty();
  console.log('call mysql '+suche);
  $('#datagrid').empty();
  //tabelle='<?php echo $tabelle;?>';

  tabelle=localStorage.getItem('tabelle');
  console.log(' xyzzz call mysql tabelle '+tabelle);

  //alert(tabelle);
  //$.post( 'quelle.php',{ table:'veranstaltung',  sqlAtts:'id,kurzzeichen,typ,status,bezeichnung,bemerkung', suche: suche})
  order='';


console.log(schema);
  
  attdef=schema[tabelle];
  atts='';
  for (i in attdef) {
      atts=atts+attdef[i]['name']+',';
  };
  
  console.log(atts);


  if (tabelle=='studiengang') {
	  fb=$('#fbSelect').val(); 
	  filter = 'fb="'+fb+'"'; 
	  order= 'order by kurzzeichen'
	  }
  if (tabelle=='modul') {
	  order= 'order by studiengang,semester,kurzzeichen'
	  sb=$('#sgSelect').val(); 
	  filter = 'studiengang='+sg; 
	  }
  if (tabelle=='gruppe') {
	  order= 'order by studiengang,semester,kurzzeichen'
	  sb=$('#sgSelect').val(); 
	  sm=$('#semesterSelect').val(); 
	
      if (!sm) sm=2;
      filter = 'studiengang='+sg; 
	  }
  if (tabelle=='veranstaltung') {
	  //order= 'order by studiengang,semester,kurzzeichen'
	  sb=$('#sgSelect').val(); 
	  sm=$('#semesterSelect').val(); 

      if (!sm) sm=2;
      filter = 'studiengang='+sg; 
	  order= 'order by xkurzzeichen'
	  }
  if (tabelle=='dozent') {
	  fb=$('#fbSelect').val(); 
	  filter = 'fb="'+fb+'"'; 
	  order= 'order by name';
	  }
  if (tabelle=='raum') {
	  fb=$('#fbSelect').val(); 
	  filter = 'fb="'+fb+'"'; 
	  order= 'order by rid'
	  }

  atts = atts.substring(0, atts.length-1);
  console.log('tabelle/atts '+tabelle+atts+filter);  
  $.post( 'quelle.php',{ table:tabelle,  sqlAtts:atts, suche: suche, filter:filter,order: order})
    .done(function( data ) {
	    //console.log('data done here'+data);
		json = $.parseJSON(data);
		var tData='<table>'; s=1; z=0;
  tabelle=localStorage.getItem('tabelle');
  console.log('call mysql tabelle '+tabelle);
  attdef=schema[tabelle];
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
					if (an!='id') tData+='<th style="width:'+aw+'">'+an+'</th>';
					else tData+='<th class=xhidden style="width:'+aw+'">'+an+'</th>';
					s++;
				};
				tData+='<th style="width:3%;text-align:center">Action</th></tr>';
			    
			}
     		z++;s=1; 
			iId='z'+z;   
			tData+='<tr class=xrow id='+iId+' +>';
			
			$.each(value,function(an,av) {
				iId='z'+z+'-'+s; 
				if (av==null) av='';
				readonly='';
				if (attdef[s-1]['modus']=='ro') readonly=' readonly';  	
				if (an=='studiengang') av=sgInfo[av];
				//if (an=='oberGruppe') av=sgInfo[av];
				if (an!='id') tData+='<td><input class=grid id='+iId+' value="'+av+'" name="'+an+'"'+readonly+'></td>';
				else tData+='<td class=xhidden><input class=grid id='+iId+' value="'+av+'" name="'+an+'" readonly></td>';
				s++;
				//console.log("\n"+s+"###"+tData);        
			});
			tData+="<td class=dup style=\"width:3%;text-align:center\"><img class=duplicate src='media/icon_duplicate.png' width=20>";
			tData+="_<img class=delete src='media/icon_delete.png' width=15></td>";
			tData+='</tr>\n';
			console.log(tData);
		});
        tData+='</table>\n';
       
	     
	   
		$('#datagrid').append(tData);  
		tInput=$('<input>').attr({value:'hu',id:'huid'});     
		$('#datagrid2').append(tInput);  
		$('input').keydown(function(e){
			keyNavigation(e,this);
		});
		
		$('input').click(function(e){
			if (document.activeElement.id != this.id) editStatus=false;
			//alert('click:'+document.activeElement.id);
		});
		
		
		//hier wird die GruppenId durch eine Auswahlliste ersetzt
		//also das input durch ein select
		//das select wird aus der Spalte Kurzzeichen generiert
        /* lass ich mal weg wir arbeiten einfach mit Namen als Referenz!!!
        var alleGruppen='<select class="grid">';
		$('input[name=kurzzeichen]').each(function() {
			console.log(this.value);
			v=this.value;
		    alleGruppen+='<option value="'+v+'">'+v+'</option>'; 		
		});
		alleGruppen+='</select>';
		console.log( alleGruppen);
		
		$('input[name=oberGruppe]').each(function() {
			console.log( this.value);
			console.log( $(this).parent());
			
            var alleGruppenN = alleGruppen.replace(/WIB/g, "Christmas");
			$(this).parent().html(alleGruppenN);
		});; 
		*/
		insertZeile();
		$('.delete').click(function(e){
          deleteDB(this);
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
  tabelle=localStorage.getItem('tabelle');
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

function debugTable() {
  $('.xrow').each(function() {
    console.log('xrow:'+ this.id )
	$(this).find('.grid').each(function() {
      console.log('grid:'+ this.id )
    });                   
  });                   
  
}



$('#insert').click(function (e) {
 //alert('ins:'+e);
			var tr    = $('tr[id=z0]').first();
			//$('tr').first().html('<h1>Bu</h1>'); //addClass("modified");
            console.log("passende Zeile"+$('tr').first().attr('id'));
            if (tr.attr('id') === undefined) {ersteZeile()}
		else {	
			var clone = tr.next().clone();	
			clone.find('input').val('');
			//$clone.find(':text').val('');
            clone.addClass("modified");
            if (tr.id === undefined) {
				//Defaultwerte aus rechtem Menü
				//gehört nicht hier her sondern in Schema
    			if (tabelle=='studiengang' || tabelle=='raum' || tabelle=='dozent') clone.find('input[name=fb]').val(fb);			
    			if (tabelle=='modul') clone.find('input[name=studiengang]').val(sgInfo[sg]);			
			};			
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
              //console.log(nId); 
		      $(this).attr('id',nId);
            });
            z=0;
			$('.xrow').each(function() {z++; $(this).attr('id','z'+z);});
			//$('.xrow').each(function() {console.log('neu:'+ this.id )});
		}

}
)

$('#update').click(function (e) {
	e.preventDefault();
    //debugTable();
    tabelle=localStorage.getItem('tabelle');
    console.log('speicher tabelle '+tabelle);
	modCalls=0;
	$('tr.modified').each(function(key,value) {modCalls++;});
	$('tr.modified').each(function(key,value) {
	   console.log('sichern val:'+value.id);
       zId=value.id;
       sqlAtts={};
       $("input[id|='"+zId+"']").each(function(key2,value2) {
         if (value2.name=='id') sqlAtts[value2.name]=value2.value;       
       });
       $("input[id|='"+zId+"']").each(function(key2,value2) {
       //$("input[id|='"+zId+"'].modified").each(function(key2,value2) {
         sqlAtts[value2.name]=value2.value;       
       });
       sqlJSON = JSON.stringify(sqlAtts);
       console.log(sqlJSON);
	   $.post("save.php", {table:tabelle,sqlAtts:sqlJSON},
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
});
</script>

<?php cFoot()?>
