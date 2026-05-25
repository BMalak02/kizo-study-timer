<?php
$dbPath = __DIR__ . '/database/database.sqlite';
$db = new PDO('sqlite:' . $dbPath);
$users = $db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
echo "Users in DB (" . count($users) . "):\n";
print_r($users);
$challenges = $db->query("SELECT * FROM challenges")->fetchAll(PDO::FETCH_ASSOC);
echo "Challenges in DB (" . count($challenges) . "):\n";
print_r($challenges);
