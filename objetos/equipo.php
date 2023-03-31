<?php
class Equipo
{
	public $pkEquipo;
	public $fkManager;
	public $fkLiga;
	
	public $nombre;
	public $capLibre;
	public $waiver;
	
	public $corteGratisHabilitado;
	public $bloqueado;
	
	public $jugadoresConContrato;
	public $jugadoresIL;
	public $jugadoresLesionados;
	public $jugadoresCovid;
	public $jugadoresConDerecho;
	public $draftpicks;
	
	public $ofertas;
	public $trades;	
	
	public $pujas;
	
	public $sanciones;
	public $bonus;
	
	public $apuesta;
	
	public $numMovesDisponibles;
	
	public $palmares;
	public $records;
}
?>