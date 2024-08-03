# Настройка PHP Compatibility и Rector

Это руководство содержит инструкции по настройке PHP_CodeSniffer с PHPCompatibility и Rector для анализа и обновления вашего PHP-кода для совместимости с PHP 8.0.

# Описание проекта

## Назначение

Этот контейнер предназначен для автоматического анализа и исправления кода PHP с целью обеспечения его совместимости с PHP 8.0. Мы используем PHP_CodeSniffer с PHPCompatibility для выявления проблем несовместимости и Rector для автоматического исправления кода.

## Основные компоненты

- **PHP_CodeSniffer**: Анализатор кода, который помогает выявить проблемы со стилем и совместимостью.
- **PHPCompatibility**: Набор правил для PHP_CodeSniffer, позволяющий определить совместимость кода с различными версиями PHP, включая PHP 8.0.
- **Rector**: Инструмент для автоматического рефакторинга кода, который помогает исправлять проблемы совместимости и улучшать качество кода.

## Основные возможности

- **Анализ кода**: Проверка кода на наличие проблем совместимости с PHP 8.0.
- **Автоматическое исправление**: Автоматическое исправление выявленных проблем совместимости.
- **Поддержка стандартов**: Соблюдение современных стандартов кода и лучших практик.

## Использование

1. **Анализ кода на совместимость с PHP 8.0**:
    ```bash
    vendor/bin/phpcs --standard=PHPCompatibility --runtime-set testVersion=8.0 src/
    ```

2. **Автоматическое исправление кода с помощью Rector**:
    ```bash
    vendor/bin/rector process src
    ```

## Преимущества

- **Экономия времени**: Автоматизация процесса поиска и исправления проблем совместимости.
- **Снижение рисков**: Обеспечение плавного перехода на PHP 8.0 без ручного исправления множества файлов.
- **Улучшение качества кода**: Соблюдение современных стандартов кода и улучшение его качества.

Этот контейнер значительно упрощает процесс обновления и поддержания кода, делая его совместимым с новыми версиями PHP.

## Требования

- PHP 7.2 или выше
- Composer

## Установка

1. **Клонируйте репозиторий** (или создайте новый проект):
    ```bash
    git clone <your-repo-url>
    cd <your-repo-directory>
    ```

2. **Инициализируйте проект Composer** (если еще не сделали это):
    ```bash
    composer init
    ```

3. **Установите PHP_CodeSniffer**:
    ```bash
    composer require --dev squizlabs/php_codesniffer
    ```

4. **Установите PHPCompatibility**:
    ```bash
    composer require --dev phpcompatibility/php-compatibility
    ```

5. **Настройте PHP_CodeSniffer для использования PHPCompatibility**:
    ```bash
    vendor/bin/phpcs --config-set installed_paths vendor/phpcompatibility/php-compatibility
    ```

6. **Проверьте установленные стандарты**:
    ```bash
    vendor/bin/phpcs -i
    ```
   Вы должны увидеть `PHPCompatibility` в списке установленных стандартов.

7. **Установите Rector**:
    ```bash
    composer require rector/rector --dev
    ```

## Конфигурация

1. **Создайте файл конфигурации `rector.php`** в корневой директории вашего проекта со следующим содержанием:
    ```php
    <?php

    declare(strict_types=1);

    use Rector\Config\RectorConfig;
    use Rector\Php74\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector;
    use Rector\Set\ValueObject\SetList;

    return static function (RectorConfig $rectorConfig): void {
        $rectorConfig->paths([
            __DIR__ . '/src',
        ]);

        $rectorConfig->rules([
            CreateFunctionToAnonymousFunctionRector::class,
            // Добавьте больше правил здесь, если необходимо
        ]);

        $rectorConfig->sets([
            SetList::PHP_80,
            SetList::CODE_QUALITY,
            SetList::DEAD_CODE,
            SetList::TYPE_DECLARATION,
            SetList::EARLY_RETURN,
        ]);
    };
    ```

## Использование

1. **Создайте тестовый PHP-файл** в `src/test.php` для анализа:
    ```php
    <?php

    // Пример использования create_function
    $func = create_function('$a, $b', 'return $a + $b;');
    echo $func(1, 2);

    // Использование list() с переменными
    list($a, $b) = [1, 2];

    // Использование each()
    $array = [1, 2, 3];
    while (list($key, $val) = each($array)) {
        echo "$key => $val\n";
    }

    // Использование функции mbstring без обязательных аргументов
    echo mb_strpos('foo', 'o');

    // Использование глобальных переменных без указания глобального контекста
    function foo() {
        $GLOBALS['bar'] = 'baz';
    }
    foo();
    echo $bar;

    // Использование зарезервированного имени (PHP 7.4+)
    class String {
        public function toString() {
            return "Это строка";
        }
    }
    $obj = new String();
    echo $obj->toString();

    // Использование функций с типами, несовместимыми с PHP 8.0
    function sum(int $a, int $b) {
        return $a + $b;
    }
    echo sum('1', '2');

    // Использование assert() без скобок
    assert 1 == 1;

    // Использование устаревших функций
    $number = rand(0, getrandmax());
    echo $number;

    // Использование динамических свойств (PHP 8.2)
    class Test {
        public $existingProperty;
    }
    $test = new Test();
    $test->dynamicProperty = 'Dynamic';

    // Параметры с значениями по умолчанию перед обязательными параметрами
    function example($a = 1, $b) {
        return $a + $b;
    }
    echo example(2);
    ```

2. **Анализируйте код с помощью PHP_CodeSniffer**:
    ```bash
    vendor/bin/phpcs --standard=PHPCompatibility --runtime-set testVersion=8.0 src/test.php
    ```

3. **Исправьте проблемы совместимости с помощью Rector**:
    ```bash
    vendor/bin/rector process src
    ```

4. **Повторно запустите PHP_CodeSniffer, чтобы убедиться, что все проблемы исправлены**:
    ```bash
    vendor/bin/phpcs --standard=PHPCompatibility --runtime-set testVersion=8.0 src/test.php
    ```

## Дополнительные ресурсы

- [Документация PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [Документация PHPCompatibility](https://github.com/PHPCompatibility/PHPCompatibility)
- [Документация Rector](https://github.com/rectorphp/rector)
