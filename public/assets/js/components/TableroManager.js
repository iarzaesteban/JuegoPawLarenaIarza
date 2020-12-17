class TableroManager {

    constructor() {
        this.seleccionadas = [];
        this.validas = [];
        document.addEventListener("DOMContentLoaded", () => {
            var aux = document.getElementById("aux")
            var tablero = document.getElementById("tablero")
            var boton = document.createElement("button")
            boton.id = "arriba"
            boton.classList.add("boton-arriba")
            this.agregarEventoMover(boton, 0, 10, tablero)
            aux.appendChild(boton)
            var botonDerecha = document.createElement("button")
            botonDerecha.id = "derecha"
            botonDerecha.classList.add("boton-derecha")
            this.agregarEventoMover(botonDerecha, 10, 0, tablero)
            aux.appendChild(botonDerecha)
            var botonizquierda = document.createElement("button")
            botonizquierda.id = "izquierda"
            botonizquierda.classList.add("boton-izquierda")
            this.agregarEventoMover(botonizquierda, -10, 0, tablero)
            aux.appendChild(botonizquierda)
            var botonabajo = document.createElement("button")
            botonabajo.id = "abajo"
            botonabajo.classList.add("boton-abajo")
            this.agregarEventoMover(botonabajo, 0, -10, tablero)
            aux.appendChild(botonabajo)
        })
    }

    seClickeoCelda(posX, posY, id) {
        if (this.validas.includes(id)){
            if (this.seleccionadas.includes(id)){
                console.log("Hay un: " + id)
                const index = this.seleccionadas.indexOf(id);
                if (index > -1) {
                    console.log("Removiendo: " + id)
                    this.seleccionadas.splice(index, 1);
                }
                HTMLContainerModifier.removeClass(id, "seleccionada")
            } else {
                console.log("Pusheando: " + id)
                this.seleccionadas.push(id);
                HTMLContainerModifier.addClass(id, "seleccionada")
            }
        }
    }

    refresh(celdas) {
        console.log(celdas)
        var obj = JSON.parse(celdas)
        this.validas = []
        var i = 0;
        for (i = 0; i < obj.length; i++) {
            var id = "celda_" + obj[i].posicionX + "_" + obj[i].posicionY
            HTMLContainerModifier.addClass(id, "disponible")
            this.validas.push(id)
        } 
    }

    actualizarTablero(sala) {
        $.post("/actualizar?nombre-sala=" + sala,
            [],
            function(data, status){
                console.log(data)
                //todo: actualizar tablero
            }
        )
    }

    loadJugadores(json) {
        var li = document.getElementById("puntuaciones")
        li.innerHTML = "";
        for(var i = 0 ; i < json.length ; i++) {
            var ul = document.createElement("ul")
            ul.innerHTML = json[i].nombre + " - " + json[i].puntuacion
            li.appendChild(ul)
        }
    }

    agregarEventoMover(elem, derecha, arriba, target) {
        elem.addEventListener("click", ()=>{
            target.scrollLeft += derecha
            target.scrollTop -= arriba
        })
    }
}


var tableroManager = new TableroManager