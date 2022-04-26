<?php
/**
 *
 * View definition for User module.
 *
 * @package MVC
 * @subpackage User
 *
 */

$pathname = basename(load('request')->location()->pathname);

?>

<section class="section-main-content" id="section-main-content">
    <?php import(MVC_VIEWS_PATH.DS.'user/'.$pathname.'.php'); ?>
</section>
