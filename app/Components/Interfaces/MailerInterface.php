<?php

namespace Simpler\Components\Interfaces;

interface MailerInterface
{
    public function send(array $params): bool;
}
