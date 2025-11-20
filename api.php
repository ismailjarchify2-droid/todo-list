<?php
require_once "config.php";

// Autoriser AJAX
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$pdo = db();

$action = $_GET["action"] ?? $_POST["action"] ?? null;

if ($action === "list") {
    $q = $pdo->query("SELECT * FROM todo ORDER BY created_at DESC");
    echo json_encode($q->fetchAll());
    exit;
}

if ($action === "add") {
    $title = trim($_POST["title"] ?? "");
    if ($title === "") {
        echo json_encode(["error" => "title required"]);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO todo (title) VALUES (?)");
    $stmt->execute([$title]);

    echo json_encode(["success" => true]);
    exit;
}

if ($action === "delete") {
    $id = intval($_POST["id"] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM todo WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true]);
    exit;
}

if ($action === "toggle") {
    $id = intval($_POST["id"] ?? 0);
    $stmt = $pdo->prepare("UPDATE todo SET done = 1 - done WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true]);
    exit;
}

echo json_encode(["error" => "Unknown action"]);
