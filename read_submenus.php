<?php
$host = '127.0.0.1';
$db   = 'port-meritime';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query("SELECT sm.id, sm.label, sm.url, m.label as m_label FROM sub_menus sm JOIN menus m ON sm.menu_id = m.id");
    while ($row = $stmt->fetch()) {
        echo $row['id'] . " | " . $row['label'] . " | " . $row['url'] . " | " . $row['m_label'] . "\n";
    }
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
