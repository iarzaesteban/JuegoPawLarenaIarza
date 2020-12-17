class TableroManager {

    constructor() {
        this.seleccionadas = [];
        this.validas = [];
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
}


var tableroManager = new TableroManager