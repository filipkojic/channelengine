<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces;

interface LoginServiceInterface
{
    /**
     * Validacija kredencijala sa prosleđenim API ključem.
     *
     * @param string $apiKey
     * @param string $accountName
     * @return bool
     * @throws \Exception
     */
    public function handleLogin(string $apiKey, string $accountName): bool;

    public function isUserLoggedIn(): bool;
}

