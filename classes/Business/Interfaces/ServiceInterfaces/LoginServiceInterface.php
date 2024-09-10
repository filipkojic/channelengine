<?php

namespace ChannelEngine\PrestaShop\Classes\Business\Interfaces\ServiceInterfaces;

/**
 * Interface for the Login Service, defining methods for handling user login and credential validation.
 */
interface LoginServiceInterface
{
    /**
     * Validates the provided API key and account name with ChannelEngine.
     *
     * This method checks whether the provided API key and account name are valid for the ChannelEngine service.
     *
     * @param string $apiKey The API key to be validated.
     * @param string $accountName The account name to be validated.
     * @return bool True if the credentials are valid, false otherwise.
     * @throws \Exception Throws an exception if validation fails or the API is unreachable.
     */
    public function handleLogin(string $apiKey, string $accountName): bool;

    /**
     * Checks if the user is currently logged in.
     *
     * This method verifies if the user has successfully logged in, based on stored session or configuration data.
     *
     * @return bool True if the user is logged in, false otherwise.
     */
    public function isUserLoggedIn(): bool;
}
