<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Compra Confirmada</title>
	<?php Layout('libs') ?>
</head>
<body>
	<?php Layout('nav') ?>
	
	<div class="row">
		<div class="col-lg-offset-3 col-md-offset-3 col-xs-10 col-sm-6 col-md-6 col-lg-6 col-xs-offset-1 col-sm-12 col-sm-offset-3">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">Compra confirmada</h3>
				</div>
				<div class="panel-body">
					Tu compra ha sido confirmada, por favor revisa tu correo para obtener tu codigo de compra.
				</div>
				<div class="panel-footer">
					<a href=<?php url('/tablero') ?>><button type="button" class="btn btn-info">Regresar al tablero</button></a>
				</div>
			</div>
		</div>
	</div>
	
</body>
</html>