<?php
$dbPath = __DIR__ . '/database/database.sqlite';
if (file_exists($dbPath)) unlink($dbPath);
$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Creating tables...\n";
$db->exec("CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT UNIQUE, password TEXT, level INTEGER DEFAULT 1, xp INTEGER DEFAULT 0, avatar TEXT, total_study_minutes INTEGER DEFAULT 0, completed_sessions INTEGER DEFAULT 0, current_streak INTEGER DEFAULT 0, last_session_date TEXT, remember_token TEXT, created_at DATETIME, updated_at DATETIME)");
$db->exec("CREATE TABLE challenges (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, description TEXT, icon TEXT, target_sessions INTEGER, target_duration INTEGER, xp_reward INTEGER, difficulty TEXT, is_active INTEGER, created_at DATETIME, updated_at DATETIME)");
$db->exec("CREATE TABLE achievements (id INTEGER PRIMARY KEY AUTOINCREMENT, slug TEXT UNIQUE, name TEXT, description TEXT, icon TEXT, badge_color TEXT, category TEXT, created_at DATETIME, updated_at DATETIME)");
$db->exec("CREATE TABLE study_sessions (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, subject TEXT, category TEXT, duration_minutes INTEGER, xp_earned INTEGER, session_type TEXT, status TEXT, notes TEXT, started_at DATETIME, completed_at DATETIME, created_at DATETIME, updated_at DATETIME)");

echo "Seeding users...\n";
$db->exec("INSERT INTO users (name, email, password, level, xp, created_at, updated_at) VALUES ('Demo User', 'demo@kizo.com', '".password_hash('password123', PASSWORD_DEFAULT)."', 12, 1200, datetime('now'), datetime('now'))");

echo "Seeding challenges...\n";
$challenges = [
    ['5 Day Streak', 'Study for 5 consecutive days', '🔥', 5, null, 250, 'easy'],
    ['10 Hour Challenge', 'Complete 10 hours of study', '⏰', null, 600, 500, 'medium'],
    ['Focus Master', 'Complete 20 Pomodoro sessions', '🎯', 20, null, 750, 'hard']
];
foreach ($challenges as $c) {
    $stmt = $db->prepare("INSERT INTO challenges (name, description, icon, target_sessions, target_duration, xp_reward, difficulty, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 1, datetime('now'), datetime('now'))");
    $stmt->execute($c);
}

$count = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
echo "User count after seed: $count\n";

echo "Database fully initialized and seeded!\n";
