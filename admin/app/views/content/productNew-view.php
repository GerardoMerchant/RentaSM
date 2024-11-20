            <!-- Form Start -->
            <form class="FormularioAjax" method="POST" action="<?php echo APP_URL; ?>app/ajax/productoAjax.php" autocomplete="off" enctype="multipart/form-data">

                <div class="container-fluid pt-4 px-4">
                    <div class="flex-md-row d-flex justify-content-between bd-highlight mb-3 ">
                        <div class="p-2 bd-highlight">
                            <h3 class="">AGREGAR UN NUEVO PRODUCTO.</h3>
                        </div>

                        <div class="p-2 bd-highlight flex-wrap">
                            <button type="submit" class="btn btn-primary">Publicar Producto</button>
                        </div>

                    </div>

                    <!-- Row -->
                    <div class="row ms-3">

                        <!-- Fisrst column -->
                        <div class="col-sm-2 col-xl-7">

                            <!-- product information card-->
                            <div class="card bg-secondary rounded h-200 p-4 mb-5">
                                <!-- <div class="bg-secondary rounded h-200 p-4"> -->
                                <div class="card-header">
                                    <h6 class="card-title">Información del producto</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="productName" class="form-label">Nombre del producto</label>
                                        <input type="text" class="form-control" id="productName" placeholder="Titulo del producto">
                                    </div>
                                    <!---->
                                    <div class="row">
                                        <div class="col-sm">
                                            <div class="mb-3">
                                                <label for="productSku" class="form-label">SKU</label>
                                                <input type="text" class="form-control" id="productSku" placeholder="SKU del producto">
                                            </div>
                                        </div>
                                        <div class="col-sm">
                                            <div class="mb-3">
                                                <label for="barcode" class="form-label">Codigo de barras</label>
                                                <input type="text" class="form-control" id="productBarcode" placeholder="0123-4567">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" placeholder="Ingresa la desripción del producto."
                                            id="productDescription" style="height: 250px;"></textarea>
                                        <label for="productDescription"> Descripción del producto </label>
                                    </div>

                                </div>
                            </div>
                            <!-- /Product info card -->

                            <!-- media card -->
                            <div class="card bg-secondary rounded h-200 p-4 mb-5">
                                <div class="card-header">
                                    <h6 class="card-title">Imagen del producto</h6>
                                </div>
                                <div class="card-body">

                                    <div class="mb-3">
                                        <label for="productImg" class="form-label">Buscar Imagen</label>
                                        <input class="form-control bg-dark mb-3" type="file" id="productImg">
                                        <img src="https://cdn-icons-png.flaticon.com/512/1570/1570791.png" id="preview" class="preview" alt="preview" />
                                    </div>
                                </div>
                            </div>
                            <!-- /media card -->

                        </div>
                        <!-- End of column-->

                        <!-- Second column -->
                        <div class="col-sm-2 col-xl-4">
                            <!-- Price card -->
                            <div class="card bg-secondary rounded h-200 p-4 mb-5">
                                <div class="card-header">
                                    <h6 class="card-title">Precio.</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="productPrice" class="form-label">Precio base</label>
                                        <input type="number" class="form-control" id="productPrice" placeholder="Precio">
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="productStatus" checked>
                                        <label class="form-check-label" for="productStatus">En stock</label>
                                    </div>
                                </div>
                            </div>
                            <!-- /Price card -->
                            <!-- Organize card -->
                            <div class="card bg-secondary rounded h-200 p-4 mb-5">
                                <div class="card-header">
                                    <h6 class="card-title">Organización e Inventario</h6>
                                </div>
                                <div class="card-body">
                                    <!-- <div class="mb-3">
                                        <label for="productCategory" class="form-label">Categoria</label>
                                        <input type="text" class="form-control" id="productCategory">
                                    </div>  -->
                                    <div class="form-floating mb-3">
                                        <select class="form-select" id="productCategory"
                                            aria-label="Selecciona la categoria">
                                            <option selected>Seleccione la categoria</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                        <label for="productCategory">Categoria</label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="productStock" class="form-label">Agregar al stock</label>
                                        <input type="number" class="form-control" id="productStock" placeholder="Stock">
                                    </div>
                                </div>
                            </div>
                            <!-- /Organize card -->

                        </div>
                        <!-- /Second column -->

                    </div>
                    <!-- /Row -->
            </form>
            </div>