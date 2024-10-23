<?php
namespace Aligent\Chat\Logger;
use Psr\Log\LoggerInterface;

/**
 * Class responsible for logging live chat events and messages.
 */
class LiveChatLogger
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * Constructor method for initializing Logger.
     *
     * @param LoggerInterface $logger The logger instance to be used.
     * @return void
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Logs data encapsulated in an array message.
     *
     * @param array $message The message data to be logged.
     * @return void
     */
    public function logData(array $message): void
    {
        $this->logger->info('Live chat configurations update: '.json_encode($message));
    }
}
