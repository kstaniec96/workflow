<?php
/**
 * Settings for view by Twig.
 *
 * @package Simpler
 * @subpackage Config
 * @version 2.0
 *
 * @link https://twig.symfony.com/
 */

return [
    /**
     * A callable implementing the function.
     *
     * @array <name, callable>
     */
    'functions' => [
        ['env'],
        ['__'],
        ['version'],
        ['asset'],
        ['nonce'],
        ['route'],
        ['csrfToken'],
        ['auth'],
        ['guest'],
        ['date'],
        ['dd'],
        ['session'],
        ['die'],
        ['var_export'],
        ['var_dump'],
        ['locale'],
        ['hasRoute'],
        ['getCurrentRouteName'],
        ['count'],
        ['storage'],

        /**
         * Custom functions.
         */
        ['activeNavItem'],
    ],

    /**
     * A path or an array of paths where to look for templates.
     */
    'pathTemplates' => [
        LAYOUTS_PATH,
        PAGES_PATH,
        PARTIALS_PATH,
        TEMPLATES_PATH.DS.'mailer',
    ],

    /**
     * The root path common to all relative paths.
     */
    'rootPath' => RESOURCES_PATH,

    /**
     * Special options for Twig views.
     */
    'options' => [
        /*
         * When set to true, the generated templates have a __toString() method
         * that you can use to display the generated nodes (default to false).
         */
        'debug' => env('PROJECT_DEBUG', false),

        /*
         * When developing with Twig, it's useful to recompile the template whenever the source
         * code changes. If you don't provide a value for the auto_reload option, it will
         * be determined automatically based on the debug value.
         */
        'auto_reload' => true,

        /*
         * The charset used by the templates.
         */
        'charset' => 'UTF-8',

        /*
         * An absolute path where to store the compiled templates,
         * or false to disable caching (which is the default).
         */
        'cache' => storagePath('framework/cache/views'),

        /*
         * If set to false, Twig will silently ignore invalid variables
         * (variables and or attributes/methods that do not exist) and replace them with a null value.
         * When set to true, Twig throws an exception instead (default to false).
         */
        'strict_variables' => false,

        /*
         * Sets the default auto-escaping strategy (name, html, js, css, url, html_attr, or a PHP callback that
         * takes the template "filename" and returns the escaping strategy to use -- the callback cannot be a function
         * name to avoid collision with built-in escaping strategies); set it too false to disable auto-escaping.
         * The name escaping strategy determines the escaping strategy to use for a template based on the template
         * filename extension (this strategy does not incur any overhead at runtime as auto-escaping
         * is done at compilation time.)
         */
        'autoescape' => 'html',

        /*
         * A flag that indicates which optimizations to apply (default to -1 -- all optimizations
         * are enabled; set it to 0 to disable).
         */
        'optimizations' => '-1',
    ],
];
