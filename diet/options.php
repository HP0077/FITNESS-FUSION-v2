<?php
/**
 * Diet Plan Selector
 * 
 * Entry point for all diet-related flows.
 * Links to goal-specific plan pages.
 */

require_once __DIR__ . '/../includes/auth.php';
requireAuth();

$userName = sessionGet('user_name', 'User');

// ── Diet goal options (data separate from presentation) ────
$options = [
    [
        'label' => 'Weight Loss',
        'desc'  => 'Calorie-deficit plans to help you shed fat safely.',
        'icon'  => '🔥',
        'href'  => BASE_URL . '/diet/wt_loss.php',
    ],
    [
        'label' => 'Weight Gain',
        'desc'  => 'Calorie-surplus plans to build healthy mass.',
        'icon'  => '💪',
        'href'  => BASE_URL . '/diet/wt_gain.php',
    ],
    [
        'label' => 'Muscle Gain',
        'desc'  => 'High-protein plans focused on lean muscle growth.',
        'icon'  => '🏋️',
        'href'  => BASE_URL . '/diet/ms_gain.php',
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diet Plans — <?= htmlspecialchars(APP_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #a5d6a7);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 15px;
            color: #333;
        }

        /* ── Top Bar ─────────────────────────────────── */
        .top-bar {
            width: 100%; max-width: 900px;
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 30px;
        }
        .top-bar a {
            text-decoration: none; color: #388e3c; font-weight: 600;
            padding: 8px 20px; border-radius: 20px; background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07); transition: background 0.3s, color 0.3s;
        }
        .top-bar a:hover { background: #43a047; color: #fff; }

        /* ── Container ───────────────────────────────── */
        .container {
            background: #fff;
            border-radius: 20px;
            padding: 50px 40px;
            width: 100%; max-width: 900px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            text-align: center;
        }
        .container h1 {
            color: #2e7d32; font-size: 2.2rem; margin-bottom: 10px;
        }
        .container .subtitle {
            color: #888; font-size: 0.95rem; margin-bottom: 40px;
        }

        /* ── Option Cards ────────────────────────────── */
        .option-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 25px;
        }
        .option-card {
            background: #f9fbe7;
            border-radius: 16px;
            padding: 35px 20px;
            text-decoration: none;
            color: #333;
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }
        .option-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 14px 40px rgba(67,160,71,0.25);
        }
        .option-card .icon {
            font-size: 2.5rem; margin-bottom: 15px;
        }
        .option-card .label {
            font-size: 1.3rem; font-weight: 700; color: #2e7d32; margin-bottom: 8px;
        }
        .option-card .desc {
            font-size: 0.85rem; color: #777; line-height: 1.5;
        }

        /* ── Bottom Links ────────────────────────────── */
        .bottom-links {
            margin-top: 35px;
            display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;
        }
        .bottom-links a {
            padding: 10px 25px; border-radius: 25px; text-decoration: none;
            font-weight: 600; font-size: 0.88rem; transition: all 0.3s;
            background: #fff; color: #388e3c; border: 2px solid #43a047;
        }
        .bottom-links a:hover { background: #43a047; color: #fff; }

        @media screen and (max-width: 600px) {
            .container { padding: 30px 20px; }
            .container h1 { font-size: 1.6rem; }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="<?= BASE_URL ?>/dashboard/dashboard.php">&larr; Dashboard</a>
    <a href="<?= BASE_URL ?>/metrics/results.php">Health Report</a>
</div>

<div class="container">
    <h1>Choose Your Diet Goal</h1>
    <p class="subtitle">Select a plan that matches your fitness objective, <?= htmlspecialchars($userName) ?>.</p>

    <div class="option-grid">
        <?php foreach ($options as $opt): ?>
            <a href="<?= htmlspecialchars($opt['href']) ?>" class="option-card">
                <div class="icon"><?= $opt['icon'] ?></div>
                <div class="label"><?= htmlspecialchars($opt['label']) ?></div>
                <div class="desc"><?= htmlspecialchars($opt['desc']) ?></div>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="bottom-links">
        <a href="<?= BASE_URL ?>/exercise/exercise.php">Exercise Plans</a>
        <a href="<?= BASE_URL ?>/metrics/results.php">Back to Results</a>
    </div>
</div>

</body>
</html>
