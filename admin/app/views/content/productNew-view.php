<?php

use app\controllers\productController;

$pc = new productController();
$categories = $pc->selectDataController("unique", "category", "status", "2");

if ($categories->rowCount() >= 1) {

    $categories = $categories->fetchAll();
} else {
    echo "No hay datos";
}
?>

<!-- Form Start -->
<form class="FormularioAjax" method="POST" action="<?php echo APP_URL; ?>app/ajax/productAjax.php" autocomplete="off" enctype="multipart/form-data">
    <input type="hidden" name="product_module" value="publicProduct">

    <div class="container-fluid pt-4 px-4">
        <div class="row">
            <div class="col-lg-10 mx-auto"> <!-- Mantiene el mismo ancho que las tarjetas -->
                <div class="row align-items-center">
                    <div class="col-md-6 text-start"> <!-- Alinea el título con la card izquierda -->
                        <h3 class="m-0">AGREGAR UN NUEVO PRODUCTO</h3>
                    </div>
                    <div class="col-md-6 text-end"> <!-- Alinea el botón a la derecha -->
                        <button type="submit" class="btn btn-primary">Publicar Producto</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4">

        <!-- Row -->
        <div class="row ms-3">
            <!-- First column -->
            <div class="col-sm-5 col-xl-7">
                <!-- product information card-->
                <div class="card bg-secondary rounded p-4 mb-5">
                    <div class="card-header">
                        <h6 class="card-title">Información del producto</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Nombre del producto</label>
                            <input type="text" class="form-control" id="productName" name="productName" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,50}" placeholder="Titulo del producto">
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div class="mb-3">
                                    <label for="productSku" class="form-label">SKU</label>
                                    <input type="text" class="form-control" id="productSku" name="productSku" pattern="[A-Za-z0-9\-_\.]{3,10}" placeholder="SKU del producto">
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="mb-3">
                                    <label for="productBarcode" class="form-label">Código de barras</label>
                                    <input type="text" class="form-control" id="productBarcode" name="productBarcode" placeholder="0123-4567">
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" placeholder="Ingresa la descripción del producto." id="productDescription" name="productDescription" style="height: 250px;"></textarea>
                            <label for="productDescription"> Descripción del producto </label>
                        </div>
                    </div>
                </div>
                <!-- /Product info card -->

                <!-- media card -->
                <div class="card bg-secondary rounded p-4 mb-5">
                    <div class="card-header">
                        <h6 class="card-title">Imagen del producto</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="productImg" class="form-label">Buscar Imagen</label>
                            <input class="form-control bg-dark mb-3" type="file" id="productImg" name="productImg">
                            <img src="<?php echo APP_URL; ?>app/views/assets/img/no-data.png" id="preview" class="preview" alt="preview" />
                        </div>
                    </div>
                </div>
                <!-- /media card -->
            </div>
            <!-- /First column -->

            <!-- Second column -->
            <div class="col-sm-5 col-xl-4">
                <!-- Price card -->
                <div class="card bg-secondary rounded p-4 mb-5">
                    <div class="card-header">
                        <h6 class="card-title">Precio</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Precio base</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" placeholder="Precio">
                        </div>
                    </div>
                </div>
                <!-- /Price card -->

                <!-- Organize card -->
                <div class="card bg-secondary rounded p-4 mb-5">
                    <div class="card-header">
                        <h6 class="card-title">Organización e Inventario</h6>
                    </div>
                    <div class="card-body">
                    <div class="mb-3">
                            <label for="productStock" class="form-label">Agregar al stock</label>
                            <input type="number" class="form-control" id="productStock" name="productStock" placeholder="Stock">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="productCategory">Categoría</label>
                            <select class="form-select" id="productCategory" name="productCategoryId" aria-label="Selecciona la categoría">
                                <option selected value="">Seleccione la categoría</option>
                                <?php
                                foreach ($categories as $category) {

                                    echo "<option value='".$category->id."'>".$category->name."</option>";
                                    
                                }
                                ?>

                            </select>

                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="productStatus">Estatus</label>
                            <select class="form-select" id="productStatus"
                                aria-label="Selecciona la categoria" name="productStatus">
                                <option value="1" selected>Planificado</option>
                                <option value="2">Publicado</option>
                                <option value="3">Inactivo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /Organize card -->
            </div>
            <!-- /Second column -->
        </div>
        <!-- /Row -->
    </div>

</form>

<script>
    const defaultFile = "http://admin.rentasm.com/app/views/assets/img/noImageSelected.jpg";
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