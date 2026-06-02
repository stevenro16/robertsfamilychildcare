<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BackupSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('Employee')->insert([
            ['id' => 'cmoyscrcn000014h1mfzh7yro', 'name' => 'Admin', 'email' => 'sgroberts', 'password' => '$2a$10$z34NvTrxlGLl/UCnspLHLODvt/RoJDvn8GgZg847gupEhg3dvJxLu', 'role' => 'ADMIN', 'mustChangePassword' => 0, 'isActive' => 1, 'createdAt' => '2026-05-09 20:17:40'],
            ['id' => '1346b3fd918b49ddb025a3a1bceae8c0', 'name' => 'Liz Roberts', 'email' => 'lroberts@robertsfamilychildcare.com', 'password' => '$2a$10$Nb3S1G8YlwMr3W9lJxNVYuhodOv.jAMk5Uai/o1cLANrPLVR.0DZK', 'role' => 'ADMIN', 'mustChangePassword' => 1, 'isActive' => 1, 'createdAt' => '2026-05-23 05:58:03'],
        ]);

        DB::table('SiteContent')->insert([
            ['id' => 'cmoyscrd3000314h16zrwjlns', 'key' => 'about_blurb', 'value' => 'Roberts Family ChildCare is a warm, nurturing home-based childcare located in Highland, CA. We provide a safe, loving environment where children can grow, explore, and thrive. Our program is built on the belief that every child deserves individualized attention and a joyful early childhood experience. Update this text in your portal under Settings → About.', 'updatedAt' => '2026-05-09 20:17:40'],
        ]);

        DB::table('Contact')->insert([
            ['id' => 'cmoyszkxg00053582v740m07b', 'name' => 'Danielle Ortiz', 'phone' => '(909) 555-1187', 'email' => 'd.ortiz.highland@outlook.com', 'photoUrl' => null, 'createdAt' => '2026-05-09 20:35:25', 'updatedAt' => '2026-05-09 20:35:25'],
            ['id' => 'cmoytug3y00103582r86ik5ve', 'name' => 'Terrence & Monique Williams', 'phone' => '(909) 555-0845', 'email' => 'mwilliams_care@gmail.com', 'photoUrl' => null, 'createdAt' => '2026-05-09 20:59:25', 'updatedAt' => '2026-05-09 20:59:25'],
            ['id' => 'cmoyv9r22001n3582yz0m6qlm', 'name' => 'Steve Williams', 'phone' => '9095555555', 'email' => null, 'photoUrl' => '/uploads/contacts/cmoyv9r22001n3582yz0m6qlm.png', 'createdAt' => '2026-05-09 21:39:19', 'updatedAt' => '2026-05-09 21:39:19'],
        ]);

        DB::table('StaffMember')->insert([
            ['id' => 'cmoyscrcw000114h13kvjj1tl', 'name' => 'Ms. Liz', 'title' => 'Owner', 'bio' => null, 'photoUrl' => '/uploads/staff/78a32141-fdc0-4d78-8174-53ea389c29e1.jpg', 'sortOrder' => 1, 'isActive' => 1, 'createdAt' => '2026-05-09 20:17:40'],
            ['id' => 'cmoyscrcw000214h11sjflzt2', 'name' => 'Ms. Jyll', 'title' => 'Nap Queen', 'bio' => 'Staff bio coming soon.', 'photoUrl' => '/uploads/staff/2315769e-d5e6-4fef-a359-fae8055d90f1.jpg', 'sortOrder' => 2, 'isActive' => 1, 'createdAt' => '2026-05-09 20:17:40'],
            ['id' => 'cmoysn0h1000035825pb794vy', 'name' => 'Ms. Jasmin', 'title' => 'Sheep Herder', 'bio' => null, 'photoUrl' => '/uploads/staff/f14a2dce-7e1e-415b-b8ed-98e7e7b528b9.jpg', 'sortOrder' => 3, 'isActive' => 1, 'createdAt' => '2026-05-09 20:25:39'],
            ['id' => '56565cb722bb49479781e619191994c5', 'name' => 'ChadGPT', 'title' => 'IT Support', 'bio' => null, 'photoUrl' => '/uploads/staff/2bba762d-aa15-4139-b4ba-fd2378d39a97.png', 'sortOrder' => 4, 'isActive' => 1, 'createdAt' => '2026-05-23 05:50:24'],
        ]);

        DB::table('Inquiry')->insert([
            ['id' => 'cmoysjx4v0000sho489mwnby2', 'parentName' => 'Jennifer Caldwell', 'parentPhone' => '(909) 555-0142', 'parentEmail' => 'jen.caldwell@email.com', 'childName' => 'Mason Caldwell', 'childDob' => '2022-03-14', 'desiredStart' => '2025-06-02', 'programInterest' => 'Toddler', 'hearAbout' => 'Google', 'message' => "We're looking for a warm, home-based setting for Mason. He's really social and loves music and outdoor play.", 'status' => 'NEW', 'isSnoozed' => 0, 'snoozeUntil' => null, 'createdAt' => '2026-05-09 20:23:14', 'updatedAt' => '2026-05-09 20:23:14'],
            ['id' => 'cmoysjx530001sho4k5cgubnw', 'parentName' => 'David & Priya Nair', 'parentPhone' => '(909) 555-0278', 'parentEmail' => 'priya.nair@gmail.com', 'childName' => 'Anika Nair', 'childDob' => '2023-07-29', 'desiredStart' => '2025-07-14', 'programInterest' => 'Infant', 'hearAbout' => 'Nextdoor', 'message' => 'Anika will be 1 year old when we need care to start. We both work full-time and need reliable Mon–Fri coverage.', 'status' => 'LEFT_VOICEMAIL', 'isSnoozed' => 0, 'snoozeUntil' => null, 'createdAt' => '2026-05-09 20:23:14', 'updatedAt' => '2026-05-09 20:23:14'],
            ['id' => 'cmoysjx5a0002sho4zscoqqpz', 'parentName' => 'Marcus Thompson', 'parentPhone' => '(909) 555-0394', 'parentEmail' => 'm.thompson84@outlook.com', 'childName' => 'Jaylen Thompson', 'childDob' => '2021-11-05', 'desiredStart' => '2025-08-01', 'programInterest' => 'Preschool', 'hearAbout' => 'Facebook', 'message' => "Jaylen is energetic and loves building blocks. We toured one other place but this felt like a much better fit from the website.", 'status' => 'PROVIDED_PRICING_WAITING', 'isSnoozed' => 0, 'snoozeUntil' => null, 'createdAt' => '2026-05-09 20:23:14', 'updatedAt' => '2026-05-09 20:23:14'],
            ['id' => 'cmoysjx5h0003sho4gtqn0sks', 'parentName' => 'Sandra Kim', 'parentPhone' => '(909) 555-0517', 'parentEmail' => 'sandra.kim.hb@yahoo.com', 'childName' => 'Lily Kim', 'childDob' => '2020-05-18', 'desiredStart' => '2025-06-16', 'programInterest' => 'Preschool', 'hearAbout' => 'Referral from another parent', 'message' => "My neighbor's daughter goes here and she absolutely raves about it. Lily has been on a waitlist at a center-based program for 6 months.", 'status' => 'FOLLOW_UP_WHEN_ROOM', 'isSnoozed' => 0, 'snoozeUntil' => null, 'createdAt' => '2026-05-09 20:23:14', 'updatedAt' => '2026-05-09 20:23:14'],
            ['id' => 'cmoysjx5o0004sho4fa5vcmg8', 'parentName' => 'Brittany & Carlos Reyes', 'parentPhone' => '(909) 555-0663', 'parentEmail' => 'breyes_family@gmail.com', 'childName' => 'Sofia Reyes', 'childDob' => '2022-09-02', 'desiredStart' => '2025-06-09', 'programInterest' => 'Toddler', 'hearAbout' => 'Yelp', 'message' => '', 'status' => 'LEFT_VOICEMAIL_2', 'isSnoozed' => 0, 'snoozeUntil' => null, 'createdAt' => '2026-05-09 20:23:14', 'updatedAt' => '2026-05-09 20:23:14'],
            ['id' => 'cmoysjx5u0005sho4cgkpp7cq', 'parentName' => 'Amanda Foster', 'parentPhone' => '(909) 555-0721', 'parentEmail' => 'amanda.foster@icloud.com', 'childName' => 'Noah Foster', 'childDob' => '2019-12-22', 'desiredStart' => '2025-09-02', 'programInterest' => 'School Age', 'hearAbout' => 'Google', 'message' => "Noah starts kindergarten in the fall but we need before/after school care. He's very independent and loves reading.", 'status' => 'COMPLETE', 'isSnoozed' => 0, 'snoozeUntil' => null, 'createdAt' => '2026-05-09 20:23:14', 'updatedAt' => '2026-05-09 20:54:53'],
            ['id' => 'cmoysjx610006sho4rqlz6sem', 'parentName' => 'Terrence & Monique Williams', 'parentPhone' => '(909) 555-0845', 'parentEmail' => 'mwilliams_care@gmail.com', 'childName' => 'Zara Williams', 'childDob' => '2023-02-11', 'desiredStart' => '2025-08-18', 'programInterest' => 'Infant', 'hearAbout' => 'Instagram', 'message' => "We've been searching for a while. Zara is very attached to her mom right now so a small, home environment is really important to us.", 'status' => 'COMPLETE', 'isSnoozed' => 0, 'snoozeUntil' => null, 'createdAt' => '2026-05-09 20:23:14', 'updatedAt' => '2026-05-09 20:59:17'],
            ['id' => 'cmoysjx680007sho43qpnr7f0', 'parentName' => 'Rachel Nguyen', 'parentPhone' => '(909) 555-0932', 'parentEmail' => 'rachel.nguyen.hld@gmail.com', 'childName' => 'Ethan Nguyen', 'childDob' => '2021-06-30', 'desiredStart' => '2025-06-23', 'programInterest' => 'Toddler', 'hearAbout' => 'Nextdoor', 'message' => 'Part-time care needed, ideally T/TH or M/W/F. Let me know what openings look like.', 'status' => 'FOLLOW_UP_WHEN_ROOM', 'isSnoozed' => 0, 'snoozeUntil' => null, 'createdAt' => '2026-05-09 20:23:14', 'updatedAt' => '2026-05-20 04:21:56'],
            ['id' => 'cmoysjx6g0008sho420b8bj6j', 'parentName' => 'Kevin & Lisa Park', 'parentPhone' => '(909) 555-1054', 'parentEmail' => 'lisakpark@gmail.com', 'childName' => 'Hannah Park', 'childDob' => '2020-10-07', 'desiredStart' => '2025-07-07', 'programInterest' => 'Preschool', 'hearAbout' => 'Google', 'message' => "Hannah is very articulate for her age and we're looking for a program that encourages learning through play. We have a dog at home so she's comfortable around animals.", 'status' => 'COMPLETE', 'isSnoozed' => 0, 'snoozeUntil' => null, 'createdAt' => '2026-05-09 20:23:14', 'updatedAt' => '2026-05-09 20:23:14'],
            ['id' => 'cmoysjx6n0009sho4mokmuvti', 'parentName' => 'Danielle Ortiz', 'parentPhone' => '(909) 555-1187', 'parentEmail' => 'd.ortiz.highland@outlook.com', 'childName' => 'Marco Ortiz', 'childDob' => '2022-12-19', 'desiredStart' => '2025-06-30', 'programInterest' => 'Toddler', 'hearAbout' => 'Facebook', 'message' => "Marco is pretty shy at first but warms up quickly. He loves dinosaurs and trucks. We currently have him with a relative but that arrangement is ending.", 'status' => 'COMPLETE', 'isSnoozed' => 0, 'snoozeUntil' => null, 'createdAt' => '2026-05-09 20:23:14', 'updatedAt' => '2026-05-09 20:32:39'],
        ]);

        DB::table('InquiryNote')->insert([
            ['id' => 'cmoyto0ew000d35827c07p07l', 'inquiryId' => 'cmoysjx5u0005sho4cgkpp7cq', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'content' => 'left voicemail', 'createdAt' => '2026-05-09 20:54:25'],
            ['id' => 'cmoyto6bx000h3582tv7j4nkf', 'inquiryId' => 'cmoysjx5u0005sho4cgkpp7cq', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'content' => "2nd voicemail left\n", 'createdAt' => '2026-05-09 20:54:32'],
            ['id' => 'cmoytoccp000l3582ltww93zc', 'inquiryId' => 'cmoysjx5u0005sho4cgkpp7cq', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'content' => "Final voicemal\n", 'createdAt' => '2026-05-09 20:54:40'],
            ['id' => 'cmoytoj3c000p3582ovs686fc', 'inquiryId' => 'cmoysjx5u0005sho4cgkpp7cq', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'content' => 'Pricing provided, waiting response.', 'createdAt' => '2026-05-09 20:54:49'],
            ['id' => 'cmoytosnf000t3582oijetgsb', 'inquiryId' => 'cmoysjx5u0005sho4cgkpp7cq', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'content' => 'Not interested at this time.', 'createdAt' => '2026-05-09 20:55:01'],
            ['id' => 'cmoytpbmb000v358211rkmuwf', 'inquiryId' => 'cmoysjx610006sho4rqlz6sem', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'content' => 'Accepted pricing, creating account.', 'createdAt' => '2026-05-09 20:55:26'],
        ]);

        DB::table('InquiryStatusHistory')->insert([
            ['id' => 'cmoysw17l00043582ozowrk1q', 'inquiryId' => 'cmoysjx6n0009sho4mokmuvti', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'oldStatus' => 'PROVIDED_PRICING_WAITING', 'newStatus' => 'COMPLETE', 'createdAt' => '2026-05-09 20:32:39'],
            ['id' => 'cmoytnwsc000b3582jccd5m1f', 'inquiryId' => 'cmoysjx5u0005sho4cgkpp7cq', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'oldStatus' => 'NEW', 'newStatus' => 'LEFT_VOICEMAIL', 'createdAt' => '2026-05-09 20:54:20'],
            ['id' => 'cmoyto2ok000f3582z7m02eyx', 'inquiryId' => 'cmoysjx5u0005sho4cgkpp7cq', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'oldStatus' => 'LEFT_VOICEMAIL', 'newStatus' => 'LEFT_VOICEMAIL_2', 'createdAt' => '2026-05-09 20:54:28'],
            ['id' => 'cmoyto7lo000j3582ncyvh7v3', 'inquiryId' => 'cmoysjx5u0005sho4cgkpp7cq', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'oldStatus' => 'LEFT_VOICEMAIL_2', 'newStatus' => 'LEFT_VOICEMAIL_FINAL', 'createdAt' => '2026-05-09 20:54:34'],
            ['id' => 'cmoytoct3000n35827xqznd2n', 'inquiryId' => 'cmoysjx5u0005sho4cgkpp7cq', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'oldStatus' => 'LEFT_VOICEMAIL_FINAL', 'newStatus' => 'PROVIDED_PRICING_WAITING', 'createdAt' => '2026-05-09 20:54:41'],
            ['id' => 'cmoytomjw000r3582bpbh9w7c', 'inquiryId' => 'cmoysjx5u0005sho4cgkpp7cq', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'oldStatus' => 'PROVIDED_PRICING_WAITING', 'newStatus' => 'COMPLETE', 'createdAt' => '2026-05-09 20:54:53'],
            ['id' => 'cmoytu9ry000z3582b6bd7zo7', 'inquiryId' => 'cmoysjx610006sho4rqlz6sem', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'oldStatus' => 'NEW', 'newStatus' => 'COMPLETE', 'createdAt' => '2026-05-09 20:59:17'],
            ['id' => 'fc5ddec3697c429b96eb84bdcd84acd3', 'inquiryId' => 'cmoysjx680007sho43qpnr7f0', 'employeeId' => 'cmoyscrcn000014h1mfzh7yro', 'oldStatus' => 'LEFT_VOICEMAIL_FINAL', 'newStatus' => 'FOLLOW_UP_WHEN_ROOM', 'createdAt' => '2026-05-20 04:21:56'],
        ]);

        DB::table('Child')->insert([
            ['id' => 'cmoysw16500023582igxbk2tp', 'inquiryId' => 'cmoysjx6n0009sho4mokmuvti', 'firstName' => 'Marco', 'lastName' => 'Ortiz', 'dateOfBirth' => '2022-12-19', 'expectedStart' => '2025-06-30', 'schedule' => '[{"day":"MON","startTime":"08:00","endTime":"17:00"},{"day":"WED","startTime":"08:00","endTime":"17:00"},{"day":"FRI","startTime":"08:00","endTime":"17:00"}]', 'status' => 'ACTIVE', 'photoUrl' => '/uploads/children/cmoysw16500023582igxbk2tp.jpg', 'notes' => 'Marco is pretty shy at first but warms up quickly. He loves dinosaurs and trucks. We currently have him with a relative but that arrangement is ending.', 'checkedInAt' => null, 'createdAt' => '2026-05-09 20:32:39', 'updatedAt' => '2026-05-20 04:21:25'],
            ['id' => 'cmoytu9kr000x3582qh5md4wy', 'inquiryId' => 'cmoysjx610006sho4rqlz6sem', 'firstName' => 'Zara', 'lastName' => 'Williams', 'dateOfBirth' => '2023-02-11', 'expectedStart' => '2026-08-18', 'schedule' => '[{"day":"TUE","startTime":"09:00","endTime":"16:00"},{"day":"WED","startTime":"09:00","endTime":"16:00"},{"day":"THU","startTime":"09:00","endTime":"16:00"}]', 'status' => 'ACTIVE', 'photoUrl' => '/uploads/children/cmoytu9kr000x3582qh5md4wy.png', 'notes' => "We've been searching for a while. Zara is very attached to her mom right now so a small, home environment is really important to us.", 'checkedInAt' => null, 'createdAt' => '2026-05-09 20:59:17', 'updatedAt' => '2026-05-19 20:07:25'],
        ]);

        DB::table('ChildNote')->insert([
            ['id' => 'cmoytxa4x00143582it8oby5y', 'childId' => 'cmoytu9kr000x3582qh5md4wy', 'employeeId' => null, 'content' => 'Test', 'createdAt' => '2026-05-09 21:01:37', 'updatedAt' => '2026-05-09 21:01:37'],
            ['id' => 'cmoyu0hbz00163582n6j7x5iu', 'childId' => 'cmoytu9kr000x3582qh5md4wy', 'employeeId' => null, 'content' => '[Checked In] May 9, 2026, 2:04 PM', 'createdAt' => '2026-05-09 21:04:07', 'updatedAt' => '2026-05-09 21:04:07'],
            ['id' => 'cmoyu84co00183582ej06r9ac', 'childId' => 'cmoytu9kr000x3582qh5md4wy', 'employeeId' => null, 'content' => '[Checked Out] May 9, 2026, 2:10 PM (checked in at 2:04 PM)', 'createdAt' => '2026-05-09 21:10:03', 'updatedAt' => '2026-05-09 21:10:03'],
            ['id' => 'cmoyu85ot001a3582yp9wnvp2', 'childId' => 'cmoysw16500023582igxbk2tp', 'employeeId' => null, 'content' => '[Checked In] May 9, 2026, 2:10 PM', 'createdAt' => '2026-05-09 21:10:05', 'updatedAt' => '2026-05-09 21:10:05'],
            ['id' => 'cmoyu86p2001c3582hf7wav0v', 'childId' => 'cmoysw16500023582igxbk2tp', 'employeeId' => null, 'content' => '[Checked Out] May 9, 2026, 2:10 PM (checked in at 2:10 PM)', 'createdAt' => '2026-05-09 21:10:06', 'updatedAt' => '2026-05-09 21:10:06'],
            ['id' => 'cmoyubjxo001e3582q5tfutqf', 'childId' => 'cmoysw16500023582igxbk2tp', 'employeeId' => null, 'content' => '[Checked In] May 9, 2026, 2:12 PM', 'createdAt' => '2026-05-09 21:12:43', 'updatedAt' => '2026-05-09 21:12:43'],
            ['id' => 'cmoyubmrp001g3582n67kc6wf', 'childId' => 'cmoysw16500023582igxbk2tp', 'employeeId' => null, 'content' => '[Checked Out] May 9, 2026, 2:12 PM (checked in at 2:12 PM)', 'createdAt' => '2026-05-09 21:12:47', 'updatedAt' => '2026-05-09 21:12:47'],
            ['id' => '22df3edadfef40aca9c88ced16f4bc95', 'childId' => 'cmoytu9kr000x3582qh5md4wy', 'employeeId' => null, 'content' => '[Checked In] May 19, 2026, 1:07 PM', 'createdAt' => '2026-05-19 20:07:14', 'updatedAt' => '2026-05-19 20:07:14'],
            ['id' => '9cf996f75036455db8ae41a46156730d', 'childId' => 'cmoytu9kr000x3582qh5md4wy', 'employeeId' => null, 'content' => '[Checked Out] May 19, 2026, 1:07 PM (checked in at 1:07 PM)', 'createdAt' => '2026-05-19 20:07:25', 'updatedAt' => '2026-05-19 20:07:25'],
            ['id' => '7725e76b7b794813b0f2cbac2261df13', 'childId' => 'cmoysw16500023582igxbk2tp', 'employeeId' => null, 'content' => '[Checked In] May 19, 2026, 9:19 PM', 'createdAt' => '2026-05-20 04:19:57', 'updatedAt' => '2026-05-20 04:19:57'],
            ['id' => 'a0d56eea43094b679fa2079c6210d156', 'childId' => 'cmoysw16500023582igxbk2tp', 'employeeId' => null, 'content' => '[Checked Out] May 19, 2026, 9:21 PM (checked in at 9:19 PM)', 'createdAt' => '2026-05-20 04:21:25', 'updatedAt' => '2026-05-20 04:21:25'],
        ]);

        DB::table('ChildDocument')->insert([
            ['id' => 'cmoyv9yxo001r3582goecke5l', 'childId' => 'cmoytu9kr000x3582qh5md4wy', 'title' => null, 'name' => 'logo_3_roberts_family_childcare.png', 'fileUrl' => '/storage/uploads/children/docs/cmoytu9kr000x3582qh5md4wy/moyv9yxl-5qx2r2.png', 'mimeType' => 'image/png', 'size' => 242033, 'uploadedByParent' => 0, 'uploadedAt' => '2026-05-09 21:39:29'],
            ['id' => 'cmoyva64v001t3582q9knok1d', 'childId' => 'cmoytu9kr000x3582qh5md4wy', 'title' => null, 'name' => 'feature_graphic_1024x500.png', 'fileUrl' => '/storage/uploads/children/docs/cmoytu9kr000x3582qh5md4wy/moyva64s-0lpfj5.png', 'mimeType' => 'image/png', 'size' => 623705, 'uploadedByParent' => 0, 'uploadedAt' => '2026-05-09 21:39:38'],
        ]);

        DB::table('ChildContact')->insert([
            ['id' => 'cmoyszkxn00073582yibch3tt', 'childId' => 'cmoysw16500023582igxbk2tp', 'contactId' => 'cmoyszkxg00053582v740m07b', 'relationship' => 'Mother', 'isPrimary' => 0, 'addedByParent' => 0, 'createdAt' => '2026-05-09 20:35:25'],
            ['id' => 'cmoytug4600123582wzir650i', 'childId' => 'cmoytu9kr000x3582qh5md4wy', 'contactId' => 'cmoytug3y00103582r86ik5ve', 'relationship' => 'Mother', 'isPrimary' => 0, 'addedByParent' => 0, 'createdAt' => '2026-05-09 20:59:25'],
            ['id' => 'cmoyv9r29001p3582inpzos16', 'childId' => 'cmoytu9kr000x3582qh5md4wy', 'contactId' => 'cmoyv9r22001n3582yz0m6qlm', 'relationship' => 'Grandfather', 'isPrimary' => 1, 'addedByParent' => 1, 'createdAt' => '2026-05-09 21:39:19'],
        ]);

        DB::table('ParentUser')->insert([
            ['id' => 'cmoyszqpm00093582vvta5tsn', 'contactId' => 'cmoyszkxg00053582v740m07b', 'username' => 'daortiz', 'password' => '$2a$10$5JTTg8vNzU1B1LikvEahduJKM8LG6zTuLHoFwVu2vLTrJ0csBPMMC', 'mustChangePassword' => 0, 'createdAt' => '2026-05-09 20:35:32', 'updatedAt' => '2026-05-09 20:36:12'],
            ['id' => 'cmoyudtzy001i3582hldsphfz', 'contactId' => 'cmoytug3y00103582r86ik5ve', 'username' => 'temoniquewilliams', 'password' => '$2a$10$KACjbJdqbaWy.h7JL6PdF.HoMEYohwkP9VY4WpOcsJCbj/s8iCXku', 'mustChangePassword' => 0, 'createdAt' => '2026-05-09 21:14:30', 'updatedAt' => '2026-05-09 21:36:50'],
        ]);

        // Message: backup used 'body' column — mapped to 'content' for the Laravel app.
        // senderId/senderName columns from old schema are not imported (not used by this app).
        DB::table('Message')->insert([
            ['id' => 'cmoyw29qu0001k1jg7mntmmye', 'parentUserId' => 'cmoyudtzy001i3582hldsphfz', 'senderRole' => 'PARENT', 'content' => 'Hi!', 'readAt' => '2026-05-09 22:02:09', 'createdAt' => '2026-05-09 22:01:29'],
            ['id' => 'cmoyw3aaa0003k1jgfc3fc724', 'parentUserId' => 'cmoyudtzy001i3582hldsphfz', 'senderRole' => 'STAFF', 'content' => 'Test 123', 'readAt' => '2026-05-09 22:02:36', 'createdAt' => '2026-05-09 22:02:17'],
            ['id' => 'cmoyw3trr0005k1jgh1zoouvv', 'parentUserId' => 'cmoyudtzy001i3582hldsphfz', 'senderRole' => 'PARENT', 'content' => 'No way', 'readAt' => '2026-05-09 22:02:44', 'createdAt' => '2026-05-09 22:02:42'],
            ['id' => 'cmoyw40ck0007k1jgzpaybz43', 'parentUserId' => 'cmoyudtzy001i3582hldsphfz', 'senderRole' => 'STAFF', 'content' => 'looks like its working', 'readAt' => '2026-05-09 22:02:50', 'createdAt' => '2026-05-09 22:02:50'],
            ['id' => 'cmoyw4tya0009k1jgvd6cn8z7', 'parentUserId' => 'cmoyszqpm00093582vvta5tsn', 'senderRole' => 'PARENT', 'content' => 'Lets test a 2nd chat', 'readAt' => '2026-05-09 22:03:36', 'createdAt' => '2026-05-09 22:03:29'],
            ['id' => 'cmoyw518r000bk1jg2isf7t54', 'parentUserId' => 'cmoyszqpm00093582vvta5tsn', 'senderRole' => 'STAFF', 'content' => 'Nice!', 'readAt' => '2026-05-09 22:03:39', 'createdAt' => '2026-05-09 22:03:38'],
        ]);
    }
}
