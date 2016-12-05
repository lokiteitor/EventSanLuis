<?php 
require_once 'php/database/Evento.php';
require_once 'php/database/PartidaEvento.php';
/**
* contralador para todas las acciones relacionadas a los eventos
*/
class EventoController
{
    public function registrar()
    {
        $evento = new Evento();
        $boleto = new Boleto();

        // primero crear el boleto ya que el evento depende de este
        if (isset($_POST['precio'])) {
            $boleto->PRECIO = $_POST['precio'];
        }
        if (isset($_POST['nino'])) {
            $boleto->NINO = $_POST['nino'];
        }
        if (isset($_POST['estudiante'])) {
            $boleto->ESTUDIANTE = $_POST['estudiante'];
        }
        if (isset($_POST['vejez'])) {
            $boleto->VEJEZ = $_POST['vejez'];
        }
        if (isset($_POST['adulto'])) {
            $boleto->ADULTO = $_POST['adulto'];
        }
        $boleto->save();         


        // crear el evento 
        $evento->ID_BOLETO = $boleto->ID;

        if (isset($_POST['tipo'])) {
            $evento->TIPO_EVENTO = $_POST['tipo'];
        }
        if (isset($_POST['titulo'])) {
            $evento->TITULO = $_POST['titulo'];
        }
        if (isset($_POST['inicio'])) {
            $evento->FECHA_INICIO = $_POST['inicio'];
        }

        if (isset($_POST['fin'])) {
            $evento->FECHA_FIN = $_POST['fin'];
        }

        if (isset($_POST['artista'])) {
            $evento->ARTISTA = $_POST['artista'];
        }

        if (isset($_POST['slugar'])) {
            $evento->ID_LUGAR = $_POST['slugar'];
        }

        if (isset($_POST['resena'])) {
            $evento->RESENA = $_POST['resena'];
        }

        if (isset($_POST['antecedentes'])) {
            $evento->ANTECEDENTES = $_POST['antecedentes'];
        }
        $evento->save();

       
    }

    public function registrarFunciones()
    {
        for ($i=0; $i < count($_POST['sala']); $i++) { 
            // creaamos una partida por funcion
            $pevento = new PartidaEvento();
            if (isset($_POST['id_evento'])) {
                $pevento->ID_EVENTO = $_POST['id_evento'];
            }
            $pevento->N_SALA = $_POST['sala'][$i];
            $pevento->HORA_INICIO = $_POST['inicio'][$i];
            $pevento->HORA_FIN = $_POST['fin'][$i];
            $pevento->CAPACIDAD = $_POST['capacidad'][$i];
            $pevento->savePartida(false);
        }
    }

    public function modificarFuncion()
    {
        // modicar el registro de una funcion
        $pevento = PartidaEvento::findPartida($_POST['id_evento'],$_POST['id_n_sala']);

        if (isset($_POST['modinicio'])) {
            $pevento->HORA_INICIO = $_POST['modinicio'];
        }

        if (isset($_POST['modfin'])) {
            $pevento->HORA_FIN = $_POST['modfin'];
        }

        if (isset($_POST['modcapacidad'])) {
            $pevento->CAPACIDAD = $_POST['modcapacidad'];
        }

        $pevento->savePartida(true);

    }
}

 ?>