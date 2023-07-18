<?php

namespace App\Services;

use App\Contracts\IRequest;

class CsrfService
{

    private $formTokenLabel = 'csrf-token';

    private $sessionTokenLabel = 'CSRF_TOKEN_SESS_IDX';

    private $request;

    private $body = [];

    private $session = [];

    private $excludeUrl = [];

    private $hashAlgo = 'sha256';

    private $hmac = true;

    private $hmacData = '58f916389dd3a484eb2da9206b593da167a95270538c768658e26f76f77e8709';

    /**
     * Create security service instance
     *
     * @param array $excludeUrl
     * @param array $session
     * @throws \Error
     */
    public function __construct(IRequest $request, $excludeUrl = null, &$session = null)
    {
        $this->request = &$request;
        $this->body    = $request->getBody();

        if (!\is_null($excludeUrl)) {
            $this->excludeUrl = $excludeUrl;
        }

        if (!\is_null($session)) {
            $this->session = &$session;
        } elseif (!\is_null($_SESSION) && isset($_SESSION)) {
            $this->session = &$_SESSION;
        } else {
            throw new \Error('No session available for persistence');
        }
    }

    /**
     * Insert a CSRF token to a form
     *
     * @return string
     */
    public function insertHiddenToken(): string
    {
        $csrfToken = $this->getCSRFToken();

        return "<input type=\"hidden\"" . " name=\"" . $this->xssafe($this->formTokenLabel) . "\"" . " value=\"" . $this->xssafe($csrfToken) . "\"" . " />";
    }

    /**
     * xss mitigation functions
     *
     * @param string $data
     * @param string $encoding
     * @return string
     */
    public function xssafe(string $data, ?string $encoding = 'UTF-8'): string
    {
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
    }

    /**
     * Generate, store, and return the CSRF token
     *
     * @return string The token
     */
    public function getCSRFToken(): string
    {
        if (empty($this->session[$this->sessionTokenLabel])) {
            $this->session[$this->sessionTokenLabel] = bin2hex(openssl_random_pseudo_bytes(32));
        }

        return $this->hmac !== false
            ? $this->getHash($this->session[$this->sessionTokenLabel])
            : $this->session[$this->sessionTokenLabel];
    }

    /**
     * Returns hash with hmacData used
     *
     * @param string $token
     * @return string hashed data
     */
    private function getHash($token): string
    {
        return \hash_hmac($this->hashAlgo, $this->hmacData, $token);
    }

    /**
     * Returns the current request URL
     *
     * @return string
     */
    private function getCurrentRequestUrl(): string
    {
        $protocol = isset($this->request->https)
            ? "https"
            : "http";

        return $protocol . "://" . $this->request->httpHost . $this->request->requestUri;
    }

    /**
     * Returns whether the request is valid.
     *
     * @return boolean
     */
    public function isValidRequest(): bool
    {
        $isValid    = false;
        $currentUrl = $this->getCurrentRequestUrl();

        if (!in_array($currentUrl, $this->excludeUrl) && !empty($this->body)) {
            $isValid = $this->validateRequest();
        }

        return $isValid;
    }

    /**
     * Validate a request based on session
     *
     * @return bool
     */
    public function validateRequest(): bool
    {
        // CSRF Token not found
        if (!isset($this->session[$this->sessionTokenLabel])) {
            return false;
        }

        // Let's pull the POST data
        if (!empty($this->body[$this->formTokenLabel])) {
            $token = $this->body[$this->formTokenLabel];
        } else {
            return false;
        }

        if (!\is_string($token)) {
            return false;
        }

        // Grab the stored token
        if ($this->hmac !== false) {
            $expected = $this->getHash($this->session[$this->sessionTokenLabel]);
        } else {
            $expected = $this->session[$this->sessionTokenLabel];
        }

        return \hash_equals($token, $expected);
    }

    /**
     * Removes the token from the session
     *
     * @return void
     */
    public function unsetToken(): void
    {
        if (!empty($this->session[$this->sessionTokenLabel])) {
            unset($this->session[$this->sessionTokenLabel]);
        }
    }
}
