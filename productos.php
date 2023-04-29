<?php include("template/cabecera.php"); ?>

<?php
include("administrador/configuracion/bd.php");

$sentenciaSQL=$conexion->prepare("SELECT * FROM libros");
$sentenciaSQL->execute();
$listaLibros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<?php foreach($listaLibros as $libro){ ?>
<div class="col-md-2">
    <div class="card">
        <img class="card-img-top" src="./imagenes/<?php echo $libro['imagen'] ?>" alt="">
        <div class="card-body">
            <h4 class="card-title"><?php echo $libro['nombre'] ?></h4>
            <!-- <a name="" id="" class="btn btn-primary" href="#" role="button">ver mas</a> -->
            <!-- <p class="card-text">Text</p> -->
        </div>
    </div>
</div>

<?php } ?>
<?php include("./template/pie.php"); ?>
