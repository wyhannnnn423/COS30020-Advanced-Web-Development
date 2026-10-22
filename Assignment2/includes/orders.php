<?php
// includes/orders.php: shared bits for the shop (order.php, order_pay.php, order_track.php, manage_orders.php)

const MY_STATES = ['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis',
                   'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 'Kuala Lumpur', 'Labuan', 'Putrajaya'];

// status value => label shown to people
const ORDER_STATUSES = [
    'awaiting_payment' => 'Awaiting payment',
    'pending'          => 'Verifying payment',
    'paid'             => 'Payment confirmed',
    'packed'           => 'Packed',
    'shipped'          => 'Shipped',
    'delivered'        => 'Delivered',
    'rejected'         => 'Payment rejected',
];

// Steps drawn on the tracking page, in order. Each status maps to the last step reached.
const ORDER_STEPS = ['Order placed', 'Payment proof received', 'Payment confirmed', 'Packed', 'Shipped', 'Delivered'];
const ORDER_STEP_OF = ['awaiting_payment' => 0, 'pending' => 1, 'paid' => 2, 'packed' => 3, 'shipped' => 4, 'delivered' => 5, 'rejected' => 0];

function order_ref(int $id): string
{
    return 'GH-' . str_pad((string)$id, 5, '0', STR_PAD_LEFT);
}

function order_total(array $o): float
{
    return (float)$o['unit_price'] * (int)$o['quantity'];
}

function money(float $n): string
{
    return 'RM ' . number_format($n, 2);
}

function order_badge(string $status): string
{
    $class = [
        'awaiting_payment' => 'status-pending', 'pending' => 'status-pending', 'paid' => 'status-approved',
        'packed' => 'status-approved', 'shipped' => 'status-approved', 'delivered' => 'status-approved',
        'rejected' => 'status-rejected',
    ][$status] ?? 'status-pending';
    $label = ORDER_STATUSES[$status] ?? $status;
    return '<span class="status-badge ' . $class . '">' . htmlspecialchars($label) . '</span>';
}
