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
        'required' => 'Field :field is required',
        'string' => 'Field :field must be a string',
        'int' => 'Field :field must be an integer',
        'numeric' => 'Field :field must be a number',
        'url' => 'Field :field must be a URL',
        'min' => 'Field :field must be greater than :min',
        'max' => 'Field :field must be less than :max',
        'regex' => 'Field :field contains an invalid value',
        'field' => 'Field :field is invalid',
        'nip' => 'Field :field must be NIP',
        'time' => 'This :field must be time',
        'date' => 'This :field must be date',
        'dateTime' => 'This :field must be date time',
        'array' => 'This :field must be array',

        /*
         * Custom rule valid messages.
         */
        //
    ],

    /*
     * Global Validator error messages.
     */
    'errors' => [
        'ruleCannotByEmpty' => 'Rule cannot be empty',
        'ruleDoesNotExist' => 'Rule :rule does not exist',
    ],
];
