<?php

use app\controllers\productController;

$pc = new productController();
$categories = $pc->listCategoryController();
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <div class="flex-md-row d-flex justify-content-between bd-highlight mb-3 ">
            <div class="p-2 bd-highlight">
                <h3 class="">Lista de Categorias.</h3>
            </div>

            <div class="p-2 bd-highlight flex-wrap">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                    aria-controls="offcanvasRight">Agregar Categoria</button>
            </div>

        </div>
        <!-- Category list table -->
        <div class="table-responsive">
            <table id="categoryList" class="table table-striped nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>CATEGORIAS</th>
                        <th>TOTAL DE PRODUCTOS</th>
                        <th>TOTAL GANADO</th>
                        <th>ESTATUS</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category) {

                        $totalSales = $pc->totalSales($category->id);
                        $totalProducts = $pc->totalProducts($category->id);

                    ?>
                        <tr>
                            <!-- Category name -->
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded me-2 p-2 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                        <img src="<?php echo APP_URL; ?>app/views/assets/img/ecommerce-images/categories/<?php
                                                                                        if ($category->image == "") {
                                                                                            echo "no-data.png";
                                                                                        } else {
                                                                                            echo $category->image;
                                                                                        }

                                                                                        ?>"
                                            alt="Producto 1" class="img-fluid" style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>
                                    <div>
                                        <h6 class="mb-1"><?php echo $category->name; ?></h6>
                                        <small class="d-inline-block text-truncate" style="max-width: 500px;"><?php echo $category->description; ?></small>
                                    </div>
                                </div>
                            </td>
                            <!-- /Category name -->

                            <!-- Category qty products -->
                            <td>
                                <div>
                                    <?php
                                    foreach ($totalSales as $sales) {
                                        echo $sales->total_sales;
                                    }
                                    ?>
                                </div>
                            </td>
                            <!-- /Category qty products -->

                            <!-- Total earned -->
                            <td>
                                <div>
                                    <?php
                                    foreach ($totalProducts as $products) {
                                        echo "$" . number_format($products->total_products_sold, 2);
                                    }

                                    ?>
                                </div>
                            </td>
                            <!-- /Total earned -->

                            <!-- Status -->
                            <td>
                                <?php
                                if ($category->status == 1) {
                                    echo "<span class='badge bg-warning fs-6' text-capitalized>Planificado</span>";
                                } elseif ($category->status == 2) {
                                    echo "<span class='badge bg-success fs-6' text-capitalized>Activo</span>";
                                } elseif ($category->status == 3) {
                                    echo "<span class='badge bg-danger fs-6' text-capitalized>Inactivo</span>";
                                }
                                ?>

                            </td>
                            <!-- /Status -->

                            <!-- Category actions -->
                            <td>
                                <div class="d-inline-block text-nowrap">
                                    <!--  <a href="<?php echo APP_URL; ?>productCategoryUpdate/<?php echo $category->id; ?>/" class="btn btn-icon">
                                        
                                    </a>-->
                                    <button
                                        type="button"
                                        class="btn btn-icon"
                                        data-bs-toggle="modal"
                                        data-bs-target="#updateModal"
                                        data-id="<?php echo $category->id; ?>"
                                        data-name="<?php echo $category->name; ?>"
                                        data-description="<?php echo $category->description; ?>"
                                        data-image="<?php echo $category->image; ?>"
                                        data-status="<?php echo $category->status; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-dark">
                                        <a href="<?php echo APP_URL ?>productCategoryImageUpdate/<?php echo $category->id; ?>" class="dropdown-item">Ver Imagen</a>

                                        <form class="FormularioAjax" method="POST"
                                            action="<?php echo APP_URL; ?>app/ajax/productAjax.php" autocomplete="off" enctype="multipart/form-data">
                                            <input type="hidden" name="product_module" value="delateCategory">
                                            <input type="hidden" name="id" value=<?php echo $category->id ?>>
                                            <button type="submit" class="dropdown-item">Suprimir</button>
                                        </form>

                                    </div>
                                </div>
                            </td>
                            <!-- /Category actions -->
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>

                        <th>CATEGORIAS</th>
                        <th>TOTAL DE PRODUCTOS</th>
                        <th>TOTAL GANADO</th>
                        <th>ESTATUS</th>
                        <th>ACCIONES</th>
                    </tr>
                </tfoot>
            </table>

        </div>
        <!-- /Category List table -->

        <!-- Update Category Modal -->

        <!-- Button trigger modal -->
        <!--   <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Launch static backdrop modal
        </button>-->

        <!-- Modal -->

        <div class="modal fade" id="updateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Actualizar Categoria </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="FormularioAjax" method="POST" action="<?php echo APP_URL; ?>app/ajax/productAjax.php" autocomplete="off" enctype="multipart/form-data">
                            <input type="hidden" name="product_module" value="updateCategory">
                            <!-- Category Form -->
                            <input type="hidden" id="modal-id" name="categoryId">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Titulo</label>
                                <input type="text" class="form-control" id="modal-name" name="categoryName" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,50}"
                                    placeholder="Titulo del producto" required>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea class="form-control" placeholder="Ingresa la desripción de la categoria."
                                    id="modal-description" style="height: 250px;" name="categoryDesc"></textarea>
                                <label for="modal-description"> Descripción </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="category">Selecciona el estatus de la categoria</label>
                                <select class="form-select" id="modal-status"
                                    aria-label="Selecciona la categoria" name="categoryStatus">
                                    <option value="1">Planificado</option>
                                    <option value="2">Publicado</option>
                                    <option value="3">Inactivo</option>
                                </select>
                            </div>
                            <!-- /Category Form -->

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Actualizar Categoria</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- /Update Category Modal -->

        <!-- OffCanvas Header -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h3 id="offcanvasRightLabel" class="">Agregar Categoria</h3>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <!-- /OffCanvas Header -->

            <!-- OffCanvas body -->
            <div class="offcanvas-body">
                <!-- Category Form -->
                <form class="FormularioAjax" method="POST" action="<?php echo APP_URL; ?>app/ajax/productAjax.php" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="product_module" value="publicCategory">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Titulo</label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,50}" placeholder="Titulo del producto" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryImg" class="form-label">Archivo adjunto</label>
                        <input class="form-control bg-dark mb-3" type="file" id="categoryImg" name="categoryImg">
                    </div>
                    <!---->
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Ingresa la desripción de la categoria."
                            id="categoryDesc" style="height: 250px;" name="categoryDesc"></textarea>
                        <label for="categoryDesc"> Descripción </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="category">Selecciona el estatus de la categoria</label>
                        <select class="form-select" id=""
                            aria-label="Selecciona la categoria" name="categoryStatus">
                            <option value="1" selected>Planificado</option>
                            <option value="2">Publicado</option>
                            <option value="3">Inactivo</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Publicar Categoria</button>
                    <button type="reset" class="btn btn-outline-warning ms-2" data-bs-dismiss="offcanvas">Descartar</button>
                </form>
                <!-- /Category Form -->
            </div>
        </div>

        <!-- /OffCanvas body -->
    </div>
</div>

<!-- DataTables js -->

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>

<script type="text/javascript">
    new DataTable('#categoryList', {
        autoWidth: false,
        language: {
            url: 'http://admin.rentasm.com/app/views/json/dataTableLenguage.json',
        },
    });
</script>

<script>
    // 
    const modal = document.getElementById('updateModal');
    modal.addEventListener('show.bs.modal', function(event) {
        // Modal trigger button
        const button = event.relatedTarget;

        // Extract information from attributes data-*
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const description = button.getAttribute('data-description');
        //const image = button.getAttribute('data-image');
        const status = button.getAttribute('data-status');

        // Update modal content
        document.getElementById('modal-id').value = id;
        document.getElementById('modal-name').value = name;
        document.getElementById('modal-description').value = description;
        //document.getElementById('modal-image').src = 'http://admin.rentasm.com/app/views/img/' + image;
        document.getElementById('modal-status').value = status;

    });
</script>