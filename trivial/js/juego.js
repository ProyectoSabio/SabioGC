document.addEventListener('DOMContentLoaded', function() {
	var botones = document.getElementsByName('resp'); //array de los botones(1º por pregunta)
	var titulo = document.getElementById('titulo');
	var ronda = document.getElementById('ronda');
	var puntos = document.getElementsByClassName('puntos'); //array que contiene cada casilla de puntuación de cada jugador
	var obj = document.getElementById('objeto');
	var pattern = /^(http|https)\:\/\/[a-z0-9\.-]+/gi;
	var respuestaCorrecta = "";
	var respuestaValida = "";
	var nronda = 1; //contador de ronda
	var player = 1; //contador de jugador
	var aleatorio; //variable que almacenara la posición de la pregunta que se mostrara para cada jugador
	var categoria;
	var panelJugadores = $(".jugador");
	var categoriaPregunta;
	var clickado;
	var botonJugar = document.getElementById('jugar');
	var ruleta = document.getElementById('ruleta');
	var btnAnular = document.getElementById("anular");
	var btnSiguiente = document.getElementById("siguiente");
	var respuesta = "";


	for (let i = 0; i < botones.length; i++) {
		botones[i].addEventListener("click", comprobar);
	};

	botonJugar.addEventListener('click', ejecutarJuego);

	$(".reiniciar").click(function() {
		window.location.reload();
	});

	$(".salir").click(function() {
		window.location.href = "./index.php";
	});
	//Inicio del juego

	$(".enviar").click(comprobarClave);

	$(".close").click(function(){
		$('#modal3').closeModal();
	});

	btnAnular.addEventListener('click', pedirPasswd);

	btnSiguiente.addEventListener('click', siguientePregunta);

	$("#inicial").openModal({
		dismissible: false, // Modal can be dismissed by clicking outside of the modal
		opacity: .5, // Opacity of modal background
		in_duration: 300, // Transition in duration
		out_duration: 200, // Transition out duration
		ready: function() {}, // Callback for Modal open
		complete: function() {} // Callback for Modal close);

	});

/**
 * Comprueba si la contrraseña introducida por el usuario es correcta. En ese caso anula la jugada.
 */
	function comprobarClave(){
		let passwordUser = $(".clave").val();
		let puntosJugador;
		if( passwordUser == password){
			$.each($('.jugador'),function(){
				// Seleccionamos el marcador del jugador que está activo.
				if($(this).css("background-color")!= 'rgb(238, 238, 238)'){
					puntosJugador = parseInt($(this).find(".puntos").html()); // Extraemos los puntos.
					if(respuesta == "correcta"){
						puntosJugador = puntosJugador - 1;
					}else{
						puntosJugador = puntosJugador + 1;
					}
					$(this).find(".puntos").html(puntosJugador.toString());
				}
			});
			$('#modal3').closeModal();
			siguientePregunta();
		}
		else{
			$('.error').html("Password incorrecta");
		}
	}
/**
* Ventana para solicitar contraseña de juego en caso de anular pregunta
*/
	function pedirPasswd(){
		$("#modal3").openModal({
			dismissible: false, // Modal can be dismissed by clicking outside of the modal
			opacity: .5, // Opacity of modal background
			in_duration: 300, // Transition in duration
			out_duration: 200, // Transition out duration
			ready: function() {}, // Callback for Modal open
			complete: function() {

				} // Callback for Modal close);

		});
	}
	/**
	 * Cierra la ventana e inicia la ruleta de nuevo
	 */
	function siguientePregunta(){
		document.getElementById('ganadores').innerHTML = comprobarGanador();
		respuesta = "";
		$("#modal1").closeModal();
		setTimeout(ejecutarJuego, 1500);
		btnAnular.style = "display: none;";
		btnSiguiente.style = "display: none;"; // botón del evento: btnSiguiente
	}

	function ejecutarJuego() {
		marcarJugador();
		$('#inicial').closeModal();
		$('body').append('<audio src="./audios/ruleta.mp3" controls autoplay loop  hidden></audio>');
		var categoria = sacarCategoria();
		ronda.innerHTML = "Ronda " + nronda + "/" + rondas + " esta jugando <b>" + listaJugadores[player - 1]["Nombre"] + "</b>";
		clickado = false;
		ejecutarRuleta(0);

	}

	function marcarJugador() {
		switch (player) {
			case 1:
				$(panelJugadores[jugadores - 1]).removeClass("jugador-" + jugadores);
				$(panelJugadores[jugadores - 1]).addClass("desactivado");
				$(panelJugadores[player - 1]).removeClass("desactivado");
				$(panelJugadores[player - 1]).addClass("jugador-1");
				// clase jugando
				break;
			case 2:
				$(panelJugadores[player - 2]).removeClass("jugador-1");
				$(panelJugadores[player - 2]).addClass("desactivado");
				$(panelJugadores[player - 1]).removeClass("desactivado");
				$(panelJugadores[player - 1]).addClass("jugador-2");
				break;
			case 3:
				$(panelJugadores[player - 2]).removeClass("jugador-2");
				$(panelJugadores[player - 2]).addClass("desactivado");
				$(panelJugadores[player - 1]).removeClass("desactivado");
				$(panelJugadores[player - 1]).addClass("jugador-3");
				break;
			case 4:
				$(panelJugadores[player - 2]).removeClass("jugador-3");
				$(panelJugadores[player - 2]).addClass("desactivado");
				$(panelJugadores[player - 1]).removeClass("desactivado");
				$(panelJugadores[player - 1]).addClass("jugador-4");
				break;

		}
	}

	function ejecutarRuleta(i) {
		if (i < 70) {
			i++;
			categoriaRuleta = categorias[Math.floor(i % categorias.length)];
			ruleta.innerHTML = categoriaRuleta;
			setTimeout(function() {
				ejecutarRuleta(i)
			}, 100);
		} else {
			ruleta.innerHTML = categoriaPregunta;
			$('audio').remove();
			$('video').remove();
			$('iframe').remove();
			setTimeout(mostrarPregunta, 1000);
		}
	}

	function mostrarPregunta() {
		$("#modal1").openModal({
			dismissible: false, // Modal can be dismissed by clicking outside of the modal
			opacity: .5, // Opacity of modal background
			in_duration: 300, // Transition in duration
			out_duration: 200, // Transition out duration
			ready: function() {}, // Callback for Modal open
			complete: function() {
					comprobar()
				} // Callback for Modal close);

		});
		ruleta.innerHTML = categorias[categoria];
		aleatorio = Math.floor(Math.random() * arrayResult[categoria].length);
		titulo.innerHTML = arrayResult[categoria][aleatorio]["pregunta"];
		mostrarObjeto(arrayResult[categoria][aleatorio]["Objeto"], arrayResult[categoria][aleatorio]["tipoObjeto"]);
		for (var i = 0; i < nrespuestas - 1; i++) {
			botones[i].setAttribute("class", "");
			botones[i].innerHTML = arrayResult[categoria][aleatorio]['Respuesta ' + (i + 1)];
			botones[i].setAttribute("value", (i + 1));
		};
		botones[botones.length - 1].setAttribute("class", "");
		botones[botones.length - 1].innerHTML = arrayResult[categoria][aleatorio]['Correcta'];
		botones[botones.length - 1].setAttribute("value", arrayResult[categoria][aleatorio]['Correcta']);
		respuestaCorrecta = arrayResult[categoria][aleatorio]['Correcta'];
		arrayResult[categoria].splice(aleatorio, 1);
		desordenarRespuestas();
	}

	function sacarCategoria() {
		let categoriaVacia = true;
		do {
			categoria = Math.floor(Math.random() * categorias.length);
			if (arrayResult[categoria].length > 0) {
				categoriaVacia = false;
				categoriaPregunta = categorias[categoria];
			}

		} while (categoriaVacia);
		return categoria;
	}

	function desordenarRespuestas() {
		var azar = Math.floor(Math.random() * nrespuestas);
		var auxiliar;
		if (azar < nrespuestas - 1) {
			auxiliar = botones[azar].innerHTML;
			botones[azar].innerHTML = botones[botones.length - 1].innerHTML;
			botones[botones.length - 1].innerHTML = auxiliar;
			respuestaValida = botones[azar];
		} else {
			respuestaValida = botones[botones.length - 1];
		}
	}

	function mostrarObjeto(objeto, tipo) {
		switch (tipo) {
			case 'Imagen':
				obj.innerHTML = "<img src='" + rutas[0] + objeto + "' alt=\"foto\" >";
				break;
			case 'Audio':
				obj.innerHTML = "<audio controls><source src=" + rutas[1] + objeto + "></audio>";
				break;
			case 'Link':
                    objeto = objeto.replace("watch?v=", "embed/");
					obj.innerHTML = "<iframe  src=" + objeto + "?html5=1 frameborder=\"0\" allowfullscreen></iframe>";
				break;
			case 'Vídeo':
					obj.innerHTML = "<video src=" + rutas[2] + objeto + " controls ></video>";
				break;
			default:
				obj.innerHTML = "";
				break;
		}
	}

	function comprobar(event) {
		event.preventDefault();
		if (!clickado) {
			clickado = true;
			preguntasTotales--;
			if (this.innerHTML == respuestaCorrecta) {
				respuesta = "correcta";
				for (var i = 0; i < jugadores; i++) {
					if ((i + 1) == player) {
						puntos[i].innerHTML = parseInt(puntos[i].innerHTML) + 1;
					};
				};
				efectoRespuestaValida(this);
				setTimeout(revisarPreguntas, 3000);
			} else {
				respuesta = "incorrecta";
				efectoRespuestaInvalida(this);
				setTimeout(revisarPreguntas, 3000);

			}
			btnAnular.style = "display: block;";
			btnSiguiente.style = "display: block;";
		};

	}

	function revisarPreguntas() {
		if (preguntasTotales > 0) {
			if (player == jugadores) {
				player = 1;
				nronda++;
			} else {
				player++;
			}
		} else {
			$("#modal1").closeModal();
			$("#modal2").openModal({
				dismissible: false, // Modal can be dismissed by clicking outside of the modal
				opacity: .5, // Opacity of modal background
				in_duration: 300, // Transition in duration
				out_duration: 200, // Transition out duration
				ready: function() {}, // Callback for Modal open
				complete: function() {} // Callback for Modal close);

			});

			$('body').append('<audio src="./audios/victory.wav" controls autoplay loop  hidden></audio>');
		}
	}

	function efectoRespuestaValida(elemento) {
		$(elemento).addClass("correcta");
		$('body').append('<audio src="./audios/correcto.mp3" controls autoplay  hidden></audio>');
		setTimeout(function() {
			$('audio').remove();
			$('iframe').remove();
			$('video').remove();
		}, 3000);
	}

	function efectoRespuestaInvalida(elemento) {
		$(elemento).addClass("incorrecta");
		$('body').append('<audio src="./audios/incorrecta.mp3" controls autoplay  hidden></audio>');
		setTimeout(function(elemento) {
			$(respuestaValida).addClass("correcta");
			$(elemento).removeClass();
		}, 1500);
		setTimeout(function() {
			$('audio').remove();
			$('iframe').remove();
			$('video').remove();
		}, 3000);
	}

	function comprobarGanador() {
		let ganador = "";
		let puntuacion = -1;
		for (let i = 0; i < jugadores; i++) {
			if (parseInt(puntos[i].innerHTML) > puntuacion) {
				ganador = "<div class=\"ganador\"><i class=\"material-icons\">" + listaJugadores[i]["Imagen"] + "</i>" + listaJugadores[i]["Nombre"] + "</div>";
				puntuacion = puntos[i].innerHTML;
			} else if (parseInt(puntos[i].innerHTML) == parseInt(puntuacion)) {
				ganador += "<div class=\"ganador\"><i class=\"material-icons\">" + listaJugadores[i]["Imagen"] + "</i>" + listaJugadores[i]["Nombre"] + "</div>";
			}
		};
		return ganador;
	}
});
