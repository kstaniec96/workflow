<?php
/**
 * This class only abstract helper for Validator class.
 *
 * @package Simpler
 * @subpackage Validator
 * @version 2.0
 */

namespace Simpler\Components\Http\Validator;

abstract class FormValidator extends Validator
{
    /**
     * @return array
     */
    abstract public function rules(): array;

    /**
     * @return array
     */
    abstract public function fields(): array;

    /**
     * @return array
     */
    abstract public function errors(): array;
}
