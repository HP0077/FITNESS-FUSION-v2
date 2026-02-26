<?php
/**
 * Muscle Gain Diet Plan
 * 
 * High-protein, calorie-surplus meal plan for lean muscle growth.
 * Same array + foreach pattern as wt_loss.php and wt_gain.php.
 */

require_once __DIR__ . '/../includes/auth.php';
requireAuth();

$userName = sessionGet('user_name', 'User');

// ── Diet Plan Data ─────────────────────────────────────────
$meals = [
    [
        'meal'     => 'Breakfast',
        'time'     => '6:30 – 7:30 AM',
        'icon'     => '🌅',
        'items'    => [
            '4 egg-white omelette with spinach and mushrooms',
            '2 slices whole wheat toast with almond butter',
            '1 banana',
            '1 glass of milk or whey protein shake',
        ],
        'calories' => '~550 kcal',
        'protein'  => '~40g',
    ],
    [
        'meal'     => 'Mid-Morning Snack',
        'time'     => '9:30 – 10:00 AM',
        'icon'     => '🥜',
        'items'    => [
            'Greek yogurt (200g) with granola and honey',
            'A handful of mixed nuts (almonds, walnuts, cashews)',
        ],
        'calories' => '~300 kcal',
        'protein'  => '~20g',
    ],
    [
        'meal'     => 'Lunch',
        'time'     => '12:30 – 1:30 PM',
        'icon'     => '🍗',
        'items'    => [
            'Grilled chicken breast (200g) or soya chunks curry (150g)',
            '2 cups brown rice or 3 multigrain rotis',
            '1 bowl rajma / black chana dal',
            'Broccoli and carrot stir-fry',
            '1 bowl curd',
        ],
        'calories' => '~750 kcal',
        'protein'  => '~55g',
    ],
    [
        'meal'     => 'Pre-Workout',
        'time'     => '3:30 – 4:00 PM',
        'icon'     => '⚡',
        'items'    => [
            '1 banana with a tablespoon of peanut butter',
            'Black coffee or green tea (for energy)',
            '2 rice cakes or 1 whole wheat toast',
        ],
        'calories' => '~250 kcal',
        'protein'  => '~8g',
    ],
    [
        'meal'     => 'Post-Workout',
        'time'     => '5:30 – 6:00 PM',
        'icon'     => '🥤',
        'items'    => [
            'Whey protein shake with milk and a banana',
            'Or 3 boiled eggs + 1 glass chocolate milk',
        ],
        'calories' => '~350 kcal',
        'protein'  => '~35g',
    ],
    [
        'meal'     => 'Dinner',
        'time'     => '8:00 – 8:30 PM',
        'icon'     => '🌙',
        'items'    => [
            'Grilled fish / salmon (200g) or paneer tikka (150g)',
            '1.5 cups rice or 2 rotis',
            '1 bowl moong dal with ghee',
            'Cucumber, tomato, and beetroot salad',
        ],
        'calories' => '~650 kcal',
        'protein'  => '~45g',
    ],
    [
        'meal'     => 'Before Bed',
        'time'     => '10:00 PM',
        'icon'     => '🥛',
        'items'    => [
            'Casein protein shake or 1 glass warm milk with turmeric',
            '1 tablespoon flaxseed',
            '5 soaked almonds',
        ],
        'calories' => '~200 kcal',
        'protein'  => '~25g',
    ],
];

$tips = [
    'Target 2,800–3,200 kcal/day with 1.6–2.2g protein per kg body weight.',
    'Spread protein intake evenly across all meals — muscle protein synthesis peaks at ~30-40g per meal.',
    'Time your pre- and post-workout meals within 1–2 hours of training.',
    'Prioritize compound exercises: squats, deadlifts, bench press, rows.',
    'Get 7–8 hours of sleep — muscle recovery happens during deep sleep.',
    'Track your lifts weekly. Progressive overload is the #1 driver of muscle growth.',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muscle Gain Diet — <?= htmlspecialchars(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f2f1, #80cbc4);
            min-height: 100vh;
            padding: 30px 15px;
            color: #333;
        }

        .top-bar {
            max-width: 850px; margin: 0 auto 25px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .top-bar a {
            text-decoration: none; font-weight: 600; padding: 8px 20px;
            border-radius: 20px; background: #fff; color: #00796b;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: background 0.3s, color 0.3s;
        }
        .top-bar a:hover { background: #00897b; color: #fff; }

        .container {
            max-width: 850px; margin: 0 auto;
            background: #fff; border-radius: 20px;
            padding: 45px 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }
        .container h1 { color: #00695c; font-size: 2rem; margin-bottom: 5px; }
        .container .subtitle { color: #999; font-size: 0.9rem; margin-bottom: 35px; }

        .badge {
            display: inline-block; padding: 2px 10px; border-radius: 10px;
            font-size: 0.78rem; font-weight: 600; margin-left: 6px;
        }
        .badge-cal { background: #e0f2f1; color: #00796b; }
        .badge-pro { background: #e8eaf6; color: #3949ab; }

        .meal-card {
            background: #f1f8e9; border-radius: 14px;
            padding: 25px; margin-bottom: 20px;
            border-left: 5px solid #00897b;
            transition: transform 0.2s;
        }
        .meal-card:hover { transform: translateX(4px); }
        .meal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 12px; flex-wrap: wrap; gap: 6px;
        }
        .meal-header h2 { font-size: 1.15rem; color: #00695c; }
        .meal-header .time { font-size: 0.8rem; color: #999; }
        .meal-card ul { padding-left: 20px; }
        .meal-card li { margin: 6px 0; font-size: 0.92rem; color: #555; line-height: 1.6; }

        .tips {
            background: #fff3e0; border-radius: 14px;
            padding: 25px; margin-top: 30px;
        }
        .tips h2 { color: #e65100; font-size: 1.1rem; margin-bottom: 12px; }
        .tips li { margin: 6px 0; font-size: 0.9rem; color: #555; line-height: 1.6; }

        .bottom-links {
            display: flex; justify-content: center; gap: 15px;
            flex-wrap: wrap; margin-top: 30px;
        }
        .bottom-links a {
            padding: 10px 25px; border-radius: 25px; text-decoration: none;
            font-weight: 600; font-size: 0.88rem; transition: all 0.3s;
        }
        .btn-primary { background: #00897b; color: #fff; }
        .btn-primary:hover { background: #00695c; }
        .btn-outline { background: #fff; color: #00796b; border: 2px solid #00897b; }
        .btn-outline:hover { background: #00897b; color: #fff; }

        @media (max-width: 600px) {
            .container { padding: 25px 18px; }
            .container h1 { font-size: 1.5rem; }
            .meal-header { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="<?= BASE_URL ?>/diet/options.php">&larr; Diet Plans</a>
    <a href="<?= BASE_URL ?>/dashboard/dashboard.php">Dashboard</a>
</div>

<div class="container">
    <h1>🏋️ Muscle Gain Diet Plan</h1>
    <p class="subtitle">High-protein surplus plan (~3,050 kcal / ~228g protein per day) for lean muscle hypertrophy.</p>

    <?php foreach ($meals as $meal): ?>
        <div class="meal-card">
            <div class="meal-header">
                <h2><?= htmlspecialchars($meal['icon'] . ' ' . $meal['meal']) ?></h2>
                <span class="time"><?= htmlspecialchars($meal['time']) ?></span>
                <span class="badge badge-cal"><?= htmlspecialchars($meal['calories']) ?></span>
                <span class="badge badge-pro"><?= htmlspecialchars($meal['protein']) ?> protein</span>
            </div>
            <ul>
                <?php foreach ($meal['items'] as $item): ?>
                    <li><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>

    <div class="tips">
        <h2>💡 Muscle Building Tips</h2>
        <ul>
            <?php foreach ($tips as $tip): ?>
                <li><?= htmlspecialchars($tip) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="bottom-links">
        <a href="<?= BASE_URL ?>/diet/options.php" class="btn-primary">All Diet Plans</a>
        <a href="<?= BASE_URL ?>/exercise/exercise.php" class="btn-outline">Exercise Plans</a>
        <a href="<?= BASE_URL ?>/metrics/results.php" class="btn-outline">Health Report</a>
    </div>
</div>

</body>
</html>
