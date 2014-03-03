<?php

/**
 * This class is used to manage phone numbers.
 * 
 * Author: Tom Phillips
 * Date: 3/1/2014
 */
class PhoneNumber
{
    public $number;

    public function __construct($number)
    {
        $this->number = $number;
    }

    /**
     * Ways to verify this is a valid input number would go here.  This is
     * far from comprehensive.
     *
     * @param	string	$number		Expected to be a phone number
     */
    public static function verify($number='')
    {
	$number = preg_replace('/[^[0-9+]/', '', $number);

	if (empty($number)) return 'Phone number empty.';

        // If number starts with a +, it must be followed by a 1 and 10 digits.
        if (substr($number, 0, 1) == '+') {
	    if (!preg_match('/\+1[0-9]{10}/', $number)) return 'Invalid phone number.';

	// If number starts with a 1, should be followed by 11 digits.
        } else if (substr($number, 0, 1) == '1') {
	    if (!preg_match('/1[0-9]{10}/', $number)) return 'Phone number not valid.';

	// If number starts with any other number, should be 10 digits.
        } else {
	    if (!preg_match('/[0-9]{10}/', $number)) return 'Invalid phone.';
        }
    }
}
?>
