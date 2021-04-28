<?php

declare(strict_types=1);

namespace App\Application\Command;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

interface CommandHandler extends MessageHandlerInterface
{
}
