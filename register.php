<?php
require 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password != $confirm) {
        $message = "รหัสผ่านไม่ตรงกัน";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // ตรวจสอบว่าชื่อผู้ใช้ซ้ำหรือไม่
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $message = "Username นี้ถูกใช้งานแล้ว";
        } else {
            // Insert user
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hash])) {
                $message = "สมัครสมาชิกสำเร็จ! <a href='login.php' class='btn btn-success'>เข้าสู่ระบบ</a>";
            } else {
                $message = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Register</h3>
                </div>
                <div class="card-body">

                    <?php if ($message): ?>
                        <div class="alert alert-info"><?= $message ?></div>
                    <?php endif; ?>

                    <form id="registerForm" method="POST">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>

                        <div class="mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm" class="form-control" placeholder="Confirm Password" required>
                        </div>

                        <button type="button" class="btn btn-primary w-100" onclick="confirmRegister()">สมัครสมาชิก</button>
                    </form>

                    <p class="mt-3 text-center">มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบที่นี่</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmRegister() {
    Swal.fire({
        title: 'ยืนยันการสมัคร?',
        text: "ตรวจสอบข้อมูลให้ถูกต้องก่อนสมัคร",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'สมัครเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('registerForm').submit();
        }
    })
}
</script>

</body>
</html>
