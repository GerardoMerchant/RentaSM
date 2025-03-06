<?php

use app\controllers\productController;

$pc = new productController();

$products = $pc->selectDataController("normal", "products", "*", "");

if ($products->rowCount() >= 1) {
    $products = $products->fetchAll();
    $pc->writeToConsole("hay datos");
} else {
    $pc->writeToConsole("No hay datos");
}

?>


<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary rounded h-100 p-4">
        <h3 class="mb-4">Lista de Productos</h3>
        <div class="table-responsive">
            <table id="productList" class="table table-striped nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>PRODUCTO</th>
                        <th>CATEGORIA</th>
                        <th>PRECIO</th>
                        <th>CANTIDAD</th>
                        <th>ESTATUS</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    foreach ($products as $product) {
                    ?>
                        <tr>
                            <td><?php echo $product->sku ?></td>
                            <td>
                                <div class="d-flex align-items-start">
                                    <div class="bg-light rounded me-2 p-2 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                        <img src="<?php echo APP_URL; ?>app/views/assets/img/ecommerce-images/products/thumbnail/<?php
                                                                                                                                    if ($product->image_url == "") {
                                                                                                                                        echo "no-data.png";
                                                                                                                                    } else {
                                                                                                                                        echo $product->image_url;
                                                                                                                                    }

                                                                                                                                    ?>"
                                            alt="Producto" class="img-fluid" style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>
                                    <div>
                                        <h6 class="mb-1"><?php echo $product->name ?></h6>
                                        <small class="d-inline-block text-truncate" style="max-width: 500px;"><?php echo $product->description ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-truncate d-flex align-items-center text-heading">
                                    <span class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center bg-info me-4" style="width: 40px; height: 40px;">
                                        <!--<i class="fas fa-table fa-lg"></i>-->

                                        <img src="<?php echo APP_URL; ?>app/views/assets/img/ecommerce-images/categories/<?php
                                                                                                                            $category = $pc->selectDataController("unique", "category", "id", $product->category_id);

                                                                                                                            if ($category->rowCount() == 1) {
                                                                                                                                $category = $category->fetch();
                                                                                                                            }

                                                                                                                            if ($category->image == "") {
                                                                                                                                echo "no-data.png";
                                                                                                                            } else {
                                                                                                                                echo $category->image;
                                                                                                                            }

                                                                                                                            ?>"
                                            alt="Producto" class="img-fluid" style="width: 40px; height: 40px; object-fit: cover;">

                                    </span>
                                    <?php

                                    echo $category->name;

                                    ?>
                                </span>
                            </td>
                            <td>
                                <span><?php echo "$" . number_format($product->price, 2); ?></span>
                            </td>
                            <td><span><?php echo $product->current_stock ?></span></td>
                            <td>
                                <?php
                                if ($product->status == 1) {
                                    echo "<span class='badge bg-warning fs-6' text-capitalized>Planificado</span>";
                                } elseif ($product->status == 2) {
                                    echo "<span class='badge bg-success fs-6' text-capitalized>Activo</span>";
                                } elseif ($product->status == 3) {
                                    echo "<span class='badge bg-danger fs-6' text-capitalized>Inactivo</span>";
                                }
                                 
                                 ?>
                            </td>
                            <td>
                                <div class="d-inline-block text-nowrap">
                                    <a href="<?php echo APP_URL."productUpdate/".$product->id; ?>" class="btn btn-icon">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-dark">
                                        <a href="#" class="dropdown-item">Ver</a>
                                        <a href="#" class="dropdown-item">Suspender</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>SKU</th>
                        <th>PRODUCTO</th>
                        <th>CATEGORIA</th>
                        <th>PRECIO</th>
                        <th>CANTIDAD</th>
                        <th>ESTATUS</th>
                        <th>ACCIONES</th>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>

<!-- DataTables js -->

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>

<script type="text/javascript">
    new DataTable('#productList', {
        autoWidth: false,
        language: {
            url: 'http://admin.rentasm.com/app/views/json/dataTableLenguage.json',
        },
    });
</script>