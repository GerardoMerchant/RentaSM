<?php
    namespace app\models;

    class viewsModel{

        protected function getViewsModel($view){

            $whiteList = ["dashboard", "productList", "productNew", "productCategoryList", "productUpdate", "productCategoryImageUpdate"];
            if(in_array($view, $whiteList)){
                if(is_file("./app/views/content/".$view."-view.php")){
                    $content = "./app/views/content/".$view."-view.php";
                }else{
                    $content = "404";
                }
            }elseif($view == "login" || $view == "index"){
                $content = "login";
            }else{
                $content = "404";
            }
            return $content;
        }
    }