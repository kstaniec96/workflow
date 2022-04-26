<?php
/**
 * This class is connects to Webhook Discord.
 *
 * @package Simpler
 * @subpackage APi
 * @version 2.0
 */

namespace Simpler\Components\Api;

use Simpler\Components\Api\Interfaces\DiscordInterface;
use JsonException;
use RuntimeException;

class Discord implements DiscordInterface
{
    /**
     * This method loaded message for Webhook Discords.
     *
     * @param string $message
     * @param $trace
     * @return bool|mixed|string
     */
    public static function send(string $message, $trace = null)
    {
        try {
            $json_data = json_encode([
                'tts' => false,
                'username' => 'Bot',

                'embeds' => [
                    [
                        'title' => '['.now().'] local.ERROR',
                        'description' => $message,
                        'color' => hexdec('fc2b2b'),
                    ],

                    [
                        'title' => 'Context',
                        'description' => $trace ?? 'Server error!',
                        'color' => hexdec('fc2b2b'),
                    ],
                ],
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            $ch = curl_init(env('DISCORD_WEBHOOK_URL'));

            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        } catch (JsonException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
