<?php

namespace Core;

use Core\Contracts\Session as SessionContract;
use Exception;

class Session implements SessionContract
{
    protected const TEMP_DATA = 'temp_data';

    public function __construct()
    {
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
        if ($this->isDestroyed()) {
            return null;
        }
        return $_SESSION[self::TEMP_DATA][$key]['value'] ?? null;
    }


    public function removeTemp(string $key): void
    {
        unset($_SESSION[self::TEMP_DATA][$key]);
    }

    public function set(string $key, mixed $value, int $expireTime = null): void
    {
        $_SESSION[$key]['value']      = $value;
        $_SESSION[$key]['expired_at'] = $expireTime;
    }

    public function get(string $key): mixed
    {
        if ($this->isDestroyed()) {
            return null;
        }
        $session   = $_SESSION[$key] ?? null;
        $expiredAt = $session['expired_at'] ?? null;
        if (!$expiredAt || $expiredAt > time()) {
            return $session['value'] ?? null;
        }
        $this->remove($key);
        return null;
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        $this->removeExpiredSessions();
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
    public function csrfToken(): string|null
    {
        if ($this->isDestroyed()) {
            return null;
        }
        $config = app()->getConfig('csrf_token');
        $key    = $config['key'] ?? 'csrf_token';
        $ttl    = $config['ttl'] ?? 15 * 60;
        $token  = $this->get($key);
        if (!$token) {
            $token = bin2hex(random_bytes(32));
            $this->set('csrf_token', $token, time() + $ttl);
        }
        return $token;
    }

    protected function removeExpiredSessions(): void
    {
        $sessions = $_SESSION;
        foreach ($sessions as $key => $session) {
            if ($key === self::TEMP_DATA) {
                continue;
            }
            $expiredAt = $session['expired_at'] ?? null;
            if ($expiredAt && $expiredAt < time()) {
                $this->remove($key);
            }
        }
    }

    public function regenerate(): void
    {
        session_regenerate_id();
    }

    public function destroy(): void
    {
        if (!$this->isDestroyed()) {
            session_destroy();
        }
    }

    protected function isDestroyed(): bool
    {
        return session_status() === PHP_SESSION_NONE;
    }

    public function start(): void
    {
        session_start();
    }
}
