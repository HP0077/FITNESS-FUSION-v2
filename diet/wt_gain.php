<?php
/**
 * Weight Gain Diet Plan
 * 
 * Structured calorie-surplus meal plan for healthy mass building.
 * Follows the same array + foreach pattern as wt_loss.php.
 */

require_once __DIR__ . '/../includes/auth.php';
requireAuth();

$userName = sessionGet('user_name', 'User');

// ── Diet Plan Data ─────────────────────────────────────────
$meals = [
    [
        'meal'     => 'Breakfast',
        'time'     => '7:00 – 8:00 AM',
        'icon'     => '🌅',
        'items'    => [
            '3 whole eggs scrambled with vegetables',
            '2 slices whole wheat toast with peanut butter',
            '1 banana and a glass of full-fat milk',
            '1 bowl of oats with mixed nuts and honey',
        ],
        'calories' => '~600 kcal',
    ],
    [
        'meal'     => 'Mid-Morning Snack',
        'time'     => '10:00 – 10:30 AM',
        'icon'     => '🥜',
        'items'    => [
            'Protein shake (milk + banana + oats + peanut butter)',
            'A handful of trail mix (almonds, cashews, raisins)',
        ],
        'calories' => '~350 kcal',
    ],
    [
        'meal'     => 'Lunch',
        'time'     => '12:30 – 1:30 PM',
        'icon'     => '🍛',
        'items'    => [
            'Chicken breast (200g) or paneer butter masala (150g)',
            '2 cups steamed rice or 3 multigrain rotis',
            '1 bowl rajma / chole / dal with ghee',
            'Mixed vegetable sabzi',
            '1 bowl curd with a pinch of sugar',
        ],
        'calories' => '~700 kcal',
    ],
    [
        'meal'     => 'Evening Snack',
        'time'     => '4:00 – 4:30 PM',
        'icon'     => '🍌',
        'items'    => [
            '2 boiled eggs or a bowl of sprouts chaat',
            '1 banana with a tablespoon of peanut butter',
            '1 glass of lassi or chocolate milk',
        ],
        'calories' => '~300 kcal',
    ],
    [
        'meal'     => 'Dinner',
        'time'     => '7:30 – 8:30 PM',
        'icon'     => '🌙',
        'items'    => [
            'Grilled fish (200g) or egg curry (3 eggs)',
            '2 rotis or 1.5 cups rice',
            '1 bowl dal with ghee',
            'Side salad with olive oil dressing',
        ],
        'calories' => '~600 kcal',
    ],
    [
        'meal'     => 'Before Bed',
        'time'     => '9:30 – 10:00 PM',
        'icon'     => '🥛',
        'items'    => [
            '1 glass warm milk with turmeric and honey',
            '5–6 soaked almonds and 2 walnuts',
        ],
        'calories' => '~200 kcal',
    ],
];

$tips = [
    'Target 2,700–3,000 kcal/day — eat more than you burn.',
    'Never skip meals. Consistency is more important than quantity in one sitting.',
    'Add healthy fats: ghee, peanut butter, avocado, olive oil.',
    'Eat every 2.5–3 hours to maintain a calorie surplus.',
    'Combine with strength training to ensure weight gain is muscle, not just fat.',
    'Stay hydrated but avoid drinking water right before meals — it reduces appetite.',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Gain Diet — <?= htmlspecialchars(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fce4ec, #f8bbd0);
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
            border-radius: 20px; background: #fff; color: #c2185b;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: background 0.3s, color 0.3s;
        }
        .top-bar a:hover { background: #e91e63; color: #fff; }

        .container {
            max-width: 850px; margin: 0 auto;
            background: #fff; border-radius: 20px;
            padding: 45px 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }
        .container h1 { color: #c2185b; font-size: 2rem; margin-bottom: 5px; }
        .container .subtitle { color: #999; font-size: 0.9rem; margin-bottom: 35px; }
        .calorie-badge {
            display: inline-block; background: #fce4ec; color: #c2185b;
            padding: 2px 10px; border-radius: 10px; font-size: 0.78rem; font-weight: 600;
            margin-left: 8px;
        }

        .meal-card {
            background: #fff8e1; border-radius: 14px;
            padding: 25px; margin-bottom: 20px;
            border-left: 5px solid #e91e63;
            transition: transform 0.2s;
        }
        .meal-card:hover { transform: translateX(4px); }
        .meal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 12px; flex-wrap: wrap; gap: 6px;
        }
        .meal-header h2 { font-size: 1.15rem; color: #c2185b; }
        .meal-header .time { font-size: 0.8rem; color: #999; }
        .meal-card ul { padding-left: 20px; }
        .meal-card li { margin: 6px 0; font-size: 0.92rem; color: #555; line-height: 1.6; }

        .tips {
            background: #e8f5e9; border-radius: 14px;
            padding: 25px; margin-top: 30px;
        }
        .tips h2 { color: #2e7d32; font-size: 1.1rem; margin-bottom: 12px; }
        .tips li { margin: 6px 0; font-size: 0.9rem; color: #555; line-height: 1.6; }

        .bottom-links {
            display: flex; justify-content: center; gap: 15px;
            flex-wrap: wrap; margin-top: 30px;
        }
        .bottom-links a {
            padding: 10px 25px; border-radius: 25px; text-decoration: none;
            font-weight: 600; font-size: 0.88rem; transition: all 0.3s;
        }
        .btn-primary { background: #e91e63; color: #fff; }
        .btn-primary:hover { background: #c2185b; }
        .btn-outline { background: #fff; color: #c2185b; border: 2px solid #e91e63; }
        .btn-outline:hover { background: #e91e63; color: #fff; }

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
    <h1>💪 Weight Gain Diet Plan</h1>
    <p class="subtitle">A calorie-surplus plan (~2,750 kcal/day) for healthy and sustained weight gain.</p>

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
