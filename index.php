<?php
include 'koneksi.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// ==========================
// TAMBAH DATA
// ==========================
if(isset($_POST['tambah'])){

    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $sandi = mysqli_real_escape_string($koneksi, $_POST['sandi']);

    mysqli_query($koneksi,
        "INSERT INTO users(nama, sandi)
         VALUES('$nama','$sandi')"
    );

    header("Location:index.php");
    exit;
}

// ==========================
// HAPUS DATA
// ==========================
if(isset($_GET['hapus'])){

    $id = (int)$_GET['hapus'];

    mysqli_query($koneksi,
        "DELETE FROM users WHERE id=$id"
    );

    header("Location:index.php");
    exit;
}

// ==========================
// AMBIL DATA EDIT
// ==========================
$editMode = false;
$editData = [];

if(isset($_GET['edit'])){

    $editMode = true;

    $id = (int)$_GET['edit'];

    $result = mysqli_query($koneksi,
        "SELECT * FROM users WHERE id=$id"
    );

    $editData = mysqli_fetch_assoc($result);
}

// ==========================
// UPDATE DATA
// ==========================
if(isset($_POST['update'])){

    $id    = (int)$_POST['id'];
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $sandi = mysqli_real_escape_string($koneksi, $_POST['sandi']);

    mysqli_query($koneksi,
        "UPDATE users
         SET nama='$nama',
             sandi='$sandi'
         WHERE id=$id"
    );

    header("Location:index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>CRUD Railway</title>

<style>

body{
    font-family:Arial;
    background:#f0f2f5;
    padding:30px;
}

.container{
    max-width:900px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

h2{
    margin-bottom:15px;
}

input{
    width:100%;
    padding:12px;
    margin-bottom:12px;
    border:1px solid #ccc;
    border-radius:6px;
    box-sizing:border-box;
}

button{
    padding:12px 20px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    color:white;
    font-weight:bold;
}

.btn-simpan{
    background:#28a745;
}

.btn-update{
    background:#007bff;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:25px;
}

table, th, td{
    border:1px solid #ddd;
}

th{
    background:#333;
    color:white;
}

th, td{
    padding:12px;
    text-align:left;
}

a{
    text-decoration:none;
    padding:6px 10px;
    color:white;
    border-radius:5px;
    font-size:14px;
}

.btn-edit{
    background:orange;
}

.btn-hapus{
    background:red;
}

</style>
</head>

<body>

<div class="container">

    <h2>
        <?= $editMode ? 'Edit User' : 'Tambah User'; ?>
    </h2>

    <form method="POST">

        <?php if($editMode){ ?>

            <input
                type="hidden"
                name="id"
                value="<?= $editData['id']; ?>"
            >

        <?php } ?>

        <input
            type="text"
            name="nama"
            placeholder="Masukkan nama"
            value="<?= $editMode ? $editData['nama'] : ''; ?>"
            required
        >

        <input
            type="password"
            name="sandi"
            placeholder="Masukkan password"
            value="<?= $editMode ? $editData['sandi'] : ''; ?>"
            required
        >

        <?php if($editMode){ ?>

            <button
                type="submit"
                name="update"
                class="btn-update"
            >
                Update Data
            </button>

        <?php } else { ?>

            <button
                type="submit"
                name="tambah"
                class="btn-simpan"
            >
                Simpan Data
            </button>

        <?php } ?>

    </form>

    <h2>Data Users</h2>

    <table>

        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Password</th>
            <th>Aksi</th>
        </tr>

        <?php

        $no = 1;

        // ASC = data urut dari pertama ditambahkan
        $data = mysqli_query($koneksi,
            "SELECT * FROM users ORDER BY id ASC"
        );

        while($d = mysqli_fetch_assoc($data)){

        ?>

        <tr>

            <td><?= $no++; ?></td>
            <td><?= $d['nama']; ?></td>
            <td><?= $d['sandi']; ?></td>

            <td>

                <a
                    href="index.php?edit=<?= $d['id']; ?>"
                    class="btn-edit"
                >
                    Edit
                </a>

                <a
                    href="index.php?hapus=<?= $d['id']; ?>"
                    class="btn-hapus"
                    onclick="return confirm('Yakin ingin hapus data?')"
                >
                    Hapus
                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>
