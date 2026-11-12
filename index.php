<?php
require_once __DIR__ . '/security.php';
require_once __DIR__ . '/database.php';
$id_peserta = $nama_peserta = $instansi = $seminar_terpilih = $sukses = $error = '';
$op = (string) ($_GET['op'] ?? '');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if (isset($_POST['delete_id'])) {
        mysqli_execute_query($koneksi, 'DELETE FROM peserta WHERE id_peserta = ?', [(string) $_POST['delete_id']]);
        header('Location: index.php'); exit;
    }
    $id_peserta = (string) ($_POST['id_peserta'] ?? '');
    $nama_peserta = trim((string) ($_POST['nama_peserta'] ?? ''));
    $instansi = trim((string) ($_POST['instansi'] ?? ''));
    $seminar_terpilih = (string) ($_POST['seminar_terpilih'] ?? '');
    if ($nama_peserta !== '' && $instansi !== '' && in_array($seminar_terpilih, ['SeminarA','SeminarB','SeminarC'], true)) {
        if ($op === 'edit') {
            mysqli_execute_query($koneksi, 'UPDATE peserta SET nama_peserta=?,instansi=?,seminar_terpilih=? WHERE id_peserta=?', [$nama_peserta,$instansi,$seminar_terpilih,$id_peserta]);
        } else {
            mysqli_execute_query($koneksi, 'INSERT INTO peserta(nama_peserta,instansi,seminar_terpilih) VALUES (?,?,?)', [$nama_peserta,$instansi,$seminar_terpilih]);
        }
        header('Location: index.php'); exit;
    }
    $error = 'Lengkapi nama, instansi, dan pilihan seminar.';
} elseif ($op === 'edit') {
    $result = mysqli_execute_query($koneksi, 'SELECT * FROM peserta WHERE id_peserta = ?', [(string) ($_GET['id'] ?? '')]);
    $row = $result->fetch_assoc();
    if ($row) {
        $id_peserta=$row['id_peserta']; $nama_peserta=$row['nama_peserta'];
        $instansi=$row['instansi']; $seminar_terpilih=$row['seminar_terpilih'];
    } else { $error='Data tidak ditemukan.'; }
}
?>
<!-- Html -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Peserta Seminar</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- Untuk memasukan data -->
        <div class="card">
            <div class="card-header">
                Pendaftaran Peserta Seminar
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo h($error) ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>

                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo h($sukses) ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");
                }
                ?>

                <form action="" method="POST"><?php echo csrf_input(); ?>
                    <div class="mb-3 row">
                        <label for="id_peserta" class="col-sm-2 col-form-label">ID Peserta</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="id_peserta" name="id_peserta" value="<?php echo h($id_peserta) ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="nama_peserta" class="col-sm-2 col-form-label">Nama Peserta</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_peserta" name="nama_peserta" value="<?php echo h($nama_peserta) ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="instansi" class="col-sm-2 col-form-label">Instansi</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="instansi" name="instansi" value="<?php echo h($instansi) ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="seminar_terpilih" class="col-sm-2 col-form-label">Seminar Terpilih</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="seminar_terpilih" id="seminar_terpilih">
                                <option value="">- Pilih Seminar -</option>
                                <option value="SeminarA" <?php if ($seminar_terpilih == "SeminarA") echo "selected" ?>>Seminar A</option>
                                <option value="SeminarB" <?php if ($seminar_terpilih == "SeminarB") echo "selected" ?>>Seminar B</option>
                                <option value="SeminarC" <?php if ($seminar_terpilih == "SeminarC") echo "selected" ?>>Seminar C</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <input type="submit" name="simpan" value="Daftar Peserta" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- Tampilkan data yang sudah diinput -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Peserta Seminar
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ID Peserta</th>
                            <th scope="col">Nama Peserta</th>
                            <th scope="col">Instansi</th>
                            <th scope="col">Seminar Terpilih</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "select * from peserta order by id_peserta desc";
                        $q2 = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id_peserta'];
                            $id_peserta = $r2['id_peserta'];
                            $nama_peserta = $r2['nama_peserta'];
                            $instansi = $r2['instansi'];
                            $seminar_terpilih = $r2['seminar_terpilih'];
                        ?>
                            <tr>
                                <th scope="row"> <?php echo $urut++ ?> </th>
                                <td scope="row"> <?php echo h($id_peserta) ?> </td>
                                <td scope="row"> <?php echo h($nama_peserta) ?> </td>
                                <td scope="row"> <?php echo h($instansi) ?> </td>
                                <td scope="row"> <?php echo h($seminar_terpilih) ?> </td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo h($id) ?>">
                                        <button type="button" class="btn btn-warning">Edit</button>
                                    </a>
                                    <form action="index.php" method="post" style="display:inline" onsubmit="return confirm('Hapus data peserta ini?');"><?php echo csrf_input(); ?><?php echo csrf_input(); ?><input type="hidden" name="delete_id" value="<?php echo h($id) ?>"><button type="submit" class="btn btn-danger">Delete</button></form>
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

    <!-- Bootstrap End -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
