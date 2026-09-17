<?php
// Centralized master data store for multimedia entries, collectibles, and video games
$items = [
    // ============ 1. ANIME: UC MAINLINE ============
    'mobile-suit-gundam-1979' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam (1979)',
        'image'  => 'img/animation/mobile-suit-gundam.jpg',
        'format' => 'TV',
        'year'   => '1979',
        'era'    => 'UC 0079',
        'desc'   => 'The original series that started it all. Slower-paced, but foundational to almost everything that follows.'
    ],
    'gundam-movie-1' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam',
        'image'  => 'img/animation/movie-trilogy-1.jpg',
        'format' => 'Movie',
        'year'   => '1981',
        'era'    => 'UC 0079',
        'desc'   => 'The first of the theatrical recompilation trilogy, condensing the early episodes with some new animation.'
    ],
    'gundam-movie-2' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam II: Soldiers of Sorrow',
        'image'  => 'img/animation/movie-trilogy-2.jpg',
        'format' => 'Movie',
        'year'   => '1981',
        'era'    => 'UC 0079',
        'desc'   => 'The second film in the trilogy, moving the story to Earth with roughly a third new footage.'
    ],
    'gundam-movie-3' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam III: Encounters in Space',
        'image'  => 'img/animation/movie-trilogy-3.jpg',
        'format' => 'Movie',
        'year'   => '1982',
        'era'    => 'UC 0079',
        'desc'   => 'The trilogy\'s finale, largely original animation and widely considered the strongest of the three films.'
    ],
    '0080-war-in-the-pocket' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam 0080: War in the Pocket',
        'image'  => 'img/animation/0080-war-in-the-pocket.jpg',
        'format' => 'OVA',
        'year'   => '1989',
        'era'    => 'UC 0079',
        'desc'   => 'A smaller, street-level story about a boy who befriends an enemy pilot during the One Year War.'
    ],
    '08th-ms-team' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam: The 08th MS Team',
        'image'  => 'img/animation/08th-ms-team.jpg',
        'format' => 'OVA',
        'year'   => '1996',
        'era'    => 'UC 0079',
        'desc'   => 'A grounded, gritty war story following the Kojima Battalion engaging in jungle warfare in Southeast Asia.'
    ],
    '08th-ms-team-millers-report' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam: The 08th MS Team - Miller\'s Report',
        'image'  => 'img/animation/08th-ms-team-millers-report.jpg',
        'format' => 'Movie',
        'year'   => '1998',
        'era'    => 'UC 0079',
        'desc'   => 'An intermediate compilation film focusing on the military trial and interrogation of Shiro Amada.'
    ],
    'ms-igloo-1' => [
        'type'   => 'anime',
        'title'  => 'MS IGLOO: The Hidden One Year War',
        'image'  => 'img/animation/ms-igloo-1.jpg',
        'format' => 'OVA',
        'year'   => '2004',
        'era'    => 'UC 0079',
        'desc'   => 'Tells the One Year War from the Principality of Zeon\'s side, following an engineer documenting mobile suit trials.'
    ],
    'ms-igloo-2' => [
        'type'   => 'anime',
        'title'  => 'MS IGLOO: Apocalypse 0079',
        'image'  => 'img/animation/ms-igloo-2.jpg',
        'format' => 'OVA',
        'year'   => '2006',
        'era'    => 'UC 0079',
        'desc'   => 'Continues the Zeon-side account through the war\'s final, most desperate battles.'
    ],
    'ms-igloo-3' => [
        'type'   => 'anime',
        'title'  => 'MS IGLOO 2: The Gravity Front',
        'image'  => 'img/animation/ms-igloo-3.jpg',
        'format' => 'OVA',
        'year'   => '2008',
        'era'    => 'UC 0079',
        'desc'   => 'Shifts to an Earth Federation weapons-testing unit developing prototype mobile suits under brutal field conditions.'
    ],
    '08th-ms-team-last-resort' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam: The 08th MS Team - Last Resort',
        'image'  => 'img/animation/08th-ms-team-last-resort.jpg',
        'format' => 'OVA',
        'year'   => '1999',
        'era'    => 'UC 0080',
        'desc'   => 'The final epilogue following Michel and Kiki as they search for the missing Shiro and Aina in the post-war jungle.'
    ],
    '0083-stardust-memory' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam 0083: Stardust Memory',
        'image'  => 'img/animation/0083-stardust-memory.jpg',
        'format' => 'OVA',
        'year'   => '1991',
        'era'    => 'UC 0083',
        'desc'   => 'Bridges the gap between the original series and Zeta, centred on a prototype Gundam stolen by remnant forces.'
    ],
    '0083-afterglow-of-zeon' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam 0083: The Afterglow of Zeon',
        'image'  => 'img/animation/0083-afterglow-of-zeon.jpg',
        'format' => 'Movie',
        'year'   => '1992',
        'era'    => 'UC 0083',
        'desc'   => 'A compilation film of Stardust Memory, retelling the conflict surrounding the stolen Gundam prototype.'
    ],
    'zeta-gundam' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Zeta Gundam',
        'image'  => 'img/animation/zeta-gundam.jpg',
        'format' => 'TV',
        'year'   => '1985',
        'era'    => 'UC 0087',
        'desc'   => 'The direct sequel to the original series. Darker in tone as the war drags on and old certainties fall apart.'
    ],
    'zeta-movie-1' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Zeta Gundam: A New Translation - Heirs to the Stars',
        'image'  => 'img/animation/zeta-movie-1.jpg',
        'format' => 'Movie',
        'year'   => '2005',
        'era'    => 'UC 0087',
        'desc'   => 'The first of a reworked theatrical trilogy, with remastered footage, new scenes, and updated mobile suit designs.'
    ],
    'zeta-movie-2' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Zeta Gundam: A New Translation - Lovers',
        'image'  => 'img/animation/zeta-movie-2.jpg',
        'format' => 'Movie',
        'year'   => '2005',
        'era'    => 'UC 0087',
        'desc'   => 'The middle chapter of the trilogy, compressing the TV series\' second act into a tighter runtime.'
    ],
    'zeta-movie-3' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Zeta Gundam: A New Translation - Love is the Pulse of the Stars',
        'image'  => 'img/animation/zeta-movie-3.jpg',
        'format' => 'Movie',
        'year'   => '2006',
        'era'    => 'UC 0087',
        'desc'   => 'The trilogy\'s conclusion, featuring a notably different, more hopeful ending than the original TV series.'
    ],
    'gundam-zz' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam ZZ',
        'image'  => 'img/animation/gundam-zz.jpg',
        'format' => 'TV',
        'year'   => '1986',
        'era'    => 'UC 0088',
        'desc'   => 'Continues directly after Zeta. Starts lighter in tone before turning serious in its second half.'
    ],
    'chars-counterattack' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam: Char\'s Counterattack',
        'image'  => 'img/animation/chars-counterattack.jpg',
        'format' => 'Movie',
        'year'   => '1988',
        'era'    => 'UC 0093',
        'desc'   => 'The dramatic conclusion to Amuro and Char\'s rivalry. Widely considered essential viewing for the original UC cast.'
    ],
    'gundam-unicorn' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam Unicorn RE:0096',
        'image'  => 'img/animation/gundam-unicorn.jpg',
        'format' => 'TV',
        'year'   => '2016',
        'era'    => 'UC 0096',
        'desc'   => 'A high-budget UC story bridging earlier eras, widely regarded as one of the best-animated entries in the franchise.'
    ],
    'twilight-axis' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam Twilight Axis - Red Trace',
        'image'  => 'img/animation/twilight-axis.jpg',
        'format' => 'Movie',
        'year'   => '2017',
        'era'    => 'UC 0096',
        'desc'   => 'A theatrical compilation following former Zeon weapons developers investigating the abandoned ruins of the Axis asteroid.'
    ],
    'silver-phantom' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam: Silver Phantom',
        'image'  => 'img/animation/silver-phantom.jpg',
        'format' => 'VR Film',
        'year'   => '2024',
        'era'    => 'UC 0096',
        'desc'   => 'An interactive VR film for Meta Quest co-produced with Atlas V, following a mercenary-owned silver Gundam.'
    ],
    'gundam-narrative' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam Narrative',
        'image'  => 'img/animation/gundam-narrative.jpg',
        'format' => 'Movie',
        'year'   => '2018',
        'era'    => 'UC 0097',
        'desc'   => 'A direct follow-up to Gundam Unicorn, tracking down the golden psycho-frame mobile suit Phenex.'
    ],
    'gundam-hathaway' => [
        'type'    => 'anime',
        'title'   => 'Mobile Suit Gundam: Hathaway',
        'image'   => 'img/animation/gundam-hathaway.jpg',
        'format'  => 'Movie',
        'year'    => '2021',
        'era'     => 'UC 0105',
        'desc'    => 'Follows Amuro\'s protege Hathaway Noa years after Char\'s Counterattack, leading the anti-Federation group Mafty.',
    ],
    'gundam-hathaway2' => [
        'type'    => 'anime',
        'title'   => 'Mobile Suit Gundam Hathaway: The Sorcery of Nymph Circe',
        'image'   => 'img/animation/gundam-hathaway2.jpg',
        'format'  => 'Movie',
        'year'    => '2026',
        'era'     => 'UC 0105',
        'desc'    => 'The second chapter in the Hathaway trilogy, continuing the fierce ideological battle between Mafty and the Federation.',
        'video'   => 'https://www.youtube.com/embed/pD7eBqTy__E?si=szLGTr_klof5dlQU',
        'gallery' => [
            'img/gallery/gundam-hathaway2-1.jpg',
            'img/gallery/gundam-hathaway2-2.jpg',
            'img/gallery/gundam-hathaway2-3.jpg'
        ]
    ],
    'gundam-f91' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam F91',
        'image'  => 'img/animation/gundam-f91.jpg',
        'format' => 'Movie',
        'year'   => '1991',
        'era'    => 'UC 0123',
        'desc'   => 'Originally planned as a TV series, condensed into a single fast-paced film with a notably high body count.'
    ],
    'victory-gundam' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Victory Gundam',
        'image'  => 'img/animation/victory-gundam.jpg',
        'format' => 'TV',
        'year'   => '1993',
        'era'    => 'UC 0153',
        'desc'   => 'A later, harsher entry in the timeline, known for its bleak depiction of war\'s cost on civilians.'
    ],

    // ============ ANIME: THE ORIGIN ============
    'gto-1' => [
        'type'   => 'anime',
        'title'  => 'The Origin I: Blue-Eyed Casval',
        'image'  => 'img/animation/gto-1.jpg',
        'format' => 'OVA',
        'year'   => '2015',
        'era'    => 'UC 0068',
        'desc'   => 'Chronicles the tragic assassination of Zeon Zum Deikun and the escape of his children, Casval and Artesia.'
    ],
    'gto-2' => [
        'type'   => 'anime',
        'title'  => 'The Origin II: Artesia\'s Sorrow',
        'image'  => 'img/animation/gto-2.jpg',
        'format' => 'OVA',
        'year'   => '2015',
        'era'    => 'UC 0071',
        'desc'   => 'Follows the siblings on Earth, the development of the first mobile workers, and Casval\'s adoption of the Char Aznable identity.'
    ],
    'gto-3' => [
        'type'   => 'anime',
        'title'  => 'The Origin III: Dawn of Rebellion',
        'image'  => 'img/animation/gto-3.jpg',
        'format' => 'OVA',
        'year'   => '2016',
        'era'    => 'UC 0074',
        'desc'   => 'Char enrolls in the Zeon military academy, forms a bond with Garma Zabi, and orchestrates an armed uprising.'
    ],
    'gto-4' => [
        'type'   => 'anime',
        'title'  => 'The Origin IV: Eve of Destiny',
        'image'  => 'img/animation/gto-4.jpg',
        'format' => 'OVA',
        'year'   => '2016',
        'era'    => 'UC 0077',
        'desc'   => 'Details Lalah Sune\'s fateful meeting with Char and the first actual mobile suit combat on the lunar surface.'
    ],
    'gto-5' => [
        'type'   => 'anime',
        'title'  => 'The Origin V: Clash at Loum',
        'image'  => 'img/animation/gto-5.jpg',
        'format' => 'OVA',
        'year'   => '2017',
        'era'    => 'UC 0079',
        'desc'   => 'The One Year War officially begins with Operation British and the lead-up to the catastrophic Battle of Loum.'
    ],
    'gto-6' => [
        'type'   => 'anime',
        'title'  => 'The Origin VI: Rise of the Red Comet',
        'image'  => 'img/animation/gto-6.jpg',
        'format' => 'OVA',
        'year'   => '2018',
        'era'    => 'UC 0079',
        'desc'   => 'Char earns his legendary moniker at Loum, setting the stage for the Federation\'s secretive Project V.'
    ],
    'advent-of-red-comet' => [
        'type'   => 'anime',
        'title'  => 'The Origin: Advent of the Red Comet',
        'image'  => 'img/animation/advent-of-red-comet.jpg',
        'format' => 'TV',
        'year'   => '2019',
        'era'    => 'UC 0068',
        'desc'   => 'A 13-episode televised broadcast edition of the 6-part OVA with new musical scores and theme animations.'
    ],
    'cucuruz-doans-island' => [
        'type'   => 'anime',
        'title'  => 'Cucuruz Doan\'s Island',
        'image'  => 'img/animation/cucuruz-doans-island.jpg',
        'format' => 'Movie',
        'year'   => '2022',
        'era'    => 'UC 0079',
        'desc'   => 'A theatrical remake integrating the character designs, mechanical aesthetic, and tone of The Origin series.'
    ],

    // ============ ANIME: THUNDERBOLT ============
    'gundam-thunderbolt' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam Thunderbolt',
        'image'  => 'img/animation/gundam-thunderbolt.jpg',
        'format' => 'OVA',
        'year'   => '2015',
        'era'    => 'UC 0079',
        'desc'   => 'A darker, hardware-focused parallel history of the original Universal Century timeline with a heavy jazz soundtrack.'
    ],
    'thunderbolt-december-sky' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam Thunderbolt: December Sky',
        'image'  => 'img/animation/thunderbolt-december-sky.jpg',
        'format' => 'Movie',
        'year'   => '2016',
        'era'    => 'UC 0079',
        'desc'   => 'The duel in the debris-strewn Thunderbolt Sector between Federation ace Io Fleming and Zeon sniper Daryl Lorenz.'
    ],
    'thunderbolt-bandit-flower' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam Thunderbolt: Bandit Flower',
        'image'  => 'img/animation/thunderbolt-bandit-flower.jpg',
        'format' => 'Movie',
        'year'   => '2017',
        'era'    => 'UC 0080',
        'desc'   => 'The conflict moves to Earth as Io pilots the Atlas Gundam against the cult-like South Seas Alliance.'
    ],

    // ============ ANIME: GQUUUUUUX ============
    'gquuuuuux-tv' => [
        'type'    => 'anime',
        'title'   => 'Mobile Suit Gundam GQuuuuuuX',
        'image'   => 'img/animation/gquuuuuux-tv.jpg',
        'format'  => 'TV',
        'year'    => '2024',
        'era'     => 'UC 0085 (Alt)',
        'desc'    => 'An alternate Universal Century timeline diverging at Side 7, exploring a radically different outcome to the One Year War.',
        'video'   => 'https://www.youtube.com/embed/LnMIaFV4q6M?si=cC1GyS90ylJX0d7K',
        'gallery' => [
            'img/gallery/gquuuuuux-tv-1.jpg',
            'img/gallery/gquuuuuux-tv-2.jpg',
            'img/gallery/gquuuuuux-tv-3.jpg'
        ]
    ],
    'gquuuuuux' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam GQuuuuuuX -Beginning-',
        'image'  => 'img/animation/gquuuuuux.jpg',
        'format' => 'Movie',
        'year'   => '2025',
        'era'    => 'UC 0085 (Alt)',
        'desc'   => 'An edited movie compilation of the 2024 TV series featuring updated animation and pacing.'
    ],

    // ============ ANIME: COSMIC ERA ============
    'gundam-seed' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam SEED',
        'image'  => 'img/animation/gundam-seed.jpg',
        'format' => 'TV',
        'year'   => '2002',
        'era'    => 'CE 71',
        'desc'   => 'A dramatic, character-focused war story that modernized the franchise internationally for the 2000s.'
    ],
    'seed-msv-astray' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam SEED MSV Astray',
        'image'  => 'img/animation/seed-msv-astray.jpg',
        'format' => 'OVA',
        'year'   => '2004',
        'era'    => 'CE 71',
        'desc'   => 'Two short promotional animated films highlighting the Red Frame and Blue Frame Astray mobile suits.'
    ],
    'seed-destiny' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam SEED Destiny',
        'image'  => 'img/animation/seed-destiny.jpg',
        'format' => 'TV',
        'year'   => '2004',
        'era'    => 'CE 73',
        'desc'   => 'The direct sequel to SEED, following a new generation of pilots into an escalated global round of conflict.'
    ],
    'stargazer' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam SEED C.E. 73: STARGAZER',
        'image'  => 'img/animation/stargazer.jpg',
        'format' => 'ONA',
        'year'   => '2006',
        'era'    => 'CE 73',
        'desc'   => 'A tragic sidestory contrasting civilian deep space exploration researchers against the ruthless Phantom Pain strike team.'
    ],
    'seed-freedom' => [
        'type'    => 'anime',
        'title'   => 'Mobile Suit Gundam SEED Freedom',
        'image'   => 'img/animation/seed-freedom.jpg',
        'format'  => 'Movie',
        'year'    => '2024',
        'era'     => 'CE 75',
        'desc'    => 'A blockbuster theatrical film catching up with Kira, Lacus, and the Compass peacekeeping force.',
        'video'   => 'https://www.youtube.com/embed/Gsj6ToFTGgc?si=mw4AF_rz-UAmes8G',
        'gallery' => [
            'img/gallery/seed-freedom-1.jpg',
            'img/gallery/seed-freedom-2.jpg',
            'img/gallery/seed-freedom-3.jpg'
        ]
    ],

    // ============ ANIME: AFTER COLONY ============
    'gundam-wing' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam Wing',
        'image'  => 'img/animation/gundam-wing.jpg',
        'format' => 'TV',
        'year'   => '1995',
        'era'    => 'AC 195',
        'desc'   => 'Five teenage pilots and their specialized Gundams descend to Earth to wage guerrilla warfare against OZ.'
    ],
    'gundam-wing-operation-meteor' => [
        'type'   => 'anime',
        'title'  => 'Gundam Wing: Operation Meteor',
        'image'  => 'img/animation/gundam-wing-operation-meteor.jpg',
        'format' => 'OVA',
        'year'   => '1996',
        'era'    => 'AC 195',
        'desc'   => 'A compilation OVA retelling the broadcast events with newly added interstitial narrative scenes.'
    ],
    'endless-waltz' => [
        'type'   => 'anime',
        'title'  => 'Gundam Wing: Endless Waltz',
        'image'  => 'img/animation/endless-waltz.jpg',
        'format' => 'OVA',
        'year'   => '1997',
        'era'    => 'AC 196',
        'desc'   => 'The definitive climax to Wing featuring iconic custom redesigns by mechanical artist Hajime Katoki.'
    ],

    // ============ ANIME: FUTURE CENTURY ============
    'g-gundam' => [
        'type'   => 'anime',
        'title'  => 'Mobile Fighter G Gundam',
        'image'  => 'img/animation/g-gundam.jpg',
        'format' => 'TV',
        'year'   => '1994',
        'era'    => 'FC 60',
        'desc'   => 'A beloved departure into super-robot martial arts tournaments where nations fight for orbital dominance.'
    ],

    // ============ ANIME: AFTER WAR ============
    'gundam-x' => [
        'type'   => 'anime',
        'title'  => 'After War Gundam X',
        'image'  => 'img/animation/gundam-x.jpg',
        'format' => 'TV',
        'year'   => '1996',
        'era'    => 'AW 0015',
        'desc'   => 'Set across a post-apocalyptic Earth recovering from a catastrophic mass colony drop.'
    ],

    // ============ ANIME: CORRECT CENTURY ============
    'turn-a-gundam' => [
        'type'   => 'anime',
        'title'  => 'Turn A Gundam',
        'image'  => 'img/animation/turn-a-gundam.jpg',
        'format' => 'TV',
        'year'   => '1999',
        'era'    => 'CC 2345',
        'desc'   => 'A poetic entry from Yoshiyuki Tomino featuring Syd Mead\'s distinct industrial designs.'
    ],
    'turn-a-earth-light' => [
        'type'   => 'anime',
        'title'  => 'Turn A Gundam I: Earth Light',
        'image'  => 'img/animation/turn-a-earth-light.jpg',
        'format' => 'Movie',
        'year'   => '2002',
        'era'    => 'CC 2345',
        'desc'   => 'A theatrical recompilation covering the first half of the TV series with restructured pacing.'
    ],
    'turn-a-moonlight-butterfly' => [
        'type'   => 'anime',
        'title'  => 'Turn A Gundam II: Moonlight Butterfly',
        'image'  => 'img/animation/turn-a-moonlight-butterfly.jpg',
        'format' => 'Movie',
        'year'   => '2002',
        'era'    => 'CC 2345',
        'desc'   => 'The concluding theatrical recompilation, covering the series\' back half and finale.'
    ],

    // ============ ANIME: ANNO DOMINI ============
    'gundam-00' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam 00',
        'image'  => 'img/animation/gundam-00.jpg',
        'format' => 'TV',
        'year'   => '2007',
        'era'    => 'AD 2307',
        'desc'   => 'Celestial Being intervenes in global conflicts using solar GN-drive powered stealth mobile suits.'
    ],
    'gundam-00-2' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam 00 (Season 2)',
        'image'  => 'img/animation/gundam-00-2.jpg',
        'format' => 'TV',
        'year'   => '2008',
        'era'    => 'AD 2312',
        'desc'   => 'The Meisters reunite four years later to dismantle the oppressive Earth Sphere Federation task force A-Laws.'
    ],
    'gundam-00-movie' => [
        'type'   => 'anime',
        'title'  => '00 The Movie: A Wakening of the Trailblazer',
        'image'  => 'img/animation/gundam-00-movie.jpg',
        'format' => 'Movie',
        'year'   => '2010',
        'era'    => 'AD 2314',
        'desc'   => 'The definitive conclusion to the AD saga, facing off against an extraterrestrial collective consciousness.'
    ],

    // ============ ANIME: ADVANCED GENERATION ============
    'gundam-age' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam AGE',
        'image'  => 'img/animation/gundam-age.jpg',
        'format' => 'TV',
        'year'   => '2011',
        'era'    => 'AG 115',
        'desc'   => 'A multi-generational saga chronicling the Asuno family across a century-long conflict against the Vagan.'
    ],
    'age-memory-of-eden' => [
        'type'   => 'anime',
        'title'  => 'Mobile Suit Gundam AGE: Memory of Eden',
        'image'  => 'img/animation/age-memory-of-eden.jpg',
        'format' => 'OVA',
        'year'   => '2013',
        'era'    => 'AG 115+',
        'desc'   => 'A compilation and expansion of the Asemu arc, focusing heavily on the tragic rivalry between Asemu Asuno and Zeheart Galette.'
    ],

    // ============ ANIME: POST DISASTER ============
    'iron-blooded-orphans' => [
        'type'   => 'anime',
        'title'  => 'Iron-Blooded Orphans',
        'image'  => 'img/animation/iron-blooded-orphans.jpg',
        'format' => 'TV',
        'year'   => '2015',
        'era'    => 'PD 325',
        'desc'   => 'A gritty story about child soldiers turning mercenaries, relying on kinetic weapons and physical inner frames.'
    ],
    'iron-blooded-orphans2' => [
        'type'   => 'anime',
        'title'  => 'Iron-Blooded Orphans (Season 2)',
        'image'  => 'img/animation/iron-blooded-orphans2.jpg',
        'format' => 'TV',
        'year'   => '2016',
        'era'    => 'PD 325',
        'desc'   => 'The brutal continuation and conclusion of Tekkadan\'s perilous rise through the underworld.'
    ],

    // ============ ANIME: REGILD CENTURY ============
    'reconguista-in-g' => [
        'type'   => 'anime',
        'title'  => 'Gundam Reconguista in G',
        'image'  => 'img/animation/reconguista-in-g.jpg',
        'format' => 'TV',
        'year'   => '2014',
        'era'    => 'RC 1014',
        'desc'   => 'A vibrant, far-future post-UC series from Yoshiyuki Tomino examining resource distribution and solar power elevators.'
    ],
    'g-reco-movie-1' => [
        'type'   => 'anime',
        'title'  => 'G-Reco I: Go! Core Fighter',
        'image'  => 'img/animation/g-reco-movie-1.jpg',
        'format' => 'Movie',
        'year'   => '2019',
        'era'    => 'RC 1014',
        'desc'   => 'The first theatrical compilation, establishing Bellri Zenam, the G-Self, and the pirate raid on the Capital Tower.'
    ],
    'g-reco-movie-2' => [
        'type'   => 'anime',
        'title'  => 'G-Reco II: Bellri\'s Fierce Charge',
        'image'  => 'img/animation/g-reco-movie-2.jpg',
        'format' => 'Movie',
        'year'   => '2020',
        'era'    => 'RC 1014',
        'desc'   => 'The crew navigates escalating territorial tension as the Megafauna prepares to break through into outer space.'
    ],
    'g-reco-movie-3' => [
        'type'   => 'anime',
        'title'  => 'G-Reco III: The Legacy of Space',
        'image'  => 'img/animation/g-reco-movie-3.jpg',
        'format' => 'Movie',
        'year'   => '2021',
        'era'    => 'RC 1014',
        'desc'   => 'Arrival at the Moon\'s Towasanga colony brings ancient historical secrets and suppressed technologies to light.'
    ],
    'g-reco-movie-4' => [
        'type'   => 'anime',
        'title'  => 'G-Reco IV: Shouting Love Into a Fierce War',
        'image'  => 'img/animation/g-reco-movie-4.jpg',
        'format' => 'Movie',
        'year'   => '2022',
        'era'    => 'RC 1014',
        'desc'   => 'The voyage ventures farther outward to the Venus Globe, where hidden factions reveal their advanced mobile armors.'
    ],
    'g-reco-movie-5' => [
        'type'   => 'anime',
        'title'  => 'G-Reco V: Crossing the Line of Death',
        'image'  => 'img/animation/g-reco-movie-5.jpg',
        'format' => 'Movie',
        'year'   => '2022',
        'era'    => 'RC 1014',
        'desc'   => 'The grand theatrical conclusion as all interplanetary forces descend upon the Earth\'s atmosphere for a final battle.'
    ],

    // ============ ANIME: AD STELLA ============
    'witch-from-mercury' => [
        'type'   => 'anime',
        'title'  => 'The Witch from Mercury (Season 1)',
        'image'  => 'img/animation/witch-from-mercury.jpg',
        'format' => 'TV',
        'year'   => '2022',
        'era'    => 'AS 122',
        'desc'   => 'Follows Suletta Mercury as an awkward transfer student to Asticassia Academy piloting the Gundam Aerial.'
    ],
    'witch-from-mercury2' => [
        'type'   => 'anime',
        'title'  => 'The Witch from Mercury (Season 2)',
        'image'  => 'img/animation/witch-from-mercury2.jpg',
        'format' => 'TV',
        'year'   => '2023',
        'era'    => 'AS 122',
        'desc'   => 'Confronts harsh corporate warfare, Earthian discrimination, and the ominous activation of the Quiet Zero project.'
    ],

    // ============ ANIME: AFTER APOCALYPSE ============
    'xarx-zero' => [
        'type'    => 'anime',
        'title'   => 'Mobile Suit Gundam RG XARX-ZERO',
        'image'   => 'img/animation/xarx-zero.jpg',
        'format'  => 'TV',
        'year'    => '2027',
        'era'     => 'AA 45',
        'desc'    => 'Set 45 years after an apocalyptic extraterrestrial attack, following orphan Ray Azumi piloting Gundam ZERO.',
        'video'   => 'https://www.youtube.com/embed/b6YBXoFAs3s?si=qNEO-bmJtMumbFLD',
        'gallery' => [
            'img/gallery/xarx-zero-1.jpg',
            'img/gallery/xarx-zero-2.jpg',
            'img/gallery/xarx-zero-3.jpg'
        ]
    ],

    // ============ ANIME: BUILD SERIES ============
    'beginning-g' => [
        'type'   => 'anime',
        'title'  => 'Gunpla Builders Beginning G',
        'image'  => 'img/animation/beginning-g.jpg',
        'format' => 'OVA',
        'year'   => '2010',
        'era'    => 'Present day',
        'desc'   => 'The original short animation establishing the concept of scanning custom-built Gunpla for holographic battles.'
    ],
    'build-fighters' => [
        'type'   => 'anime',
        'title'  => 'Gundam Build Fighters',
        'image'  => 'img/animation/build-fighters.jpg',
        'format' => 'TV',
        'year'   => '2013',
        'era'    => 'Present day',
        'desc'   => 'Sei Iori and Reiji team up to dominate the world Gunpla battle championships using the Build Strike Gundam.'
    ],
    'build-fighters-try' => [
        'type'   => 'anime',
        'title'  => 'Gundam Build Fighters Try',
        'image'  => 'img/animation/build-fighters-try.jpg',
        'format' => 'TV',
        'year'   => '2014',
        'era'    => 'Present day',
        'desc'   => 'Team Try Fighters competes in nationwide three-versus-three battles seven years after the first tournament.'
    ],
    'try-island-wars' => [
        'type'   => 'anime',
        'title'  => 'Gundam Build Fighters Try Island Wars',
        'image'  => 'img/animation/try-island-wars.jpg',
        'format' => 'OVA',
        'year'   => '2016',
        'era'    => 'Present day',
        'desc'   => 'A television special following the Try Fighters testing new mobile suits during a summer training camp at Nielsen Labs.'
    ],
    'gms-counterattack' => [
        'type'   => 'anime',
        'title'  => 'Gundam Build Fighters GM\'s Counterattack',
        'image'  => 'img/animation/gms-counterattack.jpg',
        'format' => 'ONA',
        'year'   => '2017',
        'era'    => 'Present day',
        'desc'   => 'Reunites Sei Iori and the original cast in a newly completed stadium to battle an underground syndicate known as the Gunpla Mafia.'
    ],
    'battlogue' => [
        'type'   => 'anime',
        'title'  => 'Gundam Build Fighters: Battlogue',
        'image'  => 'img/animation/battlogue.jpg',
        'format' => 'ONA',
        'year'   => '2017',
        'era'    => 'Present day',
        'desc'   => 'A series of standalone dream-match shorts featuring unique custom Gunpla squaring off in automated simulation battles.'
    ],
    'build-divers' => [
        'type'   => 'anime',
        'title'  => 'Gundam Build Divers',
        'image'  => 'img/animation/build-divers.jpg',
        'format' => 'TV',
        'year'   => '2018',
        'era'    => 'Near-future VR',
        'desc'   => 'Brings the hobby into Gunpla Battle Nexus Online (GBN), a massive multiplayer full-dive virtual environment.'
    ],
    'build-divers-rerise' => [
        'type'   => 'anime',
        'title'  => 'Gundam Build Divers Re:RISE',
        'image'  => 'img/animation/build-divers-rerise.jpg',
        'format' => 'TV',
        'year'   => '2019',
        'era'    => 'Near-future VR',
        'desc'   => 'A deeper and darker narrative as solitary pilots band together to defend an imperiled alien planet named Eldora.'
    ],
    'build-divers-rerise2' => [
        'type'   => 'anime',
        'title'  => 'Gundam Build Divers Re:RISE (Season 2)',
        'image'  => 'img/animation/build-divers-rerise2.jpg',
        'format' => 'TV',
        'year'   => '2020',
        'era'    => 'Near-future VR',
        'desc'   => 'The climax of the Re:RISE campaign, confronting artificial orbital lasers to secure Eldora\'s lasting peace.'
    ],
    'breaker-battlogue' => [
        'type'   => 'anime',
        'title'  => 'Gundam Breaker Battlogue',
        'image'  => 'img/animation/breaker-battlogue.jpg',
        'format' => 'ONA',
        'year'   => '2021',
        'era'    => 'Present day',
        'desc'   => 'A 6-episode ONA series tying the Gundam Breaker mobile and console games with the Build series, featuring custom variants like the Gundam Helios.'
    ],
    'build-metaverse' => [
        'type'   => 'anime',
        'title'  => 'Gundam Build Metaverse',
        'image'  => 'img/animation/build-metaverse.jpg',
        'format' => 'ONA',
        'year'   => '2023',
        'era'    => 'VR Metaverse',
        'desc'   => 'Celebratory miniseries assembling characters and mobile suits across all past Build generations.'
    ],

    // ============ ANIME: SD GUNDAM UNIVERSES ============
    'sd-gundam-gaiden' => [
        'type'   => 'anime',
        'title'  => 'SD Gundam Gaiden',
        'image'  => 'img/animation/sd-gundam-gaiden.jpg',
        'format' => 'OVA',
        'year'   => '1990',
        'era'    => 'SD',
        'desc'   => 'A classic fantasy reimagining of Gundam characters as medieval knights on a quest to defeat the Dark Lord Satan Gundam.'
    ],
    'sd-gundam-force' => [
        'type'   => 'anime',
        'title'  => 'Superior Defender Gundam Force',
        'image'  => 'img/animation/sd-gundam-force.jpg',
        'format' => 'TV',
        'year'   => '2003',
        'era'    => 'Neotopia',
        'desc'   => '3D computer-animated series introducing sentient chibi mobile suits defending the peaceful metropolis of Neotopia.'
    ],
    'sangoku-brave-battle-warriors' => [
        'type'   => 'anime',
        'title'  => 'SD Gundam Sangokuden Brave Battle Warriors',
        'image'  => 'img/animation/sangoku-brave-battle-warriors.jpg',
        'format' => 'TV',
        'year'   => '2010',
        'era'    => 'SD',
        'desc'   => 'An action-packed TV series adapting the Romance of the Three Kingdoms into a mythical world populated by legendary SD warrior mobile suits.'
    ],
    'sangoku-soketsuden' => [
        'type'   => 'anime',
        'title'  => 'SD Gundam World Sangoku Soketsuden',
        'image'  => 'img/animation/sangoku-soketsuden.jpg',
        'format' => 'ONA',
        'year'   => '2019',
        'era'    => 'SD',
        'desc'   => 'A futuristic re-telling of the Romance of the Three Kingdoms featuring intricate, modernized SD Gunpla designs.'
    ],
    'sd-world-heroes' => [
        'type'   => 'anime',
        'title'  => 'SD Gundam World Heroes',
        'image'  => 'img/animation/sd-world-heroes.jpg',
        'format' => 'ONA',
        'year'   => '2021',
        'era'    => 'SD',
        'desc'   => 'A direct sequel to Sangoku Soketsuden, expanding the setting into a multiverse populated by legendary historical figures.'
    ],

    // ============ 2. GUNPLA: PLASTIC MODELS ============
    'eg' => [
        'type'  => 'gunpla',
        'title' => 'Entry Grade (EG)',
        'image' => 'img/grade/grade-eg.jpg',
        'tag'   => 'Beginner',
        'desc'  => 'Snap-fit, tool-free, and cheap. Designed specifically for first-time builders.'
    ],
    'hg' => [
        'type'  => 'gunpla',
        'title' => 'High Grade (HG)',
        'image' => 'img/grade/grade-hg.jpg',
        'tag'   => '1/144 scale',
        'desc'  => 'The most common starting point. Good detail, wide selection, moderate price.'
    ],
    'rg' => [
        'type'  => 'gunpla',
        'title' => 'Real Grade (RG)',
        'image' => 'img/grade/grade-rg.jpg',
        'tag'   => '1/144 scale',
        'desc'  => 'HG-sized but with MG-level detail and an inner frame. More fiddly to build.'
    ],
    're100' => [
        'type'  => 'gunpla',
        'title' => 'RE/100 (Reborn-One Hundred)',
        'image' => 'img/grade/grade-re100.jpg',
        'tag'   => '1/100 scale',
        'desc'  => 'Features sharp 1/100 exterior detailing and proportions with a streamlined, frameless build, bringing large and niche mobile suits to life without MG assembly complexity.'
    ],
    'fm' => [
        'type'  => 'gunpla',
        'title' => 'Full Mechanics (FM)',
        'image' => 'img/grade/grade-fm.jpg',
        'tag'   => '1/100 scale',
        'desc'  => 'Delivers high-density surface mechanical details and layered semi-inner frames at 1/100 scale, balancing display presence with accessible, straightforward assembly.'
    ],
    'mg' => [
        'type'  => 'gunpla',
        'title' => 'Master Grade (MG)',
        'image' => 'img/grade/grade-mg.jpg',
        'tag'   => '1/100 scale',
        'desc'  => 'Larger, more detailed, often with an articulated inner frame. A common "second kit" after HG.'
    ],
    'pg' => [
        'type'  => 'gunpla',
        'title' => 'Perfect Grade (PG)',
        'image' => 'img/grade/grade-pg.jpg',
        'tag'   => '1/60 scale',
        'desc'  => 'The largest and most detailed mainline grade, with extensive frames and sometimes lighting.'
    ],
    'sd' => [
        'type'  => 'gunpla',
        'title' => 'SD (Super Deformed)',
        'image' => 'img/grade/grade-sd.jpg',
        'tag'   => 'Chibi scale',
        'desc'  => 'Small, simplified, stylised kits. Quick to build and popular for casual or gift builds.'
    ],

    // ============ 3. FIGURES: COLLECTOR FIGURES ============
    'robot-spirits' => [
        'type'  => 'figure',
        'title' => 'The Robot Spirits (ROBOT魂)',
        'image' => 'img/grade/figure-robot-spirits.jpg',
        'tag'   => 'Finished Action Figure',
        'desc'  => 'Pre-painted, ready-to-pose action figures emphasizing dynamic articulation and anime-accurate silhouettes without the fragility of plastic model assembly.'
    ],
    'metal-robot-spirits' => [
        'type'  => 'figure',
        'title' => 'Metal Robot Spirits (METAL ROBOT魂)',
        'image' => 'img/grade/figure-metal-robot-spirits.jpg',
        'tag'   => 'Diecast / Finished',
        'desc'  => 'Upgrades the classic Robot Spirits format with substantial diecast metal joints, factory-applied tampo markings, and premium metallic finishes.'
    ],
    'metal-build' => [
        'type'  => 'figure',
        'title' => 'Metal Build',
        'image' => 'img/grade/figure-metal-build.jpg',
        'tag'   => 'Premium Diecast (1/100)',
        'desc'  => 'Flagship high-end collector figures engineered with dense diecast skeletal frames, original stylized proportions, and multi-layered composite coatings.'
    ],
    'nxedge-style' => [
        'type'  => 'figure',
        'title' => 'NXEDGE STYLE',
        'image' => 'img/grade/figure-nxedge.jpg',
        'tag'   => 'Stylized SD Figure',
        'desc'  => 'Edgy, sharply detailed chibi-scale action figures bundled with dynamic slash effect parts and modular display bases built for dramatic posing.'
    ],
    'gundam-universe' => [
        'type'  => 'figure',
        'title' => 'Gundam Universe (GU)',
        'image' => 'img/grade/figure-gundam-universe.jpg',
        'tag'   => '6-inch Action Figure',
        'desc'  => 'Robust, affordable 6-inch action figures designed for dynamic posing and high playability with stylized, chunky proportions.'
    ],
    'gffmc' => [
        'type'  => 'figure',
        'title' => 'Gundam Fix Figuration Metal Composite (GFFMC)',
        'image' => 'img/grade/figure-gffmc.jpg',
        'tag'   => 'Premium Diecast (1/100)',
        'desc'  => 'High-end pre-painted figures supervised by Hajime Katoki, combining sharp ABS plastic detailing with heavy diecast metal inner frames and intricate decal markings.'
    ],

    // ============ 4. GAMES ============
    'gundam-versus' => [
        'type'  => 'game',
        'title' => 'Mobile Suit Gundam Extreme Vs. Maxiboost ON',
        'image' => 'img/games/gundam-versus.jpg',
        'genre' => '2v2 Arena',
        'desc'  => 'Fast, team-based mobile suit battles. A highly acclaimed arcade port bringing the definitive Extreme Vs. competitive experience to home consoles.'
    ],
    'g-generation' => [
        'type'  => 'game',
        'title' => 'SD Gundam G Generation Cross Rays',
        'image' => 'img/games/g-generation.jpg',
        'genre' => 'Tactics / Strategy',
        'desc'  => 'Turn-based strategy focusing heavily on four major alternate universes (Wing, SEED, 00, and Iron-Blooded Orphans), with a massive roster to recruit and customise.'
    ],
    'gundam-breaker' => [
        'type'  => 'game',
        'title' => 'Gundam Breaker series',
        'image' => 'img/games/gundam-breaker.jpg',
        'genre' => 'Action RPG',
        'desc'  => 'Break down enemy suits piece by piece and rebuild your own from salvaged parts — a natural fit for Gunpla fans.'
    ],
    'gundam-battle-operation-2' => [
        'type'  => 'game',
        'title' => 'Mobile Suit Gundam Battle Operation 2',
        'image' => 'img/games/gundam-battle-operation-2.jpg',
        'genre' => 'Online Shooter',
        'desc'  => 'A free-to-play 6v6 team shooter, still actively updated with new mobile suits and seasons.'
    ],
    'dynasty-warriors-gundam' => [
        'type'  => 'game',
        'title' => 'Dynasty Warriors: Gundam series',
        'image' => 'img/games/dynasty-warriors-gundam.jpg',
        'genre' => 'Musou / Action',
        'desc'  => 'Large-scale battlefield action piloting mobile suits against waves of enemies.'
    ],
    'gundam-evolution' => [
        'type'  => 'game',
        'title' => 'Gundam Evolution',
        'image' => 'img/games/gundam-evolution.jpg',
        'genre' => 'Hero Shooter',
        'desc'  => 'A team-based hero shooter with mobile suits as playable characters, each with unique abilities.'
    ]
];