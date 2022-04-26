<?php
/**
 * This class is used to check the type of variables.
 *
 * @package Simpler
 * @subpackage Utils
 * @version 2.0
 */

namespace Simpler\Utils;

use Simpler\Components\Http\Validator\Validator;
use Simpler\Utils\Interfaces\TypeUtilInterface;
use Exception;

class TypeUtil implements TypeUtilInterface
{
    /**
     * @param string $value
     * @return bool
     */
    public static function isFileName(string $value): bool
    {
        return pathinfo(basename($value))['extension'] ?? false;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isEmail(string $value): bool
    {
        return Validator::validation($value, 'email');
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isMd5(string $value): bool
    {
        return Validator::validation($value, '/^[a-f0-9]{32}$/');
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isIP(string $value): bool
    {
        return Validator::validation($value, '/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/');
    }

    /**
     * @param $value
     * @return bool
     */
    public static function isUrl($value): bool
    {
        return Validator::validation($value, 'url');
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isREGON(string $value): bool
    {
        $value = preg_replace("/[^0-9]/", "", $value);

        if (strlen($value) === 7) {
            $value = '00'.$value;
        }

        if (strlen($value) === 9) {
            $funProductArrays = static function ($a, $b) {
                return ($a * $b);
            };

            $value = str_split($value);

            $controlDigit = array_pop($value);
            $weights = [8, 9, 2, 3, 4, 5, 6, 7];

            $b = array_map($funProductArrays, $weights, $value);
            $sum = array_sum($b);

            $modulo = $sum % 11;
            $modulo = ($modulo === 10) ? 0 : $modulo;

            return $modulo === $controlDigit;
        }

        return false;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isAreaCode($value): bool
    {
        return Validator::validation($value, '/(\+([0-9]{2}))|([0-9]{3})/');
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isNIP(string $value): bool
    {
        $value = preg_replace("/[^0-9]/", "", $value);

        if (strlen($value) === 10) {
            $funProductArrays = static function ($a, $b) {
                return ($a * $b);
            };

            $nip = str_split($value);

            $controlDigit = array_pop($nip);
            $weights = [6, 5, 7, 2, 3, 4, 5, 6, 7];

            $b = array_map($funProductArrays, $weights, $nip);
            $sum = array_sum($b);
            $modulo = $sum % 11;

            return $modulo === $controlDigit;
        }

        return false;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isIBAN(string $value): bool
    {
        $Codes = array(
            "AL",
            "DZ",
            "AD",
            "AO",
            "AT",
            "AZ",
            "BH",
            "BY",
            "BE",
            "BJ",
            "BA",
            "BR",
            "VG",
            "BG",
            "BF",
            "BI",
            "CM",
            "CV",
            "FR",
            "CG",
            "CR",
            "HR",
            "CY",
            "CZ",
            "DK",
            "DO",
            "EG",
            "EE",
            "FO",
            "FI",
            "FR",
            "FR",
            "FR",
            "GA",
            "GE",
            "DE",
            "GI",
            "GR",
            "GL",
            "FR",
            "GT",
            "GG",
            "HU",
            "IS",
            "IR",
            "IQ",
            "IE",
            "IM",
            "IL",
            "IT",
            "CI",
            "JE",
            "JO",
            "KZ",
            "XK",
            "KW",
            "LV",
            "LB",
            "LI",
            "LT",
            "LU",
            "MK",
            "MG",
            "ML",
            "MT",
            "FR",
            "MR",
            "MU",
            "MD",
            "MC",
            "ME",
            "MZ",
            "NL",
            "FR",
            "NO",
            "PK",
            "PS",
            "PL",
            "PT",
            "QA",
            "FR",
            "RO",
            "LC",
            "FR",
            "SM",
            "ST",
            "SA",
            "SN",
            "RS",
            "SC",
            "SK",
            "SI",
            "ES",
            "SE",
            "CH",
            "TL",
            "TN",
            "TR",
            "UA",
            "AE",
            "GB",
            "FR",
        );

        $LengthAccount = array(
            28,
            24,
            24,
            25,
            20,
            28,
            22,
            28,
            16,
            28,
            20,
            29,
            24,
            22,
            27,
            16,
            27,
            25,
            27,
            27,
            21,
            21,
            28,
            24,
            18,
            28,
            27,
            20,
            18,
            18,
            27,
            27,
            27,
            27,
            22,
            22,
            23,
            27,
            18,
            27,
            28,
            22,
            28,
            26,
            26,
            23,
            22,
            22,
            23,
            27,
            28,
            22,
            30,
            20,
            20,
            30,
            21,
            28,
            21,
            20,
            20,
            19,
            27,
            28,
            31,
            27,
            27,
            30,
            24,
            27,
            22,
            25,
            18,
            27,
            15,
            24,
            29,
            28,
            25,
            29,
            27,
            24,
            32,
            27,
            27,
            25,
            24,
            28,
            22,
            31,
            24,
            19,
            24,
            24,
            21,
            23,
            24,
            26,
            29,
            23,
            22,
            27,
        );

        // Normalize input (remove spaces and make upcase)
        $iban = strtoupper(preg_replace("/[^0-9A-Za-z]/", "", $value));

        if (preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/', $iban)) {
            $country = substr($iban, 0, 2);
            $check = (int)substr($iban, 2, 2);
            $account = substr($iban, 4);

            $key = array_search($country, $Codes, false);

            // If we don't have the selected code
            if (!$key) {
                return false;
            }

            // If the length of the account number does not match the specified country code
            if (strlen($iban) !== $LengthAccount[$key]) {
                return false;
            }

            // To numeric representation
            $search = range('A', 'Z');
            foreach (range(10, 35) as $tmp) {
                $replace[] = strval($tmp);
            }

            $numstr = str_replace($search, $replace, $account.$country.'00');

            // Calculate checksum
            $checksum = (int)$numstr[0];

            for ($pos = 1, $posMax = strlen($numstr); $pos < $posMax; $pos++) {
                $checksum *= 10;
                $checksum += (int)$numstr[$pos];
                $checksum %= 97;
            }

            return ((98 - $checksum) === $check);
        }

        return false;
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isDateFormat(string $value): bool
    {
        return Validator::validation($value, 'date');
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isTimeFormat(string $value): bool
    {
        return Validator::validation($value, 'time');
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isDateTimeFormat(string $value): bool
    {
        return Validator::validation($value, 'dateTime');
    }

    /**
     * @param string $value
     * @return bool
     */
    public static function isRegex(string $value): bool
    {
        set_error_handler(static function () {}, E_WARNING);
        $isRegularExpression = preg_match($value, '') !== false;
        restore_error_handler();

        return $isRegularExpression;
    }

    /**
     * @param $string
     * @return bool
     */
    public static function isJson($string): bool
    {
        try {
            json_decode($string, true, 512, JSON_THROW_ON_ERROR);

            return json_last_error() === JSON_ERROR_NONE;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param string $file
     * @return bool
     */
    public static function isImage(string $file): bool
    {
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        return in_array($ext, ['jpg', 'png', 'jpeg', 'gif', 'svg']);
    }
}
