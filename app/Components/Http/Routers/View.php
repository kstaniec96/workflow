<?php
/**
 * This class of management View.
 *
 * @package Simpler
 * @subpackage Routers
 * @version 2.0
 */

namespace Simpler\Components\Http\Routers;

use Simpler\Components\Config;
use Simpler\Components\Exceptions\ThrowException;
use Simpler\Components\Http\Routers\Interfaces\ViewInterface;
use Throwable;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class View implements ViewInterface
{
    /** @var Environment */
    private static object $twig;

    /** @var string */
    private static string $view;

    /** @var array */
    private static array $share = [];

    /**
     * Init view.
     *
     * @return void
     */
    public static function init(): void
    {
        try {
            $config = Config::get('view');
            $loader = new FilesystemLoader($config['pathTemplates'], $config['rootPath']);

            self::$twig = new Environment($loader, $config['options']);
            self::$share = [];

            if ($config['options']['debug']) {
                self::$twig->addExtension(new DebugExtension());
            }

            $functions = $config['functions'] ?? null;

            if (!empty($functions)) {
                foreach ($functions as $function) {
                    $name = $function[0];
                    $callable = $function[1] ?? $name;

                    self::$twig->addFunction(new TwigFunction($name, $callable));
                }
            }
        } catch (Throwable $e) {
            throw new ThrowException($e);
        }
    }

    /**
     * Render view template.
     *
     * @param string $view
     * @param array $params
     * @return string
     */
    public static function render(string $view, array $params = []): string
    {
        self::$view = $view.'.html.twig';

        return self::$twig->render(self::$view, self::mergeParams($params));
    }

    /**
     * Render block template.
     *
     * @param string $view
     * @param string $blockName
     * @param array $params
     * @return void
     */
    public static function renderBlock(string $view, string $blockName, array $params = []): string
    {
        return self::$twig->load($view.'.html.twig')->renderBlock($blockName, self::mergeParams($params));
    }

    /**
     * Share view params.
     *
     * @param string $key
     * @param mixed $value
     * @return View
     */
    public static function share(string $key, $value): View
    {
        self::$share[$key] = $value;

        return new self();
    }

    /**
     * Merge view params.
     *
     * @param array $params
     * @return array
     */
    private static function mergeParams(array $params): array
    {
        return array_merge(self::$share, $params);
    }
}
