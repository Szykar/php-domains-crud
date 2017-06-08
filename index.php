<?php
/**
 * @author: Szymon Kargol
 */
require 'config/config.php';
require 'core.php';
require 'db.class.php';

ob_start();
if(isset($_GET['action'])) {
    $action = $_GET['action'];
    try {
        call_user_func("action_".$action);
    } catch (Exception $e) {
        displayError($e->getMessage());
    }
}

//redirect here

$db = new db($db);
$db->saveLog();

include 'view/login.phtml';

if(admin()) {
    include 'view/nav.phtml';
    switch(@$_GET['page']) {
        case 'log':
            $page = 1;
            if(isset($_GET['p']) && is_numeric($_GET['p']))
                $page = $_GET['p'];
            include 'view/logTable.phtml';
            break;
        case 'domains':
            include 'view/domains.phtml';
            break;
        case 'domain':
            if(isset($_GET['id']) && is_numeric($_GET['id']))
                $id = $_GET['id'];
            include 'view/domain.phtml';
            break;
        case 'domainSave':
            $db->updateDomain();
                header("Location: ".HOME."?page=domains");
            break;
        case 'domainInsert':
            if($db->insertDomain()) {
                header("Location: ".HOME."?page=domains");
            } else {
                throw new Exception("Nie udało się zapisać zmian");
            }
            break;
        case 'domainDelete':
            if($db->deleteDomain()) {
                header("Location: ".HOME."?page=domains");
            } else {
                throw new Exception("Nie udało się zapisać zmian");
            }
            break;
    }
}

$output = ob_get_contents();
ob_end_clean();
include 'view/template.phtml';