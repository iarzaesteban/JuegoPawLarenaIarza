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
        console.log("celdas")
        this.validas = celdas;
    }

}


var tableroManager = new TableroManager