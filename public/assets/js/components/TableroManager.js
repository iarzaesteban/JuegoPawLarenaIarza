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
            console.log(obj[i])
            this.validas.push("celda_" + obj[i].posicionX + "_" + obj[i].posicionY)
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

}


var tableroManager = new TableroManager