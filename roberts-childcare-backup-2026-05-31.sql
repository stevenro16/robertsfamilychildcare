-- Roberts Family ChildCare — Database Backup
-- Generated: 2026-05-31 23:28:36 UTC
-- Total rows: 60
--
-- To restore: apply DB_BUILD_v2.sql first to create the schema,
-- then import this file to populate all data.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET max_allowed_packet = 1073741824;

-- Employee (2 rows) -- ───────────────────────────────────────────────────────────────
TRUNCATE TABLE `Employee`;
INSERT INTO `Employee` (`id`, `name`, `email`, `password`, `role`, `mustChangePassword`, `isActive`, `createdAt`) VALUES ('cmoyscrcn000014h1mfzh7yro', 'Admin', 'sgroberts', '$2a$10$z34NvTrxlGLl/UCnspLHLODvt/RoJDvn8GgZg847gupEhg3dvJxLu', 'ADMIN', 0, 1, '2026-05-09 20:17:40.727');
INSERT INTO `Employee` (`id`, `name`, `email`, `password`, `role`, `mustChangePassword`, `isActive`, `createdAt`) VALUES ('1346b3fd918b49ddb025a3a1bceae8c0', 'Liz Roberts', 'lroberts@robertsfamilychildcare.com', '$2a$10$Nb3S1G8YlwMr3W9lJxNVYuhodOv.jAMk5Uai/o1cLANrPLVR.0DZK', 'ADMIN', 1, 1, '2026-05-23 05:58:03.839');


-- SiteContent (1 row) -- ────────────────────────────────────────────────────────────
TRUNCATE TABLE `SiteContent`;
INSERT INTO `SiteContent` (`id`, `key`, `value`, `updatedAt`) VALUES ('cmoyscrd3000314h16zrwjlns', 'about_blurb', 'Roberts Family ChildCare is a warm, nurturing home-based childcare located in Highland, CA. We provide a safe, loving environment where children can grow, explore, and thrive. Our program is built on the belief that every child deserves individualized attention and a joyful early childhood experience. Update this text in your portal under Settings → About.', '2026-05-09 20:17:40.743');


-- Contact (3 rows) -- ────────────────────────────────────────────────────────────────
TRUNCATE TABLE `Contact`;
INSERT INTO `Contact` (`id`, `name`, `phone`, `email`, `photoUrl`, `createdAt`, `updatedAt`) VALUES ('cmoyszkxg00053582v740m07b', 'Danielle Ortiz', '(909) 555-1187', 'd.ortiz.highland@outlook.com', NULL, '2026-05-09 20:35:25.493', '2026-05-09 20:35:25.493');
INSERT INTO `Contact` (`id`, `name`, `phone`, `email`, `photoUrl`, `createdAt`, `updatedAt`) VALUES ('cmoytug3y00103582r86ik5ve', 'Terrence & Monique Williams', '(909) 555-0845', 'mwilliams_care@gmail.com', NULL, '2026-05-09 20:59:25.583', '2026-05-09 20:59:25.583');
INSERT INTO `Contact` (`id`, `name`, `phone`, `email`, `photoUrl`, `createdAt`, `updatedAt`) VALUES ('cmoyv9r22001n3582yz0m6qlm', 'Steve Williams', '9095555555', NULL, '/uploads/contacts/cmoyv9r22001n3582yz0m6qlm.png', '2026-05-09 21:39:19.227', '2026-05-09 21:39:19.244');


-- ContactNote (0 rows) -- ────────────────────────────────────────────────────────────
TRUNCATE TABLE `ContactNote`;


-- StaffMember (4 rows) -- ────────────────────────────────────────────────────────────
TRUNCATE TABLE `StaffMember`;
INSERT INTO `StaffMember` (`id`, `name`, `title`, `bio`, `photoUrl`, `sortOrder`, `createdAt`) VALUES ('cmoyscrcw000114h13kvjj1tl', 'Ms. Liz', 'Owner', NULL, '/uploads/staff/78a32141-fdc0-4d78-8174-53ea389c29e1.jpg', 1, '2026-05-09 20:17:40.736');
INSERT INTO `StaffMember` (`id`, `name`, `title`, `bio`, `photoUrl`, `sortOrder`, `createdAt`) VALUES ('cmoyscrcw000214h11sjflzt2', 'Ms. Jyll', 'Nap Queen', 'Staff bio coming soon.', '/uploads/staff/2315769e-d5e6-4fef-a359-fae8055d90f1.jpg', 2, '2026-05-09 20:17:40.736');
INSERT INTO `StaffMember` (`id`, `name`, `title`, `bio`, `photoUrl`, `sortOrder`, `createdAt`) VALUES ('cmoysn0h1000035825pb794vy', 'Ms. Jasmin', 'Sheep Herder', NULL, '/uploads/staff/f14a2dce-7e1e-415b-b8ed-98e7e7b528b9.jpg', 3, '2026-05-09 20:25:39.109');
INSERT INTO `StaffMember` (`id`, `name`, `title`, `bio`, `photoUrl`, `sortOrder`, `createdAt`) VALUES ('56565cb722bb49479781e619191994c5', 'ChadGPT', 'IT Support', NULL, '/uploads/staff/2bba762d-aa15-4139-b4ba-fd2378d39a97.png', 4, '2026-05-23 05:50:24.734');


-- StaffNote (0 rows) -- ──────────────────────────────────────────────────────────────
TRUNCATE TABLE `StaffNote`;


-- GalleryImage (0 rows) -- ───────────────────────────────────────────────────────────
TRUNCATE TABLE `GalleryImage`;


-- Inquiry (10 rows) -- ────────────────────────────────────────────────────────────────
TRUNCATE TABLE `Inquiry`;
INSERT INTO `Inquiry` (`id`, `parentName`, `parentPhone`, `parentEmail`, `childName`, `childDob`, `desiredStart`, `programInterest`, `hearAbout`, `message`, `status`, `isSnoozed`, `snoozeUntil`, `createdAt`, `updatedAt`) VALUES ('cmoysjx4v0000sho489mwnby2', 'Jennifer Caldwell', '(909) 555-0142', 'jen.caldwell@email.com', 'Mason Caldwell', '2022-03-14', '2025-06-02', 'Toddler', 'Google', 'We\'re looking for a warm, home-based setting for Mason. He\'s really social and loves music and outdoor play.', 'NEW', 0, NULL, '2026-05-09 20:23:14.815', '2026-05-09 20:23:14.815');
INSERT INTO `Inquiry` (`id`, `parentName`, `parentPhone`, `parentEmail`, `childName`, `childDob`, `desiredStart`, `programInterest`, `hearAbout`, `message`, `status`, `isSnoozed`, `snoozeUntil`, `createdAt`, `updatedAt`) VALUES ('cmoysjx530001sho4k5cgubnw', 'David & Priya Nair', '(909) 555-0278', 'priya.nair@gmail.com', 'Anika Nair', '2023-07-29', '2025-07-14', 'Infant', 'Nextdoor', 'Anika will be 1 year old when we need care to start. We both work full-time and need reliable Mon–Fri coverage.', 'LEFT_VOICEMAIL', 0, NULL, '2026-05-09 20:23:14.824', '2026-05-09 20:23:14.824');
INSERT INTO `Inquiry` (`id`, `parentName`, `parentPhone`, `parentEmail`, `childName`, `childDob`, `desiredStart`, `programInterest`, `hearAbout`, `message`, `status`, `isSnoozed`, `snoozeUntil`, `createdAt`, `updatedAt`) VALUES ('cmoysjx5a0002sho4zscoqqpz', 'Marcus Thompson', '(909) 555-0394', 'm.thompson84@outlook.com', 'Jaylen Thompson', '2021-11-05', '2025-08-01', 'Preschool', 'Facebook', 'Jaylen is energetic and loves building blocks. We toured one other place but this felt like a much better fit from the website.', 'PROVIDED_PRICING_WAITING', 0, NULL, '2026-05-09 20:23:14.831', '2026-05-09 20:23:14.831');
INSERT INTO `Inquiry` (`id`, `parentName`, `parentPhone`, `parentEmail`, `childName`, `childDob`, `desiredStart`, `programInterest`, `hearAbout`, `message`, `status`, `isSnoozed`, `snoozeUntil`, `createdAt`, `updatedAt`) VALUES ('cmoysjx5h0003sho4gtqn0sks', 'Sandra Kim', '(909) 555-0517', 'sandra.kim.hb@yahoo.com', 'Lily Kim', '2020-05-18', '2025-06-16', 'Preschool', 'Referral from another parent', 'My neighbor\'s daughter goes here and she absolutely raves about it. Lily has been on a waitlist at a center-based program for 6 months.', 'FOLLOW_UP_WHEN_ROOM', 0, NULL, '2026-05-09 20:23:14.838', '2026-05-09 20:23:14.838');
INSERT INTO `Inquiry` (`id`, `parentName`, `parentPhone`, `parentEmail`, `childName`, `childDob`, `desiredStart`, `programInterest`, `hearAbout`, `message`, `status`, `isSnoozed`, `snoozeUntil`, `createdAt`, `updatedAt`) VALUES ('cmoysjx5o0004sho4fa5vcmg8', 'Brittany & Carlos Reyes', '(909) 555-0663', 'breyes_family@gmail.com', 'Sofia Reyes', '2022-09-02', '2025-06-09', 'Toddler', 'Yelp', '', 'LEFT_VOICEMAIL_2', 0, NULL, '2026-05-09 20:23:14.845', '2026-05-09 20:23:14.845');
INSERT INTO `Inquiry` (`id`, `parentName`, `parentPhone`, `parentEmail`, `childName`, `childDob`, `desiredStart`, `programInterest`, `hearAbout`, `message`, `status`, `isSnoozed`, `snoozeUntil`, `createdAt`, `updatedAt`) VALUES ('cmoysjx5u0005sho4cgkpp7cq', 'Amanda Foster', '(909) 555-0721', 'amanda.foster@icloud.com', 'Noah Foster', '2019-12-22', '2025-09-02', 'School Age', 'Google', 'Noah starts kindergarten in the fall but we need before/after school care. He\'s very independent and loves reading.', 'COMPLETE', 0, NULL, '2026-05-09 20:23:14.851', '2026-05-09 20:54:53.996');
INSERT INTO `Inquiry` (`id`, `parentName`, `parentPhone`, `parentEmail`, `childName`, `childDob`, `desiredStart`, `programInterest`, `hearAbout`, `message`, `status`, `isSnoozed`, `snoozeUntil`, `createdAt`, `updatedAt`) VALUES ('cmoysjx610006sho4rqlz6sem', 'Terrence & Monique Williams', '(909) 555-0845', 'mwilliams_care@gmail.com', 'Zara Williams', '2023-02-11', '2025-08-18', 'Infant', 'Instagram', 'We\'ve been searching for a while. Zara is very attached to her mom right now so a small, home environment is really important to us.', 'COMPLETE', 0, NULL, '2026-05-09 20:23:14.858', '2026-05-09 20:59:17.374');
INSERT INTO `Inquiry` (`id`, `parentName`, `parentPhone`, `parentEmail`, `childName`, `childDob`, `desiredStart`, `programInterest`, `hearAbout`, `message`, `status`, `isSnoozed`, `snoozeUntil`, `createdAt`, `updatedAt`) VALUES ('cmoysjx680007sho43qpnr7f0', 'Rachel Nguyen', '(909) 555-0932', 'rachel.nguyen.hld@gmail.com', 'Ethan Nguyen', '2021-06-30', '2025-06-23', 'Toddler', 'Nextdoor', 'Part-time care needed, ideally T/TH or M/W/F. Let me know what openings look like.', 'FOLLOW_UP_WHEN_ROOM', 0, NULL, '2026-05-09 20:23:14.864', '2026-05-20 04:21:56.279');
INSERT INTO `Inquiry` (`id`, `parentName`, `parentPhone`, `parentEmail`, `childName`, `childDob`, `desiredStart`, `programInterest`, `hearAbout`, `message`, `status`, `isSnoozed`, `snoozeUntil`, `createdAt`, `updatedAt`) VALUES ('cmoysjx6g0008sho420b8bj6j', 'Kevin & Lisa Park', '(909) 555-1054', 'lisakpark@gmail.com', 'Hannah Park', '2020-10-07', '2025-07-07', 'Preschool', 'Google', 'Hannah is very articulate for her age and we\'re looking for a program that encourages learning through play. We have a dog at home so she\'s comfortable around animals.', 'COMPLETE', 0, NULL, '2026-05-09 20:23:14.872', '2026-05-09 20:23:14.872');
INSERT INTO `Inquiry` (`id`, `parentName`, `parentPhone`, `parentEmail`, `childName`, `childDob`, `desiredStart`, `programInterest`, `hearAbout`, `message`, `status`, `isSnoozed`, `snoozeUntil`, `createdAt`, `updatedAt`) VALUES ('cmoysjx6n0009sho4mokmuvti', 'Danielle Ortiz', '(909) 555-1187', 'd.ortiz.highland@outlook.com', 'Marco Ortiz', '2022-12-19', '2025-06-30', 'Toddler', 'Facebook', 'Marco is pretty shy at first but warms up quickly. He loves dinosaurs and trucks. We currently have him with a relative but that arrangement is ending.', 'COMPLETE', 0, NULL, '2026-05-09 20:23:14.879', '2026-05-09 20:32:39.968');


-- InquiryNote (6 rows) -- ────────────────────────────────────────────────────────────
TRUNCATE TABLE `InquiryNote`;
INSERT INTO `InquiryNote` (`id`, `inquiryId`, `employeeId`, `content`, `createdAt`) VALUES ('cmoyto0ew000d35827c07p07l', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'left voicemail', '2026-05-09 20:54:25.304');
INSERT INTO `InquiryNote` (`id`, `inquiryId`, `employeeId`, `content`, `createdAt`) VALUES ('cmoyto6bx000h3582tv7j4nkf', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', '2nd voicemail left\n', '2026-05-09 20:54:32.974');
INSERT INTO `InquiryNote` (`id`, `inquiryId`, `employeeId`, `content`, `createdAt`) VALUES ('cmoytoccp000l3582ltww93zc', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'Final voicemal\n', '2026-05-09 20:54:40.777');
INSERT INTO `InquiryNote` (`id`, `inquiryId`, `employeeId`, `content`, `createdAt`) VALUES ('cmoytoj3c000p3582ovs686fc', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'Pricing provided, waiting response.', '2026-05-09 20:54:49.513');
INSERT INTO `InquiryNote` (`id`, `inquiryId`, `employeeId`, `content`, `createdAt`) VALUES ('cmoytosnf000t3582oijetgsb', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'Not interested at this time.', '2026-05-09 20:55:01.900');
INSERT INTO `InquiryNote` (`id`, `inquiryId`, `employeeId`, `content`, `createdAt`) VALUES ('cmoytpbmb000v358211rkmuwf', 'cmoysjx610006sho4rqlz6sem', 'cmoyscrcn000014h1mfzh7yro', 'Accepted pricing, creating account.', '2026-05-09 20:55:26.483');


-- InquiryStatusHistory (8 rows) -- ───────────────────────────────────────────────────
TRUNCATE TABLE `InquiryStatusHistory`;
INSERT INTO `InquiryStatusHistory` (`id`, `inquiryId`, `employeeId`, `oldStatus`, `newStatus`, `createdAt`) VALUES ('cmoysw17l00043582ozowrk1q', 'cmoysjx6n0009sho4mokmuvti', 'cmoyscrcn000014h1mfzh7yro', 'PROVIDED_PRICING_WAITING', 'COMPLETE', '2026-05-09 20:32:39.968');
INSERT INTO `InquiryStatusHistory` (`id`, `inquiryId`, `employeeId`, `oldStatus`, `newStatus`, `createdAt`) VALUES ('cmoytnwsc000b3582jccd5m1f', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'NEW', 'LEFT_VOICEMAIL', '2026-05-09 20:54:20.604');
INSERT INTO `InquiryStatusHistory` (`id`, `inquiryId`, `employeeId`, `oldStatus`, `newStatus`, `createdAt`) VALUES ('cmoyto2ok000f3582z7m02eyx', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'LEFT_VOICEMAIL', 'LEFT_VOICEMAIL_2', '2026-05-09 20:54:28.244');
INSERT INTO `InquiryStatusHistory` (`id`, `inquiryId`, `employeeId`, `oldStatus`, `newStatus`, `createdAt`) VALUES ('cmoyto7lo000j3582ncyvh7v3', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'LEFT_VOICEMAIL_2', 'LEFT_VOICEMAIL_FINAL', '2026-05-09 20:54:34.620');
INSERT INTO `InquiryStatusHistory` (`id`, `inquiryId`, `employeeId`, `oldStatus`, `newStatus`, `createdAt`) VALUES ('cmoytoct3000n35827xqznd2n', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'LEFT_VOICEMAIL_FINAL', 'PROVIDED_PRICING_WAITING', '2026-05-09 20:54:41.367');
INSERT INTO `InquiryStatusHistory` (`id`, `inquiryId`, `employeeId`, `oldStatus`, `newStatus`, `createdAt`) VALUES ('cmoytomjw000r3582bpbh9w7c', 'cmoysjx5u0005sho4cgkpp7cq', 'cmoyscrcn000014h1mfzh7yro', 'PROVIDED_PRICING_WAITING', 'COMPLETE', '2026-05-09 20:54:53.996');
INSERT INTO `InquiryStatusHistory` (`id`, `inquiryId`, `employeeId`, `oldStatus`, `newStatus`, `createdAt`) VALUES ('cmoytu9ry000z3582b6bd7zo7', 'cmoysjx610006sho4rqlz6sem', 'cmoyscrcn000014h1mfzh7yro', 'NEW', 'COMPLETE', '2026-05-09 20:59:17.374');
INSERT INTO `InquiryStatusHistory` (`id`, `inquiryId`, `employeeId`, `oldStatus`, `newStatus`, `createdAt`) VALUES ('fc5ddec3697c429b96eb84bdcd84acd3', 'cmoysjx680007sho43qpnr7f0', 'cmoyscrcn000014h1mfzh7yro', 'LEFT_VOICEMAIL_FINAL', 'FOLLOW_UP_WHEN_ROOM', '2026-05-20 04:21:56.292');


-- Child (2 rows) -- ──────────────────────────────────────────────────────────────────
TRUNCATE TABLE `Child`;
INSERT INTO `Child` (`id`, `inquiryId`, `firstName`, `lastName`, `dateOfBirth`, `expectedStart`, `schedule`, `status`, `photoUrl`, `notes`, `checkedInAt`, `createdAt`, `updatedAt`) VALUES ('cmoysw16500023582igxbk2tp', 'cmoysjx6n0009sho4mokmuvti', 'Marco', 'Ortiz', '2022-12-19', '2025-06-30', '[{"day":"MON","startTime":"08:00","endTime":"17:00"},{"day":"WED","startTime":"08:00","endTime":"17:00"},{"day":"FRI","startTime":"08:00","endTime":"17:00"}]', 'ACTIVE', '/uploads/children/cmoysw16500023582igxbk2tp.jpg', 'Marco is pretty shy at first but warms up quickly. He loves dinosaurs and trucks. We currently have him with a relative but that arrangement is ending.', NULL, '2026-05-09 20:32:39.917', '2026-05-20 04:21:25.510');
INSERT INTO `Child` (`id`, `inquiryId`, `firstName`, `lastName`, `dateOfBirth`, `expectedStart`, `schedule`, `status`, `photoUrl`, `notes`, `checkedInAt`, `createdAt`, `updatedAt`) VALUES ('cmoytu9kr000x3582qh5md4wy', 'cmoysjx610006sho4rqlz6sem', 'Zara', 'Williams', '2023-02-11', '2026-08-18', '[{"day":"TUE","startTime":"09:00","endTime":"16:00"},{"day":"WED","startTime":"09:00","endTime":"16:00"},{"day":"THU","startTime":"09:00","endTime":"16:00"}]', 'ACTIVE', '/uploads/children/cmoytu9kr000x3582qh5md4wy.png', 'We\'ve been searching for a while. Zara is very attached to her mom right now so a small, home environment is really important to us.', NULL, '2026-05-09 20:59:17.115', '2026-05-19 20:07:25.140');


-- ChildNote (11 rows) -- ──────────────────────────────────────────────────────────────
TRUNCATE TABLE `ChildNote`;
INSERT INTO `ChildNote` (`id`, `childId`, `content`, `createdAt`, `updatedAt`) VALUES ('cmoytxa4x00143582it8oby5y', 'cmoytu9kr000x3582qh5md4wy', 'Test', '2026-05-09 21:01:37.809', '2026-05-09 21:01:37.809');
INSERT INTO `ChildNote` (`id`, `childId`, `content`, `createdAt`, `updatedAt`) VALUES ('cmoyu0hbz00163582n6j7x5iu', 'cmoytu9kr000x3582qh5md4wy', '[Checked In] May 9, 2026, 2:04 PM', '2026-05-09 21:04:07.103', '2026-05-09 21:04:07.103');
INSERT INTO `ChildNote` (`id`, `childId`, `content`, `createdAt`, `updatedAt`) VALUES ('cmoyu84co00183582ej06r9ac', 'cmoytu9kr000x3582qh5md4wy', '[Checked Out] May 9, 2026, 2:10 PM (checked in at 2:04 PM)', '2026-05-09 21:10:03.528', '2026-05-09 21:10:03.528');
INSERT INTO `ChildNote` (`id`, `childId`, `content`, `createdAt`, `updatedAt`) VALUES ('cmoyu85ot001a3582yp9wnvp2', 'cmoysw16500023582igxbk2tp', '[Checked In] May 9, 2026, 2:10 PM', '2026-05-09 21:10:05.261', '2026-05-09 21:10:05.261');
INSERT INTO `ChildNote` (`id`, `childId`, `content`, `createdAt`, `updatedAt`) VALUES ('cmoyu86p2001c3582hf7wav0v', 'cmoysw16500023582igxbk2tp', '[Checked Out] May 9, 2026, 2:10 PM (checked in at 2:10 PM)', '2026-05-09 21:10:06.566', '2026-05-09 21:10:06.566');
INSERT INTO `ChildNote` (`id`, `childId`, `content`, `createdAt`, `updatedAt`) VALUES ('cmoyubjxo001e3582q5tfutqf', 'cmoysw16500023582igxbk2tp', '[Checked In] May 9, 2026, 2:12 PM', '2026-05-09 21:12:43.692', '2026-05-09 21:12:43.692');
INSERT INTO `ChildNote` (`id`, `childId`, `content`, `createdAt`, `updatedAt`) VALUES ('cmoyubmrp001g3582n67kc6wf', 'cmoysw16500023582igxbk2tp', '[Checked Out] May 9, 2026, 2:12 PM (checked in at 2:12 PM)', '2026-05-09 21:12:47.365', '2026-05-09 21:12:47.365');
INSERT INTO `ChildNote` (`id`, `childId`, `content`, `createdAt`, `updatedAt`) VALUES ('22df3edadfef40aca9c88ced16f4bc95', 'cmoytu9kr000x3582qh5md4wy', '[Checked In] May 19, 2026, 1:07 PM', '2026-05-19 20:07:14.862', '2026-05-19 20:07:14.862');
INSERT INTO `ChildNote` (`id`, `childId`, `content`, `createdAt`, `updatedAt`) VALUES ('9cf996f75036455db8ae41a46156730d', 'cmoytu9kr000x3582qh5md4wy', '[Checked Out] May 19, 2026, 1:07 PM (checked in at 1:07 PM)', '2026-05-19 20:07:25.156', '2026-05-19 20:07:25.156');
INSERT INTO `ChildNote` (`id`, `childId`, `content`, `createdAt`, `updatedAt`) VALUES ('7725e76b7b794813b0f2cbac2261df13', 'cmoysw16500023582igxbk2tp', '[Checked In] May 19, 2026, 9:19 PM', '2026-05-20 04:19:57.941', '2026-05-20 04:19:57.941');
INSERT INTO `ChildNote` (`id`, `childId`, `content`, `createdAt`, `updatedAt`) VALUES ('a0d56eea43094b679fa2079c6210d156', 'cmoysw16500023582igxbk2tp', '[Checked Out] May 19, 2026, 9:21 PM (checked in at 9:19 PM)', '2026-05-20 04:21:25.525', '2026-05-20 04:21:25.525');


-- ChildDocument (2 rows) -- ──────────────────────────────────────────────────────────
TRUNCATE TABLE `ChildDocument`;
INSERT INTO `ChildDocument` (`id`, `childId`, `title`, `name`, `fileUrl`, `mimeType`, `size`, `uploadedByParent`, `uploadedAt`) VALUES ('cmoyv9yxo001r3582goecke5l', 'cmoytu9kr000x3582qh5md4wy', NULL, 'logo_3_roberts_family_childcare.png', '/uploads/children/docs/cmoytu9kr000x3582qh5md4wy/moyv9yxl-5qx2r2.png', 'image/png', 242033, 0, '2026-05-09 21:39:29.437');
INSERT INTO `ChildDocument` (`id`, `childId`, `title`, `name`, `fileUrl`, `mimeType`, `size`, `uploadedByParent`, `uploadedAt`) VALUES ('cmoyva64v001t3582q9knok1d', 'cmoytu9kr000x3582qh5md4wy', NULL, 'feature_graphic_1024x500.png', '/uploads/children/docs/cmoytu9kr000x3582qh5md4wy/moyva64s-0lpfj5.png', 'image/png', 623705, 0, '2026-05-09 21:39:38.768');


-- ChildContact (3 rows) -- ───────────────────────────────────────────────────────────
TRUNCATE TABLE `ChildContact`;
INSERT INTO `ChildContact` (`id`, `childId`, `contactId`, `relationship`, `isPrimary`, `addedByParent`, `createdAt`) VALUES ('cmoyszkxn00073582yibch3tt', 'cmoysw16500023582igxbk2tp', 'cmoyszkxg00053582v740m07b', 'Mother', 0, 0, '2026-05-09 20:35:25.500');
INSERT INTO `ChildContact` (`id`, `childId`, `contactId`, `relationship`, `isPrimary`, `addedByParent`, `createdAt`) VALUES ('cmoytug4600123582wzir650i', 'cmoytu9kr000x3582qh5md4wy', 'cmoytug3y00103582r86ik5ve', 'Mother', 0, 0, '2026-05-09 20:59:25.590');
INSERT INTO `ChildContact` (`id`, `childId`, `contactId`, `relationship`, `isPrimary`, `addedByParent`, `createdAt`) VALUES ('cmoyv9r29001p3582inpzos16', 'cmoytu9kr000x3582qh5md4wy', 'cmoyv9r22001n3582yz0m6qlm', 'Grandfather', 1, 1, '2026-05-09 21:39:19.234');


-- ParentUser (2 rows) -- ─────────────────────────────────────────────────────────────
TRUNCATE TABLE `ParentUser`;
INSERT INTO `ParentUser` (`id`, `contactId`, `username`, `password`, `mustChangePassword`, `createdAt`, `updatedAt`) VALUES ('cmoyszqpm00093582vvta5tsn', 'cmoyszkxg00053582v740m07b', 'daortiz', '$2a$10$5JTTg8vNzU1B1LikvEahduJKM8LG6zTuLHoFwVu2vLTrJ0csBPMMC', 0, '2026-05-09 20:35:32.986', '2026-05-09 20:36:12.973');
INSERT INTO `ParentUser` (`id`, `contactId`, `username`, `password`, `mustChangePassword`, `createdAt`, `updatedAt`) VALUES ('cmoyudtzy001i3582hldsphfz', 'cmoytug3y00103582r86ik5ve', 'temoniquewilliams', '$2a$10$KACjbJdqbaWy.h7JL6PdF.HoMEYohwkP9VY4WpOcsJCbj/s8iCXku', 0, '2026-05-09 21:14:30.046', '2026-05-09 21:36:50.986');


-- Message (6 rows) -- ────────────────────────────────────────────────────────────────
TRUNCATE TABLE `Message`;
INSERT INTO `Message` (`id`, `parentUserId`, `senderRole`, `senderId`, `senderName`, `body`, `readAt`, `createdAt`) VALUES ('cmoyw29qu0001k1jg7mntmmye', 'cmoyudtzy001i3582hldsphfz', 'PARENT', 'cmoyudtzy001i3582hldsphfz', 'temoniquewilliams', 'Hi!', '2026-05-09 22:02:09.045', '2026-05-09 22:01:29.814');
INSERT INTO `Message` (`id`, `parentUserId`, `senderRole`, `senderId`, `senderName`, `body`, `readAt`, `createdAt`) VALUES ('cmoyw3aaa0003k1jgfc3fc724', 'cmoyudtzy001i3582hldsphfz', 'STAFF', 'cmoyscrcn000014h1mfzh7yro', 'Admin', 'Test 123', '2026-05-09 22:02:36.014', '2026-05-09 22:02:17.171');
INSERT INTO `Message` (`id`, `parentUserId`, `senderRole`, `senderId`, `senderName`, `body`, `readAt`, `createdAt`) VALUES ('cmoyw3trr0005k1jgh1zoouvv', 'cmoyudtzy001i3582hldsphfz', 'PARENT', 'cmoyudtzy001i3582hldsphfz', 'temoniquewilliams', 'No way', '2026-05-09 22:02:44.120', '2026-05-09 22:02:42.424');
INSERT INTO `Message` (`id`, `parentUserId`, `senderRole`, `senderId`, `senderName`, `body`, `readAt`, `createdAt`) VALUES ('cmoyw40ck0007k1jgzpaybz43', 'cmoyudtzy001i3582hldsphfz', 'STAFF', 'cmoyscrcn000014h1mfzh7yro', 'Admin', 'looks like its working', '2026-05-09 22:02:50.996', '2026-05-09 22:02:50.948');
INSERT INTO `Message` (`id`, `parentUserId`, `senderRole`, `senderId`, `senderName`, `body`, `readAt`, `createdAt`) VALUES ('cmoyw4tya0009k1jgvd6cn8z7', 'cmoyszqpm00093582vvta5tsn', 'PARENT', 'cmoyszqpm00093582vvta5tsn', 'daortiz', 'Lets test a 2nd chat', '2026-05-09 22:03:36.375', '2026-05-09 22:03:29.314');
INSERT INTO `Message` (`id`, `parentUserId`, `senderRole`, `senderId`, `senderName`, `body`, `readAt`, `createdAt`) VALUES ('cmoyw518r000bk1jg2isf7t54', 'cmoyszqpm00093582vvta5tsn', 'STAFF', 'cmoyscrcn000014h1mfzh7yro', 'Admin', 'Nice!', '2026-05-09 22:03:39.115', '2026-05-09 22:03:38.763');


-- TestimonialLink (0 rows) -- ────────────────────────────────────────────────────────
TRUNCATE TABLE `TestimonialLink`;


-- Testimonial (0 rows) -- ────────────────────────────────────────────────────────────
TRUNCATE TABLE `Testimonial`;


SET FOREIGN_KEY_CHECKS = 1;
