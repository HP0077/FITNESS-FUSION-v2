<?php
/**
 * Weight Loss Diet Plan
 * 
 * Structured meal plan for calorie-deficit goals.
 * Data is stored in arrays — HTML just loops and renders.
 */

require_once __DIR__ . '/../includes/auth.php';
requireAuth();

$userName = sessionGet('user_name', 'User');

// ── Diet Plan Data (single source of truth) ────────────────
$meals = [
    [
        'meal'  => 'Breakfast',
        'time'  => '7:00 – 8:00 AM',
        'icon'  => '🌅',
        'items' => [
            'Oatmeal with berries and a teaspoon of honey',
            '2 boiled egg whites',
            'Green tea or black coffee (no sugar)',
            '1 small banana or apple',
        ],
        'calories' => '~350 kcal',
    ],
    [
        'meal'  => 'Mid-Morning Snack',
        'time'  => '10:00 – 10:30 AM',
        'icon'  => '🥜',
        'items' => [
            'A handful of almonds (8–10 pieces)',
            '1 cup buttermilk or green tea',
        ],
        'calories' => '~150 kcal',
    ],
    [
        'meal'  => 'Lunch',
        'time'  => '12:30 – 1:30 PM',
        'icon'  => '🥗',
        'items' => [
            'Grilled chicken breast (150g) or paneer (120g)',
            '1 cup brown rice or 2 multigrain rotis',
            '1 bowl dal (lentil soup)',
            'Mixed vegetable salad with lemon dressing',
            '1 cup curd (low fat)',
        ],
        'calories' => '~500 kcal',
    ],
    [
        'meal'  => 'Evening Snack',
        'time'  => '4:00 – 4:30 PM',
        'icon'  => '🍵',
        'items' => [
            'Sprout salad with cucumber and tomato',
            'Or 1 bowl of roasted chana (chickpeas)',
            'Green tea or coconut water',
        ],
        'calories' => '~120 kcal',
    ],
    [
        'meal'  => 'Dinner',
        'time'  => '7:00 – 8:00 PM',
        'icon'  => '🌙',
        'items' => [
            'Grilled fish (150g) or 1 bowl mixed vegetable curry',
            '1 multigrain roti or 1 cup quinoa',
            'Cucumber and mint raita',
            'Small bowl of soup (tomato or vegetable)',
        ],
        'calories' => '~400 kcal',
    ],
];

$tips = [
    'Drink at least 3–4 liters of water throughout the day.',
    'Avoid sugar, fried foods, and processed snacks.',
    'Eat slowly — it takes 20 minutes for your brain to register fullness.',
    'Maintain a 12-hour overnight fasting window (8 PM – 8 AM).',
    'Pair this diet with 30–45 minutes of daily exercise for best results.',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Loss Diet — <?= htmlspecialchars(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            min-height: 100vh;
            padding: 30px 15px;
            color: #333;
        }

        /* ── Top Bar ─────────────────────────────────── */
        .top-bar {
            max-width: 850px; margin: 0 auto 25px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .top-bar a {
            text-decoration: none; font-weight: 600; padding: 8px 20px;
            border-radius: 20px; background: #fff; color: #e65100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: background 0.3s, color 0.3s;
        }
        .top-bar a:hover { background: #ff6d00; color: #fff; }

        /* ── Container ───────────────────────────────── */
        .container {
            max-width: 850px; margin: 0 auto;
            background: #fff; border-radius: 20px;
            padding: 45px 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }
        .container h1 {
            color: #e65100; font-size: 2rem; margin-bottom: 5px;
        }
        .container .subtitle {
            color: #999; font-size: 0.9rem; margin-bottom: 35px;
        }
        .calorie-badge {
            display: inline-block; background: #fff3e0; color: #e65100;
            padding: 2px 10px; border-radius: 10px; font-size: 0.78rem; font-weight: 600;
            margin-left: 8px;
        }

        /* ── Meal Card ───────────────────────────────── */
        .meal-card {
            background: #fffde7; border-radius: 14px;
            padding: 25px; margin-bottom: 20px;
            border-left: 5px solid #ff6d00;
            transition: transform 0.2s;
        }
        .meal-card:hover { transform: translateX(4px); }
        .meal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 12px;
        }
        .meal-header h2 { font-size: 1.15rem; color: #e65100; }
        .meal-header .time { font-size: 0.8rem; color: #999; }
        .meal-card ul { padding-left: 20px; }
        .meal-card li {
            margin: 6px 0; font-size: 0.92rem; color: #555; line-height: 1.6;
        }

        /* ── Tips ────────────────────────────────────── */
        .tips {
            background: #e8f5e9; border-radius: 14px;
            padding: 25px; margin-top: 30px;
        }
        .tips h2 { color: #2e7d32; font-size: 1.1rem; margin-bottom: 12px; }
        .tips li { margin: 6px 0; font-size: 0.9rem; color: #555; line-height: 1.6; }

        /* ── Bottom Links ────────────────────────────── */
        .bottom-links {
            display: flex; justify-content: center; gap: 15px;
            flex-wrap: wrap; margin-top: 30px;
        }
        .bottom-links a {
            padding: 10px 25px; border-radius: 25px; text-decoration: none;
            font-weight: 600; font-size: 0.88rem; transition: all 0.3s;
        }
        .btn-primary { background: #ff6d00; color: #fff; }
        .btn-primary:hover { background: #e65100; }
        .btn-outline { background: #fff; color: #e65100; border: 2px solid #ff6d00; }
        .btn-outline:hover { background: #ff6d00; color: #fff; }

        @media (max-width: 600px) {
            .container { padding: 25px 18px; }
            .container h1 { font-size: 1.5rem; }
            .meal-header { flex-direction: column; align-items: flex-start; gap: 4px; }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="<?= BASE_URL ?>/diet/options.php">&larr; Diet Plans</a>
    <a href="<?= BASE_URL ?>/dashboard/dashboard.php">Dashboard</a>
</div>

<div class="container">
    <h1>🔥 Weight Loss Diet Plan</h1>
    <p class="subtitle">A balanced calorie-deficit plan (~1,500 kcal/day) designed for sustainable fat loss.</p>

    <?php foreach ($meals as $meal): ?>
        <div class="meal-card">
            <div class="meal-header">
                <h2><?= htmlspecialchars($meal['icon'] . ' ' . $meal['meal']) ?></h2>
                <span class="time"><?= htmlspecialchars($meal['time']) ?></span>
                <span class="calorie-badge"><?= htmlspecialchars($meal['calories']) ?></span>
            </div>
            <ul>
                <?php foreach ($meal['items'] as $item): ?>
                    <li><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>

    <div class="tips">
        <h2>💡 Daily Tips</h2>
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
