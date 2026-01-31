<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= $title ?? 'Perpusdig' ?></title>

    <!-- Font Awesome -->
    <link href="<?= base_url('assets/sbadmin2/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">

    <!-- SB Admin 2 Core CSS -->
    <link href="<?= base_url('assets/sbadmin2/css/sb-admin-2.min.css') ?>" rel="stylesheet">

    <!-- ================= THEME CSS ================= -->
    <?php
$theme = $this->session->userdata('theme') ?? 'light';
?>

    <link href="<?= base_url('assets/themes/theme-' . $theme . '.css') ?>" rel="stylesheet">
    <!-- ============================================ -->
<style>
/* Theme switcher dots */
.theme-dot {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: none;
    margin: 6px;
    cursor: pointer;
    transition: transform .15s ease, box-shadow .15s ease;
}

.theme-dot:hover {
    transform: scale(1.15);
    box-shadow: 0 0 0 3px rgba(0,0,0,.15);
}

.theme-dot.active {
    box-shadow: 0 0 0 3px #4e73df;
}
</style>

</head>

<body id="page-top" class="theme-<?= $theme ?>">

<!-- Page Wrapper -->
<div id="wrapper">
