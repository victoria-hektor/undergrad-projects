<?php
/**
 * class and public function to hash password and authenticate login
 *
 * @hashPassword - returns $hashed_password
 *
 * @verifyPassword - returns $authenticated
 *
 * @returns True or false values
 */

class BcryptWrapper
{
    public function hashPassword($dirty_password): ?string
    {
        $hashed_password = null;

        if (!empty($dirty_password))
        {
            $options = array('cost' => BCRYPT_COST);
            $hashed_password = password_hash($dirty_password, PASSWORD_BCRYPT, $options);

        }

        return $hashed_password;
    }

    /**
     * public function verifies the password
     *
     * @verifyPassword = $password_plaintext, $hashed_password;
     *
     * @returns = $authenticated; (True or False)
     */
    public function verifyPassword($password_plaintext, $hashed_password)
    {
        $authenticated = false;

        # hashed_password is an array.
        # Need to access element as password_verify needs string.
        if (!is_null($hashed_password))
        {
            $hashed_password = $hashed_password[0]['password_hash'];
        }

        if (!empty($password_plaintext) && !empty($hashed_password))
        {
            if (password_verify($password_plaintext, $hashed_password) === true)
            {
                $authenticated = true;
            }
        }

        return $authenticated;
    }
}
