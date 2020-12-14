<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MyNewMigration extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('juego');
        
        $table->addColumn('nombre', 'string')
              ->addColumn('estado', 'string')
              ->addColumn('jugadorEnTurno', 'integer')
              ->create();
        $table = $this->table('jugador');
        
        $table->addColumn('nombre', 'string')
              ->addColumn('juego', 'integer')
              ->create();
        $table = $this->table('usuario');
        
        $table->addColumn('nombre', 'string')
              ->addColumn('mail', 'string')
              ->addColumn('password', 'string')
              ->create();
        $table = $this->table('tablero');
        
        $table->addColumn('nombre', 'string')
              ->addColumn('juego', 'string')
              ->create();
        $table = $this->table('comodin');
        
        $table->addColumn('nombre', 'string')
              ->addColumn('descripcion', 'string')
              ->create();
        $table = $this->table('carta');
        
        $table->addColumn('comodin', 'integer')
              ->addColumn('intensidad', 'integer')
              ->create();
        $table = $this->table('cartaJugador');
        
        $table->addColumn('jugador', 'integer')
              ->addColumn('carta', 'integer')
              ->create();
        $table = $this->table('posicion');
        
        $table->addColumn('posicionX', 'integer')
              ->addColumn('posicionY', 'integer')
              ->create();
        $table = $this->table('casillero');
        
        $table->addColumn('jugador', 'integer')
              ->addColumn('tablero', 'integer')
              ->addColumn('posicion', 'integer')
              ->create();
    }
}