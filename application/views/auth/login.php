<!DOCTYPE html>
<html>
<head>
    <title>Login Admin | Perpusdig</title>
</head>
<body>

<h2>Login Admin</h2>

<?php if ($this->session->flashdata('error')): ?>
    <p style="color:red"><?= $this->session->flashdata('error') ?></p>
<?php endif; ?>

<form method="post" action="<?= site_url('login') ?>">
    
    <input type="hidden"
           name="<?= $this->security->get_csrf_token_name(); ?>"
           value="<?= $this->security->get_csrf_hash(); ?>">

    <input type="text" name="username" placeholder="Username" required>
    <br><br>

    <input type="password" name="password" placeholder="Password" required>
    <br><br>

    <button type="submit">Login</button>
</form>


</body>
</html>
