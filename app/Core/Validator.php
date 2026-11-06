<?php
// app/Core/Validator.php
namespace App\Core;

final class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $label = ''): self
    {
        $v = trim((string)($this->data[$field] ?? ''));
        if ($v === '') $this->errors[$field] = ($label ?: $field) . ' مطلوب';
        return $this;
    }

    public function email(string $field, string $label = 'البريد الإلكتروني'): self
    {
        $v = trim((string)($this->data[$field] ?? ''));
        if ($v !== '' && !filter_var($v, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $label . ' غير صالح';
        }
        return $this;
    }

    public function minLen(string $field, int $min, string $label = ''): self
    {
        $v = trim((string)($this->data[$field] ?? ''));
        if ($v !== '' && mb_strlen($v) < $min) {
            $this->errors[$field] = ($label ?: $field) . " على الأقل $min حروف";
        }
        return $this;
    }

    public function numeric(string $field, string $label = ''): self
    {
        $v = $this->data[$field] ?? '';
        if ($v !== '' && !is_numeric($v)) {
            $this->errors[$field] = ($label ?: $field) . ' يجب أن يكون رقماً';
        }
        return $this;
    }

    public function in(string $field, array $allowed, string $label = ''): self
    {
        $v = (string)($this->data[$field] ?? '');
        if ($v !== '' && !in_array($v, $allowed, true)) {
            $this->errors[$field] = ($label ?: $field) . ' قيمة غير مسموحة';
        }
        return $this;
    }

    public function fails(): bool { return !empty($this->errors); }
    public function errors(): array { return $this->errors; }
    public function first(): string { return (string) (reset($this->errors) ?: ''); }
}