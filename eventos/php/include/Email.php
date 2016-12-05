<?php 
require_once 'vendor/autoload.php';

use Mailgun\Mailgun;
class Email
{
	private $mg;
	private $domain;
	private $dest;

	public $folio;

	function __construct()
	{
		$this->mg = new Mailgun("key-e6ba2ead547982e48163b964c759628d",null,"bin.mailgun.net");
		$this->domain = "crm.vantec.mx";	
		$this->mg->setApiVersion('5938c136');
		$this->mg->setSslEnabled(false);
	}

	public function setTo($email)
	{
		$this->dest = $email;
	}

	public function send()
	{

		$html = "<!DOCTYPE html>
		<html lang='en'>
		<head>
			<meta charset='UTF-8'>
			<title>Compra Confirmada</title>
		</head>
		<body>
			
			<p>Su compra se ha realizado con exito</p>

			<strong>Folio:</strong>$this->folio
		</body>
		</html>";

		$mail = $this->mg->sendMessage($this->domain,array(
			'from' => 'ventas@eventsanluis.ml',
			'to' => $this->dest,
			'subject' => 'Confirmacion de compra',
			'html' => $html
			));


	}
}





 ?>