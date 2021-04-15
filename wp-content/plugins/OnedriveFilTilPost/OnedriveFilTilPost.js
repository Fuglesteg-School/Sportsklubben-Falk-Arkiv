//Sender bilde til serveren. d for data
function LastOppBildeForm(side){
	
	var url = "../wp-content/plugins/OnedriveFilTilPost/OnedriveFilTilPost2.php"
	//var data = new FormData(document.getElementById("filOpplastingForm"));
	//var data = "BildeSendt=true";
	var bildeForm = new FormData();
	//var filer = document.getElementsByClassName("filOpplasting");
	
	for(var i = 0; i < sider.length; i++){
		if(sider[i].inputFilEl.files.length != 0){
			
			bildeForm.append("fil" + i, sider[i].inputFilEl.files[0]);
			
		} else {
			
			document.getElementById("statusTekst").innerHTML = "Alle sider må være lagt inn!";
			return;
		}
	}
	bildeForm.append("LagreFil", "true");
	console.log(bildeForm);
	var data = bildeForm;
	
	lasterIkon.style.display = "visible";
	document.getElementById("wpbody-content").appendChild(lasterIkon);
	
	LastOppBilde(data, function(res){ console.log(res);
		SendInfoTilServer("BildeTilTekst=" + res, function(res2){
			
			
			for(var i = 0; i < sider.length; i++){
				var tekst = document.createElement("p");
				tekst.className = "redigertTekst";
				var resultat = new DOMParser().parseFromString(res2, "text/html");
				if(resultat.getElementById("error")){
					document.getElementById("statusTekst").innerHTML = resultat.getElementById("error").innerHTML;
					lasterIkon.style.display = "none";
					return;
				}
				tekst.innerHTML = resultat.getElementById("sideInfo"+i).innerHTML;
				//console.log("sidetall: " + side);
				sider[i].innholdDiv.appendChild(tekst);
			}
			if(lagPostKnapp == undefined){ 
				lagPostKnapp = document.createElement("input");
				lagPostKnapp.type = "button";
				lagPostKnapp.value = "lagre og publiser post";
				lagPostKnapp.addEventListener("click", LagreOgPubliser);
				document.getElementById("wpbody-content").appendChild(lagPostKnapp);
			}
			
			lasterIkon.style.display = "none";
			
		});
	});
}
	
function LastOppBilde(d, callback){
	
	
	var http = new XMLHttpRequest();
	var url = "../wp-content/plugins/OnedriveFilTilPost/OnedriveFilTilPost2.php";

	http.open("POST", url, true);

	//Send the proper header information along with the request
	//http.setRequestHeader("Content-Type", "multipart/form-data");
	
	http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
		
			if(typeof(callback) != undefined){
				
				callback(http.responseText);
				
			}
		
		}
    }
http.send(d);
console.log("sendt bilde");
	
}
	
function SendInfoTilServer(d, callback){
	
	var http = new XMLHttpRequest();
	var url = "../wp-content/plugins/OnedriveFilTilPost/OnedriveFilTilPost2.php";

	http.open("POST", url, true);

	//Send the proper header information along with the request
	http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	
	http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
		
			if(typeof(callback) != undefined){
				
				callback(http.responseText);
				
			}
		
		}
    }
http.send(d);
console.log("sendt info");
	
	
}

function LagreOgPubliser(){
	//redigert tekst
	var redTekst = document.getElementsByClassName("redigertTekst");
	var samletTekst = "";
	var bilder = "";
	for(var i = 0; i < redTekst.length; i++){
		
		samletTekst += redTekst[i].getElementsByClassName("tekst")[0].innerHTML;
		bilder += redTekst[i].getElementsByClassName("vedlegg")[0].dataset.vedleggurl;
		if(i != redTekst.length-1){
			bilder += ",";
		}
	}
	
	
	//& symbolet tolkes av serveren som ny verdi, så jeg gjør om til og... 
	//dum løsning, men det får gå
	samletTekst = samletTekst.replaceAll("&", "og");
	
	var tittel = document.getElementById("tittelPåInnlegg").value;
	
	SendInfoTilServer("LagreOgPubliser=" + samletTekst + "&bilderURL=" + bilder + "&tittel=" + tittel, function(res){
		var resultat = res;
		resultat = resultat.trim();
		//console.log(resultat);
		//console.log("http://localhost/wordpress/wp-admin/post.php/?post=" + resultat + "&action=edit");
		window.location.href = "http://localhost/wordpress/wp-admin/post.php/?post=" + resultat + "&action=edit";
		//console.log(res);
	});
	
}

var sider = [];
var nySideKnapp;
var lastOppKnapp;
var lagPostKnapp;
var lasterIkon;

//collapsible greier
window.onload = function(){

	if(sider[0] == undefined){
		
		sider[0] = new side(0);
		sider[0].start();
		
	}
	
	if(nySideKnapp == undefined){
		//console.log("heisann");
		nySideKnapp = document.createElement("button");
		nySideKnapp.innerHTML = "Ny side";
		nySideKnapp.id = "nySideKnapp";
		nySideKnapp.addEventListener("click", function(){
			
			sider[sider.length] = new side(sider.length);
			
			sider[sider.length-1].start();

		});
		
		document.getElementById("wpbody-content").appendChild(nySideKnapp);
		
	}

	if(lastOppKnapp == undefined){
		
		lastOppKnapp = document.createElement("button");
		lastOppKnapp.innerHTML = "Last Opp";
		lastOppKnapp.addEventListener("click", function(){
			//console.log(this.parentElement);
			
			LastOppBildeForm(this.parentElement);
			
		});
		document.getElementById("wpbody-content").appendChild(this.lastOppKnapp);
		
	}
	
	if(lasterIkon == undefined){
		
		lasterIkon = document.createElement("div");
		lasterIkon.className = "loader";
		
	}
	
}



var side = function(sidetall){
	
	//index i arrayen sider
	this.sideTall = sidetall;
	//knappen for å laste opp fil til serveren
	//this.lastOppKnapp;
	//fil input elementet
	this.inputFilEl;
	//div som inneholder alle elementene for denne siden
	this.containerDiv;
	//menyknapp som åpner siden for brukeren. collapsible greia
	this.sideKnapp;
	//div som holder innholdet i siden
	this.innholdDiv;
	
	//initiate siden
	this.start = function(){
		
		//lag container div som inneholder alle elementer i siden
		this.containerDiv = document.createElement("div");
		this.containerDiv.className = "container";
		this.containerDiv.id = "side" + this.sideTall;
		this.containerDiv.name = "side" + this.sideTall;
		document.getElementById("sider").appendChild(this.containerDiv);
		//lag sideknapp som er collapsiblen for sideelementene
		this.sideKnapp = document.createElement("button");
		this.sideKnapp.innerHTML = "Side " + (this.sideTall + 1);
		this.sideKnapp.id = "sideKnapp" + this.sideTall;
		this.sideKnapp.name = "sideKnapp" + this.sideTall;
		this.sideKnapp.classList.add("side" + this.sideTall);
		this.sideKnapp.classList.add("collapsible");
		this.sideKnapp.addEventListener("click", function(){
			
			this.classList.toggle("active");
			
			var innhold = document.getElementById(this.classList[0]).getElementsByClassName("innhold")[0];
			if (innhold.style.display === "block") {
				innhold.style.display = "none";
			} else {
				innhold.style.display = "block";
			}
		})
		this.containerDiv.appendChild(this.sideKnapp)
		
		//Lag innhold div
		this.innholdDiv = document.createElement("div");
		this.innholdDiv.className = "innhold";
		this.containerDiv.appendChild(this.innholdDiv);
		
		//Last opp knapp 
		// this.lastOppKnapp = document.createElement("button");
		// this.lastOppKnapp.innerHTML = "Last Opp";
		// this.lastOppKnapp.addEventListener("click", function(){
			//console.log(this.parentElement);
			
			// LastOppBildeForm(this.parentElement);
			
		// });
		// this.innholdDiv.appendChild(this.lastOppKnapp);
		
		//input fil elementet
		this.inputFilEl = document.createElement("input");
		this.inputFilEl.type = "file";
		this.inputFilEl.className = "filOpplasting";
		this.inputFilEl.name = "filOpplasting";
		this.innholdDiv.appendChild(this.inputFilEl);
	}
	
}