<?php
/**
 * using mysqli_connect for database connection
 */

$databaseHost = 'localhost';
$databaseName = 'manajemen_keuangan';
$databaseUsername = 'root';
$databasePassword = '';

$mysqli = mysqli_connect($databaseHost, $databaseUsername, $databasePassword, $databaseName);
if (!$mysqli) {
    die("Tidak bisa terkoneksi ke database");
}

$tanggal = "";
$jenis_transaksi = "";
$jumlah = "";
$deskripsi = "";
$kategori = "";
$sukses = "";
$error = "";

if (isset($_GET["op"])) {
    $op = $_GET["op"];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id_transaksi = $_GET['id_transaksi'];
    $sql1 = "DELETE FROM transaksi_keuangan WHERE id_transaksi = '$id_transaksi'";
    $q1 = mysqli_query($mysqli, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}

if ($op == 'edit') {
    $id_transaksi = $_GET['id_transaksi'];
    $sql1 = "SELECT * FROM transaksi_keuangan WHERE id_transaksi = '$id_transaksi'";
    $q1 = mysqli_query($mysqli, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $tanggal = $r1['tanggal'];
    $jenis_transaksi = $r1['jenis_transaksi'];
    $jumlah = $r1['jumlah'];
    $deskripsi = $r1['deskripsi'];
    $kategori = $r1['kategori'];

    if ($tanggal == '') {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST["simpan"])) {
    $tanggal = $_POST["tanggal"];
    $jenis_transaksi = $_POST["jenis_transaksi"];
    $jumlah = $_POST["jumlah"];
    $deskripsi = $_POST["deskripsi"];
    $kategori = $_POST["kategori"];

    if ($tanggal && $jenis_transaksi && $jumlah && $deskripsi && $kategori) {
        if ($op == 'edit') {
            $sql1 = "UPDATE transaksi_keuangan SET tanggal = '$tanggal', jenis_transaksi = '$jenis_transaksi', jumlah = '$jumlah', deskripsi = '$deskripsi', kategori = '$kategori' WHERE id_transaksi = '$id_transaksi'";
            $q1 = mysqli_query($mysqli, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else {
            $sql1 = "INSERT INTO transaksi_keuangan (tanggal, jenis_transaksi, jumlah, deskripsi, kategori) VALUES ('$tanggal', '$jenis_transaksi', '$jumlah', '$deskripsi', '$kategori')";
            $q1 = mysqli_query($mysqli, $sql1);
            if ($q1) {
                $sukses = "Berhasil Memasukkan Data Baru";
            } else {
                $error = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Silahkan masukkan semua data";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('foto_bumn.jpg'); /* Change this to your image path */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }

        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 20px;
            border-radius: 15px;
            background-color: rgba(255, 255, 255, 0.9); /* Add a slight transparency */
        }

        .card-header {
            background-color: #5D6D7E;
            color: white;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #2874A6;
            border-color: #2874A6;
        }

        .btn-primary:hover {
            background-color: #1F618D;
            border-color: #1F618D;
        }

        .btn-danger {
            background-color: #C0392B;
            border-color: #C0392B;
        }

        .btn-danger:hover {
            background-color: #A93226;
            border-color: #A93226;
        }

        .btn-warning {
            background-color: #F39C12;
            border-color: #F39C12;
        }

        .btn-warning:hover {
            background-color: #D68910;
            border-color: #D68910;
        }

        table th {
            background-color: #5D6D7E;
            color: white;
        }
    </style>
</head>
<body>
<div class="mx-auto">
    <!-- Form untuk memasukkan data -->
    <div class="card">
        <div class="card-header">
            Create / Edit Data
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error ?>
                </div>
            <?php endif; ?>
            <?php if ($sukses): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $sukses ?>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="mb-3 row">
                    <label for="tanggal" class="col-sm-2 col-form-label">Tanggal</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $tanggal ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="jenis_transaksi" class="col-sm-2 col-form-label">Jenis Transaksi</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="jenis_transaksi" name="jenis_transaksi">
                            <option value="pendapatan" <?php if ($jenis_transaksi == "pendapatan") echo "selected" ?>>Pendapatan</option>
                            <option value="pengeluaran" <?php if ($jenis_transaksi == "pengeluaran") echo "selected" ?>>Pengeluaran</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="jumlah" class="col-sm-2 col-form-label">Jumlah</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="jumlah" name="jumlah" value="<?php echo $jumlah ?>">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" id="deskripsi" name="deskripsi"><?php echo $deskripsi ?></textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="kategori" name="kategori">
                            <option value="gaji" <?php if ($kategori == "gaji") echo "selected" ?>>Gaji</option>
                            <option value="belanja" <?php if ($kategori == "belanja") echo "selected" ?>>Belanja</option>
                            <option value="hiburan" <?php if ($kategori == "hiburan") echo "selected" ?>>Hiburan</option>
                            <option value="lainnya" <?php if ($kategori == "lainnya") echo "selected" ?>>Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel untuk menampilkan data -->
    <div class="card">
        <div class="card-header text-white">
            Data Transaksi Keuangan
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Jenis Transaksi</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Deskripsi</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql2 = "SELECT * FROM transaksi_keuangan ORDER BY id_transaksi DESC";
                $q2 = mysqli_query($mysqli, $sql2);
                $urut = 1;
                while ($r2 = mysqli_fetch_array($q2)) {
                    $id_transaksi = $r2["id_transaksi"];
                    $tanggal = $r2["tanggal"];
                    $jenis_transaksi = $r2["jenis_transaksi"];
                    $jumlah = $r2["jumlah"];
                    $deskripsi = $r2["deskripsi"];
                    $kategori = $r2["kategori"];
                    ?>
                    <tr>
                        <th scope="row"><?php echo $urut++ ?></th>
                        <td><?php echo $tanggal ?></td>
                        <td><?php echo $jenis_transaksi ?></td>
                        <td><?php echo $jumlah ?></td>
                        <td><?php echo $deskripsi ?></td>
                        <td><?php echo $kategori ?></td>
                        <td>
                            <a href="index.php?op=edit&id_transaksi=<?php echo $id_transaksi ?>"><button type="button"
                                                                                                    class="btn btn-danger">Edit</button></a>
                            <a href="index.php?op=delete&id_transaksi=<?php echo $id_transaksi ?>"
                               onclick="return confirm('Yakin mau delete data?')"><button type="button"
                                                                                         class="btn btn-warning">Delete</button></a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
