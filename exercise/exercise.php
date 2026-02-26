<?php
/**
 * Exercise Plans
 * 
 * Structured workout routines organized by fitness goal.
 * Same array + foreach architecture as the diet module.
 */

require_once __DIR__ . '/../includes/auth.php';
requireAuth();

$userName = sessionGet('user_name', 'User');

// ── Exercise Plan Data ─────────────────────────────────────
$plans = [
    [
        'name'      => 'Weight Loss',
        'goal'      => 'Burn fat and improve cardiovascular endurance',
        'icon'      => '🔥',
        'color'     => '#e65100',
        'frequency' => '5 days/week — 30 to 45 minutes per session',
        'exercises' => [
            'Jumping Jacks — 3 sets × 45 seconds',
            'Burpees — 3 sets × 10–12 reps',
            'Mountain Climbers — 3 sets × 30 seconds',
            'High Knees — 3 sets × 40 seconds',
            'Bodyweight Squats — 3 sets × 15 reps',
            'Plank Hold — 3 sets × 45 seconds',
        ],
        'notes' => 'Keep rest between sets to 20–30 seconds. Aim for a heart rate of 130–160 BPM. Pair with the Weight Loss diet plan for best results.',
    ],
    [
        'name'      => 'Weight Gain',
        'goal'      => 'Build overall mass through compound movements',
        'icon'      => '💪',
        'color'     => '#c2185b',
        'frequency' => '4 days/week — 40 to 50 minutes per session',
        'exercises' => [
            'Push-ups (wide grip) — 4 sets × 12–15 reps',
            'Bodyweight Squats — 4 sets × 15 reps',
            'Glute Bridges — 3 sets × 15 reps',
            'Pike Push-ups — 3 sets × 10–12 reps',
            'Lunges — 3 sets × 12 reps per leg',
            'Plank — 3 sets × 60 seconds',
        ],
        'notes' => 'Rest 60–90 seconds between sets. Focus on slow, controlled reps to maximize time under tension. Eat within 1 hour of finishing.',
    ],
    [
        'name'      => 'Muscle Gain',
        'goal'      => 'Hypertrophy through progressive overload',
        'icon'      => '🏋️',
        'color'     => '#00695c',
        'frequency' => '5–6 days/week — 45 to 60 minutes per session',
        'exercises' => [
            'Diamond Push-ups — 4 sets × 10–12 reps',
            'Bulgarian Split Squats — 4 sets × 10 reps per leg',
            'Pull-ups / Inverted Rows — 4 sets × 8–10 reps',
            'Dips (chair-assisted) — 3 sets × 12 reps',
            'Single-leg Glute Bridge — 3 sets × 12 reps per leg',
            'Plank to Push-up — 3 sets × 10 reps',
            'Superman Holds — 3 sets × 30 seconds',
        ],
        'notes' => 'Increase reps or add resistance (backpack with books) every 2 weeks. Split into Push/Pull/Legs if training 6 days. Rest 60–90 seconds between sets.',
    ],
];

$generalTips = [
    'Warm up for 5 minutes before every session — brisk walk or dynamic stretches.',
    'Cool down with 5 minutes of static stretching to prevent soreness.',
    'Stay hydrated — drink 500ml water 30 minutes before training.',
    'Track your workouts in a notebook or app to monitor progress.',
    'Rest days are not lazy days — your muscles grow during recovery.',
    'Get 7–8 hours of sleep. Growth hormone peaks during deep sleep.',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise Plans — <?= htmlspecialchars(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            min-height: 100vh;
            padding: 30px 15px;
            color: #333;
        }

        .top-bar {
            max-width: 900px; margin: 0 auto 25px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .top-bar a {
            text-decoration: none; font-weight: 600; padding: 8px 20px;
            border-radius: 20px; background: #fff; color: #1565c0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: background 0.3s, color 0.3s;
        }
        .top-bar a:hover { background: #1976d2; color: #fff; }

        .container {
            max-width: 900px; margin: 0 auto;
            background: #fff; border-radius: 20px;
            padding: 45px 35px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }
        .container h1 { color: #1565c0; font-size: 2rem; margin-bottom: 5px; }
        .container .subtitle { color: #999; font-size: 0.9rem; margin-bottom: 35px; }

        /* ── Plan Card ───────────────────────────────── */
        .plan-card {
            border-radius: 14px;
            padding: 28px;
            margin-bottom: 25px;
            transition: transform 0.2s;
        }
        .plan-card:hover { transform: translateX(4px); }
        .plan-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 6px; flex-wrap: wrap; gap: 6px;
        }
        .plan-header h2 { font-size: 1.25rem; }
        .plan-header .freq {
            font-size: 0.78rem; font-weight: 600; padding: 3px 12px;
            border-radius: 10px; background: rgba(255,255,255,0.7);
        }
        .plan-goal { font-size: 0.85rem; color: #666; margin-bottom: 14px; }
        .plan-card ul { padding-left: 20px; }
        .plan-card li { margin: 5px 0; font-size: 0.92rem; color: #444; line-height: 1.6; }
        .plan-note {
            margin-top: 12px; font-size: 0.82rem; color: #777;
            font-style: italic; border-top: 1px solid rgba(0,0,0,0.06);
            padding-top: 10px;
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
        .btn-primary { background: #1976d2; color: #fff; }
        .btn-primary:hover { background: #1565c0; }
        .btn-outline { background: #fff; color: #1565c0; border: 2px solid #1976d2; }
        .btn-outline:hover { background: #1976d2; color: #fff; }

        @media (max-width: 600px) {
            .container { padding: 25px 18px; }
            .container h1 { font-size: 1.5rem; }
            .plan-header { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="<?= BASE_URL ?>/dashboard/dashboard.php">&larr; Dashboard</a>
    <a href="<?= BASE_URL ?>/diet/options.php">Diet Plans</a>
</div>

<div class="container">
    <h1>🏃 Exercise Plans</h1>
    <p class="subtitle">Pick the routine that matches your goal, <?= htmlspecialchars($userName) ?>. All plans are bodyweight-friendly — no gym needed.</p>

    <?php foreach ($plans as $plan): ?>
        <div class="plan-card" style="background: <?= htmlspecialchars($plan['color']) ?>12; border-left: 5px solid <?= htmlspecialchars($plan['color']) ?>;">
            <div class="plan-header">
                <h2 style="color: <?= htmlspecialchars($plan['color']) ?>;">
                    <?= htmlspecialchars($plan['icon'] . ' ' . $plan['name']) ?>
                </h2>
                <span class="freq" style="color: <?= htmlspecialchars($plan['color']) ?>;">
                    <?= htmlspecialchars($plan['frequency']) ?>
                </span>
            </div>
            <p class="plan-goal"><?= htmlspecialchars($plan['goal']) ?></p>
            <ul>
                <?php foreach ($plan['exercises'] as $exercise): ?>
                    <li><?= htmlspecialchars($exercise) ?></li>
                <?php endforeach; ?>
            </ul>
            <p class="plan-note"><?= htmlspecialchars($plan['notes']) ?></p>
        </div>
    <?php endforeach; ?>

    <div class="tips">
        <h2>💡 General Training Tips</h2>
        <ul>
            <?php foreach ($generalTips as $tip): ?>
                <li><?= htmlspecialchars($tip) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="bottom-links">
        <a href="<?= BASE_URL ?>/dashboard/dashboard.php" class="btn-primary">Dashboard</a>
        <a href="<?= BASE_URL ?>/diet/options.php" class="btn-outline">Diet Plans</a>
        <a href="<?= BASE_URL ?>/metrics/results.php" class="btn-outline">Health Report</a>
    </div>
</div>

</body>
</html>
