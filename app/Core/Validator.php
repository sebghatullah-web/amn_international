<?php

namespace App\Core;

/**
 * Server side input validation.
 *
 * $v = new Validator($_POST, [
 *     'name'  => 'required|min:2|max:100',
 *     'email' => 'required|email',
 *     'phone' => 'nullable|phone',
 * ]);
 * if ($v->fails()) { ... $v->errors() ... }
 */
class Validator
{
    private array $data;
    private array $rules;
    private array $messages = [];
    private array $validated = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->run();
    }

    private function run(): void
    {
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $this->data[$field] ?? null;
            if (is_string($value)) {
                $value = trim($value);
            }
            $nullable = in_array('nullable', $rules, true);
            $empty = ($value === null || $value === '');

            if ($empty && $nullable) {
                $this->validated[$field] = $value === null ? null : '';
                continue;
            }

            foreach ($rules as $rule) {
                $param = null;
                if (strpos($rule, ':') !== false) {
                    [$rule, $param] = explode(':', $rule, 2);
                }
                $ok = $this->apply($rule, $field, $value, $param);
                if (!$ok) {
                    break; // first failing rule wins
                }
            }
            $this->validated[$field] = $value;
        }
    }

    private function apply(string $rule, string $field, $value, ?string $param): bool
    {
        $fail = function (string $message) use ($field) {
            if (!isset($this->messages[$field])) {
                $this->messages[$field] = $message;
            }
            return false;
        };

        switch ($rule) {
            case 'required':
                if ($value === null || $value === '' || $value === []) {
                    return $fail(__('validation.required', ['field' => $this->label($field)]));
                }
                return true;
            case 'email':
                if (!filter_var((string)$value, FILTER_VALIDATE_EMAIL)) {
                    return $fail(__('validation.email', ['field' => $this->label($field)]));
                }
                return true;
            case 'min':
                if (mb_strlen((string)$value) < (int)$param) {
                    return $fail(__('validation.min', ['field' => $this->label($field), 'min' => $param]));
                }
                return true;
            case 'max':
                if (mb_strlen((string)$value) > (int)$param) {
                    return $fail(__('validation.max', ['field' => $this->label($field), 'max' => $param]));
                }
                return true;
            case 'numeric':
                if (!is_numeric($value)) {
                    return $fail(__('validation.numeric', ['field' => $this->label($field)]));
                }
                return true;
            case 'url':
                if (!filter_var((string)$value, FILTER_VALIDATE_URL) && strpos((string)$value, '/') !== 0) {
                    return $fail(__('validation.url', ['field' => $this->label($field)]));
                }
                return true;
            case 'in':
                if (!in_array((string)$value, explode(',', (string)$param), true)) {
                    return $fail(__('validation.invalid', ['field' => $this->label($field)]));
                }
                return true;
            case 'integer':
                if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                    return $fail(__('validation.numeric', ['field' => $this->label($field)]));
                }
                return true;
            case 'decimal':
                if (!preg_match('/^\d+(\.\d{1,4})?$/', (string)$value)) {
                    return $fail(__('validation.numeric', ['field' => $this->label($field)]));
                }
                return true;
            case 'phone':
                if (!preg_match('/^[+0-9][0-9\s\-\(\)]{5,20}$/', (string)$value)) {
                    return $fail(__('validation.phone', ['field' => $this->label($field)]));
                }
                return true;
            case 'regex':
                if (!preg_match($param, (string)$value)) {
                    return $fail(__('validation.invalid', ['field' => $this->label($field)]));
                }
                return true;
            case 'nullable':
            case 'string':
                return true;
            default:
                return true;
        }
    }

    private function label(string $field): string
    {
        $key = 'fields.' . $field;
        $label = __($key);
        return $label === $key ? ucwords(str_replace('_', ' ', $field)) : $label;
    }

    public function fails(): bool
    {
        return !empty($this->messages);
    }

    public function errors(): array
    {
        return $this->messages;
    }

    public function validated(): array
    {
        return $this->validated + array_intersect_key($this->data, $this->rules);
    }
}
