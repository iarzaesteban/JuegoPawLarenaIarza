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
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#"+formID).serialize(),
                    dataType: "text",
                    sucess: function(data) {
                        var res = document.getElementById(outID)
                        if (res) {
                            res.html(data)
                        } else {
                            console.error("No se encontró el elemento out: " + outID)
                        }
                    },
                    error: function(http, status, exception) {
                        console.error(status + " ||| " + exception)
                    }
                })
                return false
            })
        } else {
            console.error("No se encontró el contenedor: " + formID)
        }
    }


}