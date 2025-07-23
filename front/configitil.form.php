<?php

include ('../../../inc/includes.php');

Session::checkRight("profile", READ);

$config = new PluginRolecategoriesitilConfigitil();
if (isset($_POST["update"])) {
   $config->updated($_POST);
    Html::back();
}
