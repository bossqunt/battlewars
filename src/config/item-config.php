<?php
return [
    // Define item types and their base stats
    'item_types' => [
        'Weapon' => [
            'base_stats' => ['attack' => 10, 'defense' => 5],
            'modifiers' => ['physical_attack', 'physical_attack_percent', 'crit_chance_percent'],
        ],
        'Armor' => [
            'base_stats' => ['defense' => 15, 'health' => 20],
            'modifiers' => ['armor', 'armor_percent', 'health_percent'],
        ],
        'Ring' => [
            'base_stats' => ['crit_chance_percent' => 5, 'crit_multi_percent' => 10],
            'modifiers' => ['life_steal_percent', 'speed_percent'],
        ],
        'Amulet' => [
            'base_stats' => ['health' => 10, 'stamina' => 5],
            'modifiers' => ['crit_chance_percent', 'crit_multi_percent'],
        ],
    ],

    // Define rarities and their rules
    'rarities' => [
        'Common' => ['modifiers' => 1],
        'Uncommon' => ['modifiers' => 2],
        'Rare' => ['modifiers' => 3],
        'Epic' => ['modifiers' => [3, 4]],
        'Legendary' => ['modifiers' => [5, 6]],
        'Godly' => ['modifiers' => 6],
    ],

    // Define tier ranges for modifiers
    'tiers' => [
        't0' => [1, 5],
        't1' => [5, 10],
        't2' => [10, 15],
        't3' => [15, 20],
        't4' => [20, 25],
        't5' => [25, 30],
        't6' => [30, 35],
    ],

    // Define probabilities for special rules
    'probabilities' => [
        'physical_attack_percent' => 50, // 50% chance to roll alongside physical_attack
    ],
];