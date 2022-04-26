<?php
/**
 * Default Template for Webhook Slack.
 *
 * @package Simpler
 * @subpackage Templates
 * @version 2.0
 */

function slackDefaultTemplate(array $data = []): array
{
    $text = $attachments = '';

    if (!empty($data)) {
        // Only text - default style
        if (!array_key_exists('attachments', $data)) {
            foreach ($data['text'] as $content) {
                $text .= $content."\n";
            }
        } // Attachments style
        else {
            $data = $data['attachments'];

            $attachments = [
                'color' => $data['color'] ?? '#ed3833',
                'pretext' => $data['pretext'] ?? 'To report a mistake',
                'author_name' => $data['author_name'] ?? '',
                'title' => $data['title'] ?? '',
                'text' => $data['text'] ?? '',
            ];
        }
    }

    return [
        'text' => $text,
        'attachments' => [$attachments],
    ];
}
