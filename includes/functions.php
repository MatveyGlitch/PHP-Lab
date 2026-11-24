<?php

date_default_timezone_set('Europe/Moscow');

// ===== ЗАДАНИЕ 1. ВОЗВЕДЕНИЕ В СТЕПЕНЬ =====

function power($val, $pow)
{
    if ($pow === 0) {
        return 1;
    }

    if ($pow < 0) {
        return 1 / power($val, -$pow);
    }

    return $val * power($val, $pow - 1);
}

$powerBase = 2;
$powerExponent = 8;

if (($_POST['task'] ?? '') === 'power') {
    $powerBase = $_POST['base'] ?? '';
    $powerExponent = $_POST['exponent'] ?? '';

    $base = filter_var($powerBase, FILTER_VALIDATE_FLOAT);
    $exponent = filter_var($powerExponent, FILTER_VALIDATE_INT);

    if ($base === false || $exponent === false) {
        $powerError = 'Введите корректные числа.';
    } elseif ($exponent < -100 || $exponent > 100) {
        $powerError = 'Степень должна быть от -100 до 100.';
    } elseif ($base == 0 && $exponent < 0) {
        $powerError = 'Ноль нельзя возводить в отрицательную степень.';
    } else {
        $result = power($base, $exponent);

        if (is_finite($result)) {
            $powerResult = "$base ^ $exponent = $result";
        } else {
            $powerError = 'Результат выходит за допустимый диапазон.';
        }
    }
}


// ===== ЗАДАНИЕ 2. ТЕКУЩЕЕ ВРЕМЯ =====

function declension($number, $forms)
{
    $lastTwo = $number % 100;
    $lastOne = $number % 10;

    if ($lastTwo >= 11 && $lastTwo <= 14) {
        return $forms[2];
    }

    if ($lastOne === 1) {
        return $forms[0];
    }

    if ($lastOne >= 2 && $lastOne <= 4) {
        return $forms[1];
    }

    return $forms[2];
}

function getCurrentTime($time)
{
    $hours = (int) $time->format('G');
    $minutes = (int) $time->format('i');

    $hourWord = declension($hours, ['час', 'часа', 'часов']);
    $minuteWord = declension($minutes, ['минута', 'минуты', 'минут']);

    return "$hours $hourWord $minutes $minuteWord";
}

$now = new DateTimeImmutable();

$clockTime = $now->format('H:i');
$currentTime = getCurrentTime($now);


// ===== ЗАДАНИЕ 3. ЦИКЛ WHILE =====

$multiplesOfThree = [];
$i = 0;

while ($i <= 100) {
    if ($i % 3 === 0) {
        $multiplesOfThree[] = $i;
    }

    $i++;
}


// ===== ЗАДАНИЕ 4. ЦИКЛ DO...WHILE =====

function getParityRows()
{
    $rows = [];
    $i = 0;

    do {
        if ($i === 0) {
            $rows[] = "$i — это ноль";
        } elseif ($i % 2 === 0) {
            $rows[] = "$i — чётное число";
        } else {
            $rows[] = "$i — нечётное число";
        }

        $i++;

    } while ($i <= 10);

    return $rows;
}

$parityRows = getParityRows();


// ===== ЗАДАНИЕ 5. ЦИКЛ FOR БЕЗ ТЕЛА =====

$forOutput = '';

for ($i = 0; $i < 10; $forOutput .= $i . ' ', $i++) {
    // Тело цикла пустое
}

$forOutput = trim($forOutput);



// ===== ЗАДАНИЕ 6. ОБЛАСТИ И ГОРОДА =====

$regions = [
        'Московская область' => [
                'Балашиха',
                'Коломна',
                'Клин',
                'Красногорск',
                'Подольск'
        ],

        'Ленинградская область' => [
                'Выборг',
                'Всеволожск',
                'Кировск',
                'Кириши',
                'Гатчина'
        ],

        'Рязанская область' => [
                'Рязань',
                'Касимов',
                'Кораблино',
                'Скопин',
                'Сасово'
        ],

        'Тверская область' => [
                'Тверь',
                'Кимры',
                'Конаково',
                'Ржев',
                'Торжок'
        ],

        'Калужская область' => [
                'Калуга',
                'Киров',
                'Козельск',
                'Обнинск',
                'Боровск'
        ]
];


// ===== ЗАДАНИЕ 7. ФИЛЬТРАЦИЯ ГОРОДОВ =====

$filteredRegions = [];

foreach ($regions as $region => $cities) {
    foreach ($cities as $city) {
        if (str_starts_with($city, 'К')) {
            $filteredRegions[$region][] = $city;
        }
    }
}


// ===== ЗАДАНИЕ 8. ТРАНСЛИТЕРАЦИЯ =====

function transliterate($text)
{
    $letters = [
            'а' => 'a',   'А' => 'A',
            'б' => 'b',   'Б' => 'B',
            'в' => 'v',   'В' => 'V',
            'г' => 'g',   'Г' => 'G',
            'д' => 'd',   'Д' => 'D',
            'е' => 'e',   'Е' => 'E',
            'ё' => 'yo',  'Ё' => 'Yo',
            'ж' => 'zh',  'Ж' => 'Zh',
            'з' => 'z',   'З' => 'Z',
            'и' => 'i',   'И' => 'I',
            'й' => 'y',   'Й' => 'Y',
            'к' => 'k',   'К' => 'K',
            'л' => 'l',   'Л' => 'L',
            'м' => 'm',   'М' => 'M',
            'н' => 'n',   'Н' => 'N',
            'о' => 'o',   'О' => 'O',
            'п' => 'p',   'П' => 'P',
            'р' => 'r',   'Р' => 'R',
            'с' => 's',   'С' => 'S',
            'т' => 't',   'Т' => 'T',
            'у' => 'u',   'У' => 'U',
            'ф' => 'f',   'Ф' => 'F',
            'х' => 'kh',  'Х' => 'Kh',
            'ц' => 'ts',  'Ц' => 'Ts',
            'ч' => 'ch',  'Ч' => 'Ch',
            'ш' => 'sh',  'Ш' => 'Sh',
            'щ' => 'shch','Щ' => 'Shch',
            'ъ' => '',    'Ъ' => '',
            'ы' => 'y',   'Ы' => 'Y',
            'ь' => '',    'Ь' => '',
            'э' => 'e',   'Э' => 'E',
            'ю' => 'yu',  'Ю' => 'Yu',
            'я' => 'ya',  'Я' => 'Ya'
    ];

    return strtr($text, $letters);
}

if (($_POST['task'] ?? '') === 'translit') {
    $translitInput = $_POST['source_text'] ?? '';

    if (!is_string($translitInput)) {
        $translitInput = '';
    }

    if (trim($translitInput) === '') {
        $translitResult = 'Введите текст для транслитерации.';
    } else {
        $translitResult = transliterate($translitInput);
    }
}


// ===== ЗАДАНИЕ 9. ПРЕОБРАЗОВАНИЕ СТРОК =====

function convertString($text)
{
    $text = transliterate($text);

    return str_replace(' ', '_', $text);
}

if (($_POST['task'] ?? '') === 'slug') {
    $slugInput = $_POST['slug_text'] ?? '';

    if (!is_string($slugInput)) {
        $slugInput = '';
    }

    if (trim($slugInput) === '') {
        $slugResult = 'Введите текст для преобразования.';
    } else {
        $slugResult = convertString($slugInput);
    }
}


// ===== ЗАДАНИЕ 10. КАЛЬКУЛЯТОР =====

$calcA = '';
$calcB = '';

if (($_POST['task'] ?? '') === 'calculator') {
    $calcA = $_POST['calc_a'] ?? '';
    $calcB = $_POST['calc_b'] ?? '';
    $operation = $_POST['operation'] ?? '';

    if (!is_string($calcA) || !is_string($calcB)) {
        $calcA = '';
        $calcB = '';
    }

    // Проверка введённых данных
    if (!is_numeric($calcA) || !is_numeric($calcB)) {
        $calcError = 'Введите два корректных числа.';
    } else {
        $a = (float) $calcA;
        $b = (float) $calcB;

        // Определение арифметической операции
        switch ($operation) {
            case 'add':
                $result = $a + $b;
                $symbol = '+';
                break;

            case 'subtract':
                $result = $a - $b;
                $symbol = '−';
                break;

            case 'multiply':
                $result = $a * $b;
                $symbol = '×';
                break;

            case 'divide':
                if ($b == 0) {
                    $calcError = 'Деление на ноль невозможно.';
                } else {
                    $result = $a / $b;
                    $symbol = '÷';
                }
                break;

            default:
                $calcError = 'Неизвестная операция.';
                break;
        }

        // Формирование результата
        if (!isset($calcError)) {
            if (is_finite($result)) {
                $calcResult = "$a $symbol $b = $result";
            } else {
                $calcError = 'Результат выходит за допустимый диапазон.';
            }
        }
    }
}


// ===== ЗАДАНИЕ 11. ЗАГРУЗКА ФАЙЛОВ =====

if (($_POST['task'] ?? '') === 'upload') {
    $maxFileSize = 2 * 1024 * 1024;

    // Папка для хранения файлов вне htdocs
    $uploadDir = dirname($_SERVER['DOCUMENT_ROOT']) . '/lab7-files/';

    if (!isset($_FILES['file'])) {
        $uploadError = 'Файл не был передан на сервер.';
    } else {
        $file = $_FILES['file'];

        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            $uploadError = 'Выберите файл для загрузки.';
        } elseif ($file['error'] === UPLOAD_ERR_INI_SIZE ||
                $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            $uploadError = 'Размер файла превышает допустимый предел.';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $uploadError = 'Произошла ошибка при загрузке файла.';
        } elseif ($file['size'] > $maxFileSize) {
            $uploadError = 'Размер файла не должен превышать 2 МБ.';
        } else {
            // Создание папки при её отсутствии
            if (!is_dir($uploadDir) &&
                    !mkdir($uploadDir, 0755, true)) {
                $uploadError = 'Не удалось создать папку для файлов.';
            } else {
                // Получение исходного имени файла
                $originalName = basename(
                        str_replace('\\', '/', $file['name'])
                );

                // Генерация уникального имени для хранения
                $uniqueName = bin2hex(random_bytes(16)) . '.bin';

                $destination = $uploadDir . $uniqueName;

                // Перемещение файла на сервер
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $uploadMessage = "Файл успешно загружен: $originalName";
                } else {
                    $uploadError = 'Не удалось сохранить файл на сервере.';
                }
            }
        }
    }
}

// Проверка превышения общего размера POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        empty($_POST) && empty($_FILES)) {
    $uploadError = 'Запрос пуст или превышен допустимый размер загрузки.';
}