document.addEventListener('DOMContentLoaded', function(e) {
	var selectPlayer = document.getElementById('numJugadores');
	var bloqueJugadores = document.getElementById('jugadores');
	var paragraph;
	var inp;
	var text;
	var selectTag;
	var selTex;
	var opcion;
	var span;
	var imagenes = Array("&#xE8A6;","&#xE8D0;","&#xE420;","&#xE7E9;","&#xE80E;","&#xEB44;","&#xEB3C;","&#xEB40");
	var rondas = document.getElementById('rondas');
	var spanRondas = document.getElementById('rondaS');
	var jugadores = document.getElementsByName('jugadores[]') || undefined;
	var playButton = document.getElementById('jugar');

	rondas.addEventListener('change',comprobarPreguntas);
	numJugadores.addEventListener('change',comprobarPreguntas);
	playButton.addEventListener('click',function(e){
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
		    paragraph = document.createElement("p");
		    text = document.createTextNode("J "+(i+1)+" ");
		    paragraph.appendChild(text);
		    inp = document.createElement("input");
			inp.setAttribute("name","jugadores[]");
			paragraph.appendChild(inp);
			selectTag = document.createElement("select");
			selectTag.setAttribute("name","foto[]");
			selectTag.style.fontFamily="Material Icons";
			span = document.createElement("span");
			span.setAttribute("id","Jugador"+(i+1));
			for (var j = 0 ; j < imagenes.length; j++) {
				addImages(j, imagenes);
			};
			paragraph.appendChild(selectTag);
			paragraph.appendChild(span);
			bloqueJugadores.appendChild(paragraph);
		};
		 jugadores = document.getElementsByName('jugadores[]');
	}
	function addImages(index, imagenes){
		opcion = document.createElement("option");
		opcion.innerHTML = "<i class=\"medium material-icons\">"+imagenes[index]+"</i>";
		opcion.setAttribute("value",imagenes[index]);
		opcion.style.fontFamily="Material Icons";
		selectTag.appendChild(opcion);
	}

	function validarRonda(value){
		var regIsNumber = /[0-9]+/g;
		if (!regIsNumber.test(value)) {
			spanRondas.innerHTML = "Solo se aceptara números enteros positivos";
			return false;
		}
		spanRondas.innerHTML = "";
		return true;
	}

	function isValidTeamName(value){
		return /[a-zA-Z(0-9 _-º)?]/g.test(value);
	}

	function validar(){
		var errorR = validarRonda(rondas.value);
		var errorJ = Array();
		for (var i = 0; i < jugadores.length; i++) {
			errorJ[i] = isValidTeamName(jugadores[i].value);
			document.getElementById('Jugador '+(i+1)).innerHTML = errorJ[i] ? '' : 'Campo vacío';
		};

		if (!errorR || (errorJ.indexOf(false)!=-1 || errorJ.length==0))
			return false;
		return true;
	}

});
