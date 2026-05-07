<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include 'koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    // ==========================
    // GET: Ambil Data
    // ==========================
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

    // ==========================
    // POST: Tambah Data
    // ==========================
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $nama  = mysqli_real_escape_string($koneksi, $input['nama']);
        $sandi = mysqli_real_escape_string($koneksi, $input['sandi']);
        
        $sql = "INSERT INTO users(nama, sandi) VALUES('$nama', '$sandi')";
        if (mysqli_query($koneksi, $sql)) {
            echo json_encode(["message" => "Data berhasil ditambah"]);
        } else {
            echo json_encode(["message" => "Gagal tambah data", "error" => mysqli_error($koneksi)]);
        }
        break;

    // ==========================
    // PUT: Update Data
    // ==========================
    case 'PUT':
        $input = json_decode(file_get_contents('php://input'), true);
        $id    = (int)$input['id'];
        $nama  = mysqli_real_escape_string($koneksi, $input['nama']);
        $sandi = mysqli_real_escape_string($koneksi, $input['sandi']);
        
        $sql = "UPDATE users SET nama='$nama', sandi='$sandi' WHERE id=$id";
        if (mysqli_query($koneksi, $sql)) {
            echo json_encode(["message" => "Data berhasil diupdate"]);
        } else {
            echo json_encode(["message" => "Gagal update data"]);
        }
        break;

    // ==========================
    // DELETE: Hapus Data
    // ==========================
    case 'DELETE':
        $input = json_decode(file_get_contents('php://input'), true);
        $id    = (int)$input['id'];
        
        $sql = "DELETE FROM users WHERE id=$id";
        if (mysqli_query($koneksi, $sql)) {
            echo json_encode(["message" => "Data berhasil dihapus"]);
        } else {
            echo json_encode(["message" => "Gagal hapus data"]);
        }
        break;

    default:
        echo json_encode(["message" => "Method tidak dikenali"]);
        break;
}

mysqli_close($koneksi);
?>
