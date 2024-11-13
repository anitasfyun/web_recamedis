<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "recamedis";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Menambahkan pasien
if (isset($_POST['add_patient'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $nomor_telepon = mysqli_real_escape_string($conn, $_POST['nomor_telepon']);
    
    $sql = "INSERT INTO Pasien (nama, jenis_kelamin, tanggal_lahir, alamat, nomor_telepon) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nama, $jenis_kelamin, $tanggal_lahir, $alamat, $nomor_telepon);
    $stmt->execute();
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit();
}

// Mengupdate pasien
if (isset($_POST['update_patient'])) {
    $id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $nomor_telepon = mysqli_real_escape_string($conn, $_POST['nomor_telepon']);
    
    $sql = "UPDATE Pasien SET nama=?, jenis_kelamin=?, tanggal_lahir=?, alamat=?, nomor_telepon=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nama, $jenis_kelamin, $tanggal_lahir, $alamat, $nomor_telepon, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit();
}

// Menghapus pasien
if (isset($_GET['delete_patient'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_patient']);
    // Hapus terlebih dahulu rekam medis yang terkait
    $sql = "DELETE FROM RekamMedis WHERE pasien_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    // Kemudian hapus data pasien
    $sql = "DELETE FROM Pasien WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit();
}

// Menambahkan dokter
if (isset($_POST['add_doctor'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['doctor_name']);
    $spesialis = mysqli_real_escape_string($conn, $_POST['specialization']);
    $sql = "INSERT INTO Dokter (nama, spesialis) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nama, $spesialis);
    $stmt->execute();
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit();
}

// Mengupdate dokter
if (isset($_POST['update_doctor'])) {
    $id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $nama = mysqli_real_escape_string($conn, $_POST['doctor_name']);
    $spesialis = mysqli_real_escape_string($conn, $_POST['specialization']);
    $sql = "UPDATE Dokter SET nama=?, spesialis=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nama, $spesialis, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit();
}

// Menghapus dokter
if (isset($_GET['delete_doctor'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_doctor']);
    // Hapus terlebih dahulu rekam medis yang terkait
    $sql = "DELETE FROM RekamMedis WHERE dokter_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    // Kemudian hapus data dokter
    $sql = "DELETE FROM Dokter WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit();
}

// Menambahkan rekam medis
if (isset($_POST['add_record'])) {
    $pasien_id = mysqli_real_escape_string($conn, $_POST['pasien_id']);
    $dokter_id = mysqli_real_escape_string($conn, $_POST['dokter_id']);
    $keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);
    $diagnosa = mysqli_real_escape_string($conn, $_POST['diagnosa']);
    $treatment = mysqli_real_escape_string($conn, $_POST['treatment']);
    $tanggal = date('Y-m-d');
    
    $sql = "INSERT INTO RekamMedis (pasien_id, dokter_id, tanggal, keluhan, diagnosa, treatment) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $pasien_id, $dokter_id, $tanggal, $keluhan, $diagnosa, $treatment);
    
    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    } else {
        header("Location: ".$_SERVER['PHP_SELF']."?error=1");
    }
    $stmt->close();
    exit();
}

// Mengupdate rekam medis
if (isset($_POST['update_record'])) {
    $id = mysqli_real_escape_string($conn, $_POST['record_id']);
    $pasien_id = mysqli_real_escape_string($conn, $_POST['pasien_id']);
    $dokter_id = mysqli_real_escape_string($conn, $_POST['dokter_id']);
    $keluhan = mysqli_real_escape_string($conn, $_POST['keluhan']);
    $diagnosa = mysqli_real_escape_string($conn, $_POST['diagnosa']);
    $treatment = mysqli_real_escape_string($conn, $_POST['treatment']);
    
    $sql = "UPDATE RekamMedis SET pasien_id=?, dokter_id=?, keluhan=?, diagnosa=?, treatment=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisssi", $pasien_id, $dokter_id, $keluhan, $diagnosa, $treatment, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit();
}

// Menghapus rekam medis
if (isset($_GET['delete_record'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_record']);
    $sql = "DELETE FROM RekamMedis WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']."?success=1");
    exit();
}

// Fungsi untuk mendapatkan data
function getPatients($conn) {
    $sql = "SELECT * FROM Pasien ORDER BY nama ASC";
    return $conn->query($sql);
}

function getDoctors($conn) {
    $sql = "SELECT * FROM Dokter ORDER BY nama ASC";
    return $conn->query($sql);
}

function getRecords($conn) {
    $sql = "SELECT rm.*, p.nama AS pasien_nama, d.nama AS dokter_nama 
            FROM RekamMedis rm
            JOIN Pasien p ON rm.pasien_id = p.id
            JOIN Dokter d ON rm.dokter_id = d.id
            ORDER BY rm.tanggal DESC";
    return $conn->query($sql);
}

// Cek untuk alert
$alert = '';
if (isset($_GET['success'])) {
    $alert = '<div class="alert alert-success">Data berhasil diproses!</div>';
} elseif (isset($_GET['error'])) {
    $alert = '<div class="alert alert-danger">Terjadi kesalahan! Pastikan semua field terisi dengan benar.</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronic Medical Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: pink;
            padding: 1rem 2rem;
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white;
        }
        .navbar-custom .nav-link:hover {
            color: #f0f0f0;
        }
        .content-wrapper {
            margin-top: 80px;
            padding: 20px;
            min-height: calc(100vh - 180px);
        }
        .table-responsive {
            margin-top: 20px;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .footer {
            background-color: pink;
            color: white;
            padding: 20px 0;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 40px;
        }
        .footer-content {
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid">
             <a class="navbar-brand" href="#">
                <img src="assets/lg.png" alt="Logo" width="50" height="30" class="d-inline-block align-text-top">
                EMR System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" 
                    aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="openTab(event, 'Pasien')">Pasien</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="openTab(event, 'Dokter')">Dokter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="openTab(event, 'RekamMedis')">Rekam Medis</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="container">
            <?php echo $alert; ?>

            <!-- Pasien Section -->
            <div id="Pasien" class="tabcontent">
                <h2>Data Pasien</h2>
                <form method="POST" class="mb-4">
                    <input type="hidden" name="patient_id" id="patient_id">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Pasien" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" required>
                                <option value="">Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input type="text" class="form-control" name="nomor_telepon" id="nomor_telepon" placeholder="No. Telepon" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_patient">Tambah Pasien</button>
                    <button type="submit" class="btn btn-warning" name="update_patient" style="display:none;">Update Pasien</button>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Gender</th>
                                <th>Tanggal Lahir</th>
                                <th>No. Telepon</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $patients = getPatients($conn);
                            while ($row = $patients->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['nama']}</td>
                                        <td>{$row['jenis_kelamin']}</td>
                                        <td>{$row['tanggal_lahir']}</td>
                                        <td>{$row['nomor_telepon']}</td>
                                        <td>{$row['alamat']}</td>
                                        <td class='action-buttons'>
                                            <button class='btn btn-sm btn-warning' onclick='editPatient({$row['id']}, \"{$row['nama']}\", \"{$row['jenis_kelamin']}\", \"{$row['tanggal_lahir']}\", \"{$row['nomor_telepon']}\", \"{$row['alamat']}\")'>Edit</button>
                                            <a class='btn btn-sm btn-danger' href='?delete_patient={$row['id']}' onclick=\"return confirm('Yakin ingin menghapus?');\">Hapus</a>
                                        </td>
                                    </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Dokter Section -->
            <div id="Dokter" class="tabcontent" style="display:none;">
                <h2>Data Dokter</h2>
                <form method="POST" class="mb-4">
                    <input type="hidden" name="doctor_id" id="doctor_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="doctor_name" id="doctor_name" placeholder="Nama Dokter" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="text" class="form-control" name="specialization" id="specialization" placeholder="Spesialis" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_doctor">Tambah Dokter</button>
                    <button type="submit" class="btn btn-warning" name="update_doctor" style="display:none;">Update Dokter</button>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Spesialis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $doctors = getDoctors($conn);
                            while ($row = $doctors->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['nama']}</td>
                                        <td>{$row['spesialis']}</td>
                                        <td class='action-buttons'>
                                            <button class='btn btn-sm btn-warning' onclick='editDoctor({$row['id']}, \"{$row['nama']}\", \"{$row['spesialis']}\")'>Edit</button>
                                            <a class='btn btn-sm btn-danger' href='?delete_doctor={$row['id']}' onclick=\"return confirm('Yakin ingin menghapus?');\">Hapus</a>
                                        </td>
                                    </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rekam Medis Section -->
            <div id="RekamMedis" class="tabcontent" style="display:none;">
                <h2>Data Rekam Medis</h2>
                <form method="POST" class="mb-4">
                    <input type="hidden" name="record_id" id="record_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <select name="pasien_id" id="pasien_id" class="form-control" required>
                                <option value="">Pilih Pasien</option>
                                <?php
                                $patients = getPatients($conn);
                                while ($row = $patients->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['nama']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <select name="dokter_id" id="dokter_id" class="form-control" required>
                                <option value="">Pilih Dokter</option>
                                <?php
                                $doctors = getDoctors($conn);
                                while ($row = $doctors->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['nama']} - {$row['spesialis']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <textarea class="form-control" name="keluhan" id="keluhan" placeholder="Keluhan Pasien" required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <textarea class="form-control" name="diagnosa" id="diagnosa" placeholder="Diagnosa" required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <textarea class="form-control" name="treatment" id="treatment" placeholder="Treatment/Pengobatan" required></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="add_record">Tambah Rekam Medis</button>
                    <button type="submit" class="btn btn-warning" name="update_record" style="display:none;">Update Rekam Medis</button>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Keluhan</th>
                                <th>Diagnosa</th>
                                <th>Treatment</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $records = getRecords($conn);
                            while ($row = $records->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['tanggal']}</td>
                                        <td>{$row['pasien_nama']}</td>
                                        <td>{$row['dokter_nama']}</td>
                                        <td>{$row['keluhan']}</td>
                                        <td>{$row['diagnosa']}</td>
                                        <td>{$row['treatment']}</td>
                                        <td class='action-buttons'>
                                            <button class='btn btn-sm btn-warning' onclick='editRecord({$row['id']}, \"{$row['pasien_id']}\", \"{$row['dokter_id']}\", \"{$row['keluhan']}\", \"{$row['diagnosa']}\", \"{$row['treatment']}\")'>Edit</button>
                                            <a class='btn btn-sm btn-danger' href='?delete_record={$row['id']}' onclick=\"return confirm('Yakin ingin menghapus?');\">Hapus</a>
                                        </td>
                                    </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-text">
                <strong>EMR System</strong> &copy; <?php echo date('Y'); ?> All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("nav-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.classList.add("active");
        }

        // Buka tab Pasien secara default
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("Pasien").style.display = "block";
        });

        function editPatient(id, nama, jenis_kelamin, tanggal_lahir, nomor_telepon, alamat) {
            document.getElementById('patient_id').value = id;
            document.getElementById('nama').value = nama;
            document.getElementById('jenis_kelamin').value = jenis_kelamin;
            document.getElementById('tanggal_lahir').value = tanggal_lahir;
            document.getElementById('nomor_telepon').value = nomor_telepon;
            document.getElementById('alamat').value = alamat;
            document.querySelector('button[name="add_patient"]').style.display = 'none';
            document.querySelector('button[name="update_patient"]').style.display = 'inline';
        }

        function editDoctor(id, nama, spesialis) {
            document.getElementById('doctor_id').value = id;
            document.getElementById('doctor_name').value = nama;
            document.getElementById('specialization').value = spesialis;
            document.querySelector('button[name="add_doctor"]').style.display = 'none';
            document.querySelector('button[name="update_doctor"]').style.display = 'inline';
        }

        function editRecord(id, pasien_id, dokter_id, keluhan, diagnosa, treatment) {
            document.getElementById('record_id').value = id;
            document.getElementById('pasien_id').value = pasien_id;
            document.getElementById('dokter_id').value = dokter_id;
            document.getElementById('keluhan').value = keluhan;
            document.getElementById('diagnosa').value = diagnosa;
            document.getElementById('treatment').value = treatment;
            document.querySelector('button[name="add_record"]').style.display = 'none';
            document.querySelector('button[name="update_record"]').style.display = 'inline';
        }

        // Reset form setelah submit
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    this.reset();
                    let addButtons = this.querySelectorAll('button[name^="add_"]');
                    let updateButtons = this.querySelectorAll('button[name^="update_"]');
                    addButtons.forEach(btn => btn.style.display = 'inline');
                    updateButtons.forEach(btn => btn.style.display = 'none');
                }, 100);
            });
        });
    </script>
</body>
</html>