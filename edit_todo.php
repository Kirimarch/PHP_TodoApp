<?php
session_start();
require 'db.php';

// ถ้าไม่ได้ login ให้ redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$message = '';

// ดึงข้อมูล TODO เดิมมาแสดง
$stmt = $pdo->prepare("SELECT * FROM todos WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$todo = $stmt->fetch();

if (!$todo) {
    echo "ไม่พบ TODO นี้";
    exit;
}

// ถ้ามีการ submit แก้ไข
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $due_date = $_POST['due_date'];
    $due_time = $_POST['due_time'];
    $imagePath = $todo['image'];

    // ถ้าอัปโหลดรูปใหม่
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

    // update ลง database
    $stmt = $pdo->prepare("UPDATE todos SET title = ?, due_date = ?, due_time = ?, image = ? WHERE id = ? AND user_id = ?");
if ($stmt->execute([$title, $due_date, $due_time, $imagePath, $id, $_SESSION['user_id']])) {
        header("Location: index.php");
        exit;
    } else {
        $message = "แก้ไข TODO ไม่สำเร็จ";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>แก้ไข TODO</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning">
            <h4>แก้ไข TODO</h4>
        </div>
        <div class="card-body">

            <?php if ($message): ?>
                <div class="alert alert-danger"><?= $message ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label>ชื่อ TODO</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($todo['title']) ?>" required>
                </div>

                <div class="mb-3">
    <label>วันที่สิ้นสุด (Due Date)</label>
    <input type="date" name="due_date" class="form-control" value="<?= htmlspecialchars($todo['due_date']) ?>" required min="<?= date('Y-m-d') ?>">
</div>

<div class="mb-3">
    <label>เวลาสิ้นสุด (Due Time)</label>
    <input type="time" name="due_time" class="form-control" value="<?= htmlspecialchars($todo['due_time']) ?>" required>
</div>

                <div class="mb-3">
                    <label>อัปโหลดรูปใหม่ (optional)</label>
                    <input type="file" name="image" class="form-control">
                    <?php if ($todo['image']): ?>
                        <p class="mt-2">รูปปัจจุบัน:</p>
                        <img src="<?= $todo['image'] ?>" width="150">
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-warning">บันทึกการแก้ไข</button>
                <a href="index.php" class="btn btn-secondary">กลับ</a>
            </form>

        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
