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

namespace lunarelly\telegram\task;

use lunarelly\telegram\exception\TelegramException;
use pocketmine\scheduler\AsyncTask;

final class TelegramAsyncTask extends AsyncTask
{
    /**
     * @internal
     */
    public function __construct(private string $link)
    {
    }

    private function getLink(): string
    {
        return $this->link;
    }

    public function onRun(): void
    {
        $curl = curl_init($this->getLink());

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = json_decode(curl_exec($curl), true);
        $error = curl_error($curl);

        if ($error !== "") {
            throw new TelegramException($error);
        }

        if (!($response)) {
            throw new TelegramException("Bad Request: response is null");
        }

        if (!(array_key_exists("ok", $response))) {
            throw new TelegramException("Bad Request: invalid link or server is down");
        }

        if ($response["ok"] === false) {
            throw new TelegramException($response["description"]);
        }
    }
}