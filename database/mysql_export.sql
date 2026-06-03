-- Roberts Family ChildCare — MySQL Export
-- Generated: 2026-06-02 08:35:11
-- Source: SQLite → MySQL converter
-- Import via phpMyAdmin: select your database, click Import, choose this file.

SET FOREIGN_KEY_CHECKS=0;
SET NAMES utf8mb4;
SET time_zone='+00:00';

-- --------------------------------------------------------
-- Table: `Employee`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `Employee`;
CREATE TABLE `Employee` (
  `id` varchar(32) NOT NULL,
  `name` longtext NOT NULL,
  `email` longtext NOT NULL,
  `password` longtext NOT NULL,
  `role` longtext NOT NULL DEFAULT 'STAFF',
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `mustChangePassword` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` longtext DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Employee` (`id`, `name`, `email`, `password`, `role`, `isActive`, `mustChangePassword`, `remember_token`, `createdAt`) VALUES
('cmoyscrcn000014h1mfzh7yro', 'Admin', 'admin', '$2y$12$u0V6bxWgkDS8dmWhdsd8IexHNEuaYtn2VKqptY4x/uWJN8m/qczLC', 'ADMIN', 1, 0, NULL, '2026-05-09 20:17:40'),
('1346b3fd918b49ddb025a3a1bceae8c0', 'Liz Roberts', 'lroberts@robertsfamilychildcare.com', '$2a$10$Nb3S1G8YlwMr3W9lJxNVYuhodOv.jAMk5Uai/o1cLANrPLVR.0DZK', 'ADMIN', 1, 1, NULL, '2026-05-23 05:58:03');

-- --------------------------------------------------------
-- Table: `ParentUser`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `ParentUser`;
CREATE TABLE `ParentUser` (
  `id` varchar(32) NOT NULL,
  `contactId` varchar(32) DEFAULT NULL,
  `username` longtext NOT NULL,
  `password` longtext NOT NULL,
  `mustChangePassword` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` longtext DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ParentUser` (`id`, `contactId`, `username`, `password`, `mustChangePassword`, `remember_token`, `createdAt`, `updatedAt`) VALUES
('cmoyszqpm00093582vvta5tsn', 'cmoyszkxg00053582v740m07b', 'daortiz', '$2a$10$5JTTg8vNzU1B1LikvEahduJKM8LG6zTuLHoFwVu2vLTrJ0csBPMMC', 0, NULL, '2026-05-09 20:35:32', '2026-05-09 20:36:12'),
('cmoyudtzy001i3582hldsphfz', 'cmoytug3y00103582r86ik5ve', 'temoniquewilliams', '$2a$10$KACjbJdqbaWy.h7JL6PdF.HoMEYohwkP9VY4WpOcsJCbj/s8iCXku', 0, NULL, '2026-05-09 21:14:30', '2026-05-09 21:36:50');

-- --------------------------------------------------------
-- Table: `Contact`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `Contact`;
CREATE TABLE `Contact` (
  `id` varchar(32) NOT NULL,
  `name` longtext NOT NULL,
  `email` longtext DEFAULT NULL,
  `phone` longtext DEFAULT NULL,
  `photoUrl` longtext DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Contact` (`id`, `name`, `email`, `phone`, `photoUrl`, `createdAt`, `updatedAt`) VALUES
('cmoyszkxg00053582v740m07b', 'Danielle Ortiz', 'd.ortiz.highland@outlook.com', '(909) 555-1187', NULL, '2026-05-09 20:35:25', '2026-05-09 20:35:25'),
('cmoytug3y00103582r86ik5ve', 'Terrence & Monique Williams', 'mwilliams_care@gmail.com', '(909) 555-0845', NULL, '2026-05-09 20:59:25', '2026-05-09 20:59:25'),
('cmoyv9r22001n3582yz0m6qlm', 'Steve Williams', NULL, '9095555555', '/uploads/contacts/cmoyv9r22001n3582yz0m6qlm.png', '2026-05-09 21:39:19', '2026-05-09 21:39:19');

-- --------------------------------------------------------
-- Table: `Child`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `Child`;
CREATE TABLE `Child` (
  `id` varchar(32) NOT NULL,
  `firstName` longtext NOT NULL,
  `lastName` longtext NOT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `photoUrl` longtext DEFAULT NULL,
  `status` longtext NOT NULL DEFAULT 'ACTIVE',
  `schedule` longtext DEFAULT NULL,
  `checkedInAt` datetime DEFAULT NULL,
  `expectedStart` date DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `inquiryId` varchar(32) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Child` (`id`, `firstName`, `lastName`, `dateOfBirth`, `photoUrl`, `status`, `schedule`, `checkedInAt`, `expectedStart`, `notes`, `inquiryId`, `createdAt`, `updatedAt`) VALUES
('cmoysw16500023582igxbk2tp', 'Marco', 'Ortiz', '2022-12-19', '/storage/uploads/children/cmoysw16500023582igxbk2tp.jpg', 'ACTIVE', '{"monday":{"dropoff":"08:00","pickup":"17:00"},"tuesday":{"dropoff":"09:00","pickup":"16:00"},"wednesday":null,"thursday":null,"friday":{"dropoff":"08:00","pickup":"17:00"}}', NULL, '2025-06-30', 'Marco is pretty shy at first but warms up quickly. He loves dinosaurs and trucks. We currently have him with a relative but that arrangement is ending.', 'cmoysjx6n0009sho4mokmuvti', '2026-05-09 20:32:39', '2026-06-02 07:26:52'),
('cmoytu9kr000x3582qh5md4wy', 'Zara', 'Williams', '2023-02-11', '/storage/uploads/children/cmoytu9kr000x3582qh5md4wy.jpg', 'ACTIVE', '{"monday":null,"tuesday":{"dropoff":"08:00","pickup":"16:00"},"wednesday":{"dropoff":"08:00","pickup":"16:00"},"thursday":{"dropoff":"08:00","pickup":"16:00"},"friday":null}', NULL, '2026-08-18', 'We\'ve been searching for a while. Zara is very attached to her mom right now so a small, home environment is really important to us.', 'cmoysjx610006sho4rqlz6sem', '2026-05-09 20:59:17', '2026-06-02 07:21:09'),
('4e55b66b68c24f8288fe739161bbdc9a', 'Mason', 'Caldwell', '2022-03-14', NULL, 'INACTIVE', '{"monday":null,"tuesday":null,"wednesday":null,"thursday":null,"friday":null}', NULL, NULL, NULL, 'cmoysjx4v0000sho489mwnby2', '2026-06-02 07:21:24', '2026-06-02 07:45:13'),
('8b48476c60a24c3f9dfcd6836b540aa2', 'Mason', 'Caldwell', '2022-03-14', '/storage/uploads/children/8b48476c60a24c3f9dfcd6836b540aa2.jpg', 'ACTIVE', NULL, '2026-06-02 07:25:56', NULL, NULL, 'cmoysjx4v0000sho489mwnby2', '2026-06-02 07:21:26', '2026-06-02 07:25:56');

-- --------------------------------------------------------
-- Table: `ChildNote`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `ChildNote`;
CREATE TABLE `ChildNote` (
  `id` varchar(32) NOT NULL,
  `childId` varchar(32) NOT NULL,
  `employeeId` varchar(32) DEFAULT NULL,
  `content` longtext NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ChildNote` (`id`, `childId`, `employeeId`, `content`, `createdAt`, `updatedAt`) VALUES
('cmoytxa4x00143582it8oby5y', 'cmoytu9kr000x3582qh5md4wy', NULL, 'Test', '2026-05-09 21:01:37', '2026-05-09 21:01:37'),
('cmoyu0hbz00163582n6j7x5iu', 'cmoytu9kr000x3582qh5md4wy', NULL, '[Checked In] May 9, 2026, 2:04 PM', '2026-05-09 21:04:07', '2026-05-09 21:04:07'),
('cmoyu84co00183582ej06r9ac', 'cmoytu9kr000x3582qh5md4wy', NULL, '[Checked Out] May 9, 2026, 2:10 PM (checked in at 2:04 PM)', '2026-05-09 21:10:03', '2026-05-09 21:10:03'),
('cmoyu85ot001a3582yp9wnvp2', 'cmoysw16500023582igxbk2tp', NULL, '[Checked In] May 9, 2026, 2:10 PM', '2026-05-09 21:10:05', '2026-05-09 21:10:05'),
('cmoyu86p2001c3582hf7wav0v', 'cmoysw16500023582igxbk2tp', NULL, '[Checked Out] May 9, 2026, 2:10 PM (checked in at 2:10 PM)', '2026-05-09 21:10:06', '2026-05-09 21:10:06'),
('cmoyubjxo001e3582q5tfutqf', 'cmoysw16500023582igxbk2tp', NULL, '[Checked In] May 9, 2026, 2:12 PM', '2026-05-09 21:12:43', '2026-05-09 21:12:43'),
('cmoyubmrp001g3582n67kc6wf', 'cmoysw16500023582igxbk2tp', NULL, '[Checked Out] May 9, 2026, 2:12 PM (checked in at 2:12 PM)', '2026-05-09 21:12:47', '2026-05-09 21:12:47'),
('22df3edadfef40aca9c88ced16f4bc95', 'cmoytu9kr000x3582qh5md4wy', NULL, '[Checked In] May 19, 2026, 1:07 PM', '2026-05-19 20:07:14', '2026-05-19 20:07:14'),
('9cf996f75036455db8ae41a46156730d', 'cmoytu9kr000x3582qh5md4wy', NULL, '[Checked Out] May 19, 2026, 1:07 PM (checked in at 1:07 PM)', '2026-05-19 20:07:25', '2026-05-19 20:07:25'),
('7725e76b7b794813b0f2cbac2261df13', 'cmoysw16500023582igxbk2tp', NULL, '[Checked In] May 19, 2026, 9:19 PM', '2026-05-20 04:19:57', '2026-05-20 04:19:57'),
('a0d56eea43094b679fa2079c6210d156', 'cmoysw16500023582igxbk2tp', NULL, '[Checked Out] May 19, 2026, 9:21 PM (checked in at 9:19 PM)', '2026-05-20 04:21:25', '2026-05-20 04:21:25');

-- --------------------------------------------------------
-- Table: `ChildDocument`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `ChildDocument`;
CREATE TABLE `ChildDocument` (
  `id` varchar(32) NOT NULL,
  `childId` varchar(32) NOT NULL,
  `title` longtext DEFAULT NULL,
  `name` longtext NOT NULL,
  `fileUrl` longtext NOT NULL,
  `mimeType` longtext DEFAULT NULL,
  `size` int,
  `uploadedByParent` tinyint(1) NOT NULL DEFAULT 0,
  `uploadedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ChildDocument` (`id`, `childId`, `title`, `name`, `fileUrl`, `mimeType`, `size`, `uploadedByParent`, `uploadedAt`) VALUES
('cmoyv9yxo001r3582goecke5l', 'cmoytu9kr000x3582qh5md4wy', NULL, 'logo_3_roberts_family_childcare.png', '/storage/uploads/children/docs/cmoytu9kr000x3582qh5md4wy/moyv9yxl-5qx2r2.png', 'image/png', 242033, 0, '2026-05-09 21:39:29'),
('cmoyva64v001t3582q9knok1d', 'cmoytu9kr000x3582qh5md4wy', NULL, 'feature_graphic_1024x500.png', '/storage/uploads/children/docs/cmoytu9kr000x3582qh5md4wy/moyva64s-0lpfj5.png', 'image/png', 623705, 0, '2026-05-09 21:39:38');

-- --------------------------------------------------------
-- Table: `ChildContact`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `ChildContact`;
CREATE TABLE `ChildContact` (
  `id` varchar(32) NOT NULL,
  `childId` varchar(32) NOT NULL,
  `contactId` varchar(32) NOT NULL,
  `relationship` longtext NOT NULL,
  `isPrimary` tinyint(1) NOT NULL DEFAULT 0,
  `addedByParent` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ChildContact` (`id`, `childId`, `contactId`, `relationship`, `isPrimary`, `addedByParent`, `createdAt`) VALUES
('cmoyszkxn00073582yibch3tt', 'cmoysw16500023582igxbk2tp', 'cmoyszkxg00053582v740m07b', 'Mother', 0, 0, '2026-05-09 20:35:25'),
('cmoytug4600123582wzir650i', 'cmoytu9kr000x3582qh5md4wy', 'cmoytug3y00103582r86ik5ve', 'Mother', 0, 0, '2026-05-09 20:59:25'),
('cmoyv9r29001p3582inpzos16', 'cmoytu9kr000x3582qh5md4wy', 'cmoyv9r22001n3582yz0m6qlm', 'Grandfather', 1, 1, '2026-05-09 21:39:19');

-- --------------------------------------------------------
-- Table: `ChildCheckinLog`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `ChildCheckinLog`;
CREATE TABLE `ChildCheckinLog` (
  `id` varchar(32) NOT NULL,
  `childId` varchar(32) NOT NULL,
  `action` longtext NOT NULL,
  `employeeId` varchar(32) DEFAULT NULL,
  `occurredAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ChildCheckinLog` (`id`, `childId`, `action`, `employeeId`, `occurredAt`, `note`) VALUES
('b44ccf391a704bb89ff9ce9a923e3ba6', 'cmoysw16500023582igxbk2tp', 'CHECKIN', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 06:58:37', NULL),
('0177dc51882e4fc883d71f83a4c15c58', 'cmoytu9kr000x3582qh5md4wy', 'CHECKIN', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 06:58:41', NULL),
('7085fe73a36c40b8ab9aa6df09c7c462', 'cmoytu9kr000x3582qh5md4wy', 'CHECKOUT', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:05:28', NULL),
('4b028f77010547129e4dce60f3f7111a', 'cmoysw16500023582igxbk2tp', 'CHECKOUT', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:05:31', NULL),
('cec2689aa86945a18374b0eaf4ca4270', 'cmoytu9kr000x3582qh5md4wy', 'CHECKIN', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:10:35', NULL),
('b3651ad2961542ccacf47ab888c10ad6', 'cmoysw16500023582igxbk2tp', 'CHECKIN', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:10:40', NULL),
('17a367bbc07947a7984b235fec736bbd', 'cmoysw16500023582igxbk2tp', 'CHECKOUT', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:17:56', NULL),
('e39a7960d39c41d3951d19c4478ad5c4', 'cmoytu9kr000x3582qh5md4wy', 'CHECKOUT', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:17:59', NULL),
('f7214bb1bb9648d0b9880f99eaeb3109', 'cmoytu9kr000x3582qh5md4wy', 'CHECKIN', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:18:42', NULL),
('1717f77f4c7440829cacc049858c0267', 'cmoytu9kr000x3582qh5md4wy', 'CHECKOUT', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:18:52', NULL),
('b99d9c4c4fa14984a0553c1311d61442', 'cmoytu9kr000x3582qh5md4wy', 'CHECKIN', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:18:54', NULL),
('2c71219f72984108b44cd74c997f9492', 'cmoytu9kr000x3582qh5md4wy', 'CHECKOUT', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:21:09', NULL),
('67f0d4ad84f04cfaacc475049a95db60', '4e55b66b68c24f8288fe739161bbdc9a', 'CHECKIN', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:25:52', NULL),
('2d34b644902548a59d816f822c171906', '8b48476c60a24c3f9dfcd6836b540aa2', 'CHECKIN', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:25:56', NULL),
('fd2de67e5ccd422db8fb602b218ee719', '4e55b66b68c24f8288fe739161bbdc9a', 'CHECKOUT', 'cmoyscrcn000014h1mfzh7yro', '2026-06-02 07:26:07', NULL);

-- --------------------------------------------------------
-- Table: `Inquiry`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `Inquiry`;
CREATE TABLE `Inquiry` (
  `id` varchar(32) NOT NULL,
  `parentName` longtext NOT NULL,
  `parentEmail` longtext DEFAULT NULL,
  `parentPhone` longtext DEFAULT NULL,
  `childName` longtext DEFAULT NULL,
  `childDob` date DEFAULT NULL,
  `desiredStart` date DEFAULT NULL,
  `hearAbout` longtext DEFAULT NULL,
  `programInterest` longtext DEFAULT NULL,
  `message` longtext DEFAULT NULL,
  `status` longtext NOT NULL DEFAULT 'NEW',
  `isSnoozed` tinyint(1) NOT NULL DEFAULT 0,
  `snoozeUntil` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Inquiry` (`id`, `parentName`, `parentEmail`, `parentPhone`, `childName`, `childDob`, `desiredStart`, `hearAbout`, `programInterest`, `message`, `status`, `isSnoozed`, `snoozeUntil`, `createdAt`, `updatedAt`) VALUES
('cmoysjx4v0000sho489mwnby2', 'Jennifer Caldwell', 'jen.caldwell@email.com', '(909) 555-0142', 'Mason Caldwell', '2022-03-14', '2025-06-02', 'Google', 'Toddler', 'We\'re looking for a warm, home-based setting for Mason. He\'s really social and loves music and outdoor play.', 'COMPLETE', 0, NULL, '2026-05-09 20:23:14', '2026-06-02 07:21:24'),
('cmoysjx530001sho4k5cgubnw', 'David & Priya Nair', 'priya.nair@gmail.com', '(909) 555-0278', 'Anika Nair', '2023-07-29', '2025-07-14', 'Nextdoor', 'Infant', 'Anika will be 1 year old when we need care to start. We both work full-time and need reliable Mon–Fri coverage.', 'LEFT_VOICEMAIL', 0, NULL, '2026-05-09 20:23:14', '2026-05-09 20:23:14'),
('cmoysjx5a0002sho4zscoqqpz', 'Marcus Thompson', 'm.thompson84@outlook.com', '(909) 555-0394', 'Jaylen Thompson', '2021-11-05', '2025-08-01', 'Facebook', 'Preschool', 'Jaylen is energetic and loves building blocks. We toured one other place but this felt like a much better fit from the website.', 'PROVIDED_PRICING_WAITING', 0, NULL, '2026-05-09 20:23:14', '2026-05-09 20:23:14'),
('cmoysjx5h0003sho4gtqn0sks', 'Sandra Kim', 'sandra.kim.hb@yahoo.com', '(909) 555-0517', 'Lily Kim', '2020-05-18', '2025-06-16', 'Referral from another parent', 'Preschool', 'My neighbor\'s daughter goes here and she absolutely raves about it. Lily has been on a waitlist at a center-based program for 6 months.', 'FOLLOW_UP_WHEN_ROOM', 0, NULL, '2026-05-09 20:23:14', '2026-05-09 20:23:14'),
('cmoysjx5o0004sho4fa5vcmg8', 'Brittany & Carlos Reyes', 'breyes_family@gmail.com', '(909) 555-0663', 'Sofia Reyes', '2022-09-02', '2025-06-09', 'Yelp', 'Toddler', '', 'LEFT_VOICEMAIL_2', 0, NULL, '2026-05-09 20:23:14', '2026-05-09 20:23:14'),
('cmoysjx5u0005sho4cgkpp7cq', 'Amanda Foster', 'amanda.foster@icloud.com', '(909) 555-0721', 'Noah Foster', '2019-12-22', '2025-09-02', 'Google', 'School Age', 'Noah starts kindergarten in the fall but we need before/after school care. He\'s very independent and loves reading.', 'COMPLETE', 0, NULL, '2026-05-09 20:23:14', '2026-05-09 20:54:53'),
('cmoysjx610006sho4rqlz6sem', 'Terrence & Monique Williams', 'mwilliams_care@gmail.com', '(909) 555-0845', 'Zara Williams', '2023-02-11', '2025-08-18', 'Instagram', 'Infant', 'We\'ve been searching for a while. Zara is very attached to her mom right now so a small, home environment is really important to us.', 'COMPLETE', 0, NULL, '2026-05-09 20:23:14', '2026-05-09 20:59:17'),
('cmoysjx680007sho43qpnr7f0', 'Rachel Nguyen', 'rachel.nguyen.hld@gmail.com', '(909) 555-0932', 'Ethan Nguyen', '2021-06-30', '2025-06-23', 'Nextdoor', 'Toddler', 'Part-time care needed, ideally T/TH or M/W/F. Let me know what openings look like.', 'FOLLOW_UP_WHEN_ROOM', 0, NULL, '2026-05-09 20:23:14', '2026-05-20 04:21:56'),
('cmoysjx6g0008sho420b8bj6j', 'Kevin & Lisa Park', 'lisakpark@gmail.com', '(909) 555-1054', 'Hannah Park', '2020-10-07', '2025-07-07', 'Google', 'Preschool', 'Hannah is very articulate for her age and we\'re looking for a program that encourages learning through play. We have a dog at home so she\'s comfortable around animals.', 'COMPLETE', 0, NULL, '2026-05-09 20:23:14', '2026-05-09 20:23:14'),
('cmoysjx6n0009sho4mokmuvti', 'Danielle Ortiz', 'd.ortiz.highland@outlook.com', '(909) 555-1187', 'Marco Ortiz', '2022-12-19', '2025-06-30', 'Facebook', 'Toddler', 'Marco is pretty shy at first but warms up quickly. He loves dinosaurs and trucks. We currently have him with a relative but that arrangement is ending.', 'COMPLETE', 0, NULL, '2026-05-09 20:23:14', '2026-05-09 20:32:39');

-- --------------------------------------------------------
-- Table: `InquiryNote`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `InquiryNote`;
CREATE TABLE `InquiryNote` (
  `id` varchar(32) NOT NULL,
  `inquiryId` varchar(32) NOT NULL,
  `employeeId` varchar(32) NOT NULL,
  `content` longtext NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `InquiryNote` (`id`, `inquiryId`, `employeeId`, `content`, `createdAt`) VALUES
('cmoyto0ew000d35827c07p07l', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'left voicemail', '2026-05-09 20:54:25'),
('cmoyto6bx000h3582tv7j4nkf', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', '2nd voicemail left\n', '2026-05-09 20:54:32'),
('cmoytoccp000l3582ltww93zc', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'Final voicemal\n', '2026-05-09 20:54:40'),
('cmoytoj3c000p3582ovs686fc', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'Pricing provided, waiting response.', '2026-05-09 20:54:49'),
('cmoytosnf000t3582oijetgsb', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'Not interested at this time.', '2026-05-09 20:55:01'),
('cmoytpbmb000v358211rkmuwf', 'cmoysjx610006sho4rqlz6sem', 'cmoyscrcn000014h1mfzh7yro', 'Accepted pricing, creating account.', '2026-05-09 20:55:26'),
('d4e0b9f2725f4ddaa8c83c083a96a21b', 'cmoysjx4v0000sho489mwnby2', 'cmoyscrcn000014h1mfzh7yro', 'Converted from inquiry — child record created (#4e55b66b68c24f8288fe739161bbdc9a). Inquiry automatically marked Complete upon conversion.', '2026-06-02 07:21:24'),
('27ba0977bbe94c75821dd71eb41f0373', 'cmoysjx4v0000sho489mwnby2', 'cmoyscrcn000014h1mfzh7yro', 'Converted from inquiry — child record created (#8b48476c60a24c3f9dfcd6836b540aa2). Inquiry automatically marked Complete upon conversion.', '2026-06-02 07:21:26');

-- --------------------------------------------------------
-- Table: `InquiryStatusHistory`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `InquiryStatusHistory`;
CREATE TABLE `InquiryStatusHistory` (
  `id` varchar(32) NOT NULL,
  `inquiryId` varchar(32) NOT NULL,
  `employeeId` varchar(32) NOT NULL,
  `oldStatus` longtext NOT NULL,
  `newStatus` longtext NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `InquiryStatusHistory` (`id`, `inquiryId`, `employeeId`, `oldStatus`, `newStatus`, `createdAt`) VALUES
('cmoysw17l00043582ozowrk1q', 'cmoysjx6n0009sho4mokmuvti', 'cmoyscrcn000014h1mfzh7yro', 'PROVIDED_PRICING_WAITING', 'COMPLETE', '2026-05-09 20:32:39'),
('cmoytnwsc000b3582jccd5m1f', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'NEW', 'LEFT_VOICEMAIL', '2026-05-09 20:54:20'),
('cmoyto2ok000f3582z7m02eyx', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'LEFT_VOICEMAIL', 'LEFT_VOICEMAIL_2', '2026-05-09 20:54:28'),
('cmoyto7lo000j3582ncyvh7v3', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'LEFT_VOICEMAIL_2', 'LEFT_VOICEMAIL_FINAL', '2026-05-09 20:54:34'),
('cmoytoct3000n35827xqznd2n', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'LEFT_VOICEMAIL_FINAL', 'PROVIDED_PRICING_WAITING', '2026-05-09 20:54:41'),
('cmoytomjw000r3582bpbh9w7c', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'PROVIDED_PRICING_WAITING', 'COMPLETE', '2026-05-09 20:54:53'),
('cmoytu9ry000z3582b6bd7zo7', 'cmoysjx610006sho4rqlz6sem', 'cmoyscrcn000014h1mfzh7yro', 'NEW', 'COMPLETE', '2026-05-09 20:59:17'),
('fc5ddec3697c429b96eb84bdcd84acd3', 'cmoysjx680007sho43qpnr7f0', 'cmoyscrcn000014h1mfzh7yro', 'LEFT_VOICEMAIL_FINAL', 'FOLLOW_UP_WHEN_ROOM', '2026-05-20 04:21:56'),
('4052dda91b8940d5b7fdd69067ff8162', 'cmoysjx4v0000sho489mwnby2', 'cmoyscrcn000014h1mfzh7yro', 'NEW', 'FOLLOW_UP_WHEN_ROOM', '2026-06-01 21:40:04'),
('a6c884ffde25498191b347c6c21a9686', 'cmoysjx4v0000sho489mwnby2', 'cmoyscrcn000014h1mfzh7yro', 'FOLLOW_UP_WHEN_ROOM', 'LEFT_VOICEMAIL', '2026-06-01 21:42:44'),
('a75faca374ac4f2091c8e38443490052', 'cmoysjx4v0000sho489mwnby2', 'cmoyscrcn000014h1mfzh7yro', 'LEFT_VOICEMAIL', 'LEFT_VOICEMAIL_FINAL', '2026-06-01 21:42:46'),
('d924b68f221a46108e07b0c87a871b4b', 'cmoysjx4v0000sho489mwnby2', 'cmoyscrcn000014h1mfzh7yro', 'LEFT_VOICEMAIL_FINAL', 'PROVIDED_PRICING_WAITING', '2026-06-01 21:42:52'),
('7256e50547da4556a287a0d6b4eafda4', 'cmoysjx4v0000sho489mwnby2', 'cmoyscrcn000014h1mfzh7yro', 'PROVIDED_PRICING_WAITING', 'COMPLETE', '2026-06-02 07:21:24'),
('63f90ef9888746fc958e8d7e59916497', 'cmoysjx4v0000sho489mwnby2', 'cmoyscrcn000014h1mfzh7yro', 'COMPLETE', 'COMPLETE', '2026-06-02 07:21:26');

-- --------------------------------------------------------
-- Table: `Message`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `Message`;
CREATE TABLE `Message` (
  `id` varchar(32) NOT NULL,
  `parentUserId` varchar(32) NOT NULL,
  `senderRole` longtext NOT NULL,
  `content` longtext NOT NULL,
  `readAt` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Message` (`id`, `parentUserId`, `senderRole`, `content`, `readAt`, `createdAt`) VALUES
('cmoyw29qu0001k1jg7mntmmye', 'cmoyudtzy001i3582hldsphfz', 'PARENT', 'Hi!', '2026-05-09 22:02:09', '2026-05-09 22:01:29'),
('cmoyw3aaa0003k1jgfc3fc724', 'cmoyudtzy001i3582hldsphfz', 'STAFF', 'Test 123', '2026-05-09 22:02:36', '2026-05-09 22:02:17'),
('cmoyw3trr0005k1jgh1zoouvv', 'cmoyudtzy001i3582hldsphfz', 'PARENT', 'No way', '2026-05-09 22:02:44', '2026-05-09 22:02:42'),
('cmoyw40ck0007k1jgzpaybz43', 'cmoyudtzy001i3582hldsphfz', 'STAFF', 'looks like its working', '2026-05-09 22:02:50', '2026-05-09 22:02:50'),
('cmoyw4tya0009k1jgvd6cn8z7', 'cmoyszqpm00093582vvta5tsn', 'PARENT', 'Lets test a 2nd chat', '2026-05-09 22:03:36', '2026-05-09 22:03:29'),
('cmoyw518r000bk1jg2isf7t54', 'cmoyszqpm00093582vvta5tsn', 'STAFF', 'Nice!', '2026-05-09 22:03:39', '2026-05-09 22:03:38');

-- --------------------------------------------------------
-- Table: `StaffMember`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `StaffMember`;
CREATE TABLE `StaffMember` (
  `id` varchar(32) NOT NULL,
  `name` longtext NOT NULL,
  `title` longtext NOT NULL,
  `bio` longtext DEFAULT NULL,
  `photoUrl` longtext DEFAULT NULL,
  `sortOrder` int NOT NULL DEFAULT 0,
  `isActive` tinyint(1) NOT NULL DEFAULT 1,
  `startDate` date DEFAULT NULL,
  `yearsExperience` int,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `StaffMember` (`id`, `name`, `title`, `bio`, `photoUrl`, `sortOrder`, `isActive`, `startDate`, `yearsExperience`, `createdAt`) VALUES
('cmoyscrcw000114h13kvjj1tl', 'Ms. Liz', 'Owner', NULL, '/storage/uploads/staff/cmoyscrcw000114h13kvjj1tl.jpg', 1, 1, NULL, NULL, '2026-05-09 20:17:40'),
('cmoyscrcw000214h11sjflzt2', 'Ms. Jyll', 'Nap Queen', 'Staff bio coming soon.', '/storage/uploads/staff/cmoyscrcw000214h11sjflzt2.jpg', 2, 1, NULL, NULL, '2026-05-09 20:17:40'),
('cmoysn0h1000035825pb794vy', 'Ms. Jasmin', 'Sheep Herder', NULL, '/storage/uploads/staff/cmoysn0h1000035825pb794vy.jpg', 3, 1, NULL, NULL, '2026-05-09 20:25:39'),
('56565cb722bb49479781e619191994c5', 'ChadGPT', 'IT Support', NULL, '/uploads/staff/2bba762d-aa15-4139-b4ba-fd2378d39a97.png', 4, 0, NULL, NULL, '2026-05-23 05:50:24');

-- --------------------------------------------------------
-- Table: `StaffNote`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `StaffNote`;
CREATE TABLE `StaffNote` (
  `id` varchar(32) NOT NULL,
  `staffMemberId` varchar(32) NOT NULL,
  `employeeId` varchar(32) NOT NULL,
  `content` longtext NOT NULL,
  `sentiment` longtext NOT NULL DEFAULT 'NEUTRAL',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `StaffNote` (`id`, `staffMemberId`, `employeeId`, `content`, `sentiment`, `createdAt`, `updatedAt`) VALUES
('d912c7dfcab84607b2d86c91d155f043', '56565cb722bb49479781e619191994c5', 'cmoyscrcn000014h1mfzh7yro', 'NA', 'NEGATIVE', '2026-06-01 18:38:50', '2026-06-01 18:38:50'),
('700724b8692044a693b30b125533d573', 'cmoyscrcw000114h13kvjj1tl', 'cmoyscrcn000014h1mfzh7yro', 'Test Note', 'NEUTRAL', '2026-06-02 06:17:15', '2026-06-02 06:17:15'),
('5db613307ba2471ab0bd4f3557a782a6', 'cmoyscrcw000114h13kvjj1tl', 'cmoyscrcn000014h1mfzh7yro', 'Test Note 2', 'POSITIVE', '2026-06-02 06:17:21', '2026-06-02 06:17:21'),
('4051d36700eb4d01940c0b0a861ed297', 'cmoyscrcw000114h13kvjj1tl', 'cmoyscrcn000014h1mfzh7yro', 'Test Note 3', 'NEGATIVE', '2026-06-02 06:17:28', '2026-06-02 06:17:28');

-- --------------------------------------------------------
-- Table: `GalleryImage`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `GalleryImage`;
CREATE TABLE `GalleryImage` (
  `id` varchar(32) NOT NULL,
  `filename` longtext NOT NULL,
  `caption` longtext DEFAULT NULL,
  `takenAt` date DEFAULT NULL,
  `sortOrder` int NOT NULL DEFAULT 0,
  `data` longtext DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `GalleryImage` (`id`, `filename`, `caption`, `takenAt`, `sortOrder`, `data`, `createdAt`) VALUES
('a9263ee257f54ba3b6e66203384ceb16', '/storage/uploads/gallery/a9263ee257f54ba3b6e66203384ceb16.jpg', NULL, '2026-05-31 00:00:00', 1, NULL, '2026-06-02 06:10:17'),
('26d6d2a0b8f3417d962f3355e89dcdcb', '/storage/uploads/gallery/26d6d2a0b8f3417d962f3355e89dcdcb.jpg', NULL, '2026-06-04 00:00:00', 2, NULL, '2026-06-02 06:10:29'),
('11688cd5bb434836879713329b2e6936', '/storage/uploads/gallery/11688cd5bb434836879713329b2e6936.jpg', NULL, NULL, 3, NULL, '2026-06-02 06:10:36'),
('f51236f3811d40eebaa1dff337ead586', '/storage/uploads/gallery/f51236f3811d40eebaa1dff337ead586.jpg', NULL, NULL, 4, NULL, '2026-06-02 06:10:43'),
('aa5dc8f2fe3748808eca263f38d9ff90', '/storage/uploads/gallery/aa5dc8f2fe3748808eca263f38d9ff90.jpg', NULL, NULL, 5, NULL, '2026-06-02 06:10:53');

-- --------------------------------------------------------
-- Table: `TestimonialLink`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `TestimonialLink`;
CREATE TABLE `TestimonialLink` (
  `id` varchar(32) NOT NULL,
  `createdById` varchar(32) NOT NULL,
  `token` longtext NOT NULL,
  `parentName` longtext DEFAULT NULL,
  `usedAt` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `TestimonialLink` (`id`, `createdById`, `token`, `parentName`, `usedAt`, `createdAt`) VALUES
('e76b594d25bc4178984e8eb07b25dbca', 'cmoyscrcn000014h1mfzh7yro', 'gq0HRM538nLxPz5TmaJSW9B6M6ds4GJC', 'Jyll Roberts', NULL, '2026-06-02 07:31:07');

-- --------------------------------------------------------
-- Table: `Testimonial`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `Testimonial`;
CREATE TABLE `Testimonial` (
  `id` varchar(32) NOT NULL,
  `linkId` varchar(32) NOT NULL,
  `parentName` longtext NOT NULL,
  `content` longtext NOT NULL,
  `rating` int,
  `status` longtext NOT NULL DEFAULT 'PENDING',
  `isActive` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reviewedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `SiteContent`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `SiteContent`;
CREATE TABLE `SiteContent` (
  `id` varchar(32) NOT NULL,
  `key` longtext NOT NULL,
  `value` text,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `SiteContent` (`id`, `key`, `value`, `updatedAt`) VALUES
('cmoyscrd3000314h16zrwjlns', 'about_blurb', 'Roberts Family ChildCare is a warm, nurturing home-based childcare located in Highland, CA. We provide a safe, loving environment where children can grow, explore, and thrive. Our program is built on the belief that every child deserves individualized attention and a joyful early childhood experience. Update this text in your portal under Settings → About.', '2026-05-09 20:17:40'),
('4a7d52e2e4474f6baf16e8011801bc97', 'hero_headline', '', '2026-06-02 07:53:12'),
('fe633dba48d54561b4712733d20edcac', 'hero_subheadline', '', '2026-06-02 07:53:12'),
('0b5ff7b12f834b2ba2377f6f552e4fde', 'about_body', '', '2026-06-02 07:53:12'),
('e5648b6df3dd4748a78943348493850f', 'programs_infant', 'Test', '2026-06-02 07:53:12'),
('c31106f335224fdea00d13b900d6824d', 'programs_young_toddler', '', '2026-06-02 07:53:12'),
('61418cb52bf34eb09324346a14b559e1', 'programs_toddler', '', '2026-06-02 07:53:12'),
('98359a499f014739b8211a6d5367078c', 'programs_preschool', '', '2026-06-02 07:53:12'),
('9b6aded057f34a319799c1f038f3fb7e', 'notification_email', '', '2026-06-02 07:53:12');

-- --------------------------------------------------------
-- Table: `migrations`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` varchar(32) NOT NULL,
  `migration` longtext NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(8, '0001_01_01_000000_create_users_table', 1),
(9, '0001_01_01_000001_create_cache_table', 1),
(10, '0001_01_01_000002_create_jobs_table', 1),
(11, '2026_06_01_000001_create_app_schema', 1),
(12, '2026_06_01_073549_add_start_date_and_years_experience_to_staff_member', 1),
(13, '2026_06_01_075052_add_taken_at_to_gallery_image', 1),
(14, '2026_06_01_080134_add_is_active_to_staff_member', 1),
(15, '2026_06_01_090000_create_child_checkin_log_table', 2);

-- --------------------------------------------------------
-- Table: `sessions`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(32) NOT NULL,
  `user_id` int,
  `ip_address` longtext DEFAULT NULL,
  `user_agent` longtext DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `cache`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(32) NOT NULL,
  `value` text NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `cache_locks`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(32) NOT NULL,
  `owner` longtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` varchar(32) NOT NULL,
  `queue` longtext NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` int NOT NULL,
  `reserved_at` int,
  `available_at` int NOT NULL,
  `created_at` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `job_batches`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(32) NOT NULL,
  `name` longtext NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` longtext DEFAULT NULL,
  `cancelled_at` int,
  `created_at` int NOT NULL,
  `finished_at` int,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `failed_jobs`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` varchar(32) NOT NULL,
  `uuid` varchar(32) NOT NULL,
  `connection` longtext NOT NULL,
  `queue` longtext NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `password_reset_tokens`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(32) NOT NULL,
  `token` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` varchar(32) NOT NULL,
  `name` longtext NOT NULL,
  `email` longtext NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` longtext NOT NULL,
  `remember_token` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;
