<?php
/**
 * This class is used to validate the data from the request.
 *
 * @package Simpler
 * @subpackage Validator
 * @version 2.0
 */

namespace Simpler\Components\Http\Validator;

use Simpler\Components\Auth\Auth;
use Simpler\Components\Config;
use Simpler\Components\Enums\HttpStatus;
use Simpler\Components\Exceptions\ServerErrorException;
use Simpler\Components\Exceptions\UnprocessableException;
use Simpler\Utils\TypeUtil;
use RuntimeException;

class Validator implements ValidatorInterface
{
    /** @var bool */
    protected bool $auth = false;

    /** @var array */
    protected array $rules = [];

    /** @var array */
    protected array $fields = [];

    /** @var array */
    protected array $errors = [];

    /** @var array */
    private static array $config = [];

    /** @var array */
    private static array $mainRules = [
        'nullable',
        'required',
        'numeric',
        'int',
        'regex',
        'min',
        'max',
        'array',
        'string',
    ];

    /** @var null|string */
    private ?string $error = null;

    /** @var string */
    private static string $fieldName = '';

    /**
     * Valid request fields.
     *
     * @param null|array $rules
     * @return array
     */
    public function validated(?array $rules = null): array
    {
        if (is_null($rules)) {
            $this->checkAuth();
            $this->serviceDependency();
        }

        $validated = [];

        self::$config = Config::get('validator');
        self::$mainRules = array_merge(self::$mainRules, array_keys(self::$config['rules']));

        foreach ($rules ?? $this->rules as $field => $rule) {
            $this->validRule($rule);
            $value = request()->get($field);

            $this->validValue($field, $value, $rule);
            $validated[$field] = $value;
        }

        return $validated;
    }

    /**
     * Data validation patterns from the form.
     *
     * @param mixed $value
     * @param null|string $pattern
     * @return bool
     */
    public static function validation($value, ?string $pattern): bool
    {
        if (compare(strtolower($pattern), 'email')) {
            return filter_var($value, FILTER_VALIDATE_EMAIL);
        }

        $rules = Config::get('validator.rules');
        $pattern = TypeUtil::isRegex($pattern) ? $pattern : $rules[$pattern];

        if (empty($value) || empty($pattern)) {
            return false;
        }

        return preg_match($pattern, $value);
    }

    /**
     * Validation value from request.
     *
     * @param string $field
     * @param mixed $value
     * @param string $rule
     * @return void
     */
    private function validValue(string $field, $value, string $rule): void
    {
        foreach ($this->getRules($rule) as $getRule) {
            $ruleName = $this->splitRule($getRule, 'name');

            self::$fieldName = $this->getField($ruleName, $field);
            $this->error = $this->errors[$field][$ruleName] ?? null;

            switch ($ruleName) {
                case 'nullable':
                    break;

                case 'required':
                    if (empty($value)) {
                        $this->validException('required');
                    }
                    break;

                case 'string':
                    if (!is_string($value)) {
                        $this->validException('string');
                    }
                    break;

                case 'int':
                    if (!is_int($value)) {
                        $this->validException('int');
                    }
                    break;

                case 'numeric':
                    if (!is_numeric($value)) {
                        $this->validException('numeric');
                    }
                    break;

                case 'array':
                    if (!is_array($value)) {
                        $this->validException('array');
                    }
                    break;

                case 'min':
                    $min = $this->splitRule($getRule);

                    if ((is_numeric($value) ? $value : strlen($value)) < $min) {
                        $this->validException('min', [
                            'min' => $min,
                        ]);
                    }

                    break;

                case 'max':
                    $max = $this->splitRule($getRule);

                    if ((is_numeric($value) ? $value : strlen($value)) > $max) {
                        $this->validException('max', [
                            'max' => $max,
                        ]);
                    }
                    break;

                case 'regex':
                    if (!self::validation($value, $this->splitRule($getRule))) {
                        $this->validException('field');
                    }
                    break;

                default:
                    if (!self::validation($value, $getRule)) {
                        $message = $this->error ?? __('validator.rules.'.$rule, [], true);

                        if (empty($message)) {
                            $message = __('validator.rules.field', ['field' => self::$fieldName]);
                        }

                        throw new UnprocessableException($message);
                    }
            }
        }
    }

    /**
     * Valid rule name.
     *
     * @param string $rule
     * @return void
     */
    private function validRule(string $rule): void
    {
        foreach ($this->getRules($rule) as $getRule) {
            if (!in_array($this->splitRule($getRule, 'name'), self::$mainRules, true)) {
                throw new ServerErrorException(
                    __('validator.errors.ruleDoesNotExist', [
                        'rule' => $getRule,
                    ])
                );
            }
        }
    }

    /**
     * @param string $rule
     * @return array
     */
    private function getRules(string $rule): array
    {
        if (empty($rule)) {
            throw new ServerErrorException(__('validator.errors.ruleCannotByEmpty'));
        }

        if (strpos($rule, '|') !== false) {
            return explode('|', $rule);
        }

        return [$rule];
    }

    /**
     * @param string $rule
     * @param string $key
     * @return string|array
     */
    private function splitRule(string $rule, string $key = 'value')
    {
        if (strpos($rule, ':') !== false) {
            $explode = explode(':', $rule);

            return [
                'name' => $explode[0],
                'value' => $explode[1] ?? '',
            ][$key];
        }

        return $rule;
    }

    /**
     * Throw valid exception.
     *
     * @param string $index
     * @param array $bind
     * @return void
     */
    private function validException(string $index, array $bind = []): void
    {
        throw new UnprocessableException(
            $this->error ?? __(
                'validator.rules.'.$index,
                array_merge(
                    ['field' => self::$fieldName],
                    $bind
                )
            )
        );
    }

    /**
     * @param string $rule
     * @param string $field
     * @return string
     */
    private function getField(string $rule, string $field): string
    {
        return $this->fields[$rule] ?? $field;
    }

    /**
     * Check user is auth.
     *
     * @return void
     */
    private function checkAuth(): void
    {
        if ($this->auth && Auth::guest()) {
            throw new RuntimeException(__('framework.errors.unauthorized'), HttpStatus::UNAUTHORIZED);
        }
    }

    /**
     * Service class instance and set dependency data.
     *
     * @return void
     */
    private function serviceDependency(): void
    {
        $instance = container(static::class);

        $this->errors = $instance->call('errors');
        $this->rules = $instance->call('rules');
        $this->fields = $instance->call('fields');
    }
}
