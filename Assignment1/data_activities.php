<?php

// ----------------------LOGIC---------------------------------
// Centralized activity dataset — Workshops and Competitions together.
// Used by activities.php (to render both catalog sections) and by
// activity_reg.php (to show the correct title + poster when booking
// a workshop slot).
// ------------------------------------------------------------------

$workshops = [
    'ws-basic-assembly' => [
        'title'        => 'Basic Assembly: Clean Builds Without Paint',
        'image'        => 'img/workshop/basic-assembly.jpg',
        'level'        => 'Beginner',
        'desc'         => 'Learn nub removal, seam checking, and part-fitting techniques to get a polished result straight out of the box.',
        'next_session' => 'Sat 6 Dec, 10:00 AM - 12:00 PM',
        'fee'          => 'RM 50',
        'venue'        => 'Gundam Hub Studio, Room A'
    ],
    'ws-panel-lining' => [
        'title'        => 'Panel Lining: Tools & Technique',
        'image'        => 'img/workshop/panel-lining.jpg',
        'level'        => 'Beginner',
        'desc'         => 'Learn how to choose the right lining tool and apply clean, even panel lines without smudging.',
        'next_session' => 'Sat 13 Dec, 10:00 AM - 12:00 PM',
        'fee'          => 'RM 50',
        'venue'        => 'Gundam Hub Studio, Room A'
    ],
    'ws-decal' => [
        'title'        => 'Decal & Sticker Application',
        'image'        => 'img/workshop/decal.jpg',
        'level'        => 'Beginner',
        'desc'         => 'Covers dry transfers, water slide decals, and how to blend them seamlessly into the kit\'s surface.',
        'next_session' => 'Sat 20 Dec, 10:00 AM - 12:00 PM',
        'fee'          => 'RM 50',
        'venue'        => 'Gundam Hub Studio, Room B'
    ],
    'ws-marker-touchup' => [
        'title'        => 'Basic Touch-Up with Gundam Markers',
        'image'        => 'img/workshop/marker.jpg',
        'level'        => 'Beginner',
        'desc'         => 'Learn how to use Gundam Markers to fix nub marks, scuffs, and small paint chips without needing an airbrush.',
        'next_session' => 'Sat 27 Dec, 10:00 AM - 12:00 PM',
        'fee'          => 'RM 50',
        'venue'        => 'Gundam Hub Studio, Room A'
    ]
];

$competitions = [
    'gbwc-13-my' => [
        'title'        => 'GBWC 13th Tournament - Malaysia',
        'image'        => 'img/competitions/gbwc-13-my.jpg',
        'level'        => 'All Levels',
        'desc'         => 'Entries exhibited and judged on-site, with course winners advancing to the World Final in Tokyo.',
        'registration' => '18 Apr - 31 Aug 2025',
        'fee'          => 'Free entry',
        'venue'        => 'MyTOWN Shopping Centre, Kuala Lumpur',
        'status'       => 'Ended'
    ],
    'gbwc-12-my' => [
        'title'        => 'GBWC 12th Tournament - Malaysia',
        'image'        => 'img/competitions/gbwc-12-my.jpg',
        'level'        => 'All Levels',
        'desc'         => 'Entries exhibited and judged on-site, with course winners advancing to the World Final in Tokyo.',
        'registration' => '3 Jun - 15 Sep 2024',
        'fee'          => 'Free entry',
        'venue'        => 'MyTOWN Shopping Centre, Kuala Lumpur',
        'status'       => 'Ended'
    ],
    'gbwc-11-my' => [
        'title'        => 'GBWC 11th Tournament - Malaysia',
        'image'        => 'img/competitions/gbwc-11-my.jpg',
        'level'        => 'All Levels',
        'desc'         => 'Entries exhibited and judged on-site, with course winners advancing to the World Final in Tokyo.',
        'registration' => '1 Jul - 15 Aug 2023',
        'fee'          => 'Free entry',
        'venue'        => 'MyTOWN Shopping Centre, Kuala Lumpur',
        'status'       => 'Ended'
    ],
    'gbwc-10-my' => [
        'title'        => 'GBWC 10th Tournament - Malaysia',
        'image'        => 'img/competitions/gbwc-10-my.jpg',
        'level'        => 'All Levels',
        'desc'         => 'Held mainly online worldwide due to COVID-19 restrictions, with course winners advancing to the World Final.',
        'registration' => '1 Jun - 30 Sep 2022',
        'fee'          => 'Free entry',
        'venue'        => 'Online submission',
        'status'       => 'Ended'
    ]
];