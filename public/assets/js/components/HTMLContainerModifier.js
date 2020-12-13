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
}