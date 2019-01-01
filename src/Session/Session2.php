<?php

/**
 * An extended module for Session class.
 *
 * This is the module containing Session2 class.
 *
 * @author  Anna
 */

namespace Anna\Session;

/**
 * An extension class for wrapping sessions.
 */
class Session2 extends \Anax\Session\Session implements \Anax\Session\SessionInterface
{
    /**
     * Append (string) values in session.        AA 181219
     *
     * @param string $key   in session variable.
     * @param string  $value to set in session.
     *
     * @return self
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function append($key, $value)
    {
        if (is_string($value)) {
            $_SESSION[$key] .= $value;
            return $_SESSION[$key];
        } else {
            return null;
        }
    }
}
