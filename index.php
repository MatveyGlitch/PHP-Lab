<?php
require_once __DIR__ . '/includes/functions.php';

function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description"
          content="PHP Lab — лабораторная работа №7 по Web-программированию">

    <title>PHP Lab — Лабораторная №7</title>

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

            <a href="#dashboard" class="nav-link active">
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
                Обзор
            </a>

            <span class="nav-caption">ЗАДАНИЯ</span>

            <a href="#task-1" class="nav-link">
                <span class="nav-number">01</span>
                Возведение в степень
            </a>

            <a href="#task-2" class="nav-link">
                <span class="nav-number">02</span>
                Текущее время
            </a>

            <a href="#task-3" class="nav-link">
                <span class="nav-number">03</span>
                Цикл while
            </a>

            <a href="#task-4" class="nav-link">
                <span class="nav-number">04</span>
                Цикл do...while
            </a>

            <a href="#task-5" class="nav-link">
                <span class="nav-number">05</span>
                Цикл for
            </a>

            <a href="#task-6" class="nav-link">
                <span class="nav-number">06</span>
                Области и города
            </a>

            <a href="#task-7" class="nav-link">
                <span class="nav-number">07</span>
                Фильтрация городов
            </a>

            <a href="#task-8" class="nav-link">
                <span class="nav-number">08</span>
                Транслитерация
            </a>

            <a href="#task-9" class="nav-link">
                <span class="nav-number">09</span>
                Преобразование строк
            </a>

            <a href="#task-10" class="nav-link">
                <span class="nav-number">10</span>
                Калькулятор
            </a>

            <a href="#task-11" class="nav-link">
                <span class="nav-number">11</span>
                Загрузка файлов
            </a>

            <span class="nav-caption">ДОПОЛНИТЕЛЬНО</span>

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

        <header class="topbar" id="dashboard">

            <div class="topbar-info">
                <h1>Лабораторная №7</h1>
            </div>

            <div class="topbar-actions">
                <a href="gallery.php" class="button button-primary">

                    <svg width="19" height="19" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">

                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="m21 15-5-5L5 21"/>

                    </svg>

                    Фотогалерея
                </a>
            </div>

        </header>

        <main class="main-content">

            <div class="tasks-grid">

                <!-- Задание 1 -->
                <section class="task-card" id="task-1">

                    <div class="task-heading">
                        <span class="task-number">01</span>

                        <div>
                            <h2>Возведение в степень</h2>
                        </div>
                    </div>

                    <form method="post" action="#task-1" class="task-form">

                        <input type="hidden" name="task" value="power">

                        <div class="form-row">

                            <div class="form-field">
                                <label for="power-base">Число</label>

                                <input type="number"
                                       id="power-base"
                                       name="base"
                                       step="any"
                                       value="<?= h($powerBase ?? 2) ?>"
                                       required>
                            </div>

                            <div class="form-field">
                                <label for="power-exponent">Степень</label>

                                <input type="number"
                                       id="power-exponent"
                                       name="exponent"
                                       min="-100"
                                       max="100"
                                       step="1"
                                       value="<?= h($powerExponent ?? 8) ?>"
                                       required>
                            </div>

                        </div>

                        <button type="submit" class="button button-primary">
                            Вычислить степень
                        </button>

                    </form>

                    <div class="result-box">
                        <span class="result-label">Результат выполнения</span>

                        <div class="result-value <?= isset($powerError) ? 'result-error' : '' ?>"><?= h($powerError ?? $powerResult ?? 'Введите число и степень') ?></div>
                    </div>

                </section>

                <!-- Задание 2 -->
                <section class="task-card" id="task-2">

                    <div class="task-heading">
                        <span class="task-number">02</span>

                        <div>
                            <h2>Текущее время</h2>
                        </div>
                    </div>

                    <div class="clock-panel">

                        <span class="clock-caption">Время на сервере</span>

                        <strong class="clock-value">
                            <?= h($clockTime ?? '--:--') ?>
                        </strong>

                        <span class="clock-description">
                            <?= h($currentTime ?? 'Время ещё не получено') ?>
                        </span>

                    </div>

                    <a href="index.php#task-2"
                       class="button button-secondary">
                        ↻ Обновить время
                    </a>

                </section>

                <!-- Задание 3 -->
                <section class="task-card task-wide" id="task-3">

                    <div class="task-heading">
                        <span class="task-number">03</span>

                        <div>
                            <h2>Числа, кратные трём (while)</h2>
                        </div>
                    </div>

                    <div class="result-box">

                        <span class="result-label">Результат выполнения</span>

                        <div class="numbers-grid">

                            <?php foreach (($multiplesOfThree ?? []) as $number): ?>

                                <span class="number-chip">
                                    <?= h($number) ?>
                                </span>

                            <?php endforeach; ?>

                            <?php if (!isset($multiplesOfThree)): ?>
                                <p class="result-placeholder">
                                    Здесь будут выведены числа, кратные трём.
                                </p>
                            <?php endif; ?>

                        </div>

                    </div>

                </section>

                <!-- Задание 4 -->
                <section class="task-card" id="task-4">

                    <div class="task-heading">
                        <span class="task-number">04</span>

                        <div>
                            <h2>Чётные и нечётные числа (do...while)</h2>
                        </div>
                    </div>

                    <div class="result-box">

                        <span class="result-label">Результат выполнения</span>

                        <div class="parity-list">

                            <?php foreach (($parityRows ?? []) as $row): ?>

                                <div class="parity-row">
                                    <?= h($row) ?>
                                </div>

                            <?php endforeach; ?>

                            <?php if (!isset($parityRows)): ?>
                                <p class="result-placeholder">
                                    Здесь появится результат работы цикла.
                                </p>
                            <?php endif; ?>

                        </div>

                    </div>

                </section>

                <!-- Задание 5 -->
                <section class="task-card" id="task-5">

                    <div class="task-heading">
                        <span class="task-number">05</span>

                        <div>
                            <h2>Цикл for без тела</h2>
                        </div>
                    </div>

                    <div class="code-preview">
                        <span class="code-keyword">for</span> (...)
                        { }
                    </div>

                    <div class="result-box">

                        <span class="result-label">Результат выполнения</span>

                        <div class="result-value"><?= h($forOutput ?? 'Результат работы цикла') ?></div>

                    </div>

                </section>

                <!-- Задание 6 -->
                <section class="task-card" id="task-6">

                    <div class="task-heading">
                        <span class="task-number">06</span>

                        <div>
                            <h2>Области и города</h2>
                        </div>
                    </div>

                    <div class="regions-list">

                        <?php foreach (($regions ?? []) as $region => $cities): ?>

                            <div class="region-card">
                                <h3><?= h($region) ?></h3>
                                <p><?= h(implode(', ', $cities)) ?></p>
                            </div>

                        <?php endforeach; ?>

                        <?php if (!isset($regions)): ?>
                            <p class="result-placeholder">
                                Здесь будет расположен список областей и городов.
                            </p>
                        <?php endif; ?>

                    </div>

                </section>

                <!-- Задание 7 -->
                <section class="task-card" id="task-7">

                    <div class="task-heading">
                        <span class="task-number">07</span>

                        <div>
                            <h2>Фильтрация городов</h2>
                        </div>
                    </div>

                    <div class="filter-info">
                        <span>Начальная буква:</span>
                        <strong>К</strong>
                    </div>

                    <div class="regions-list">

                        <?php foreach (($filteredRegions ?? []) as $region => $cities): ?>

                            <div class="region-card">
                                <h3><?= h($region) ?></h3>
                                <p><?= h(implode(', ', $cities)) ?></p>
                            </div>

                        <?php endforeach; ?>

                        <?php if (!isset($filteredRegions)): ?>
                            <p class="result-placeholder">
                                Здесь появятся отфильтрованные города.
                            </p>
                        <?php endif; ?>

                    </div>

                </section>

                <!-- Задание 8 -->
                <section class="task-card" id="task-8">

                    <div class="task-heading">
                        <span class="task-number">08</span>

                        <div>
                            <h2>Транслитерация текста</h2>
                        </div>
                    </div>

                    <form method="post" action="#task-8" class="task-form">

                        <input type="hidden" name="task" value="translit">

                        <div class="form-field">

                            <label for="translit-input">Исходный текст</label>

                            <textarea id="translit-input"
                                      name="source_text"
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="Введите текст на русском языке"
                                      required><?= h($translitInput ?? '') ?></textarea>

                        </div>

                        <button type="submit" class="button button-primary">
                            Транслитерировать
                        </button>

                    </form>

                    <div class="result-box">

                        <span class="result-label">Результат преобразования</span>

                        <div class="result-value"><?= h($translitResult ?? 'Здесь появится результат') ?></div>

                    </div>

                </section>

                <!-- Задание 9 -->
                <section class="task-card" id="task-9">

                    <div class="task-heading">
                        <span class="task-number">09</span>

                        <div>
                            <h2>Преобразование строк</h2>
                        </div>
                    </div>

                    <form method="post" action="#task-9" class="task-form">

                        <input type="hidden" name="task" value="slug">

                        <div class="form-field">

                            <label for="slug-input">Исходный текст</label>

                            <textarea id="slug-input"
                                      name="slug_text"
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="Например: Основы языка PHP"
                                      required><?= h($slugInput ?? '') ?></textarea>

                        </div>

                        <button type="submit" class="button button-primary">
                            Преобразовать строку
                        </button>

                    </form>

                    <div class="result-box">

                        <span class="result-label">Результат преобразования</span>

                        <div class="result-value"><?= h($slugResult ?? 'Здесь появится результат') ?></div>

                    </div>

                </section>

                <!-- Задание 10 -->
                <section class="task-card" id="task-10">

                    <div class="task-heading">
                        <span class="task-number">10</span>

                        <div>
                            <h2>Калькулятор</h2>
                        </div>
                    </div>

                    <form method="post" action="#task-10" class="task-form">

                        <input type="hidden" name="task" value="calculator">

                        <div class="form-row">

                            <div class="form-field">
                                <label for="calc-a">Первое число</label>

                                <input type="number"
                                       id="calc-a"
                                       name="calc_a"
                                       step="any"
                                       value="<?= h($calcA ?? '') ?>"
                                       placeholder="0"
                                       required>
                            </div>

                            <div class="form-field">
                                <label for="calc-b">Второе число</label>

                                <input type="number"
                                       id="calc-b"
                                       name="calc_b"
                                       step="any"
                                       value="<?= h($calcB ?? '') ?>"
                                       placeholder="0"
                                       required>
                            </div>

                        </div>

                        <div class="calculator-buttons">

                            <button type="submit"
                                    name="operation"
                                    value="add"
                                    class="operation-button"
                                    title="Сложение"
                                    aria-label="Сложение">
                                +
                            </button>

                            <button type="submit"
                                    name="operation"
                                    value="subtract"
                                    class="operation-button"
                                    title="Вычитание"
                                    aria-label="Вычитание">
                                −
                            </button>

                            <button type="submit"
                                    name="operation"
                                    value="multiply"
                                    class="operation-button"
                                    title="Умножение"
                                    aria-label="Умножение">
                                ×
                            </button>

                            <button type="submit"
                                    name="operation"
                                    value="divide"
                                    class="operation-button"
                                    title="Деление"
                                    aria-label="Деление">
                                ÷
                            </button>

                        </div>

                    </form>

                    <div class="result-box">

                        <span class="result-label">Результат вычисления</span>

                        <div class="calculator-result <?= isset($calcError) ? 'result-error' : '' ?>"><?= h($calcError ?? $calcResult ?? '0') ?></div>

                    </div>

                </section>

                <!-- Задание 11 -->
                <section class="task-card" id="task-11">

                    <div class="task-heading">
                        <span class="task-number">11</span>

                        <div>
                            <h2>Загрузка файлов</h2>
                        </div>
                    </div>

                    <form method="post"
                          action="#task-11"
                          enctype="multipart/form-data"
                          class="task-form">

                        <input type="hidden" name="task" value="upload">

                        <div class="upload-area">

                            <div class="upload-icon">↑</div>

                            <h3>Выберите файл</h3>

                            <input type="file"
                                   id="upload-file"
                                   name="file"
                                   aria-label="Выбрать файл"
                                   required>

                        </div>

                        <button type="submit" class="button button-primary">
                            Загрузить на сервер
                        </button>

                    </form>

                    <div class="result-box">

                        <span class="result-label">Статус загрузки</span>

                        <div class="result-value <?= isset($uploadError) ? 'result-error' : '' ?>"><?= h($uploadError ?? $uploadMessage ?? 'Файл ещё не загружен') ?></div>

                    </div>

                </section>

            </div>

            <!-- Дополнительное задание -->
            <section class="gallery-banner">

                <div class="gallery-banner-content">

                    <span class="welcome-label">
                        ДОПОЛНИТЕЛЬНОЕ ЗАДАНИЕ
                    </span>

                    <h2>Фотогалерея</h2>

                    <a href="gallery.php" class="button button-light">
                        Открыть фотогалерею
                    </a>

                </div>

                <div class="gallery-decoration" aria-hidden="true">
                    <div class="photo-shape photo-shape-one"></div>
                    <div class="photo-shape photo-shape-two"></div>
                    <div class="photo-shape photo-shape-three"></div>
                </div>

            </section>

        </main>

    </div>

</div>

</body>
</html>