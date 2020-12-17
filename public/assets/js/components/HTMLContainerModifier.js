class HTMLContainerModifier {

    static loadEvent(containerID, event, callBack = null) {
        var container = document.getElementById(containerID)
        if (container) {
            container.addEventListener(event, callBack)
        } else {
            console.error("No se pudo encotrar el id: " + containerID)
        }
    }

    static redirectOnClick(containerID, url) {
        this.loadEvent(containerID, "click", ()=>{document.location.replace(url)})
    }

    static PostDinamicoFormConResultadoOut(formID, outID, buttonID, url) {
        var div = document.getElementById(formID)
        if (div) {
            div.addEventListener("submit", function(ev) {
                ev.preventDefault()
                $.post(url,
                    $("#"+formID).serialize(),
                    function(data, status){
                        var res = document.getElementById(outID)
                        console.log(data)
                        if (res) {
                            res.innerHTML = data
                        } else {
                            console.error("No se encontró el elemento out: " + outID)
                        }
                })
                return false
            })
        } else {
            console.error("No se encontró el contenedor: " + formID)
        }
    }

    static redirectOnClickWithParam(id, url, property, nameParam) {
        var div = document.getElementById(id)
        if (div){
            if (property == "innerText") {
                url += "?" + nameParam + "=" + document.getElementById(id).innerText
            }
            this.redirectOnClick(id, url)
        }
    }

    static redirectWithCondition(url, strExpected, time, urlReturn, params) {
        setInterval(function(){
            $.post(url,
                params,
                function(data, status){
                    console.log(data)
                    if (strExpected == data){
                        console.log("Redirigiendo...")
                        document.location.replace(url)
                    }
            })
        },time,"JavaScript");
    }

    static postConScheduler(outID, url, time, params = []) {
        setInterval(function(){
            $.post(url,
                params,
                function(data, status){
                    var res = document.getElementById(outID)
                    if (res) {
                        res.innerHTML = data
                    } else {
                        console.error("No se encontró el elemento out: " + outID)
                    }
            })
        },time,"JavaScript");
    }

    static postDinamico(outID, buttonID, url) {
        this.loadEvent(buttonID, "click", ()=>{
            $.post(url,
                [],
                function(data, status){
                    var res = document.getElementById(outID)
                    console.log(data)
                    if (res) {
                        res.innerHTML = data
                    } else {
                        console.error("No se encontró el elemento out: " + outID)
                    }
            })
            return false
        })
    }

    static refreshObject(obj, url, time) {
        setInterval(function(){
            $.post(url,
                "",
                function(data, status){
                    obj.refresh(data)
            })
        },time,"JavaScript");
    }

    static ocuparPostDinamico(outId, buttonId, sala, tableroManager){
        this.loadEvent(buttonId, "click", ()=>{
            $.post("/ocupar?nombre-sala=" + sala +"&seleccion={" +tableroManager.seleccionadas.join(', ')+"}",
                {seleccion : tableroManager.seleccionadas},
                function(data, status){
                    var res = document.getElementById(outId)
                    console.log(data)
                    if (res) {
                        if (data == "error"){
                            console.warn("No se puede ocupar... No es su turno...")
                        } else {
                            tableroManager.actualizarTablero(sala)
                            res.innerHTML = data
                            tableroManager.seleccionadas = []
                        }
                    } else {
                        console.error("No se encontró el elemento out: " + outID)
                    }
            })
            return false
        })
    }

    static removeClassOfAll(clase) {
        var elementos = document.querySelectorAll('.' + clase)
        for (var i = 0; i < document.length; i++) {
            document[i].classList.remove(clase)
        }
    }

    static removeClass(id, c) {
        var div = document.getElementById(id)
        if (div) {
            div.classList.remove(c);
        }
    }

    static addClass(id, c) {
        var div = document.getElementById(id)
        if (div) {
            div.classList.add(c);
        }
    }

}