<?php
	
	require_once "../../config/app.php";
	//require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\productController;

	if(isset($_POST['product_module'])){

		$productInst = new productController();

		if($_POST['product_module']=="publicProduct"){
			echo $productInst->publicProductController();
		}
        
		if($_POST['product_module']=="delate"){
			//echo $productInst->eliminarProductoControlador();
		}

		if($_POST['product_module']=="updateProduct"){
			echo $productInst->updateProductController();
		}

		if($_POST['product_module']=="delateImage"){
			//echo $productInst->eliminarImagenProductoControlador();
		}

		if($_POST['product_module']=="updateImage"){
			//echo $productInst->actualizarImagenProductoControlador();
		}

		/*-------------------------- Category Section ---------------------------*/

        if($_POST['product_module']=="publicCategory"){
			echo $productInst->publicCategorycontroller();
		}

		if($_POST['product_module']=="updateCategory"){
			echo $productInst->updateCategorycontroller();
		}

		if($_POST['product_module']=="updateCategoryImage"){
			echo $productInst->updateCategoryImageController();
		}

		if($_POST['product_module']=="delateCategory"){
			echo $productInst->deleteCategoryController();
		}

		/*-------------------------- /Category Section ---------------------------*/

	}else{
        		//session_destroy();
		header("Location: ".APP_URL."login/");
    }