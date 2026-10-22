<?php
// includes/community.php
// Turns a community_table row into the array shape the A1 templates already use.

function community_post_from_row(array $row): array
{
    $full  = trim($row['first_name'] . ' ' . $row['last_name']);
    $title = !empty($row['title']) ? $row['title'] : $row['related_activity'];
    return [
        'title'   => $title,
        'user'    => $full,
        'initial' => strtoupper(mb_substr($row['first_name'], 0, 1) . mb_substr($row['last_name'], 0, 1)),
        'tag'     => !empty($row['tag']) ? $row['tag'] : 'Community',
        'related' => $row['related_activity'],
        'image'   => (!empty($row['contribution_image']) && is_file($row['contribution_image']))
                        ? $row['contribution_image'] : 'img/banner/community.jpg',
        'caption' => $row['caption'] ?? '',
    ];
}
