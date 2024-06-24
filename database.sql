-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2023. Ápr 13. 17:54
-- Kiszolgáló verziója: 10.4.17-MariaDB
-- PHP verzió: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `szakdoga`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `advertisement`
--

CREATE TABLE `advertisement` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1500) NOT NULL,
  `price` int(11) NOT NULL,
  `shipping` varchar(255) NOT NULL,
  `settlement` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `advertisement`
--

INSERT INTO `advertisement` (`id`, `user_id`, `title`, `description`, `price`, `shipping`, `settlement`, `category`, `date`) VALUES
(67, 36, 'NVIDIA Geforce Rtx 3050 8GB', 'Alzából 2022.08.22.-én vásárolt, nagyon keveset használt, RTX 3050 8G videókártya, 2 év garanciával, dobozával, számlával eladó kihasználatlanság miatt. Személyesen átvehető Budapesten, de csomagküldés is megoldható. Az ár fix, csere nem jöhet szóba.', 100000, 'Csomagküldéssel is', 'Budapest', 'Videókártya', '2023-03-06 18:33:33'),
(70, 36, 'Asus PRIME B360M-C LGA1151 alaplap', 'Keveset használt, 2 éve a dobozában csücsül egyébként hibátlan állapotban', 30000, 'Csak személyes átvétellel', 'Debrecen', 'Alaplap', '2023-03-06 18:37:43'),
(71, 36, 'Viva 77182 USB A - Micro USB kábel', 'Alig használtam, eredeti csomagolásában elvihető', 5000, 'Csak személyes átvétellel', 'Sopron', 'Kábel, csatlakozó', '2023-03-06 18:40:03'),
(86, 36, 'HP EliteDesk 800 G1 MT Intel Core i5-i7-4790 8GB-16GB 256-500GB SSD 1TB HDD DVD', 'Ha üzenetet írsz kérlek add meg a Telefonszámodat és az email címed ezzel is gyorsítva az ügyintézést. Futárral tudom küldeni ,másnapra ott van! Megtekinthető és azonnal átvehető tesztelhető Vecsésen H-P: 9-18 Sz: 8-14', 200000, 'Csomagküldéssel is', 'Vecsés', 'PC, asztali számítógép', '2023-03-30 17:16:18'),
(88, 52, 'Intel i5 4460 processzor', 'Eladó a címben említett hibátlan állapotú processzor. A termékre az átvételtől számított 1 hét garanciát vállalok. Átvehető helyileg Miskolcon vagy Foxpost.', 10000, 'Csomagküldéssel is', 'Miskolc', 'Processzor', '2023-04-13 16:50:23'),
(89, 53, 'Gigabyte Geforce GTX 950 Windforce 2 GB', 'Keveset használt, már régóta a dobozában csücsül egyébként hibátlan állapotban.', 30000, 'Csak személyes átvétellel', 'Debrecen', 'Videókártya', '2023-04-13 16:57:31'),
(90, 54, 'Lenovo ThinkPad P50 i7-6820HQ/16GB/512GB NVME SSD/webcam/1920x1080/Nvidia Quadro M2000M', 'Viszonylag új, keveset használtam. Garancia van hozzá, csomagküldés is megoldható.', 100000, 'Csomagküldéssel is', 'Debrecen', 'Laptop, notebook', '2023-04-13 17:05:32'),
(91, 36, 'Gigabyte GTX950 OC (GV-N950OC-2GD)', 'Eladó egy használt, de hibátlanul működő Gigabyte GTX 950 OC. https://www.gigabyte.com/Graphics-Card/GV-N950OC-2GD#kf. Húzva sosem volt, fiam használta. Doboza már nincs meg. Nálam meg lehet nézni, ki lehet próbálni.', 15000, 'Csak személyes átvétellel', 'Budapest', 'Videókártya', '2023-04-13 17:09:13'),
(92, 36, 'Razer Viper Ultimate + Charging Dock RZ01-03050100-R3G1', 'Eladó 1 db keveset használt egér. 24 hónap garanciával. Személyesen Győrben átvehető .', 50000, 'Csak személyes átvétellel', 'Győr', 'Egyéb', '2023-04-13 17:15:51'),
(93, 36, 'LG 29\"-os 21:9 képarányú 29UM58-P monitor', 'Eladó a képeken látható, 29UM58-P típusú, hibátlanul működő 29 col képátlójú, 21:9 képarányú, 2560x1080 felbontású IPS paneles monitor. Pixelhiba mentes! Első tulajdonostól. A gyári doboza nincs már meg. Egy hét próbagaranciát tudok adni rá. Átvehető Székesfehérváron.', 50000, 'Csak személyes átvétellel', 'Székesfehérvár', 'Monitor', '2023-04-13 17:24:52'),
(94, 52, 'Geforce GTX 950 Windforce 2 GB', '2-3 évig használtam, egyébként semmi baja, hibátlanul működik. Csomagküldés megoldható a vevő terhére.', 25000, 'Csomagküldéssel is', 'Sopron', 'Videókártya', '2023-04-13 17:31:18'),
(95, 52, 'SanDisk Ultra Flair 128GB USB 3.0 Flash Drive', 'Eladó a címben említett, keveset használt pendrive. Nincs semmi rejtett hibája, a működése kifogástalan! Személyes átvétel Sopronban előre egyeztetett helyen és időpontban. Az ár fix, alku nincs!', 6000, 'Csak személyes átvétellel', 'Sopron', 'Adattároló, merevlemez', '2023-04-13 17:36:33');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `m_date` datetime NOT NULL,
  `text` varchar(300) NOT NULL,
  `seen` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `m_date`, `text`, `seen`) VALUES
(110, 54, 36, '2023-04-13 17:02:11', 'Szia!\r\n', 1),
(111, 54, 36, '2023-04-13 17:02:52', 'Szeretném megvenni az általad meghirdetett GTX 950-es kártyát ha még megvan', 1),
(112, 54, 36, '2023-04-13 17:03:04', 'Írd már meg mikor és hol vehetem át.', 1),
(113, 36, 52, '2023-04-13 17:10:23', 'Szia', 1),
(114, 36, 52, '2023-04-13 17:10:41', 'Megvan még az i-5 4460-as processzorod?', 1),
(115, 52, 36, '2023-04-13 17:11:09', 'Szia!', 1),
(116, 52, 36, '2023-04-13 17:11:21', 'Igen megvan, érdekel?', 1),
(117, 53, 52, '2023-04-13 17:12:01', 'Szia\r\n', 0),
(118, 53, 52, '2023-04-13 17:12:19', 'Érdekelne a 4460-as processzorod', 0),
(119, 1, 36, '2023-04-13 17:47:56', 'Szia!\r\n', 1),
(120, 1, 36, '2023-04-13 17:48:57', 'Az NVIDIA Geforce Rtx 3050 8GB című hirdetésednél nem a címben szereplő termékről töltöttél fel képeket', 1),
(121, 1, 36, '2023-04-13 17:49:03', 'Kérlek mihamarabb javítsd', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `writer_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `rate_date` datetime NOT NULL,
  `text` varchar(500) NOT NULL,
  `rating` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `ratings`
--

INSERT INTO `ratings` (`id`, `writer_id`, `target_id`, `rate_date`, `text`, `rating`) VALUES
(42, 52, 36, '2023-04-13 16:46:25', 'Minden a legnagyobb rendben ment átvételkor, megbízható, korrekt ember', 'positive'),
(43, 36, 52, '2023-04-13 16:52:12', 'Sokáig tartott a helyszín fixálása, egyébként tranzakció közben minden rendben ment', 'positive'),
(44, 53, 36, '2023-04-13 16:58:52', 'Megbeszéltük a tranzakció időpontját és helyszínét, majd egy nappal előtte közölte, hogy másnak adta el a terméket', 'negative'),
(45, 54, 36, '2023-04-13 17:02:00', 'Szuper gyors eladó, kiváló termékek. Ajánlom! ', 'positive'),
(46, 52, 54, '2023-04-13 17:32:45', 'Goromba, lekezelő beszélgetőpartner. Az időpont egyeztetésében is kompromisszumképtelen.', 'negative'),
(47, 55, 36, '2023-04-13 17:33:47', 'Leírásnak megfelelő terméket kaptam, hiánytalanul, jól becsomagolva. Csak ajánlani tudom! ', 'positive');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `writer_id` int(11) NOT NULL,
  `r_text` varchar(1000) NOT NULL,
  `target_user_id` int(11) NOT NULL,
  `target_ad_id` int(11) NOT NULL,
  `r_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `reports`
--

INSERT INTO `reports` (`id`, `writer_id`, `r_text`, `target_user_id`, `target_ad_id`, `r_date`) VALUES
(31, 36, 'Hibásak a hirdetés címében megadott paraméterek', 54, 90, '2023-04-13 17:45:24'),
(32, 52, 'Nem a meghirdetett termékről töltött fel képeket', 36, 67, '2023-04-13 17:47:17');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `register_date` datetime NOT NULL,
  `phone` varchar(20) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `register_date`, `phone`, `full_name`, `score`) VALUES
(1, 'ADMIN', '123', 'hargera@gmail.com', '2023-03-04 17:08:22', '', '', 0),
(36, 'csbeno10', '123', 'csbeno10@gmail.com', '2023-03-06 16:14:53', '20-583-8346', 'Csomor Benő', 2),
(52, 'Tmartin', '123', 'Talits.Martin@gmail.com', '2023-04-13 16:46:01', '', '', 1),
(53, 'Zole21', '123', 'Bzoli@gmail.com', '2023-04-13 16:55:15', '', 'Boros Zoltán György', 0),
(54, 'Hogya95', '123', 'hogyamatyi@gmail.com', '2023-04-13 17:01:13', '', '', -1),
(55, 'KisB', '123', 'KissBenedek@gmail.com', '2023-04-13 17:33:14', '', '', 0);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `advertisement`
--
ALTER TABLE `advertisement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `price` (`price`),
  ADD KEY `shipping` (`shipping`),
  ADD KEY `settlement` (`settlement`),
  ADD KEY `category` (`category`),
  ADD KEY `user_id` (`user_id`);

--
-- A tábla indexei `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- A tábla indexei `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `writer_id` (`writer_id`),
  ADD KEY `target_id` (`target_id`);

--
-- A tábla indexei `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `writer_id` (`writer_id`),
  ADD KEY `target_user_id` (`target_user_id`),
  ADD KEY `target_ad_id` (`target_ad_id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `advertisement`
--
ALTER TABLE `advertisement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT a táblához `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT a táblához `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT a táblához `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `advertisement`
--
ALTER TABLE `advertisement`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `receiver_id` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sender_id` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`writer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`target_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `target_ad_id` FOREIGN KEY (`target_ad_id`) REFERENCES `advertisement` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `target_user_id` FOREIGN KEY (`target_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `writer_id` FOREIGN KEY (`writer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
