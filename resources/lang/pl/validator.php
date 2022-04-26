<?php
/**
 * Settings for validator.
 *
 * @package Simpler
 * @subpackage Config
 * @version 2.0
 */

return [
    /*
    * Rule valid messages.
    */
    'rules' => [
        'required' => 'Pole :field jest wymagane',
        'string' => 'Pole :field musi być tekstem',
        'int' => 'Pole :field musi być liczbą całkowitą',
        'numeric' => 'Pole :field musi być liczbą',
        'url' => 'Pole :field musi być adresem URL',
        'min' => 'Pole :field musi być większe od :min',
        'max' => 'Pole :field musi być mniejsze od :max',
        'regex' => 'Pole :field zawiera nieprawidłową wartość',
        'field' => 'Pole :field jest nieprawidłowe',
        'nip' => 'Pole :field musi być NIP-em',
        'time' => 'Pole :field musi być czasem',
        'date' => 'Pole :field musi być datą',
        'dateTime' => 'Pole :field musi być datą i czasem',
        'array' => 'Pole :field musi być tablicą',

        /*
         * Custom rule valid messages.
         */
        //
    ],

    /*
     * Global Validator error messages.
     */
    'errors' => [
        'ruleCannotByEmpty' => 'Reguła nie może być pusta',
        'ruleDoesNotExist' => 'Reguła :rule nie istnieje',
    ],
];
