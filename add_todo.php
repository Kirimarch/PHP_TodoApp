<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $due_date = $_POST['due_date'];
    $due_time = $_POST['due_time'];
    $user_id = $_SESSION['user_id'];
    $imagePath = null;

    // อัปโหลดรูป
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir);
        }
        $filename = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            $message = "เกิดข้อผิดพลาดในการอัปโหลดรูป";
        }
    }

    // insert todo
    $stmt = $pdo->prepare("INSERT INTO todos (user_id, title, due_date, due_time, image) VALUES (?, ?, ?, ?, ?)");
if ($stmt->execute([$user_id, $title, $due_date, $due_time, $imagePath])) {

        header("Location: index.php");
        exit;
    } else {
        $message = "เพิ่ม TODO ไม่สำเร็จ";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>เพิ่ม TODO</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4>เพิ่ม TODO ใหม่</h4>
        </div>
        <div class="card-body">

            <?php if ($message): ?>
                <div class="alert alert-danger"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>ชื่อ TODO</label>
                    <input type="text" name="title" class="form-control" placeholder="เช่น อ่านหนังสือ" required>
                </div>

                <div class="mb-3">
    <label>วันที่สิ้นสุด (Due Date)</label>
    <input type="date" name="due_date" class="form-control" required min="<?= date('Y-m-d') ?>">
</div>

<div class="mb-3">
    <label>เวลาสิ้นสุด (Due Time)</label>
    <input type="time" name="due_time" class="form-control" required>
</div>

                <div class="mb-3">
                    <label>อัปโหลดรูปภาพ (optional)</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">เพิ่ม TODO</button>
                <a href="index.php" class="btn btn-secondary">กลับ</a>
            </form>

        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
