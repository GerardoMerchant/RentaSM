<?php

namespace app\controllers;

use app\models\mainModel;
use PDOException;

class productController extends mainModel
{
    public function selectDataController($type, $table, $field, $id)
    {
        $type = $this->cleanString($type);
        $table = $this->cleanString($table);
        $field = $this->cleanString($field);
        $id = $this->cleanString($id);
        $data = $this->selectData($type, $table, $field, $id);
        return $data;
    }

    /*-------------------------- Category Section ---------------------------*/

    /*----------  Insert new category ----------*/
    public function publicCategorycontroller()
    {
        # Storage Data
        $categoryName = $this->cleanString($_POST['categoryName']);
        $categoryDesc = $this->cleanString($_POST['categoryDesc']);
        $categoryStatus = $this->cleanString($_POST['categoryStatus']);
        //$categoryImg;


        # Checking required fields #
        $fields = [$categoryName, $categoryDesc, $categoryStatus];

        foreach ($fields as $field) {
            if (!isset($field) || empty($field)) {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No has llenado todos los campos que son obligatorios",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }
        }

        # /Checking required fields #

        # checking data integrity #
        if ($this->verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,50}", $categoryName)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El NOMBRE no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        if ($this->verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .,]{3,150}", $categoryDesc)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La DESCRIPCIÓN no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        if ($this->verifyData("[1-3]{1}", $categoryStatus)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La opción seleccionada no es valida",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        # checking data integrity #

        #Image management#

        # Directory of images #
        $img_dir = "../views/assets/img/ecommerce-images/categories/";
        # Check if an image was selected #
        if ($_FILES['categoryImg']['name'] != "" && $_FILES['categoryImg']['size'] > 0) {
            # Create directory #
            if (!file_exists($img_dir)) {
                if (!mkdir($img_dir, 0777)) {
                    $alert = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error inesperado",
                        "texto" => "Error al crear el directorio",
                        "icono" => "error"
                    ];
                    return json_encode($alert);
                    exit();
                }
            }

            # Checking format of image #
            if ($_FILES['categoryImg']['type'] != "image/jpeg" && $_FILES['categoryImg']['type'] != "image/jpg" && $_FILES['categoryImg']['type'] != "image/png") {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "La imagen que ha seleccionado es de un formato no permitido",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            # Checking size of image #
            if (($_FILES['categoryImg']['size'] / 1024) > 5120) {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "La imagen que ha seleccionado supera el peso permitido",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            # Name of the image #    

            $lastId = $this->runQuery("SELECT MAX(id) AS last_id FROM category;");
            if ($lastId->rowCount() == 1) {
                $lastId = $lastId->fetch();
                $imgName = "category-" . $lastId->last_id += 1;
            } else {
                // Can't get the last id.

                $imgName = "category-" . rand(0, 1000);
            }
            # /Name of the image #


            # Extension of the image #
            switch ($_FILES['categoryImg']['type']) {
                case 'image/jpeg':
                    $imgName = $imgName . ".jpg";
                    break;
                case 'image/png':
                    $imgName = $imgName . ".png";
                    break;
                case 'image/jpg':
                    $imgName = $imgName . ".jpg";
                    break;
            }

            chmod($img_dir, 0777);

            # Moving image to directory #
            if (!move_uploaded_file($_FILES['categoryImg']['tmp_name'], $img_dir . $imgName)) {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No podemos subir la imagen al sistema en este momento",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }
        } else {
            $imgName = "";
        }



        $categoyDataReg = [
            [
                "name_field" => "name",
                "marker_field" => ":categoryName",
                "value_field" => $categoryName
            ],
            [
                "name_field" => "description",
                "marker_field" => ":categoryDesc",
                "value_field" => $categoryDesc
            ],
            [
                "name_field" => "status",
                "marker_field" => ":categoryStatus",
                "value_field" => $categoryStatus
            ],
            [
                "name_field" => "image",
                "marker_field" => ":categoryImg",
                "value_field" => $imgName
            ],
            [
                "name_field" => "creation_date",
                "marker_field" => ":categoryCreated",
                "value_field" => date("Y-m-d H:i:s")
            ],
            [
                "name_field" => "modification_date",
                "marker_field" => ":categoryModified",
                "value_field" => date("Y-m-d H:i:s")
            ],

        ];

        $public_category = $this->saveData("category", $categoyDataReg);
        if ($public_category->rowCount() == 1) {
            $alert = [
                "tipo" => "recargar",
                "titulo" => "Categoria registrada",
                "texto" => "La categoria " . $categoryName . " se registro con exito",
                "icono" => "success"
            ];
        } else {
            if (is_file($img_dir . $imgName)) {
                chmod($img_dir . $imgName, 0777);
                unlink($img_dir . $imgName);
            }
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No se pudo registrar la categoria, por favor intente nuevamente",
                "icono" => "error"
            ];
        }
        return json_encode($alert);
    }
    /*----------  /Insert new category ----------*/

    /*----------  Update Category ----------*/
    public function updateCategorycontroller()
    {
        # Storage Data
        $categoryName = $this->cleanString($_POST['categoryName']);
        $categoryDesc = $this->cleanString($_POST['categoryDesc']);
        $categoryStatus = $this->cleanString($_POST['categoryStatus']);
        $categoryId = $_POST['categoryId'];

        # Checking required fields #
        if ($categoryStatus == "" || $categoryName == "" || $categoryDesc == "" || $categoryId == "") {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        # /Checking required fields #

        # checking data integrity #
        if ($this->verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,50}", $categoryName)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El NOMBRE no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        if ($this->verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .,]{3,500}", $categoryDesc)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La DESCRIPCIÓN no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        if ($this->verifyData("[1-3]{1}", $categoryStatus)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La opción seleccionada no es valida",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        # /checking data integrity #

        # Dataset #

        $categoryDataUpdate = [
            [
                "name_field" => "name",
                "marker_field" => ":categoryName",
                "value_field" => $categoryName
            ],
            [
                "name_field" => "description",
                "marker_field" => ":categoryDesc",
                "value_field" => $categoryDesc
            ],
            [
                "name_field" => "status",
                "marker_field" => ":categoryStatus",
                "value_field" => $categoryStatus
            ],
            [
                "name_field" => "modification_date",
                "marker_field" => ":categoryModified",
                "value_field" => date("Y-m-d H:i:s")
            ],
        ];

        # Conditional #

        $categoryCondition = [
            "condition_field" => "id",
            "condition_marker" => ":categoryId",
            "condition_value" => $categoryId
        ];

        # /Dataset #

        # Update category data #
        if ($this->updateData("category", $categoryDataUpdate, $categoryCondition)) {
            $alert = [
                "tipo" => "redireccionar",
                "titulo" => "Categoria actualizada",
                "texto" => "La categoria " . $categoryName . " se actualizo correctamente",
                "icono" => "success",
                "url" => "http://admin.rentasm.com/productCategoryList/"
            ];
        } else {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No se pudo actualizar la categoria, por favor intente nuevamente",
                "icono" => "error"
            ];
        }
        # /Update category data #

        return json_encode($alert);
    }
    /*----------  /Update Category ----------*/

    /*---------- Update Category Image ----------*/
    public function updateCategoryImageController()
    {
        # Storage Data
        $categoryId = ($_POST['categoryId']);
        $categoryOldImg = ($_POST['categoryOldImg']);

        # Checking required fields #
        if ($categoryId == "") {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        # /Checking required fields #

        #Image management#

        # Directory of images #
        $img_dir = "../views/assets/img/ecommerce-images/categories/";

        # Check if an image was selected #
        if ($_FILES['categoryImg']['name'] == "" && $_FILES['categoryImg']['size'] <= 0) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No ha seleccionado una imagen para la categoria",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        # Create directory #
        if (!file_exists($img_dir)) {
            if (!mkdir($img_dir, 0777)) {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error al crear el directorio",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }
        }

        # Checking format of image #
        if ($_FILES['categoryImg']['type'] != "image/jpeg" && $_FILES['categoryImg']['type'] != "image/jpg" && $_FILES['categoryImg']['type'] != "image/png") {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La imagen que ha seleccionado es de un formato no permitido",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        # Checking size of image #
        if (($_FILES['categoryImg']['size'] / 1024) > 5120) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La imagen que ha seleccionado supera el peso permitido",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        # Name of the image #
        $imgName = "category-" . $categoryId;

        # Extension of the image #
        switch ($_FILES['categoryImg']['type']) {
            case 'image/jpeg':
                $imgName = $imgName . ".jpg";
                break;
            case 'image/png':
                $imgName = $imgName . ".png";
                break;
            case 'image/jpg':
                $imgName = $imgName . ".jpg";
                break;
        }

        chmod($img_dir, 0777);

        # Deleting old image #
        if (is_file($img_dir . $categoryOldImg)) {
            chmod($img_dir . $categoryOldImg, 0777);
            unlink($img_dir . $categoryOldImg);
        }

        # Moving image to directory #
        if (!move_uploaded_file($_FILES['categoryImg']['tmp_name'], $img_dir . $imgName)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No podemos subir la imagen al sistema en este momento",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }


        $categoyDataReg = [

            [
                "name_field" => "image",
                "marker_field" => ":categoryImg",
                "value_field" => $imgName
            ],
            [
                "name_field" => "modification_date",
                "marker_field" => ":categoryModified",
                "value_field" => date("Y-m-d H:i:s")
            ]

        ];

        $condition = [
            "condition_field" => "id",
            "condition_marker" => ":categoryId",
            "condition_value" => $categoryId
        ];

        if ($this->updateData("category", $categoyDataReg, $condition)) {

            $alert = [
                "tipo" => "recargar",
                "titulo" => "Foto actualizada",
                "texto" => "La imagen de la categoria: " . $categoryId . " se actualizo correctamente",
                "icono" => "success"
            ];
        } else {

            $alert = [
                "tipo" => "recargar",
                "titulo" => "Foto actualizada",
                "texto" => "No hemos podido actualizar algunos datos de la categoria: " . $categoryId . ", sin embargo la foto ha sido actualizada",
                "icono" => "warning"
            ];
        }

        return json_encode($alert);
    }
    /*---------- List Category ----------*/
    public function listCategoryController()
    {
        $checkData =  "SELECT * FROM category";



        $data = $this->runQuery($checkData);


        return $data = $data->fetchAll();
    }
    /*---------- /List Category ----------*/

    /*---------- Select data ----------*/
    public function selectDataUpdateCategoryImageController($type, $table, $field, $id)
    {
        $type = $this->cleanString($type);
        $table = $this->cleanString($table);
        $field = $this->cleanString($field);
        $id = $this->cleanString($id);
        $data = $this->selectData($type, $table, $field, $id);
        return $data;
    }
    /*---------- /Select data ----------*/

    public function deleteCategoryController()
{
    # Storage Data
    $id = $_POST['id'];

    # Verifying category
    $data = $this->runQuery("SELECT * FROM category WHERE id = '$id'");

    if ($data->rowCount() == 0) {
        $alert = [
            "tipo" => "simple",
            "titulo" => "Ocurrió un error inesperado",
            "texto" => "No hemos encontrado la categoría en el sistema",
            "icono" => "error"
        ];
        return json_encode($alert);
        exit();
    }

    try {
        # Delete category data
        $deleteCategory = $this->delateData("category", "id", $id);

        if ($deleteCategory->rowCount() == 1) {
            $alert = [
                "tipo" => "recargar",
                "titulo" => "Categoría eliminada",
                "texto" => "La categoría ha sido eliminada con éxito del sistema",
                "icono" => "success"
            ];
        } else {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No hemos podido eliminar la categoría del sistema",
                "icono" => "error"
            ];
        }
    } catch (PDOException $e) {
        # Si el error es por restricción de clave foránea
        if ($e->getCode() == 23000) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Error de dependencia",
                "texto" => "No puedes eliminar esta categoría porque tiene productos asociados.",
                "icono" => "warning"
            ];
        } else {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Error en la base de datos",
                "texto" => "Ocurrió un error inesperado. Contacta al administrador.",
                "icono" => "error"
            ];
        }

        # Registrar el error en un log para depuración
        error_log("Error SQL: " . $e->getMessage()."\n", 3, __DIR__."/../logs/error_".date("Y-m-d").".log");
    }

    return json_encode($alert);
}


    public function getProductCategoryDataContoller($id)
    {
        $productData = "SELECT * FROM category WHERE id = '$id'";
        $data = $this->runQuery($productData);
        return $data = $data->fetchAll();
    }

    /*-------------------------- /Category Section ---------------------------*/

    /*---------- Run Query ----------*/
    public function customConsult($query)
    {
        //$query = $this->cleanString($query);
        $data = $this->runQuery($query);
        return $data;
    }

    /*---------- /Run Query ----------*/

    /*---------- Total sales ----------*/
    public function totalSales($id)
    {
        $totalSales = "SELECT sd.category_id, SUM(sd.total_price) AS total_sales
        FROM sales_detail sd
        WHERE sd.category_id = $id";
        $data = $this->runQuery($totalSales);
        return $data = $data->fetchAll();
    }
    /*---------- /Total sales ----------*/

    /*---------- Total products ----------*/
    public function totalProducts($id)
    {
        $totalProducts = "SELECT sd.category_id, SUM(sd.quantity) AS total_products_sold
        FROM sales_detail sd
        WHERE sd.category_id = $id";
        $data = $this->runQuery($totalProducts);
        return $data = $data->fetchAll();
    }
    /*---------- /Total products ----------*/

    /*---------- Console Log ----------*/
    function writeToConsole($data)
    {
        $console = $data;
        echo "<script>console.log('Console: " . $console . "' );</script>";
    }
    /*---------- /Console Log ----------*/

    public function getProductDataContoller($sku)
    {
        $productData = "SELECT * FROM products WHERE sku = '$sku'";
        $data = $this->runQuery($productData);
        return $data = $data->fetchAll();
    }

    /*---------- Public product ----------*/

    public function publicProductController()
    {
        # Storage Data
        $productName = $this->cleanString($_POST['productName']);
        $productSku = $this->cleanString($_POST['productSku']);
        $productDescription = $this->cleanString($_POST['productDescription']);
        $productPrice = $this->cleanString($_POST['productPrice']);
        $productStock = $this->cleanString($_POST['productStock']);
        $productCategoryId = $this->cleanString($_POST['productCategoryId']);
        $productStatus = $this->cleanString($_POST['productStatus']);

        # Checking required fields #
        $fields = [$productName, $productSku, $productDescription, $productPrice, $productStock, $productCategoryId, $productStatus];

        foreach ($fields as $field) {
            if (!isset($field) || empty($field)) {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No has llenado todos los campos que son obligatorios.",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }
        }

        # /Checking required fields #

        # checking data integrity #

        if ($this->verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,150}", $productName)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El NOMBRE no coincide con el formato solicitado.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .,]{3,500}", $productDescription)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La DESCRIPCIÓN no coincide con el formato solicitado.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("[A-Za-z0-9\-_\.]{3,50}", $productSku)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El SKU no coincide con el formato solicitado.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("[\d]{1,5}", $productStock)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La cantidad de STOCK no coincide con el formato solicitado.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("[\d]{1,5}", $productPrice)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La cantidad en el PRECIO no coincide con el formato solicitado.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("[\d]{1,5}", $productCategoryId)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La opción seleccionada no es valida.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("[1-3]{1}", $productStatus)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La opción seleccionada no es valida.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        # checking data integrity #

        # SKU Validation #
        $checkSKu = $this->selectData("unique", "products", "sku", $productSku);
        if ($checkSKu->rowCount() >= 1) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Ya hay un producto con un codigo SKU similar.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }
        # /SKU Validation #

        # Image management #

        # Directory of images #

        $img_dir = "../views/assets/img/ecommerce-images/products/thumbnail/";

        # Check if an image was selected #
        if ($_FILES['productImg']['name'] != "" && $_FILES['productImg']['size'] > 0) {

            # Create directory #
            if (!file_exists($img_dir)) {
                if (!mkdir($img_dir, 0777)) {
                    $alert = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error inesperado",
                        "texto" => "Error al crear el directorio",
                        "icono" => "error"
                    ];
                    return json_encode($alert);
                    exit();
                }
            }

            # Checking format of image #
            if ($_FILES['productImg']['type'] != "image/jpeg" && $_FILES['productImg']['type'] != "image/jpg" && $_FILES['productImg']['type'] != "image/png") {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "La imagen que ha seleccionado es de un formato no permitido",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            # Checking size of image #
            if (($_FILES['productImg']['size'] / 1024) > 5120) {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "La imagen que ha seleccionado supera el peso permitido",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            # Name of the image #

            $lastId = $this->runQuery("SELECT MAX(id) AS last_id FROM products;");
            if ($lastId->rowCount() == 1) {
                $lastId = $lastId->fetch();
                $imgName = "product-" . $lastId->last_id += 1;
            } else {
                // Can't get the last id.

                $imgName = "product-" . rand(0, 1000);
            }

            # Extension of the image #
            switch ($_FILES['productImg']['type']) {
                case 'image/jpeg':
                    $imgName = $imgName . ".jpg";
                    break;
                case 'image/png':
                    $imgName = $imgName . ".png";
                    break;
                case 'image/jpg':
                    $imgName = $imgName . ".jpg";
                    break;
            }

            chmod($img_dir, 0777);

            # Moving image to directory #
            if (!move_uploaded_file($_FILES['productImg']['tmp_name'], $img_dir . $imgName)) {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No podemos subir la imagen al sistema en este momento",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }
        } else {
            $imgName = "";
        }

        # /Image management #

        # Dataset products#

        $productDataReg = [
            [
                "name_field" => "name",
                "marker_field" => ":productName",
                "value_field" => $productName
            ],
            [
                "name_field" => "description",
                "marker_field" => ":productDesc",
                "value_field" => $productDescription
            ],
            [
                "name_field" => "price",
                "marker_field" => ":productPrice",
                "value_field" => $productPrice
            ],
            [
                "name_field" => "current_stock",
                "marker_field" => ":productStock",
                "value_field" => $productStock
            ],
            [
                "name_field" => "category_id",
                "marker_field" => ":productCategoryId",
                "value_field" => $productCategoryId
            ],
            [
                "name_field" => "sku",
                "marker_field" => ":productSku",
                "value_field" => $productSku
            ],
            [
                "name_field" => "status",
                "marker_field" => ":productStatus",
                "value_field" => $productStatus
            ],
            [
                "name_field" => "image_url",
                "marker_field" => ":categoryImg",
                "value_field" => $imgName
            ],
            [
                "name_field" => "mw",
                "marker_field" => ":productMw",
                "value_field" => 0
            ],
            [
                "name_field" => "creation_date",
                "marker_field" => ":categoryCreated",
                "value_field" => date("Y-m-d H:i:s")
            ],
            [
                "name_field" => "modification_date",
                "marker_field" => ":categoryModified",
                "value_field" => date("Y-m-d H:i:s")
            ],

        ];

        # /Dataset products#

        # Dataset stock_movements #



        # /Dataset stock_movements #

        $public_product = $this->saveData("products", $productDataReg);
        if ($public_product['sql']->rowCount() == 1) {

            $lastId = $public_product['lastId'];

            $stockDataReg = [
                [
                    "name_field" => "product_id",
                    "marker_field" => ":stockProductId",
                    "value_field" => $lastId
                ],
                [
                    "name_field" => "change_type",
                    "marker_field" => ":stockchange",
                    "value_field" => "addition"
                ],
                [
                    "name_field" => "quantity",
                    "marker_field" => ":stockquantity",
                    "value_field" => $productStock
                ],
                [
                    "name_field" => "previous_stock",
                    "marker_field" => ":stockPrevious",
                    "value_field" => 0
                ],
                [
                    "name_field" => "new_stock",
                    "marker_field" => ":stockNew",
                    "value_field" => $productStock
                ],
                [
                    "name_field" => "created_at",
                    "marker_field" => ":stockCreated",
                    "value_field" => date("Y-m-d H:i:s")
                ],
                [
                    "name_field" => "user_id",
                    "marker_field" => ":stockUserId",
                    "value_field" => 0
                ],
                [
                    "name_field" => "notes",
                    "marker_field" => ":stockNotes",
                    "value_field" => "Registro de nuevo producto"
                ],
            ];

            $public_stock = $this->saveData("stock_movements", $stockDataReg);

            if ($public_stock->rowCount() == 1) {
                $alert = [
                    "tipo" => "recargar",
                    "titulo" => "Producto registrado",
                    "texto" => "El producto " . $productName . " se registro con exito id:" . $lastId,
                    "icono" => "success"
                ];
            } else {
                if (is_file($img_dir . $imgName)) {
                    chmod($img_dir . $imgName, 0777);
                    unlink($img_dir . $imgName);
                }
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No se pudo registrar el producto, por favor intente nuevamente",
                    "icono" => "error"
                ];
            }
        } else {
            if (is_file($img_dir . $imgName)) {
                chmod($img_dir . $imgName, 0777);
                unlink($img_dir . $imgName);
            }
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No se pudo registrar el producto, por favor intente nuevamente",
                "icono" => "error"
            ];
        }

        return json_encode($alert);
    }

    /*---------- /Public product ----------*/

    /*---------- update product ----------*/

    public function updateProductController()
    {
        # Storage Data

        $productId = $this->cleanString($_POST['productId']); // hidden
        $productName = $this->cleanString($_POST['productName']);
        $productSku = $this->cleanString($_POST['productSku']);
        $productDescription = $this->cleanString($_POST['productDescription']);
        $productPrice = $this->cleanString($_POST['productPrice']);
        $productStock = $this->cleanString($_POST['productStock']);
        $productCategoryId = $this->cleanString($_POST['productCategoryId']);
        $productStatus = $this->cleanString($_POST['productStatus']);
        $productStockOption = $this->cleanString($_POST['productStockOption']);
        $totalStock = 0;

        // Validate id

        $validateId = $this->selectData("unique", "products", "id", $productId);
        if ($validateId->rowCount() != 1) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El ID del producto es incorrecto.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        } else {
            $validateId = $validateId->fetch();
            $productOldImg = $validateId->image_url;
            $productOldStock = $validateId->current_stock;
            $productSku_original = $validateId->sku;
        }

        // /Validate id

        # Checking required fields #
        $fields = [$productId, $productSku_original, $productName, $productSku, $productDescription, $productPrice, $productCategoryId, $productStatus];

        foreach ($fields as $field) {
            if (!isset($field) || empty($field)) {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No has llenado todos los campos que son obligatorios.",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }
        }

        # /Checking required fields #

        # checking data integrity #

        if ($this->verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{3,150}", $productName)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El NOMBRE no coincide con el formato solicitado.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 .,]{3,500}", $productDescription)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La DESCRIPCIÓN no coincide con el formato solicitado.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("[A-Za-z0-9\-_\.]{3,50}", $productSku)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El SKU no coincide con el formato solicitado.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("$|^\d{1,5}", $productStock)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La cantidad de STOCK no coincide con el formato solicitado.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("[\d .,]{1,10}", $productPrice)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La cantidad en el PRECIO no coincide con el formato solicitado.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("[\d]{1,5}", $productCategoryId)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La opción seleccionada no es valida.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        if ($this->verifyData("[1-3]{1}", $productStatus)) {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "La opción seleccionada no es valida.",
                "icono" => "error"
            ];
            return json_encode($alert);
            exit();
        }

        # checking data integrity #

        # SKU Validation #

        if ($productSku != $productSku_original) {
            $checkSKu = $this->selectData("unique", "products", "sku", $productSku);
            if ($checkSKu->rowCount() >= 1) {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Ya hay un producto con un codigo SKU similar.",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }
        }

        # /SKU Validation #

        # Stock managment #

        if ($productStock != "") {

            if ($productStockOption == "addition") {

                $totalStock = $productStock + $productOldStock;
            } elseif ($productStockOption === "removal") {

                $totalStock = $productOldStock - $productStock;
            }
        } else {
            $totalStock = $productOldStock;
        }



        # /Stock managment #

        # Image management #

        # Directory of images #
        $img_dir = "../views/assets/img/ecommerce-images/products/thumbnail/";

        # Check if an image was selected #
        if ($_FILES['productImg']['name'] != "" && $_FILES['productImg']['size'] > 0) {

            # Create directory #
            if (!file_exists($img_dir)) {
                if (!mkdir($img_dir, 0777)) {
                    $alert = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error inesperado",
                        "texto" => "Error al crear el directorio",
                        "icono" => "error"
                    ];
                    return json_encode($alert);
                    exit();
                }
            }

            # Checking format of image #
            if ($_FILES['productImg']['type'] != "image/jpeg" && $_FILES['productImg']['type'] != "image/jpg" && $_FILES['productImg']['type'] != "image/png") {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "La imagen que ha seleccionado es de un formato no permitido",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            # Checking size of image #
            if (($_FILES['productImg']['size'] / 1024) > 5120) {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "La imagen que ha seleccionado supera el peso permitido",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }

            # Name of the image #

            $imgName = "product-" . $productId;


            # Extension of the image #
            switch ($_FILES['productImg']['type']) {
                case 'image/jpeg':
                    $imgName = $imgName . ".jpg";
                    break;
                case 'image/png':
                    $imgName = $imgName . ".png";
                    break;
                case 'image/jpg':
                    $imgName = $imgName . ".jpg";
                    break;
            }

            chmod($img_dir, 0777);

            # Deleting old image #
            if (is_file($img_dir . $productOldImg)) {
                chmod($img_dir . $productOldImg, 0777);
                unlink($img_dir . $productOldImg);
            }

            # Moving image to directory #
            if (!move_uploaded_file($_FILES['productImg']['tmp_name'], $img_dir . $imgName)) {
                $alert = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No podemos subir la imagen al sistema en este momento",
                    "icono" => "error"
                ];
                return json_encode($alert);
                exit();
            }
        } else {
            $imgName = $productOldImg;
        }

        # /Image management #

        # Dataset #

        $productDataReg = [
            [
                "name_field" => "name",
                "marker_field" => ":productName",
                "value_field" => $productName
            ],
            [
                "name_field" => "description",
                "marker_field" => ":productDesc",
                "value_field" => $productDescription
            ],
            [
                "name_field" => "price",
                "marker_field" => ":productPrice",
                "value_field" => $productPrice
            ],
            [
                "name_field" => "current_stock",
                "marker_field" => ":productStock",
                "value_field" => $totalStock
            ],
            [
                "name_field" => "category_id",
                "marker_field" => ":productCategoryId",
                "value_field" => $productCategoryId
            ],
            [
                "name_field" => "sku",
                "marker_field" => ":productSku",
                "value_field" => $productSku
            ],
            [
                "name_field" => "status",
                "marker_field" => ":productStatus",
                "value_field" => $productStatus
            ],
            [
                "name_field" => "image_url",
                "marker_field" => ":categoryImg",
                "value_field" => $imgName
            ],
            [
                "name_field" => "mw",
                "marker_field" => ":productMw",
                "value_field" => 0
            ],
            [
                "name_field" => "modification_date",
                "marker_field" => ":productModified",
                "value_field" => date("Y-m-d H:i:s")
            ],

        ];

        $condition = [

            "condition_field" => "id",
            "condition_marker" => ":productId",
            "condition_value" => $productId

        ];

        # /Dataset #

        $public_product = $this->updateData("products", $productDataReg, $condition);
        if ($public_product->rowCount() == 1) {

            if ($productStock != "") {
                $stock_data_reg = [
                    [
                        "name_field" => "product_id",
                        "marker_field" => ":productId",
                        "value_field" => $productId
                    ],
                    [
                        "name_field" => "change_type",
                        "marker_field" => ":change",
                        "value_field" => $productStockOption
                    ],
                    [
                        "name_field" => "quantity",
                        "marker_field" => ":quantity",
                        "value_field" => $productStock
                    ],
                    [
                        "name_field" => "previous_stock",
                        "marker_field" => ":previous_stock",
                        "value_field" => $productOldStock
                    ],
                    [
                        "name_field" => "new_stock",
                        "marker_field" => ":new_stock",
                        "value_field" => $totalStock
                    ],
                    [
                        "name_field" => "created_at",
                        "marker_field" => ":created_at",
                        "value_field" => date("Y-m-d H:i:s")
                    ],
                    [
                        "name_field" => "user_id",
                        "marker_field" => ":user_id",
                        "value_field" => 0
                    ],
                    [
                        "name_field" => "notes",
                        "marker_field" => ":notes",
                        "value_field" => "Actualizacion del producto"
                    ]
                ];

                $stock_movement = $this->saveData("stock_movements", $stock_data_reg);
                if ($stock_movement->rowCount() == 1) {
                    $alert = [
                        "tipo" => "recargar",
                        "titulo" => "Producto Actualizado",
                        "texto" => "El producto " . $productName . " se actualizó con exito.",
                        "icono" => "success"
                    ];
                }
            } else {
                $alert = [
                    "tipo" => "recargar",
                    "titulo" => "Producto Actualizado",
                    "texto" => "El producto " . $productName . " se actualizó con exito.",
                    "icono" => "success"
                ];
            }
        } else {
            if (is_file($img_dir . $imgName)) {
                chmod($img_dir . $imgName, 0777);
                unlink($img_dir . $imgName);
            }
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No se pudo actualizar el producto, por favor intente nuevamente.",
                "icono" => "error"
            ];
        }

        return json_encode($alert);
    }

    /*---------- /update product ----------*/

    /*---------- Disable product ----------*/

    public function turnProductStatusContorller()
    {
        $id = $_POST['id'];
        $turn_option = $_POST['turn_option'];
        

        $data = [
            [
                "name_field" => "status",
                "marker_field" => ":status",
                "value_field" => $turn_option
            ],

            [
                "name_field" => "modification_date",
                "marker_field" => ":modification_date",
                "value_field" => date("Y-m-d H:i:s")
            ]
        ];

        $condition = [
            "condition_field" => "id",
            "condition_marker" => ":productId",
            "condition_value" => $id
        ];

        $disableProduct = $this->updateData("products", $data, $condition);

        if ($disableProduct->rowCount() == 1) {
            $alert = [
                "tipo" => "recargar",
                "titulo" => "Status Actualizado",
                "texto" => "",
                "icono" => "success"
            ];
        } else {
            $alert = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Algo salio mal, intente nuevamente.",
                "icono" => "error"
            ];
        }

        return json_encode($alert);
    }

    /*---------- /Disable product ----------*/
}
