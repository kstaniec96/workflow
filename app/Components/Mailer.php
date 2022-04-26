<?php
/**
 * This class of management of sending e-mails.
 * In addition, he also creates them.
 *
 * @package Simpler
 * @version 2.0
 */

namespace Simpler\Components;

use Simpler\Components\Http\Routers\View;
use Simpler\Components\Interfaces\MailerInterface;
use Exception;
use RuntimeException;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Swift_Plugins_LoggerPlugin;
use Swift_Plugins_Loggers_ArrayLogger;
use Swift_SmtpTransport;

class Mailer implements MailerInterface
{
    private object $mailer;

    /**
     * Init mailer
     */
    public function __construct()
    {
        $config = $this->config();

        $transport = (new Swift_SmtpTransport($config['host'], $config['port']))
            ->setUsername($config['username'])
            ->setPassword($config['password']);

        $transport->setStreamOptions(
            ['ssl' => ['allow_self_signed' => true, 'verify_peer' => false, 'verify_peer_name' => false]]
        );

        $this->mailer = new Swift_Mailer($transport);
    }

    /**
     * Send a message email.
     *
     * @param array $params
     * @return bool
     */
    public function send(array $params): bool
    {
        try {
            $subject = __($params['subject']);
            $to = explode(',', $params['to']);
            $template = $params['template'];
            $templateName = $template['name'] ?? 'default';

            if (array_key_exists('template', $params)) {
                $templateParams = $template['params'] ?? [];
                $templateParams['subject'] = $subject;

                $params['html'] = View::render($templateName, $templateParams);
            }

            $logger = new Swift_Plugins_Loggers_ArrayLogger();
            $this->mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

            $message = (new Swift_Message($subject))
                ->setFrom(env('MAILER_SENDER'), env('PROJECT_NAME', 'Simpler'))
                ->setTo($to[0]);

            if (count($to) > 1) {
                unset($to[0]);
                $message = $message->setCc($to);
            }

            $message = $message->setBody($params['html'], 'text/html');

            if (array_key_exists('attachment', $params)) {
                $attach = $params['attachment'];

                if (!empty($attach)) {
                    $message->attach(Swift_Attachment::fromPath($attach['path']));
                }
            }

            return compare($this->mailer->send($message), 1);
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Set email configurations.
     *
     * @return array
     */
    private function config(): array
    {
        return [
            'host' => env('MAILER_HOST'),
            'port' => env('MAILER_PORT'),
            'username' => env('MAILER_USER'),
            'password' => env('MAILER_PASS'),
        ];
    }
}
