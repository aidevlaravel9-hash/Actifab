<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Account Created</title>
</head>

<body style="font-family: Arial; background:#f4f4f4; padding:20px;">

<div style="max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:8px;">

    <h2 style="color:#2a7d3e;">Account Created</h2>

    <p>Hello <?php echo e($data['name']); ?>,</p>

    <p>Your account has been successfully created. Below are your login details:</p>

    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td><strong>Email:</strong></td>
            <td><?php echo e($data['email']); ?></td>
        </tr>
        <tr>
            <td><strong>Password:</strong></td>
            <td><?php echo e($data['password']); ?></td>
        </tr>
        <tr>
            <td><strong>Role:</strong></td>
            <td><?php echo e($data['role']); ?></td>
        </tr>
    </table>

    <p style="margin-top:20px;">
        Please login and change your password after first login.
    </p>

    <br>
    <p>Regards,<br><strong>Your Team</strong></p>

</div>

</body>
</html><?php /**PATH C:\laragon\www\Actifab\resources\views/emails/user-create-mail.blade.php ENDPATH**/ ?>