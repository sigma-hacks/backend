<?php

namespace App\Types;

class ValidateResult
{
    private bool $status;

    private string $error;

    private array $fields_error;

    private array $data;

    public function __construct(bool $status = false, array $data = [], string $error = '', array $fields_error = [])
    {
        $this->status = $status;
        $this->data = $data;
        $this->error = $error;
        $this->fields_error = $fields_error;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getFieldsError(): array
    {
        return $this->fields_error;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public static function create(bool $status = false, array $data = [], string $error = '', array $fields_error = []): self
    {
        return new ValidateResult($status, $data, $error, $fields_error);
    }

    public function toJSON(): bool|string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        $result = [
            'status' => $this->status,
        ];

        if (! empty($this->error)) {
            $result['error'] = $this->error;
        }

        if (! empty($this->data)) {
            $result['data'] = $this->data;
        }

        if (! empty($this->fields_error)) {
            $result['fields_error'] = $this->fields_error;
        }

        return $result;
    }

    public function toString(): string
    {
        $result = '';
        foreach ($this->toArray() as $key => $value) {
            $result .= "{$key}={$value}&";
        }

        return $result;
    }
}
