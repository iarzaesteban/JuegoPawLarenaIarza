class TableroManager {

    constructor() {
        this.seleccionadas = [];
    }

    seClickeoCelda(id) {
        if (this.seleccionadas.includes(id)){
            arrayRemove(this.seleccionadas, id)
            HTMLContainerModifier.removeClass(id, "seleccionada")
        } else {
            this.seleccionadas.push(id);
            HTMLContainerModifier.addClass(id, "seleccionada")
        }
    }

}