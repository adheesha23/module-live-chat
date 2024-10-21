<?php
namespace Aligent\Chat\Logger;

use Exception;
use Monolog\Handler\HandlerException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Logger as MonologLogger;
use RuntimeException;

class LiveChatLogger extends MonologLogger
{

    /**
     * Path to the log file for LiveChat.
     *
     * This constant defines the file system path where LiveChat logs are stored.
     */
    const string LIVECHAT_LOG_PATH = '/var/log/livechat.log';
    private MonologLogger $logger;

    /**
     * Class constructor.
     *
     * Initializes a logger for the 'livechat' channel and sets up a file handler.
     * Throws a RuntimeException if the base path constant "BP" is not defined.
     * Logs errors if logger initialization fails.
     *
     * @return void
     */
    public function __construct()
    {
        try {
            // Initialize logger with the channel name 'livechat'
            $this->logger = new Logger('livechat');

            // Check if BP is defined
            if (!defined('BP')) {
                throw new RuntimeException('Base path constant "BP" is not defined.');
            }

            // Push handler for logging into the specific file
            $this->logger->pushHandler(new StreamHandler(BP . self::LIVECHAT_LOG_PATH, Logger::INFO));
        } catch (HandlerException $e) {
            error_log("Failed to initialize logger: " . $e->getMessage());
        } catch (Exception $e) {
            error_log("Unexpected error during logger initialization: " . $e->getMessage());
        }
    }

    /**
     * Logs the LiveChat configuration data.
     *
     * @param array $dataToLog
     * @return void
     */
    public function logData(array $dataToLog): void
    {
        $this->logger->info('LiveChat configuration data saved.', $dataToLog);
    }
}
