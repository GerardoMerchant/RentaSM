<?php
$id = $url[1];

use app\controllers\productController;

$pc = new productController();

$img = $pc->selectDataUpdateCategoryImageController("unique", "category", "id", $id);

if ($img->rowCount() == 1) {
    
    $img = $img->fetch();
} else {
    
}
?>
<!-- Form Start -->
<form class="FormularioAjax" method="POST" action="<?php echo APP_URL; ?>app/ajax/productAjax.php" autocomplete="off" enctype="multipart/form-data">

    <input type="hidden" name="product_module" value="updateCategoryImage">
    <input type="hidden" name="categoryId" value="<?php echo $id; ?>">
    <input type="hidden" name="categoryOldImg" value="<?php echo $img->image; ?>">

    <div class="container-fluid pt-4 px-4">
       

        <!-- media card -->
        <div class="card bg-secondary rounded h-200 p-4 mb-5">
            <div class="card-header">
 <div class="flex-md-row d-flex justify-content-between bd-highlight mb-3 ">
            <div class="p-2 bd-highlight">
                <h3 class="">ACTUALIZAR IMAGEN DE LA CATEGORIA: <?php echo $img->name; ?></h3>
                <p class="text-center"><?php echo "<strong>Categoria creada:</strong> " . date("d-m-Y  h:i:s A", strtotime($img->creation_date)) .
                                            " &nbsp; <strong>Categoria actualizada:</strong> " . date("d-m-Y  h:i:s A", strtotime($img->modification_date)); ?></p>

            </div>

            <div class="p-2 bd-highlight flex-wrap">
                <a href="<?php echo APP_URL; ?>productCategoryList/" class="btn btn-secondary">Volver a la lista de categorias</a>
                <button type="submit" class="btn btn-primary">Actualizar Imagen</button>
                
            </div>
        </div>
                <h6 class="card-title">Seleccione la imagen de la categoria</h6>
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <label for="productImg" class="form-label">Buscar Imagen</label>
                    <input class="form-control bg-dark mb-3" type="file" id="productImg" name="categoryImg">
                    <h4>Imagen actual</h4>
                    <?php
                    if ($img->image != "") {
                        echo "<img src='" . APP_URL . "app/views/assets/img/ecommerce-images/categories/" . $img->image . "' id='preview' class='preview' alt='preview' />";
                    } else {
                        echo "<img src='" . APP_URL . "app/views/assets/img/no-data.png' id='preview' class='preview' alt='preview' />";
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- /media card -->
    </div>

</form>

<script type="text/javascript">
    defaultFile = 'http://admin.rentasm.com/app/views/img/noImageSelected.jpg'
    const file = document.getElementById('productImg');
    const img = document.getElementById('preview');
    file.addEventListener('change', e => {
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        } else {
            img.src = defaultFile;
        }

    });
</script>