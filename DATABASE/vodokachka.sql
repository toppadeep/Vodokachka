-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 31 2023 г., 13:50
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `vodokachka`
--

-- --------------------------------------------------------

--
-- Структура таблицы `bills`
--

CREATE TABLE `bills` (
  `id` bigint UNSIGNED NOT NULL,
  `resident_id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `amount_rub` double(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `bills`
--

INSERT INTO `bills` (`id`, `resident_id`, `period_id`, `amount_rub`) VALUES
(1, 1, 1, 988.46),
(2, 1, 2, 809.21),
(3, 1, 3, 860.57),
(4, 1, 4, 1196.27),
(5, 1, 5, 1362.38),
(6, 1, 6, 1109.81),
(7, 1, 7, 1422.84),
(8, 1, 8, 954.13),
(9, 1, 9, 907.09),
(10, 1, 10, 893.69),
(11, 1, 11, 1139.45),
(12, 1, 12, 1058.31),
(13, 4, 12, 1587.15),
(14, 3, 12, 1058.31);

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '1930_01_01_000010_create_residents_table', 1),
(2, '1930_01_01_000020_create_periods_table', 1),
(3, '1930_01_01_000030_create_pump_meter_records_table', 1),
(4, '1930_01_01_000040_create_bills_table', 1),
(5, '2014_10_12_000000_create_users_table', 1),
(6, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(7, '2019_08_19_000000_create_failed_jobs_table', 1),
(8, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(9, '2023_12_25_154901_create_rates_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `periods`
--

CREATE TABLE `periods` (
  `id` bigint UNSIGNED NOT NULL,
  `begin_date` timestamp NOT NULL,
  `end_date` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `periods`
--

INSERT INTO `periods` (`id`, `begin_date`, `end_date`) VALUES
(1, '2022-12-31 21:00:00', '2023-01-31 20:59:00'),
(2, '2023-01-31 21:00:00', '2023-02-28 20:59:00'),
(3, '2023-02-28 21:00:00', '2023-03-31 20:59:00'),
(4, '2023-03-31 21:00:00', '2023-04-30 20:59:00'),
(5, '2023-04-30 21:00:00', '2023-05-31 20:59:00'),
(6, '2023-05-31 21:00:00', '2023-06-30 20:59:00'),
(7, '2023-06-30 21:00:00', '2023-07-31 20:59:00'),
(8, '2023-07-31 21:00:00', '2023-08-31 20:59:00'),
(9, '2023-08-31 21:00:00', '2023-09-30 20:59:00'),
(10, '2023-09-30 21:00:00', '2023-10-31 20:59:00'),
(11, '2023-10-31 21:00:00', '2023-11-30 20:59:00'),
(12, '2023-11-30 21:00:00', '2023-11-30 20:59:00'),
(13, '2023-12-31 21:00:00', '2023-12-31 20:59:00');

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `pump_meter_records`
--

CREATE TABLE `pump_meter_records` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `amount_volume` double(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `pump_meter_records`
--

INSERT INTO `pump_meter_records` (`id`, `period_id`, `amount_volume`) VALUES
(1, 1, 42.03),
(2, 2, 39.44),
(3, 3, 36.00),
(4, 4, 50.05),
(5, 5, 57.00),
(6, 6, 47.00),
(7, 7, 60.50),
(8, 8, 40.57),
(9, 9, 38.57),
(10, 10, 38.00),
(11, 11, 48.45),
(12, 12, 45.00);

-- --------------------------------------------------------

--
-- Структура таблицы `rates`
--

CREATE TABLE `rates` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED NOT NULL,
  `amount_price` double(8,2) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `rates`
--

INSERT INTO `rates` (`id`, `period_id`, `amount_price`, `create_date`) VALUES
(1, 1, 141.08, '2023-12-31 09:42:28'),
(2, 2, 123.08, '2023-12-31 09:42:41'),
(3, 3, 143.40, '2023-12-31 09:42:51'),
(5, 4, 143.38, '2023-12-31 09:43:22'),
(6, 5, 143.38, '2023-12-31 09:43:59'),
(7, 6, 141.65, '2023-12-31 09:44:30'),
(8, 7, 141.08, '2023-12-31 09:44:56'),
(9, 8, 141.08, '2023-12-31 09:45:02'),
(10, 9, 141.08, '2023-12-31 09:45:06'),
(11, 10, 141.08, '2023-12-31 09:45:10'),
(12, 11, 141.08, '2023-12-31 09:45:14'),
(13, 12, 141.08, '2023-12-31 09:45:18'),
(14, 13, 142.08, '2023-12-31 09:45:26');

-- --------------------------------------------------------

--
-- Структура таблицы `residents`
--

CREATE TABLE `residents` (
  `id` bigint UNSIGNED NOT NULL,
  `fio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` double(8,2) NOT NULL,
  `start_date` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `residents`
--

INSERT INTO `residents` (`id`, `fio`, `area`, `start_date`) VALUES
(1, 'Иванов Павел Васильевич', 34.00, '2023-01-06 13:31:00'),
(2, 'Шастун Ольга Николаевна', 44.00, '2023-02-18 13:31:00'),
(3, 'Михайлов Вадим Викторович', 34.00, '2023-12-14 13:32:00'),
(4, 'Михайлова Светлана Викторовна', 54.00, '2023-12-01 13:32:00'),
(5, 'Иванов Павел Альбертович', 2.00, '2023-12-07 13:32:00'),
(6, 'Иванов Алексей Альбертович', 44.00, '2023-12-03 13:33:00'),
(7, 'Миколян Евгений Сергеевич', 4.00, '2023-12-10 13:33:00');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bills_resident_id_period_id_unique` (`resident_id`,`period_id`),
  ADD KEY `bills_period_id_foreign` (`period_id`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `periods`
--
ALTER TABLE `periods`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `pump_meter_records`
--
ALTER TABLE `pump_meter_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pump_meter_records_period_id_unique` (`period_id`);

--
-- Индексы таблицы `rates`
--
ALTER TABLE `rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rates_period_id_unique` (`period_id`);

--
-- Индексы таблицы `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `periods`
--
ALTER TABLE `periods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `pump_meter_records`
--
ALTER TABLE `pump_meter_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `rates`
--
ALTER TABLE `rates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `residents`
--
ALTER TABLE `residents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`),
  ADD CONSTRAINT `bills_resident_id_foreign` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`);

--
-- Ограничения внешнего ключа таблицы `pump_meter_records`
--
ALTER TABLE `pump_meter_records`
  ADD CONSTRAINT `pump_meter_records_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`);

--
-- Ограничения внешнего ключа таблицы `rates`
--
ALTER TABLE `rates`
  ADD CONSTRAINT `rates_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
