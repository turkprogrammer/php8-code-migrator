<?php

declare(strict_types=1);

// Пример функции с типами параметров и возвращаемым типом
function sum(int $a, int $b): int {
    return $a + $b;
}

// Пример функции с типом mixed
function getValue(mixed $value): mixed {
    return $value;
}

// Пример класса с конструктором
class Example {
    public function __construct(public $property)
    {
    }

    // Пример метода с union типами
    public function getProperty(): int|string {
        return $this->property;
    }

    // Пример использования match
    public function getStatus(int $code): string {
        return match ($code) {
            200 => 'OK',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            default => 'Unknown',
        };
    }
}

// Пример использования named arguments
$result = sum(a: 5, b: 10);

// Пример функции с параметром по умолчанию
function greet(string $name = 'Guest'): string {
    return "Hello, $name";
}

// Пример использования nullsafe оператора
class User {
    public ?Address $address = null;
}

class Address {
    public ?string $street = null;
}

$user = new User();
$street = $user?->address?->street;

echo $result;
echo greet();
echo (new Example(42))->getStatus(200);

// Пример использования устаревших функций
$hex = hex2bin('48656c6c6f20576f726c64'); // устаревшая функция в PHP 8.0

// Пример функции без строгой типизации
function add($a, $b): float|int|array {
    return $a + $b;
}

echo add(5, '10'); // вызов с несоответствием типов

// Пример использования create_function
$func = function ($a, $b) : float|int|array {
    return $a + $b;
};
echo $func(1, 2);
