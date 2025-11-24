<?php
require 'config/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $ps_id      = $_POST['playstation_id'];
    $nama       = htmlspecialchars($_POST['nama']);
    $wa         = htmlspecialchars($_POST['no_wa']);
    $tanggal    = $_POST['tanggal'];
    $jam_mulai  = $_POST['jam_mulai'];
    $durasi     = (int) $_POST['durasi'];
    $metode     = $_POST['metode_pembayaran']; 
    
    $nama_bukti = null;

    if (isset($_FILES['bukti_bayar']) && $_FILES['bukti_bayar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['bukti_bayar']['tmp_name'];
        $file_name = $_FILES['bukti_bayar']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed = ['jpg', 'jpeg', 'png'];
        if (in_array($file_ext, $allowed)) {
            $nama_bukti = 'bukti_' . time() . '.' . $file_ext;
            move_uploaded_file($file_tmp, 'assets/bukti_bayar/' . $nama_bukti);
        }
    }

    $stmt = $conn->prepare("SELECT harga_per_jam FROM playstation WHERE id = ?");
    $stmt->execute([$ps_id]);
    $ps = $stmt->fetch();
    $total_harga = $ps['harga_per_jam'] * $durasi;

    $jam_selesai = date('H:i:s', strtotime("+$durasi hours", strtotime("$tanggal $jam_mulai")));

    $query_cek = "SELECT count(*) as total FROM booking 
                  WHERE playstation_id = :ps_id AND tanggal_booking = :tgl
                  AND status_booking NOT IN ('batal', 'selesai') 
                  AND ((jam_mulai < :selesai_baru AND jam_selesai > :mulai_baru))";
    $stmt_cek = $conn->prepare($query_cek);
    $stmt_cek->execute([':ps_id'=>$ps_id, ':tgl'=>$tanggal, ':mulai_baru'=>$jam_mulai, ':selesai_baru'=>$jam_selesai]);
    
    if ($stmt_cek->fetch()['total'] > 0) {
        echo "<script>alert('Maaf, jam tersebut baru saja diambil orang lain!'); window.history.go(-2);</script>";
        exit;
    }

    try {
        $sql_simpan = "INSERT INTO booking (playstation_id, nama_pelanggan, no_wa, tanggal_booking, jam_mulai, jam_selesai, total_jam, total_harga, status_booking, metode_pembayaran, bukti_pembayaran) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?)";
        $stmt_simpan = $conn->prepare($sql_simpan);
        $stmt_simpan->execute([
            $ps_id, $nama, $wa, $tanggal, $jam_mulai, $jam_selesai, $durasi, $total_harga, $metode, $nama_bukti
        ]);

        $id_booking = $conn->lastInsertId();
        header("Location: cetak_bukti.php?id=$id_booking");
        exit;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>