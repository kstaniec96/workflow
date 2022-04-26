<?php
/**
 *
 * Verify account for Auth module.
 *
 * @package MVC
 * @subpackage Auth
 *
 */

$controller = load('controller');
$auth = $controller->loadModel('auth/auth');

$auth->verify();
$catalog = $this->load('request')->query()->get('action')->value;

$controller->loadTemplate('auth/'.$catalog.'/second');