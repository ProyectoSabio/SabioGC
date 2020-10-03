const { TEAM_NAME } = require("./constants/regexp");
const { INTEGER_NUMBERS_TEXT } = require("./constants/texts");

document.addEventListener('DOMContentLoaded', function(e) {
	var selectPlayer = document.getElementById('numJugadores');
	var bloqueJugadores = document.getElementById('jugadores');
	var p;
	var inp;
	var text;
	var sel;
	var selTex;
	var opcion;
	var span;
	var imagenes = Array("&#xE8A6;","&#xE8D0;","&#xE420;","&#xE7E9;","&#xE80E;","&#xEB44;","&#xEB3C;","&#xEB40");
	var rondas = document.getElementById('rondas');
	var spanRondas = document.getElementById('rondaS');
	var jugadores = document.getElementsByName('jugadores[]') || undefined;
	var btn = document.getElementById('jugar');

	rondas.addEventListener('change',comprobarPreguntas);
	numJugadores.addEventListener('change',comprobarPreguntas);
	btn.addEventListener('click',function(e){
		e.preventDefault();
		if (validar()) {
			document.forms[0].submit();
		};
	});

	selectPlayer.addEventListener('change', function(e) {
		while (bloqueJugadores.childNodes.length >= 1 ){
			bloqueJugadores.removeChild(bloqueJugadores.firstChild );
		}
		crearInputs();
	});

	/**
	* comprueba si existen suficientes preguntas para el número de rondas y jugadores seleccionado
	*/
	function comprobarPreguntas(){
		if((rondas.value * selectPlayer.value)>40){
			rondas.style.borderBottom = "1px solid red";
			spanRondas.innerHTML ="no hay suficientes preguntas";
		}else{
			rondas.style.borderBottom = "1px solid gray";
			spanRondas.innerHTML ="";
		}
	}

	function crearInputs(){
		for (var i = 0; i < selectPlayer.value; i++) {
		    p = document.createElement("p");
		    text = document.createTextNode("J "+(i+1)+" ");
		    p.appendChild(text);
		    inp = document.createElement("input");
			inp.setAttribute("name","jugadores[]");
			p.appendChild(inp);
			sel = document.createElement("select");
			sel.setAttribute("name","foto[]");
			sel.style.fontFamily="Material Icons";
			span = document.createElement("span");
			span.setAttribute("id","Jugador"+(i+1));
			for (var j = 0 ; j < imagenes.length; j++) {
				opcion = document.createElement("option");
				opcion.innerHTML = "<i class=\"medium material-icons\">"+imagenes[j]+"</i>";
				opcion.setAttribute("value",imagenes[j]);
				opcion.style.fontFamily="Material Icons";
				// opcion.innerHTML = imgNombre[j];
				sel.appendChild(opcion);
			};
			p.appendChild(sel);
			p.appendChild(span);
			bloqueJugadores.appendChild(p);
		};
		 jugadores = document.getElementsByName('jugadores[]');
	}

	function setRoundText(value){
		Number.isInteger(value) ? spanRondas.innerHTML(INTEGER_NUMBERS_TEXT)
			: spanRondas.innerHTML("")
	}

	function validarNombreEquipo(value){
		return TEAM_NAME.test(value);
	}

	function validar(){
		setRoundText(rondas.value)
		var errorJ = Array();
		for (var i = 0; i < jugadores.length; i++) {
			errorJ[i] = validarNombreEquipo(jugadores[i].value);
			if (errorJ[i]) {
				document.getElementById('Jugador'+(i+1)).innerHTML ="";
			}else{
				document.getElementById('Jugador'+(i+1)).innerHTML ="Campo vácío";
			}
		};

		if (!Number.isInteger(rondas.value) || (errorJ.indexOf(false)!=-1 || errorJ.length==0))
			return false;
		return true;
	}

});
