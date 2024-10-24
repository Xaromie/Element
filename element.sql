-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 07, 2023 at 06:20 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `element`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `ID` int NOT NULL,
  `Name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Username` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `S_KEY` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'None',
  `Avatar` varchar(600) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'None',
  `Cover` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'None',
  `Description` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Subscribers` int NOT NULL DEFAULT '0',
  `Subscriptions` int NOT NULL DEFAULT '0',
  `Posts` int NOT NULL DEFAULT '0',
  `Status` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'None',
  `Theme` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Default',
  `CreateDate` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `ID` int NOT NULL,
  `Verify` int NOT NULL DEFAULT '0',
  `UserID_1` int NOT NULL,
  `UserID_2` int NOT NULL,
  `LastMessage` text,
  `LastMessage_Date` varchar(150) DEFAULT NULL,
  `CreateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `chat_message`
--

CREATE TABLE `chat_message` (
  `ID` int NOT NULL,
  `From` int NOT NULL,
  `For` int NOT NULL,
  `Message` varchar(700) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Viewed` int NOT NULL DEFAULT '0',
  `Date` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `ID` int UNSIGNED NOT NULL,
  `User` int NOT NULL,
  `Post` int NOT NULL,
  `Text` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Date` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `icons`
--

CREATE TABLE `icons` (
  `ID` int UNSIGNED NOT NULL,
  `UserID` int NOT NULL,
  `IconID` varchar(100) NOT NULL,
  `Date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `PostID` int UNSIGNED NOT NULL,
  `UserID` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Type` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Text',
  `Text` varchar(700) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `Likes` int NOT NULL DEFAULT '0',
  `Dislikes` int DEFAULT '0',
  `Comments` int NOT NULL DEFAULT '0',
  `Date` varchar(70) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_dislikes`
--

CREATE TABLE `post_dislikes` (
  `ID` int UNSIGNED NOT NULL,
  `PostID` int NOT NULL,
  `UserID` int NOT NULL,
  `Date` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `ID` int UNSIGNED NOT NULL,
  `PostID` int NOT NULL,
  `UserID` int NOT NULL,
  `Date` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `ID` int UNSIGNED NOT NULL,
  `User` int NOT NULL,
  `ToUser` int NOT NULL,
  `Date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `subs_gold`
--

CREATE TABLE `subs_gold` (
  `ID` int UNSIGNED NOT NULL,
  `UserID` varchar(100) NOT NULL,
  `Status` varchar(100) NOT NULL DEFAULT 'Active',
  `Received` varchar(100) NOT NULL,
  `Date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `sub_keys`
--

CREATE TABLE `sub_keys` (
  `ID` int UNSIGNED NOT NULL,
  `Key` varchar(400) NOT NULL,
  `Activated` varchar(100) DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE `updates` (
  `ID` int UNSIGNED NOT NULL,
  `Type` varchar(150) NOT NULL,
  `Version` varchar(150) NOT NULL,
  `Content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `updates`
--

INSERT INTO `updates` (`ID`, `Type`, `Version`, `Content`) VALUES
(1, 'Release', '0.1', '• Добавлен месенджер.\n• Улучшение безопасности.\n• Изменён шрифт.\n• Не большие изменения в интерфейсе.\n• Оптимизация и удаление лишних элементов.'),
(2, 'Release', '0.2', '• Малозаметные изменения.\r\n• Добавлена страница \"Настройки\".\r\n• Добавлен выбор устройства для адаптации контента.\r\n• Адаптация чата под телефоны.\r\n• Улучшение защиты.'),
(3, 'Release', '0.3', '• Теперь при выходе с сайта сессия остаётся.\r\n• Переработан вход в аккаунт и регистрация.\r\n• Теперь для регистрации нужно использовать настоящую почту.\r\n• Добавлена кнопка \"Показать больше\".\r\n• Исправление багов.\r\n• Улучшение чата.\r\n• Улучшение интерфеса.'),
(4, 'Release', '0.4', '• Улучшение защиты.\r\n• Посты теперь нужно публиковать раз в три минуты.'),
(5, 'Release', '0.4.1', '• Лайки и дизлайки работают более стабильно.\r\n• Улучшение защиты.\r\n• Исправление багов.'),
(6, 'Release', '0.5', '• Добавлены комментарии.\r\n• Улучшение интерфейса.\r\n• Исправление багов.\r\n• Теперь в уникальном нике нельзя указывать символы, к примеру \"/, $, &\".'),
(7, 'Release', '0.6', '• Теперь к постам можно прикрепить изображение.\r\n• Добавлена страница \"Информация\".\r\n• Переписана система вывода постов.\r\n• Улучшение защиты.\r\n• Улучшение интерфейса.\r\n• Улучшение модерации.\r\n• Исправление багов.'),
(8, 'Release', '0.7', '• Новый интерфейс.\r\n• Добавлена тёмная тема.\r\n• При написании поста теперь строка расширяется в зависимости от количества текста.\r\n• Письма подтверждения теперь нормальные.\r\n• Исправление багов.\r\n• Оптимизация.'),
(9, 'Release', '0.8', '• Рабочий поиск.\r\n• Добавлена информация о профиле.\r\n• Теперь если у вас нет аватара, будет показыватся первая буква вашего имени.\r\n• Оптимизация.'),
(10, 'Release', '0.9', '• Добавлена подписка Gold.\n• Добавлена мобильная навигация.\n• Обновлён список разрешенных почт для создания аккаунта.\n• Ускорены анимации.\n• Улучшение интерфейса.\n• Исправление бвгов.'),
(11, 'Release', '1.0', 'Для Gold-пользователей:\r\n• Теперь можно добавить описание к профилю.\r\n• Теперь можно отключить золотую тему.\r\nДля всех:\r\n• Улучшение настроек, а именно - теперь можно изменить имя или же удалить аватарку.\r\n• Сайт стал работать более плавно, так же была изменена струкура сайта.\r\n• Система тем была переписана.\r\n• Лента постов теперь работает адекватно.\r\n• Теперь мы не собираем данные об устройстве.\r\n• Улучшение интерфейса.\r\n• Исправление багов.'),
(12, 'Beta', '1.0.1', '• В URL больше не используется кирилица.\n• Изменены шрифты.\n• Улучшение интерфейса.\n• Улучшение адаптации интерфейса.\n• Изменена анимация \"Поделиться\".\n• Изменена анимация при навидении на почту в настройках.'),
(13, 'Beta', '1.0.2', '• Теперь можно добавить обложку в профиль.\n• Вы теперь можете посмотреть профиль без аккаунта.\n• Улучшена оптимизация страницы профиля.\n• Исправление багов прошлой версии.'),
(14, 'Beta', '1.0.3', '• Улучшена сортировака чатов в мессенджере.\n• Оптимизация мессенджера.'),
(15, 'Beta', '1.0.4', '• Теперь можно удалить спам-чат.\n• Теперь можно отправить сообщение на Enter.\n• Улучшена стабильность мессенджера.\n• Исправление багов.'),
(16, 'Release', '1.1', '• Теперь можно добавить обложку к профилю.\r\n• Обновлена страница авторизации.\r\n• Описание теперь может поставить даже пользователь без Gold подписки.\r\n• В URL больше не используется кирилица.\r\n• Обновление мессенджера.\r\n• Исправление багов.\r\n• Оптимизация.\r\n• Улучшение интерфейса.'),
(17, 'Beta', '1.1.1', '• Теперь вы можете удалить свой пост.\r\n• Улучшение интерфейса.'),
(18, 'Beta', '1.1.2', '• Кнопка поделиться теперь работает везде.\r\n• Теперь можно полноценно посмотреть изображение или же скачать его.\r\n• Теперь можно использовать эмодзи и HTML символы.\r\n• Теперь список Gold пользователей есть на главной странице.\r\n• Исправление багов.\r\n• Улучшение интерфейса.'),
(19, 'Beta', '1.1.3', 'Для Gold пользователей:\r\n• Вы можете сохранить любой пост в формате EPACK.\r\nДля всех:\r\n• Можно посмотреть пост в формате EPACK в новой вкладке.\r\n• Добавлен предпросмотр функций на странице подписки.\r\n• Улучшен, и оптимизирован интерфейс.'),
(20, 'Beta', '1.1.4', '• Итак, самое важно, то чего нам всем не хватало... ТЕПЕРЬ МОЖНО ПЕРЕЙТИ НА ПРОФИЛЬ ПОЛЬЗОВАТЕЛЯ ПРИ НАЖАТИИ НА НИК В ПОСТЕ.\r\n• Добавлена анимация загрузки.\r\n• Исправлена уязвимость в EPACK.\r\n• Исправлены многие серьёзные и не очень баги.\r\n• Улучшение интерфейса.'),
(21, 'Beta', '1.1.5', '• Теперь можно подписываться на пользователей, и видеть такой контент, который вы хотите.\r\n• Теперь можно открыть фото на весь экран.\r\n• Исправлены некоторые баги.\r\n• Добавлена предзагрузка постов.\r\n• Улучшение интерфейса.'),
(22, 'Beta', '1.1.6', '• Теперь можно публиковать деликатный контент, а так же очистить метаданные при отправке файла.\r\n• Чат адаптирован под телефоны.\r\n• Теперь при выборе темы с демонстрациями преимуществ подписки Gold, адаптируется видео.\r\n• Изменены правила, подробнее на beta.elm.lol/info/rules.\r\n• Улучшение интерфейса.\r\n• Исправление багов.'),
(23, 'Release', '1.2', 'Для Gold пользователей:\r\n• Вы можете сохранить любой пост в формате EPACK.\r\nДля всех:\r\n• Добавлен предпросмотр функций на странице подписки.\r\n• Добавлена анимация загрузки некоторых элементов.\r\n• Теперь можно подписываться на пользователей, и видеть такой контент, который вы хотите.\r\n• Теперь можно публиковать деликатный контент, а так же очистить метаданные при отправке файла.\r\n• Теперь список Gold пользователей есть на главной странице.\r\n• Теперь можно использовать эмодзи и HTML символы.\r\n• Теперь можно полноценно посмотреть изображение или же скачать его.\r\n• Теперь вы можете удалить свой пост.\r\n• Теперь при выборе темы с демонстрациями преимуществ подписки Gold, адаптируется видео.\r\n• Чат адаптирован под телефоны.\r\n• Улучшен, и оптимизирован интерфейс.\r\n• Исправление багов.\r\n• Изменены правила, подробнее на elm.lol/info/rules.');

-- --------------------------------------------------------

--
-- Table structure for table `verify_email`
--

CREATE TABLE `verify_email` (
  `ID` int UNSIGNED NOT NULL,
  `Username` varchar(40) NOT NULL,
  `Name` varchar(40) NOT NULL,
  `Email` varchar(70) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Code` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `chat_message`
--
ALTER TABLE `chat_message`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `icons`
--
ALTER TABLE `icons`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`PostID`);

--
-- Indexes for table `post_dislikes`
--
ALTER TABLE `post_dislikes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `subs_gold`
--
ALTER TABLE `subs_gold`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `sub_keys`
--
ALTER TABLE `sub_keys`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `verify_email`
--
ALTER TABLE `verify_email`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_message`
--
ALTER TABLE `chat_message`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `icons`
--
ALTER TABLE `icons`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `PostID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_dislikes`
--
ALTER TABLE `post_dislikes`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subs_gold`
--
ALTER TABLE `subs_gold`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_keys`
--
ALTER TABLE `sub_keys`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `verify_email`
--
ALTER TABLE `verify_email`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
