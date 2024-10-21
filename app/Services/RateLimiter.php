<?php

namespace App\Services;

/**
 * Class RateLimiter
 *
 * @package RateLimiter\Limiter
 */
class RateLimiter
{
    /**
     * Limit to this many requests
     *
     * @var int
     */
    private int $limit = 0;

    /**
     * Limit for this duration in seconds
     *
     * @var int
     */
    private int $duration = 0;

    /**
     * RateLimiter constructor.
     *
     * @param int $limit
     * @param int $duration
     */
    public function __construct(int $limit = 60, int $duration = 60)
    {
        $this->limit = $limit;
        $this->duration = $duration;
    }

    /**
     * Check the request limit for provided identifier
     */
    public function check($identifier): bool
    {
        $key = "rate_limiter_{$identifier}";

        $requestData = $_SESSION[$key] ?? null;

        $currentTime = microtime(true);

        // Store initial request in session
        if (!$requestData) {
            // Initialize if there's no previous data
            $_SESSION[$key] = [
                'count' => 1,
                'start_time' => $currentTime,
                'last_request' => $currentTime,
            ];

            return true;
        }

        $requestCount = $requestData['count'];
        $startTime    = $requestData['start_time'];

        // Check if the time window since initial request has passed
        if ($currentTime - $startTime > $this->duration) {

            // Reset the counter if time window since last request has passed
            if ($currentTime - $_SESSION[$key]['last_request'] > $this->duration) {
                $_SESSION[$key] = [
                    'count' => 1,
                    'start_time' => $currentTime,
                    'last_request' => $currentTime,
                ];

                return true;
            }
            // Set last request as start time if time window since last request not passed
            else {
                $_SESSION[$key] = [
                    'count' => 2,
                    'start_time' => $_SESSION[$key]['last_request'],
                    'last_request' => $currentTime
                ];

                return $_SESSION[$key]['count'] < $this->limit;
            }

            return true;
        }

        // Increment requests count
        if ($requestCount < $this->limit) {
            // Update the request count
            $_SESSION[$key]['count']++;
            $_SESSION[$key]['last_request'] = $currentTime;

            return true;
        }

        return false;
    }
}
