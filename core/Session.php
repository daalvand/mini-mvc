<?php

namespace Core;

use Core\Contracts\Session as SessionContract;
use Exception;

class Session implements SessionContract
{
    protected const TEMP_DATA = 'temp_data';

    public function __construct()
    {
        session_start();
        $this->setRemoveTrueForTempData();
    }

    public function setTemp(string $key, mixed $value): void
    {
        $_SESSION[self::TEMP_DATA][$key] = [
             'remove' => false,
             'value'  => $value,
        ];
    }

    public function getTemp(string $key): mixed
    {
        return $_SESSION[self::TEMP_DATA][$key]['value'] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        $this->removeTempData();
    }

    protected function removeTempData(): void
    {
        $tempData = $_SESSION[self::TEMP_DATA] ?? [];
        foreach ($tempData as $key => $tempDatum) {
            if ($tempDatum['remove']) {
                unset($tempData[$key]);
            }
        }
        $_SESSION[self::TEMP_DATA] = $tempData;
    }

    protected function setRemoveTrueForTempData(): void
    {
        $tempData = $_SESSION[self::TEMP_DATA] ?? [];
        foreach ($tempData as $key => $tempDatum) {
            $tempData[$key]['remove'] = true;
        }
        $_SESSION[self::TEMP_DATA] = $tempData;
    }

    /**
     * @throws Exception
     */
    public function csrfToken(): string
    {
        $token = $this->getTemp('csrf_token');
        if (!$token) {
            $token = bin2hex(random_bytes(32));
            $this->setTemp('csrf_token', $token);
        }
        return $token;
    }
}
