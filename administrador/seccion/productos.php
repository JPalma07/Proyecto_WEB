<?php include('../template/cabecera.php')?>
<?php 
    $txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
    $txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
    $txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
    $accion=(isset($_POST['accion']))?$_POST['accion']:"";

    include("../configuracion/bd.php");
    
    switch($accion){
        case "Agregar":
            $sentenciaSQL= $conexion->prepare("INSERT INTO libros (nombre,imagen) VALUES (:nombre,:imagen);");
            $sentenciaSQL->bindParam(':nombre',$txtNombre);
           

            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            if($tmpImagen!=""){
                move_uploaded_file($tmpImagen,"../../imagenes/".$nombreArchivo);
            }

            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->execute();
            break;

        case "Modificar":
            $sentenciaSQL=$conexion->prepare("UPDATE libros SET nombre=:nombre WHERE id=:id");
            $sentenciaSQL->bindParam(':nombre',$txtNombre);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();

            if($txtImagen!=""){
            
            
            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            move_uploaded_file($tmpImagen,"../../imagenes/".$nombreArchivo);


            $sentenciaSQL=$conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
            
            if(isset($libro["imagen"]) &&($libro["imagen"]!="imagen.jpg")){
                if(file_exists("../../imagenes/".$libro["imagen"])){
                    unlink("../../imagenes/".$libro["imagen"]);
                }
            }

            $sentenciaSQL=$conexion->prepare("UPDATE libros SET imagen=:imagen WHERE id=:id");
            $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            }      
            header("Location:productos.php");
            break;

        case "Cancelar":
            header("Location:productos.php");
            break;

        case "Seleccionar":
            $sentenciaSQL=$conexion->prepare("SELECT * FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            $txtNombre=$libro['nombre'];
            $txtImagen=$libro['imagen'];

            break;
        case "Borrar":

            $sentenciaSQL=$conexion->prepare("SELECT imagen FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if(isset($libro["imagen"]) &&($libro["imagen"]!="imagen.jpg")){
                if(file_exists("../../imagenes/".$libro["imagen"])){
                    unlink("../../imagenes/".$libro["imagen"]);
                }
            }

            $sentenciaSQL=$conexion->prepare("DELETE FROM libros WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            header("Location:productos.php");
            break;
            
}
    $sentenciaSQL=$conexion->prepare("SELECT * FROM libros");
    $sentenciaSQL->execute();
    $listaLibros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

    <div class="col-md-5">

        <div class="card">
            <div class="card-header">
                Datos del libro
            </div>

            <div class="card-body">

                <form method="POST" enctype="multipart/form-data">

                    <div class = "form-group">
                    <label for="txtID">ID</label>
                    <input type="text" require readonly class="form-control" name="txtID" value="<?php echo $txtID ?>" id="txtID" placeholder="ID">
                    </div>

                    <div class = "form-group">
                    <label for="txtNombre">Nombre:</label>
                    <input type="text" require class="form-control" name="txtNombre" value="<?php echo $txtNombre ?>" id="txtNombre" placeholder="Nombre">
                    </div>

                    <div class = "form-group">
                    <label for="txtNombre">Imagen:</label>

                    <br>
                    <?php if($txtImagen!=""){ ?>
                     <img class="img-thumbnail rounded" src="../../imagenes/<?php echo $txtImagen; ?>" width="50" alt="">
                    <?php } ?>

                    <input type="file" require class="form-control" name="txtImagen"  id="txtImagen" placeholder="Nombre dgene la ima">
                    </div>

                    <div class="btn-group" role="group" aria-label="">
                        <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":""?> value="Agregar" class="btn btn-success">Agregar</button>
                        <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":""?> value="Modificar"  class="btn btn-warning">Modificar</button>
                        <button type="submit" name="accion" <?php echo ($accion!=="Seleccionar")?"disabled":""?> value="Cancelar" class="btn btn-info">Cancelar</button>
                    </div>
                    </form>

                 </div>

        </div>

    </div>

    <div class="col-md-7">

       <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
<?php foreach($listaLibros as $libro){?>

        <tbody>
            <tr>
                <td><?php echo $libro['id'] ?></td>
                <td><?php echo $libro['nombre'] ?></td>
                <td>

                    <img class="img-thumbnail rounded" src="../../imagenes/<?php echo $libro['imagen']; ?>" width="50" alt="">
                    
                </td>


                <td>

                    <form method="post">
                        <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id'] ?>">
                        <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary">
                        <input type="submit" name="accion" value="Borrar" class="btn btn-danger">
                    </form>

                </td>
            
            </tr>
    <?php }?>
        </tbody>
       </table>

    </div>

<?php include('../template/pie.php')?>