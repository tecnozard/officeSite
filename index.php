<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$allowed_pages = ['home', 'about', 'features', 'services', 'contact','privacy','terms'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home'; 
}



include 'header.php';

include "pages/$page.php";

include 'footer.php';

?>
