<?php
/**
 * Class and function to validate the phone number
 *
 * @__construct() & __destruct()
 * @validateID
 * @returns = $checked_id
 * Bool Values (true or false)
 *
 */
class SoapValidator
{
    public function __construct() { }

    public function __destruct() { }

    public function validateId($id): bool
    {
        $checked_id = false;

        if (!empty($id) && $id === '21-3110-AC')
        {
            $checked_id = true;
        }

        return $checked_id;
    }


}
