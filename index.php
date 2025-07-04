<?php
session_start();
require 'db.php';

// ถ้าไม่ได้ login ให้ redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ดึง TODO ของ user นี้
$stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$todos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>สวัสดี, <?= $_SESSION['username'] ?></h3>
        <button class="btn btn-danger" onclick="confirmLogout()">Logout</button>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5>TODO ของคุณ</h5>
        </div>
        <div class="card-body">
            <a href="add_todo.php" class="btn btn-success mb-3">+ เพิ่ม TODO ใหม่</a>

            <?php if ($todos): ?>
                <div class="row">
                    <?php foreach ($todos as $todo): ?>
                        <div class="col-md-3 mb-3">
                            <div class="card h-100">
                               <?php if ($todo['image']): ?>
    <img src="<?= $todo['image'] ?>" class="card-img-top" style="height:150px; object-fit:cover;">
<?php else: ?>
    <div class="card-img-top d-flex justify-content-center align-items-center bg-light text-secondary" style="height:150px; font-size:18px;">
        No Image 🐹
    </div>
<?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($todo['title']) ?></h5>
                                    <p>กำหนดเสร็จ: <?= htmlspecialchars($todo['due_date']) ?> <?= htmlspecialchars(substr($todo['due_time'],0,5)) ?></p>
<p id="countdown-<?= $todo['id'] ?>" class="fw-bold"></p>

                                </div>
                                <div class="card-footer text-center">
                                    <a href="edit_todo.php?id=<?= $todo['id'] ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $todo['id'] ?>)">ลบ</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>ยังไม่มี TODO</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'แน่ใจหรือไม่?',
        text: "คุณต้องการลบ TODO นี้ใช่ไหม",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete_todo.php?id=' + id;
        }
    })
}
</script>

<script>
function confirmLogout() {
    Swal.fire({
        title: 'ออกจากระบบ?',
        text: "คุณแน่ใจหรือไม่ว่าต้องการออกจากระบบ",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ออกจากระบบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    })
}
</script>

</body>
</html>
<script>
// สร้าง countdown สำหรับแต่ละ TODO
<?php foreach ($todos as $todo): 
    $dueDateTime = $todo['due_date'] . 'T' . substr($todo['due_time'],0,5) . ':00';
?>
countdownTimer('countdown-<?= $todo['id'] ?>', '<?= $dueDateTime ?>');
<?php endforeach; ?>

function countdownTimer(elementId, dueDateTimeStr) {
    const countDownDate = new Date(dueDateTimeStr).getTime();

    const x = setInterval(function() {
        const now = new Date().getTime();
        const distance = countDownDate - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

        const el = document.getElementById(elementId);
        if (distance < 0) {
            el.innerHTML = "หมดเวลาแล้ว";
            el.classList.add('text-danger');
            clearInterval(x);
        } else {
            el.innerHTML = "เวลาที่เหลือ: " + days + " วัน " + hours + " ชั่วโมง";
            if (days < 1) {
                el.classList.add('text-danger');
            } else {
                el.classList.remove('text-danger');
            }
        }
    }, 1000);
}
</script>