# JuegoPawLarenaIarza

#Modelo de clases: https://app.diagrams.net/#G1hy_Qqsp1v9g3s0_xeic_Rysk20hZ_ahl
#Podemos visualizar el maquetado en figma: https://www.figma.com/file/HgKRa17SXiURKKf3nXEHgu/Epidemiology?node-id=0%3A1

#Comenzamos


# Planteo del problema


Se requiere desarrollar un juego didáctico con temática de enfermedades contagiosas.
Al ingresar a la página se podrá ingresar o crear una sala.
Dentro de la sala, pueden ingresar una cantidad de jugadores que en esta iteración no está definida aún.
Cuando el creador de la sala lo decida, puede iniciar la partida.
La partida consiste en un juego de mesa, con un dado y un tablero.
La jugabilidad es similar la TEC, sólo que no se pueden capturar fichas.
Comienza un jugador aleatorio, y sólo por el primer turno el orden de tirada de los jugadores es aleatorio.
Luego, se mantiene el mismo orden.
Cuando un jugador le toca jugar, debe tirar un dado. Según el número que saque, puede ocupar la misma cantidad de celdas.
Cada jugador comienza con una celda ocupada, y puede ir ocupando celdas alrededor de las que ya tiene ocupadas.
Hay celdas especiales que activan diferentes efectos. Como cambiar la cantidad de celdas que ocupará el jugador que la ocupe, por algunos turnos.
Cada jugador tiene una serie de cartas que tienen un efecto sobre los rivales. Cuando un jugador saca un 1, puede tirar una de las cartas, la que elija.
Lar cartas las tiene desde el inicio de la partida, y puede ver en todo momento cuales tiene. No obstante, sólo puede usar una vez cada una.
Las cartas, son elegidas al azar por el sistema al inicio de la partida.

Gana el jugador con más celdas ocupadas luego de 12 turnos, o cuando ya no se puedan ocupar más celdas.