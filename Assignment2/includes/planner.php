<?php
// includes/planner.php
// The numbers behind the Build Planner (smart_feature.php).
// These are rough, typical values for a Malaysian hobby shop; change them here to tune the maths.

// Per grade (the item_category stored in smart_feature_table):
//   hours   = typical snap-fit build time for a beginner pace of 1.0
//   surface = how much surface there is to coat, relative to an Entry Grade kit
//   price   = typical kit price in RM
//   level   = difficulty rank: 1 easiest ... 5 hardest
const GRADES = [
    'Entry Grade'    => ['hours' => 1,  'surface' => 1.0, 'price' => 40,   'level' => 1],
    'SD'             => ['hours' => 1,  'surface' => 0.8, 'price' => 35,   'level' => 1],
    'High Grade'     => ['hours' => 3,  'surface' => 1.5, 'price' => 70,   'level' => 2],
    'Full Mechanics' => ['hours' => 4,  'surface' => 2.0, 'price' => 175,  'level' => 3],
    'Real Grade'     => ['hours' => 6,  'surface' => 2.0, 'price' => 148,  'level' => 3],
    'Re/100'         => ['hours' => 7,  'surface' => 3.0, 'price' => 299,  'level' => 4],
    'Master Grade'   => ['hours' => 12, 'surface' => 3.5, 'price' => 460,  'level' => 4],
    'Perfect Grade'  => ['hours' => 40, 'surface' => 8.0, 'price' => 1000, 'level' => 5],
];

// Skill level: hardest grade they can handle, and how fast they build.
const SKILLS = [
    'beginner'     => ['label' => 'Beginner',     'max_level' => 2, 'speed' => 1.5],
    'intermediate' => ['label' => 'Intermediate', 'max_level' => 4, 'speed' => 1.0],
    'advanced'     => ['label' => 'Advanced',     'max_level' => 5, 'speed' => 0.8],
];

// Finish: extra hours per surface unit, and the supplies it uses.
//   marker_rm / topcoat_ml / paint_ml / primer_ml are per surface unit (a "surface" of 1.0 = an EG kit).
const FINISHES = [
    'snap'    => ['label' => 'Snap-fit only (no tools beyond nippers)', 'hours' => 0.0,  'markers' => 0, 'topcoat_ml' => 0,  'primer_ml' => 0, 'paint_ml' => 0],
    'lining'  => ['label' => 'Panel lining',                            'hours' => 0.5,  'markers' => 1, 'topcoat_ml' => 0,  'primer_ml' => 0, 'paint_ml' => 0],
    'topcoat' => ['label' => 'Panel lining + matte topcoat',            'hours' => 1.25, 'markers' => 1, 'topcoat_ml' => 12, 'primer_ml' => 0, 'paint_ml' => 0],
    'paint'   => ['label' => 'Full custom paint job',                   'hours' => 5.25, 'markers' => 1, 'topcoat_ml' => 12, 'primer_ml' => 8, 'paint_ml' => 10],
];

// Prices of supplies in RM
const PRICE_MARKER = 12.0;   // one panel line marker
const PRICE_TOPCOAT_PER_ML = 0.28;   // 100 ml spray can about RM 28
const PRICE_PRIMER_PER_ML  = 0.25;   // 100 ml can about RM 25
const PRICE_PAINT_PER_ML   = 0.85;   // 10 ml bottle about RM 8.50

// Which grades fit a skill level?
function grades_for_skill(string $skill): array
{
    $max = SKILLS[$skill]['max_level'];
    return array_keys(array_filter(GRADES, fn($g) => $g['level'] <= $max));
}

// Works out time, supplies and cost for one grade + skill + finish.
function plan_for_grade(string $grade, string $skill, string $finish): array
{
    $g = GRADES[$grade];
    $f = FINISHES[$finish];
    $s = SKILLS[$skill];

    $surface = $g['surface'];
    $hours   = $g['hours'] * $s['speed'] + $f['hours'] * $surface;

    $topcoat = $f['topcoat_ml'] * $surface;
    $primer  = $f['primer_ml']  * $surface;
    $paint   = $f['paint_ml']   * $surface;

    $supplies_cost = $f['markers'] * PRICE_MARKER
                   + $topcoat * PRICE_TOPCOAT_PER_ML
                   + $primer  * PRICE_PRIMER_PER_ML
                   + $paint   * PRICE_PAINT_PER_ML;

    return [
        'hours'    => round($hours, 1),
        'markers'  => $f['markers'],
        'topcoat'  => round($topcoat),
        'primer'   => round($primer),
        'paint'    => round($paint),
        'kit_rm'   => $g['price'],
        'supplies' => round($supplies_cost, 2),
        'total'    => round($g['price'] + $supplies_cost, 2),
    ];
}
