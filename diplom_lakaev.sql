-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Май 12 2026 г., 21:42
-- Версия сервера: 8.0.45-0ubuntu0.22.04.1
-- Версия PHP: 8.1.2-1ubuntu2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `diplom_lakaev`
--

-- --------------------------------------------------------

--
-- Структура таблицы `elo_history`
--

CREATE TABLE `elo_history` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `game_id` int DEFAULT NULL,
  `elo_before` int NOT NULL,
  `elo_after` int NOT NULL,
  `change` int NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'game',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `elo_history`
--

INSERT INTO `elo_history` (`id`, `user_id`, `game_id`, `elo_before`, `elo_after`, `change`, `reason`, `created_at`) VALUES
(1, 3, NULL, 1000, 1026, 26, 'game', '2026-04-24 14:32:10'),
(2, 6, NULL, 1256, 1230, -26, 'game', '2026-04-24 14:32:10'),
(3, 3, NULL, 1026, 1050, 24, 'game', '2026-04-24 14:32:10'),
(4, 6, NULL, 1230, 1206, -24, 'game', '2026-04-24 14:32:10'),
(5, 6, NULL, 1206, 1215, 9, 'game', '2026-04-27 13:59:08'),
(6, 3, NULL, 1050, 1041, -9, 'game', '2026-04-27 13:59:08'),
(7, 6, NULL, 1215, 1208, -7, 'game', '2026-04-27 14:01:11'),
(8, 3, NULL, 1041, 1048, 7, 'game', '2026-04-27 14:01:11'),
(9, 3, NULL, 1048, 1078, 30, 'game', '2026-05-01 13:59:31'),
(10, 5, NULL, 1523, 1493, -30, 'game', '2026-05-01 13:59:31'),
(11, 5, NULL, 1493, 1480, -13, 'game', '2026-05-01 14:19:22'),
(12, 3, NULL, 1078, 1091, 13, 'game', '2026-05-01 14:19:22'),
(13, 3, NULL, 1091, 1088, -3, 'game', '2026-05-02 13:30:36'),
(14, 5, NULL, 1480, 1483, 3, 'game', '2026-05-02 13:30:36'),
(15, 5, 1, 1483, 1486, 3, 'game', '2026-05-02 13:50:07'),
(16, 3, 1, 1088, 1085, -3, 'game', '2026-05-02 13:50:07'),
(17, 3, 2, 1085, 1114, 29, 'game', '2026-05-02 14:08:12'),
(18, 5, 2, 1486, 1457, -29, 'game', '2026-05-02 14:08:12'),
(19, 5, 3, 1457, 1461, 4, 'game', '2026-05-02 14:16:51'),
(20, 3, 3, 1114, 1110, -4, 'game', '2026-05-02 14:16:51'),
(21, 3, 4, 1110, 1138, 28, 'game', '2026-05-02 14:18:59'),
(22, 5, 4, 1461, 1433, -28, 'game', '2026-05-02 14:18:59'),
(23, 3, 5, 1138, 1149, 11, 'game', '2026-05-04 13:16:58'),
(24, 5, 5, 1433, 1422, -11, 'game', '2026-05-04 13:16:58'),
(25, 5, 6, 1422, 1428, 6, 'game', '2026-05-04 13:39:03'),
(26, 3, 6, 1149, 1143, -6, 'game', '2026-05-04 13:39:03');

-- --------------------------------------------------------

--
-- Структура таблицы `game`
--

CREATE TABLE `game` (
  `id` int NOT NULL,
  `white_user_id` int NOT NULL,
  `black_user_id` int DEFAULT NULL,
  `tournament_id` int DEFAULT NULL,
  `status` enum('pending','active','finished','draw','white_win','black_win') DEFAULT 'pending',
  `current_fen` varchar(100) DEFAULT NULL,
  `last_move_at` datetime DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `finished_at` datetime DEFAULT NULL,
  `winner_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `game`
--

INSERT INTO `game` (`id`, `white_user_id`, `black_user_id`, `tournament_id`, `status`, `current_fen`, `last_move_at`, `started_at`, `finished_at`, `winner_id`) VALUES
(3, 5, 3, NULL, '', 'rnbqkbnr/ppppp2p/5p2/6pQ/3PP3/8/PPP2PPP/RNB1KBNR b KQkq - 1 3', NULL, '2026-05-02 11:16:08', '2026-05-02 11:16:51', 5),
(4, 3, 5, NULL, '', 'rnbqkbnr/ppppp2p/5p2/6pQ/3PP3/8/PPP2PPP/RNB1KBNR b KQkq - 1 3', NULL, '2026-05-02 11:18:25', '2026-05-02 11:18:59', 3),
(5, 3, 5, NULL, 'draw', 'rnb1kbnr/ppp1pppp/8/3q4/8/8/PP1PPPPP/RNBQKBNR w KQkq - 8 7', NULL, '2026-05-04 10:13:55', '2026-05-04 10:16:57', NULL),
(6, 5, 3, NULL, '', 'rnbqkbnr/ppppp2p/5p2/6pQ/3PP3/8/PPP2PPP/RNB1KBNR b KQkq - 1 3', NULL, '2026-05-04 10:37:27', '2026-05-04 10:39:02', 5),
(7, 7, 3, NULL, 'white_win', 'r2qk2r/pppnbQpp/3p1n2/4p1N1/3P2b1/2P1P3/PP3PPP/RNB1KB1R b KQkq - 0 7', '2026-05-12 16:12:01', '2026-05-12 16:08:45', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `gamemode`
--

CREATE TABLE `gamemode` (
  `id` int NOT NULL,
  `name` varchar(80) NOT NULL,
  `control_time` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `gamemode`
--

INSERT INTO `gamemode` (`id`, `name`, `control_time`) VALUES
(1, 'Классика - 90 минут + 30 секунд на ход', '90 минут + 30 секунд на ход'),
(2, 'Классика - 60 минут + 30 секунд на ход', '60 минут + 30 секунд на ход'),
(3, 'Классика - 2 часа на партию', '2 часа на партию'),
(4, 'Рапид - 15 минут + 10 секунд на ход', '15 минут + 10 секунд на ход'),
(5, 'Рапид - 15 минут на партию', '15 минут на партию'),
(6, 'Рапид - 10 минут + 5 секунд на ход', '10 минут + 5 секунд на ход'),
(7, 'Рапид - 10 минут на партию', '10 минут на партию'),
(8, 'Блиц - 5 минут + 3 секунды на ход', '5 минут + 3 секунды на ход'),
(9, 'Блиц - 5 минут на партию', '5 минут на партию'),
(10, 'Блиц - 3 минуты + 2 секунды на ход', '3 минуты + 2 секунды на ход'),
(11, 'Блиц - 3 минуты на партию', '3 минуты на партию'),
(12, 'Пуля - 2 минуты + 1 секунда на ход', '2 минуты + 1 секунда на ход'),
(13, 'Пуля - 2 минуты на партию', '2 минуты на партию'),
(14, 'Пуля - 1 минута + 1 секунда на ход', '1 минута + 1 секунда на ход'),
(15, 'Пуля - 1 минута на партию', '1 минута на партию');

-- --------------------------------------------------------

--
-- Структура таблицы `level`
--

CREATE TABLE `level` (
  `id` int NOT NULL,
  `name` varchar(85) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `level`
--

INSERT INTO `level` (`id`, `name`) VALUES
(1, 'Международный уровень'),
(2, 'Федеральный уровень'),
(3, 'Муниципальный уровень'),
(4, 'Региональный уровень'),
(5, 'Уровень учреждения');

-- --------------------------------------------------------

--
-- Структура таблицы `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8mb4_general_ci NOT NULL,
  `apply_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1776935225),
('m250509_065639_add_status_to_planning', 1776935233),
('m260423_090104_create_tournament_matches_table', 1776935234),
('m260423_090437_create_tournament_byes_table', 1776935234),
('m260424_090737_create_elo_history_table', 1777021737);

-- --------------------------------------------------------

--
-- Структура таблицы `move`
--

CREATE TABLE `move` (
  `id` int NOT NULL,
  `game_id` int NOT NULL,
  `user_id` int NOT NULL,
  `move_number` int NOT NULL,
  `move_san` varchar(10) NOT NULL,
  `move_fen` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `move`
--

INSERT INTO `move` (`id`, `game_id`, `user_id`, `move_number`, `move_san`, `move_fen`, `created_at`) VALUES
(1, 7, 7, 1, 'd4', 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq d3 0 1', '2026-05-12 16:08:56'),
(2, 7, 3, 2, 'Nf6', 'rnbqkb1r/pppppppp/5n2/8/3P4/8/PPP1PPPP/RNBQKBNR w KQkq - 1 2', '2026-05-12 16:09:12'),
(3, 7, 7, 3, 'c3', 'rnbqkb1r/pppppppp/5n2/8/3P4/2P5/PP2PPPP/RNBQKBNR b KQkq - 0 2', '2026-05-12 16:09:42'),
(4, 7, 3, 4, 'd6', 'rnbqkb1r/ppp1pppp/3p1n2/8/3P4/2P5/PP2PPPP/RNBQKBNR w KQkq - 0 3', '2026-05-12 16:09:57'),
(5, 7, 7, 5, 'Qb3', 'rnbqkb1r/ppp1pppp/3p1n2/8/3P4/1QP5/PP2PPPP/RNB1KBNR b KQkq - 1 3', '2026-05-12 16:10:23'),
(6, 7, 3, 6, 'Bg4', 'rn1qkb1r/ppp1pppp/3p1n2/8/3P2b1/1QP5/PP2PPPP/RNB1KBNR w KQkq - 2 4', '2026-05-12 16:10:49'),
(7, 7, 7, 7, 'Nf3', 'rn1qkb1r/ppp1pppp/3p1n2/8/3P2b1/1QP2N2/PP2PPPP/RNB1KB1R b KQkq - 3 4', '2026-05-12 16:10:55'),
(8, 7, 3, 8, 'e5', 'rn1qkb1r/ppp2ppp/3p1n2/4p3/3P2b1/1QP2N2/PP2PPPP/RNB1KB1R w KQkq e6 0 5', '2026-05-12 16:11:08'),
(9, 7, 7, 9, 'e3', 'rn1qkb1r/ppp2ppp/3p1n2/4p3/3P2b1/1QP1PN2/PP3PPP/RNB1KB1R b KQkq - 0 5', '2026-05-12 16:11:22'),
(10, 7, 3, 10, 'Nbd7', 'r2qkb1r/pppn1ppp/3p1n2/4p3/3P2b1/1QP1PN2/PP3PPP/RNB1KB1R w KQkq - 1 6', '2026-05-12 16:11:35'),
(11, 7, 7, 11, 'Ng5', 'r2qkb1r/pppn1ppp/3p1n2/4p1N1/3P2b1/1QP1P3/PP3PPP/RNB1KB1R b KQkq - 2 6', '2026-05-12 16:11:43'),
(12, 7, 3, 12, 'Be7', 'r2qk2r/pppnbppp/3p1n2/4p1N1/3P2b1/1QP1P3/PP3PPP/RNB1KB1R w KQkq - 3 7', '2026-05-12 16:11:54'),
(13, 7, 7, 13, 'Qxf7#', 'r2qk2r/pppnbQpp/3p1n2/4p1N1/3P2b1/2P1P3/PP3PPP/RNB1KB1R b KQkq - 0 7', '2026-05-12 16:12:01');

-- --------------------------------------------------------

--
-- Структура таблицы `planning`
--

CREATE TABLE `planning` (
  `id` int NOT NULL,
  `content` varchar(255) NOT NULL,
  `organizer` varchar(85) DEFAULT NULL,
  `user_id` int NOT NULL,
  `gamemode_id` int NOT NULL,
  `imageFile` varchar(255) NOT NULL,
  `quantity_rounds` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` tinyint DEFAULT '0' COMMENT '0-pending, 1-rejected, 2-approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `region`
--

CREATE TABLE `region` (
  `id` int NOT NULL,
  `name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `region`
--

INSERT INTO `region` (`id`, `name`) VALUES
(1, 'Республика Адыгея'),
(2, 'Республика Алтай'),
(3, 'Республика Башкортостан'),
(4, 'Республика Бурятия'),
(5, 'Республика Дагестан'),
(6, 'Республика Ингушетия'),
(7, 'Кабардино-Балкарская Республика'),
(8, 'Республика Калмыкия'),
(9, 'Карачаево-Черкесская Республика'),
(10, 'Республика Карелия'),
(11, 'Республика Коми'),
(12, 'Республика Крым'),
(13, 'Республика Марий Эл'),
(14, 'Республика Мордовия'),
(15, 'Республика Саха (Якутия)'),
(16, 'Республика Северная Осетия — Алания'),
(17, 'Республика Татарстан'),
(18, 'Республика Тыва'),
(19, 'Удмуртская Республика'),
(20, 'Республика Хакасия'),
(21, 'Чеченская Республика'),
(22, 'Чувашская Республика'),
(23, 'Донецкая Народная Республика'),
(24, 'Луганская Народная Республика'),
(25, 'Алтайский край'),
(26, 'Забайкальский край'),
(27, 'Камчатский край'),
(28, 'Краснодарский край'),
(29, 'Красноярский край'),
(30, 'Пермский край'),
(31, 'Приморский край'),
(32, 'Ставропольский край'),
(33, 'Хабаровский край'),
(34, 'Амурская область'),
(35, 'Архангельская область'),
(36, 'Астраханская область'),
(37, 'Белгородская область'),
(38, 'Брянская область'),
(39, 'Владимирская область'),
(40, 'Волгоградская область'),
(41, 'Вологодская область'),
(42, 'Воронежская область'),
(43, 'Запорожская область'),
(44, 'Ивановская область'),
(45, 'Иркутская область'),
(46, 'Калининградская область'),
(47, 'Калужская область'),
(48, 'Кемеровская область'),
(49, 'Кировская область'),
(50, 'Костромская область'),
(51, 'Курганская область'),
(52, 'Курская область'),
(53, 'Ленинградская область'),
(54, 'Липецкая область'),
(55, 'Магаданская область'),
(56, 'Московская область'),
(57, 'Мурманская область'),
(58, 'Нижегородская область'),
(59, 'Новгородская область'),
(60, 'Новосибирская область'),
(61, 'Омская область'),
(62, 'Оренбургская область'),
(63, 'Орловская область'),
(64, 'Пензенская область'),
(65, 'Псковская область'),
(66, 'Ростовская область'),
(67, 'Рязанская область'),
(68, 'Самарская область'),
(69, 'Саратовская область'),
(70, 'Сахалинская область'),
(71, 'Свердловская область'),
(72, 'Смоленская область'),
(73, 'Тамбовская область'),
(74, 'Тверская область'),
(75, 'Томская область'),
(76, 'Тульская область'),
(77, 'Тюменская область'),
(78, 'Ульяновская область'),
(79, 'Херсонская область'),
(80, 'Челябинская область'),
(81, 'Ярославская область'),
(82, 'Москва'),
(83, 'Санкт-Петербург'),
(84, 'Севастополь'),
(85, 'Еврейская автономная область'),
(86, 'Ненецкий автономный округ'),
(87, 'Ханты-Мансийский автономный округ - Югра'),
(88, 'Чукотский автономный округ'),
(89, 'Ямало-Ненецкий автономный округ');

-- --------------------------------------------------------

--
-- Структура таблицы `reg_to_tournament`
--

CREATE TABLE `reg_to_tournament` (
  `id` int NOT NULL,
  `tournament_id` int NOT NULL,
  `user_id` int NOT NULL,
  `registration_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `reg_to_tournament`
--

INSERT INTO `reg_to_tournament` (`id`, `tournament_id`, `user_id`, `registration_date`) VALUES
(1, 1, 2, '2026-04-23 15:05:32'),
(2, 1, 3, '2026-04-23 15:09:26'),
(3, 1, 5, '2026-04-23 15:13:34'),
(4, 1, 6, '2026-04-23 15:17:18');

-- --------------------------------------------------------

--
-- Структура таблицы `tournament`
--

CREATE TABLE `tournament` (
  `id` int NOT NULL,
  `img` varchar(255) NOT NULL,
  `name` varchar(90) NOT NULL,
  `description` varchar(255) NOT NULL,
  `gamemode_id` int NOT NULL,
  `location` varchar(90) NOT NULL,
  `quantity_rounds` int NOT NULL,
  `status` enum('Запланирован','В процессе','Завершен') DEFAULT 'Запланирован',
  `level_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `tournament`
--

INSERT INTO `tournament` (`id`, `img`, `name`, `description`, `gamemode_id`, `location`, `quantity_rounds`, `status`, `level_id`) VALUES
(1, '2026-05-08_10-32-59.png', 'Турнир для теста', 'Тестовый турнир 1', 2, 'Москва', 7, 'Запланирован', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `tournament_byes`
--

CREATE TABLE `tournament_byes` (
  `id` int NOT NULL,
  `tournament_id` int NOT NULL,
  `user_id` int NOT NULL,
  `round` int NOT NULL,
  `points` decimal(3,1) DEFAULT '1.0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `tournament_matches`
--

CREATE TABLE `tournament_matches` (
  `id` int NOT NULL,
  `tournament_id` int NOT NULL,
  `round` int NOT NULL,
  `white_player_id` int NOT NULL,
  `black_player_id` int NOT NULL,
  `result` enum('pending','white_win','black_win','draw') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `winner_id` int DEFAULT NULL,
  `status` enum('pending','played') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `played_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `elo` int DEFAULT '1000',
  `role` int NOT NULL DEFAULT '0',
  `region_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `first_name`, `last_name`, `email`, `password`, `elo`, `role`, `region_id`, `created_at`, `updated_at`) VALUES
(2, 'DimaChesser123', 'Дмитрий', 'Лакаев', 'dimalakaev@gmail.com', '$2y$13$namQl5vbn8nIzO5l6VGRi.mZVJXSqzSwjc9QupQxHQQ8Jw2hTaRSW', 1409, 0, 51, '2026-04-20 12:54:53', '2026-04-20 12:54:53'),
(3, 'EgorChesser1778', 'Егорка', 'Рогов', 'er@gmail.ru', '$2y$13$PwiM.1G0z4IBqwzyp05EIO5V4.OBTg8lR1JBpPX5N2GhCPJFLmaDW', 1143, 0, 16, '2026-04-20 14:06:59', '2026-05-12 21:21:49'),
(4, 'AdminChess', 'Администратор', 'Администраторов', 'ad@gmail.com', '$2y$13$0GG3Qw6bzv32vJLoyP0N5Ow3kTN2DL6Kx2Z90sQ6jiMmtO6peN4AO', 1000, 1, 51, '2026-04-23 14:34:21', '2026-04-23 14:34:21'),
(5, 'TestUser123', 'Тестовик', 'Тестовый', 'tests123@mail.ru', '$2y$13$PdqQuGiGcUElVnQ.gp4Ncu.cAdpq02zUujYctTYNqFUQNTf0IhUBi', 1428, 0, 6, '2026-04-23 15:12:37', '2026-05-08 11:40:57'),
(6, 'TestUser456', 'Проверка', 'Проверочная', 'test456@gmail.com', '$2y$13$J.swj25iR60/zb7aJdqwNe1xKDpiW7/c84R9ogl6Y3GPRmepr1dwi', 1208, 0, 19, '2026-04-23 15:16:19', '2026-04-27 14:01:11'),
(7, 'ChessMaster52', 'Питер', 'Паркер', 'dl@gmail.com', '$2y$13$Fm2/9awAf/EZZRlUncSui.lcMVhSj9JuPrp5wzON.HeTp9mlI3UmW', 1000, 0, 51, '2026-05-09 15:33:55', '2026-05-09 15:35:54');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `elo_history`
--
ALTER TABLE `elo_history`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-game-white_user_id` (`white_user_id`),
  ADD KEY `idx-game-black_user_id` (`black_user_id`),
  ADD KEY `idx-game-winner_id` (`winner_id`),
  ADD KEY `idx-game-tournament_id` (`tournament_id`);

--
-- Индексы таблицы `gamemode`
--
ALTER TABLE `gamemode`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `move`
--
ALTER TABLE `move`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-move-game_id` (`game_id`),
  ADD KEY `idx-move-user_id` (`user_id`);

--
-- Индексы таблицы `planning`
--
ALTER TABLE `planning`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-planning-user_id` (`user_id`),
  ADD KEY `idx-planning-gamemode_id` (`gamemode_id`);

--
-- Индексы таблицы `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reg_to_tournament`
--
ALTER TABLE `reg_to_tournament`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_registration` (`tournament_id`,`user_id`),
  ADD KEY `idx-reg_to_tournament-tournament_id` (`tournament_id`),
  ADD KEY `idx-reg_to_tournament-user_id` (`user_id`);

--
-- Индексы таблицы `tournament`
--
ALTER TABLE `tournament`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-tournament-gamemode_id` (`gamemode_id`),
  ADD KEY `idx-tournament-level_id` (`level_id`);

--
-- Индексы таблицы `tournament_byes`
--
ALTER TABLE `tournament_byes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-tb-tournament` (`tournament_id`),
  ADD KEY `fk-tb-user` (`user_id`);

--
-- Индексы таблицы `tournament_matches`
--
ALTER TABLE `tournament_matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx-tm-tournament` (`tournament_id`),
  ADD KEY `idx-tm-round` (`round`),
  ADD KEY `fk-tm-white` (`white_player_id`),
  ADD KEY `fk-tm-black` (`black_player_id`),
  ADD KEY `fk-tm-winner` (`winner_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk-user-region_id` (`region_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `elo_history`
--
ALTER TABLE `elo_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT для таблицы `game`
--
ALTER TABLE `game`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `gamemode`
--
ALTER TABLE `gamemode`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `level`
--
ALTER TABLE `level`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `move`
--
ALTER TABLE `move`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT для таблицы `planning`
--
ALTER TABLE `planning`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `region`
--
ALTER TABLE `region`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT для таблицы `reg_to_tournament`
--
ALTER TABLE `reg_to_tournament`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `tournament`
--
ALTER TABLE `tournament`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `tournament_byes`
--
ALTER TABLE `tournament_byes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `tournament_matches`
--
ALTER TABLE `tournament_matches`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `fk-game-black_user_id` FOREIGN KEY (`black_user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-game-tournament_id` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-game-white_user_id` FOREIGN KEY (`white_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-game-winner_id` FOREIGN KEY (`winner_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `move`
--
ALTER TABLE `move`
  ADD CONSTRAINT `fk-move-game_id` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-move-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `planning`
--
ALTER TABLE `planning`
  ADD CONSTRAINT `fk-planning-gamemode_id` FOREIGN KEY (`gamemode_id`) REFERENCES `gamemode` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-planning-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `reg_to_tournament`
--
ALTER TABLE `reg_to_tournament`
  ADD CONSTRAINT `fk-reg_to_tournament-tournament_id` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-reg_to_tournament-user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `fk-tournament-gamemode_id` FOREIGN KEY (`gamemode_id`) REFERENCES `gamemode` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk-tournament-level_id` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tournament_byes`
--
ALTER TABLE `tournament_byes`
  ADD CONSTRAINT `fk-tb-tournament` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-tb-user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tournament_matches`
--
ALTER TABLE `tournament_matches`
  ADD CONSTRAINT `fk-tm-black` FOREIGN KEY (`black_player_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-tm-tournament` FOREIGN KEY (`tournament_id`) REFERENCES `tournament` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-tm-white` FOREIGN KEY (`white_player_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk-tm-winner` FOREIGN KEY (`winner_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk-user-region_id` FOREIGN KEY (`region_id`) REFERENCES `region` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
