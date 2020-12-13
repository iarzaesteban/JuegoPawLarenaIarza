class AppPaw
{
    constructor()
   {

        document.addEventListener("DOMContentLoaded", () => {
            Paw.cargarScript("Paw-Menu", "/assets/js/components/HTMLContainerModifier.js", () => {
               //var htmlContainerModifier = new HTMLContainerModifier();
            });

        });
   }
}

let app = new AppPaw();