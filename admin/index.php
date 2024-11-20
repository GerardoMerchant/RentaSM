<?php

require_once "./config/app.php";
require_once "./autoload.php";

if (isset($_GET['views'])) {
    $url = explode("/", $_GET['views']);
} else {
    $url = ["login"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php

    require_once "./app/views/inc/head.php";
    ?>
    <title><?php echo $url[0]; ?></title>

    

</head>

<body>

    <?php
    //echo "url= ".$_GET['views'];
    use app\controllers\viewsController;
    $viewsController = new viewsController();
    $view = $viewsController->getViewsController($url[0]);
    if ($view == "login" || $view == "404") {
        require_once "./app/views/content/" . $view . "-view.php";
    } else {
        // indlude Navbar. 
        require_once "./app/views/inc/navbar.php";

        require_once $view;

        require_once "./app/views/inc/footer.php";
    }

    require_once "./app/views/inc/script.php";
    ?>

</body>

</html>