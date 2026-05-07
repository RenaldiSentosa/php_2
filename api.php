<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'koneksi.php';

// Ambil method request (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

// Ambil input mentah dari body request (biasanya JSON dari Postman)
$json = file_get_contents('php://input');
$input = json_decode($json, true);

switch($method) {
    // ======================================================
    // GET: Ambil Data (Bisa semua data atau berdasarkan ID)
    // ======================================================
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $sql = "SELECT * FROM users WHERE id=$id";
        } else {
            $sql = "SELECT * FROM users ORDER BY id ASC";
        }
        
        $result = mysqli_query($koneksi, $sql);
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode($data);
        break;

    // ======================================================
    // POST: Tambah Data Baru
    // ======================================================
    case 'POST':
        if (isset($input['nama'], $input['sandi'])) {
            $nama  = mysqli_real_escape_string($koneksi, $input['nama']);
            $sandi = mysqli_real_escape_string($koneksi, $input['sandi']);
            
            $sql = "INSERT INTO users(nama, sandi) VALUES('$nama', '$sandi')";
            if (mysqli_query($koneksi, $sql)) {
                echo json_encode(["status" => "success", "message" => "Data berhasil ditambah"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal: " . mysqli_error($koneksi)]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap (nama & sandi diperlukan)"]);
        }
        break;

    // ======================================================
    // PUT: Update Data (Harus kirim ID, Nama, & Sandi)
    // ======================================================
    case 'PUT':
        if (isset($input['id'], $input['nama'], $input['sandi'])) {
            $id    = (int)$input['id'];
            $nama  = mysqli_real_escape_string($koneksi, $input['nama']);
            $sandi = mysqli_real_escape_string($koneksi, $input['sandi']);

            $sql = "UPDATE users SET nama='$nama', sandi='$sandi' WHERE id=$id";
            
            if (mysqli_query($koneksi, $sql)) {
                echo json_encode(["status" => "success", "message" => "Data berhasil diupdate"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal update: " . mysqli_error($koneksi)]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Data tidak lengkap (id, nama, sandi diperlukan)"]);
        }
        break;

    // ======================================================
    // DELETE: Hapus Data (Harus kirim ID)
    // ======================================================
    case 'DELETE':
        if (isset($input['id'])) {
            $id = (int)$input['id'];
            $sql = "DELETE FROM users WHERE id=$id";

            if (mysqli_query($koneksi, $sql)) {
                echo json_encode(["status" => "success", "message" => "Data berhasil dihapus"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal hapus: " . mysqli_error($koneksi)]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "ID diperlukan untuk menghapus data"]);
        }
        break;

    default:
        header("HTTP/1.1 405 Method Not Allowed");
        echo json_encode(["status" => "error", "message" => "Method tidak diizinkan"]);
        break;
}

mysqli_close($koneksi);
?>
