<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <?php Layout('libs'); ?>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php Layout('nav'); ?>
        </div>
        <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-lg-offset-2 col-md-offset-2" >

                <form action=<?php url('/login') ?> method="POST" class="form-horizontal" role="form">

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Login</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="username" class="control-label col-md-4 col-lg-4">Nombre de Usuario</label>
                                <div class="col-md-6 col-lg-6">
                                    <input type="text" name="username" class="form-control" required >
                                </div>                                                                        
                            </div>

                            <div class="form-group">
                                <label for="password" class="control-label col-md-4 col-lg-4">Password</label>
                                <div class="col-md-6 col-lg-6">
                                    <input type="password" name="password" class="form-control"  required >    
                                </div>
                                
                            </div>                                        
                            <div class="form-group">
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xs-6 col-lg-offset-4 col-lg-offset-4 col-md-offset-4 col-xs-offset-4">
                                    <button type="submit" class="btn btn-primary">Ingresar</button>
                                </div>
                            </div>                            
                        </div>
                    </div>                
                </form>                    
            </div>
        </div>
    </div>
</body>
</html>