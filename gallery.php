<?php

session_start();

if (!isset($_SESSION['gallery_token'])) {
    $_SESSION['gallery_token'] = bin2hex(random_bytes(32));
}

// ===== НАСТРОЙКИ ГАЛЕРЕИ =====

$uploadDir = __DIR__ . '/uploads/';
$thumbnailDir = __DIR__ . '/thumbnails/';

$maxFileSize = 20 * 1024 * 1024;
$thumbnailSize = 800;

$allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
];

$error = '';
$success = '';


// ===== БЕЗОПАСНЫЙ ВЫВОД =====

function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}


// ===== СОЗДАНИЕ МИНИАТЮРЫ =====

function createThumbnail($source, $destination, $mimeType, $maxSize)
{
    $image = match ($mimeType) {
        'image/jpeg' => @imagecreatefromjpeg($source),
        'image/png' => @imagecreatefrompng($source),
        'image/webp' => @imagecreatefromwebp($source),
        default => false
    };

    if ($image === false) {
        return false;
    }

    // Получение размеров оригинала
    $width = imagesx($image);
    $height = imagesy($image);

    // Расчёт пропорций миниатюры
    $scale = min(
            $maxSize / $width,
            $maxSize / $height,
            1
    );

    $newWidth = max(1, (int) round($width * $scale));
    $newHeight = max(1, (int) round($height * $scale));

    // Создание нового изображения
    $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

    if ($thumbnail === false) {
        imagedestroy($image);
        return false;
    }

    // Сохранение прозрачности PNG и WEBP
    if ($mimeType === 'image/png' || $mimeType === 'image/webp') {
        imagealphablending($thumbnail, false);
        imagesavealpha($thumbnail, true);

        $transparent = imagecolorallocatealpha(
                $thumbnail, 0, 0, 0, 127
        );

        imagefill($thumbnail, 0, 0, $transparent);
    }

    // Копирование с изменением размеров
    $copied = imagecopyresampled(
            $thumbnail,
            $image,
            0, 0,
            0, 0,
            $newWidth,
            $newHeight,
            $width,
            $height
    );

    // Сохранение уменьшенной копии
    $saved = false;

    if ($copied) {
        $saved = match ($mimeType) {
            'image/jpeg' => @imagejpeg($thumbnail, $destination, 95),
            'image/png' => @imagepng($thumbnail, $destination, 6),
            'image/webp' => @imagewebp($thumbnail, $destination, 95),
            default => false
        };
    }

    imagedestroy($image);
    imagedestroy($thumbnail);

    return $saved &&
            is_file($destination) &&
            filesize($destination) > 0;
}


// ===== УДАЛЕНИЕ ИЗОБРАЖЕНИЯ =====

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        isset($_POST['delete_photo'])) {

    $filename = $_POST['delete_photo'];
    $token = $_POST['csrf_token'] ?? '';

    // Проверка защитного токена
    if (!is_string($token) ||
            !hash_equals($_SESSION['gallery_token'], $token)) {
        $error = 'Недопустимый запрос.';
    }

    // Проверка имени файла
    elseif (!is_string($filename) ||
            !preg_match('/\A[a-f0-9]{32}\.(jpg|png|webp)\z/', $filename)) {
        $error = 'Недопустимое имя файла.';
    }

    else {
        $originalPath = $uploadDir . $filename;
        $thumbnailPath = $thumbnailDir . $filename;

        if (!is_file($originalPath) ||
                !is_file($thumbnailPath)) {
            $error = 'Изображение не найдено.';
        }

        elseif (!@unlink($originalPath)) {
            $error = 'Не удалось удалить оригинал изображения.';
        }

        elseif (!@unlink($thumbnailPath)) {
            $error = 'Оригинал удалён, но не удалось удалить миниатюру.';
        }

        else {
            header('Location: gallery.php?deleted=1', true, 303);
            exit;
        }
    }
}


// ===== ОБРАБОТКА ЗАГРУЗКИ =====

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
        !isset($_POST['delete_photo'])) {

    if (!extension_loaded('gd')) {
        $error = 'Библиотека GD не подключена в PHP.';
    } elseif (!isset($_FILES['photo'])) {
        $error = 'Изображение не передано или превышен размер запроса.';
    } else {
        $file = $_FILES['photo'];

        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            $error = 'Выберите изображение.';
        } elseif ($file['error'] === UPLOAD_ERR_INI_SIZE ||
                $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            $error = 'Размер изображения превышает допустимый предел.';
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            $error = 'Произошла ошибка при загрузке изображения.';
        } elseif ($file['size'] > $maxFileSize) {
            $error = 'Размер изображения не должен превышать 20 МБ.';
        } else {

            // Определение фактического типа файла
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);

            // Получение информации об изображении
            $imageInfo = @getimagesize($file['tmp_name']);

            if (!isset($allowedTypes[$mimeType])) {
                $error = 'Допускаются только JPG, PNG и WEBP.';
            } elseif ($imageInfo === false ||
                    $imageInfo['mime'] !== $mimeType) {
                $error = 'Выбранный файл не является корректным изображением.';
            } elseif ($imageInfo[0] * $imageInfo[1] > 25000000) {
                $error = 'Разрешение изображения слишком большое.';
            } elseif ($mimeType === 'image/webp' &&
                    (!function_exists('imagecreatefromwebp') ||
                            !function_exists('imagewebp'))) {
                $error = 'Текущая версия GD не поддерживает WEBP.';
            } else {

                // Создание папок при необходимости
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }

                if (!is_dir($thumbnailDir)) {
                    @mkdir($thumbnailDir, 0755, true);
                }

                if (!is_writable($uploadDir) ||
                        !is_writable($thumbnailDir)) {
                    $error = 'Нет доступа к папкам галереи.';
                } else {

                    // Генерация уникального имени изображения
                    $extension = $allowedTypes[$mimeType];

                    $filename = bin2hex(random_bytes(16))
                            . '.' . $extension;

                    $originalPath = $uploadDir . $filename;
                    $thumbnailPath = $thumbnailDir . $filename;

                    // Создание уменьшенной копии
                    $thumbnailCreated = createThumbnail(
                            $file['tmp_name'],
                            $thumbnailPath,
                            $mimeType,
                            $thumbnailSize
                    );

                    if (!$thumbnailCreated) {
                        if (is_file($thumbnailPath)) {
                            unlink($thumbnailPath);
                        }

                        $error = 'Не удалось создать миниатюру.';
                    } elseif (!move_uploaded_file(
                            $file['tmp_name'],
                            $originalPath
                    )) {
                        unlink($thumbnailPath);

                        $error = 'Не удалось сохранить оригинал изображения.';
                    } else {

                        // Перенаправление после успешной загрузки
                        header('Location: gallery.php?uploaded=1', true, 303);
                        exit;
                    }
                }
            }
        }
    }
}


// ===== СООБЩЕНИЕ ОБ УСПЕШНОЙ ЗАГРУЗКЕ =====

if (($_GET['uploaded'] ?? '') === '1' && $error === '') {
    $success = 'Изображение успешно загружено!';
}
if (($_GET['deleted'] ?? '') === '1' && $error === '') {
    $success = 'Изображение успешно удалено!';
}


// ===== ПОЛУЧЕНИЕ СПИСКА ФОТОГРАФИЙ =====

$photos = [];

$files = glob($thumbnailDir . '*.{jpg,png,webp}', GLOB_BRACE);

foreach ($files ?: [] as $thumbnail) {

    $filename = basename($thumbnail);

    // Проверяем наличие оригинального изображения
    if (is_file($uploadDir . $filename)) {
        $photos[] = [
                'name' => $filename,
                'original' => 'uploads/' . rawurlencode($filename),
                'thumbnail' => 'thumbnails/' . rawurlencode($filename),
                'time' => filemtime($thumbnail)
        ];
    }
}

// Сортировка: сначала новые фотографии
usort($photos, function ($a, $b) {
    return $b['time'] <=> $a['time'];
});

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="PHP Lab — фотогалерея с загрузкой изображений">

    <title>PHP Lab — Фотогалерея</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="app-shell">

    <!-- Боковое меню -->
    <aside class="sidebar">

        <a href="index.php" class="brand">
            <div class="brand-logo">&lt;/&gt;</div>

            <div class="brand-info">
                <h2>PHP Lab</h2>
            </div>
        </a>

        <nav class="sidebar-nav">

            <span class="nav-caption">НАВИГАЦИЯ</span>

            <a href="index.php" class="nav-link">
                <svg class="nav-icon"
                     width="22" height="22" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.7"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">

                    <rect x="3" y="3" width="7" height="9" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="5" rx="1.5"/>
                    <rect x="14" y="12" width="7" height="9" rx="1.5"/>
                    <rect x="3" y="16" width="7" height="5" rx="1.5"/>

                </svg>
                Основные задания
            </a>

            <a href="#gallery" class="nav-link active">
                <svg class="nav-icon gallery-nav-icon"
                     xmlns="http://www.w3.org/2000/svg"
                     width="22" height="22"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8"
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     aria-hidden="true">

                    <rect x="3" y="3" width="18" height="18" rx="3"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <path d="m21 15-5-5L5 21"/>

                </svg>

                Фотогалерея
            </a>

        </nav>

        <div class="sidebar-footer">
            <small>PHP Lab © 2026</small>
        </div>

    </aside>

    <!-- Рабочая область -->
    <div class="workspace">

        <header class="topbar" id="gallery">

            <div class="topbar-info">
                <h1>Фотогалерея</h1>
            </div>

            <div class="topbar-actions">
                <a href="index.php" class="button button-secondary">
                    К заданиям
                </a>

            </div>

        </header>

        <main class="main-content">

            <!-- Форма загрузки изображений -->
            <section class="task-card gallery-upload-panel" id="upload">

                <div class="task-heading">
                    <span class="task-number">
                        <svg width="23" height="23" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.7"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">

                             <path d="M7 18H6a4 4 0 0 1-.5-8A6.5 6.5 0 0 1 18 8a5 5 0 0 1 0 10h-1"/>
                             <path d="M12 21V11"/>
                             <path d="m8 15 4-4 4 4"/>

                          </svg>
                    </span>

                    <div>
                        <h2>Загрузка изображения</h2>
                    </div>
                </div>

                <form method="post"
                      action="gallery.php#upload"
                      enctype="multipart/form-data"
                      class="task-form gallery-upload-form">

                    <div class="upload-area gallery-upload-area">

                        <div class="upload-icon">
                            <svg width="27" height="27" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.7"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">

                                <path d="M14 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-9"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <path d="m3 17 5-5 4 4 4-4 5 5"/>
                                <path d="M19 2v6"/>
                                <path d="M16 5h6"/>

                            </svg>
                        </div>

                        <h3>Выберите изображение</h3>

                        <p>JPG, PNG, WEBP — до 20 МБ</p>

                        <input type="file"
                               id="photo"
                               name="photo"
                               accept="image/jpeg,image/png,image/webp"
                               aria-label="Выбрать изображение"
                               required>

                    </div>

                    <button type="submit" class="button button-primary">
                        Загрузить изображение
                    </button>

                </form>

                <!-- Сообщение об ошибке -->
                <?php if ($error !== ''): ?>

                    <div class="gallery-message gallery-error" role="alert">
                        <?= h($error) ?>
                    </div>

                <?php endif; ?>

                <!-- Сообщение об успешной загрузке -->
                <?php if ($success !== ''): ?>

                    <div class="gallery-message gallery-success" role="status">
                        ✓ <?= h($success) ?>
                    </div>

                <?php endif; ?>

            </section>

            <!-- Галерея фотографий -->
            <section class="gallery-section">

                <div class="gallery-heading">
                    <h2>Все фотографии</h2>
                </div>

                <?php if (empty($photos)): ?>

                    <div class="gallery-empty">

                        <div class="gallery-empty-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.7"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">

                                <rect x="6" y="6" width="15" height="15" rx="2"/>
                                <circle cx="11" cy="11" r="2"/>
                                <path d="m21 16-4-4-9 9"/>
                                <path d="M3 17V5a2 2 0 0 1 2-2h12"/>

                            </svg>
                        </div>

                        <h3>Галерея пока пуста</h3>

                        <p>Загрузите первое изображение.</p>

                    </div>

                <?php else: ?>

                    <div class="gallery-grid">

                        <?php foreach ($photos as $index => $photo): ?>

                            <div class="gallery-item">

                                <!-- Просмотр оригинала -->
                                <a href="<?= h($photo['original']) ?>"
                                   class="gallery-image"
                                   target="_blank"
                                   rel="noopener noreferrer">

                                    <img src="<?= h($photo['thumbnail']) ?>"
                                         alt="Фотография <?= $index + 1 ?>"
                                         loading="lazy">

                                </a>

                                <!-- Удаление фотографии -->
                                <form method="post"
                                      action="gallery.php"
                                      class="gallery-delete-form"
                                      onsubmit="return confirm('Удалить эту фотографию?');">

                                    <input type="hidden"
                                           name="delete_photo"
                                           value="<?= h($photo['name']) ?>">

                                    <input type="hidden"
                                           name="csrf_token"
                                           value="<?= h($_SESSION['gallery_token']) ?>">

                                    <button type="submit"
                                            class="gallery-delete-button"
                                            title="Удалить фотографию"
                                            aria-label="Удалить фотографию">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             width="19" height="19"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.8"
                                             stroke-linecap="round"
                                             stroke-linejoin="round"
                                             aria-hidden="true">

                                            <path d="M3 6h18"/>
                                            <path d="M19 6v14a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V6"/>
                                            <path d="M8 6V3h8v3"/>
                                            <path d="M10 10v7"/>
                                            <path d="M14 10v7"/>

                                        </svg>

                                    </button>

                                </form>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            </section>

        </main>

    </div>

</div>

</body>
</html>