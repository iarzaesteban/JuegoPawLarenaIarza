class Celda {

    constructor(posX, posY, jugador) {
        if (jugador != ""){
            //Cambiar clase para colorcitos
        } else {
            //Agregar los eventos
            var id = "celda_" + posX + "_" + posY
            HTMLContainerModifier.loadEvent(id, "click", ()=>{
                console.log("Se clickeo: " + id)
                tableroManager.seClickeoCelda(posX, posY, id)
            })
        }
    }

}