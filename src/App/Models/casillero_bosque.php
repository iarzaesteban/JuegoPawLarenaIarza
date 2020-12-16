<?
namespace Src\App\Models;

use src\App\Models\Casillero;

class CasilleroBosque extends Casillero {

    public function ocupar($jugador) {
        //todo: Aplicar reglas casillero
        parent::ocupar($jugador);
    }
    
}

?>