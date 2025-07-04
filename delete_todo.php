<?php
session_start();
require 'db.php';

// ถ้าไม่ได้ login ให้ redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

// ดึงข้อมูล TODO เพื่อลบไฟล์รูปถ้ามี
$stmt = $pdo->prepare("SELECT * FROM todos WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$todo = $stmt->fetch();

if ($todo) {
    // ลบรูปใน uploads ถ้ามี
    if ($todo['image'] && file_exists($todo['image'])) {
        unlink($todo['image']);
    }

    // ลบ TODO ใน database
    $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
}

header("Location: index.php");
exit;
?>
