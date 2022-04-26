<?php
/**
 * The proposed structure of the project.
 *
 * @package Simpler
 * @version 2.0
 */

/** @package BASIC PATHES * */
const DS = DIRECTORY_SEPARATOR;
const ROOT_PATH = __DIR__;

const APP_PATH = ROOT_PATH.DS.'app';
const PROJECT_PATH = ROOT_PATH.DS.'project';
const PUBLIC_PATH = ROOT_PATH.DS.'public';
const ASSETS_PATH = PUBLIC_PATH.DS.'assets';
const ROUTES_PATH = ROOT_PATH.DS.'routes';

const HTTP_PATH = PROJECT_PATH.DS.'Http';
const CONTROLLERS_PATH = HTTP_PATH.DS.'Controllers';
const MIDDLEWARES_PATH = HTTP_PATH.DS.'Middlewares';
const VALIDATORS_PATH = HTTP_PATH.DS.'Validators';

const MODELS_PATH = PROJECT_PATH.DS.'Models';
const COMMANDS_PATH = PROJECT_PATH.DS.'Console'.DS.'Commands';
const INTERFACES_PATH = PROJECT_PATH.DS.'Interfaces';
CONST SERVICES_PATH = PROJECT_PATH.DS.'Services';
CONST EXCEPTIONS_PATH = PROJECT_PATH.DS.'Exceptions';

const TEST_PATH = ROOT_PATH.DS.'tests';

/** @package RESOURCES PATHES */
const RESOURCES_PATH = ROOT_PATH.DS.'resources';
const VIEWS_PATH = RESOURCES_PATH.DS.'views';
const PAGES_PATH = VIEWS_PATH.DS.'pages';
const PARTIALS_PATH = VIEWS_PATH.DS.'partials';
const LAYOUTS_PATH = VIEWS_PATH.DS.'layouts';
const TEMPLATES_PATH = RESOURCES_PATH.DS.'templates';
const ERRORS_PATH = RESOURCES_PATH.DS.'errors';
const LANG_PATH = RESOURCES_PATH.DS.'lang';

/** @package STORAGE PATHES */
const STORAGE_PATH = ROOT_PATH.DS.'storage';
const STUBS_PATH = STORAGE_PATH.DS.'framework'.DS.'stubs';

// Bootloader Framework
require APP_PATH.DS.'src'.DS.'bootloader.php';
