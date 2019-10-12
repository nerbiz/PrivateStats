<?php

namespace Nerbiz\PrivateStats;

class Server
{
    /**
     * (From PrestaShop)
     * Get the server variable REMOTE_ADDR, or the first ip of HTTP_X_FORWARDED_FOR (when using proxy).
     * @return string IP of client
     * @see https://github.com/PrestaShop/PrestaShop/blob/develop/classes/Tools.php#L377
     */
    public static function getRemoteAddress(): string
    {
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }

        if (array_key_exists('X-Forwarded-For', $headers)) {
            $_SERVER['HTTP_X_FORWARDED_FOR'] = $headers['X-Forwarded-For'];
        }

        if (
            isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            && $_SERVER['HTTP_X_FORWARDED_FOR']
            && (
                ! isset($_SERVER['REMOTE_ADDR'])
                || preg_match('/^127\..*/i', trim($_SERVER['REMOTE_ADDR']))
                || preg_match('/^172\.16.*/i', trim($_SERVER['REMOTE_ADDR']))
                || preg_match('/^192\.168\.*/i', trim($_SERVER['REMOTE_ADDR']))
                || preg_match('/^10\..*/i', trim($_SERVER['REMOTE_ADDR']))
            )
        ) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
                $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                return $ips[0];
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Get the current request URI
     * @return string|null
     */
    public static function getRequestUri(): ?string
    {
        return $_SERVER['REQUEST_URI'] ?? null;
    }

    /**
     * Get the referrer of the current request
     * @return string|null
     */
    public static function getReferrer(): ?string
    {
        return $_SERVER['HTTP_REFERER'] ?? null;
    }
}
