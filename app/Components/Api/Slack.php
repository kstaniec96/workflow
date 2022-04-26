<?php
/**
 * This class is connects to Webhook Slack.
 *
 * @package Simpler
 * @subpackage APi
 * @version 2.0
 */

namespace Simpler\Components\Api;

use Simpler\Components\Api\Interfaces\SlackInterface;
use JsonException;
use RuntimeException;

class Slack implements SlackInterface
{
    /**
     * This method loaded message for Webhook Slack.
     *
     * @param array $params
     * @param string|null $template
     * @return void
     */
    public static function send(array $params, ?string $template = null): void
    {
        $c = curl_init(env('SLACK_WEBHOOK_URL'));

        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, self::_createMessage($params, $template));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($c);
        curl_close($c);
    }

    /**
     * This method send Webhook Slack.
     *
     * @param array $params
     * @param string|null $template
     * @return array
     */
    private static function _createMessage(array $params, ?string $template = null): array
    {
        $template = $template ?? ((string)env('SLACK_WEBHOOK_TEMPLATE'));
        $template = ucfirst($template);

        self::getTemplate($template);
        $func = 'slack'.(ucfirst(basename($template))).'Template';

        try {
            return ['payload' => json_encode($func($params), JSON_THROW_ON_ERROR)];
        } catch (JsonException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Get template html from file.
     *
     * @param string $template
     * @return void
     */
    private static function getTemplate(string $template): void
    {
        import(TEMPLATES_PATH.DS.'slack'.DS.$template.'.php');
    }
}
