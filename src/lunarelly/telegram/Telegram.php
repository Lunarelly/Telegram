<?php

/**
 *  _                               _ _
 * | |   _   _ _ __   __ _ _ __ ___| | |_   _
 * | |  | | | |  _ \ / _  |  __/ _ \ | | | | |
 * | |__| |_| | | | | (_| | | |  __/ | | |_| |
 * |_____\____|_| |_|\____|_|  \___|_|_|\___ |
 *                                      |___/
 *
 * @author Lunarelly
 * @link https://github.com/Lunarelly
 *
 */

declare(strict_types=1);

namespace lunarelly\telegram;

use lunarelly\telegram\task\TelegramAsyncTask;
use pocketmine\Server;

final class Telegram
{
    /**
     * Initializes new Telegram instance.
     *
     * @param string $token
     * @param string $chatId
     */
    public function __construct(private string $token, private string $chatId)
    {
    }

    /**
     * Returns your token.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Returns your chat ID.
     *
     * @return string
     */
    public function getChatId(): string
    {
        return $this->chatId;
    }

    /**
     * Sends a message to your chat.
     *
     * @param string $message
     * @return void
     */
    public function sendMessage(string $message): void
    {
        $message = str_replace([" ", "\n"], ["%20", "%0A"], $message);
        $link = sprintf("https://api.telegram.org/bot%s/sendMessage?chat_id=%s&text=%s", $this->getToken(), $this->getChatId(), $message);

        Server::getInstance()->getAsyncPool()->submitTask(new TelegramAsyncTask($link));
    }

    /**
     * Compares two Telegram instances.
     *
     * @param Telegram $telegram
     * @return bool
     */
    public function equals(self $telegram): bool
    {
        return $this->getToken() === $telegram->getToken() && $this->getChatId() === $telegram->getChatId();
    }
}