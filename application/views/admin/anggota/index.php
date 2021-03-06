<h2 class="mb-4">Data Anggota <?= $title ?></h2>

<?php if ($this->session->flashdata('message')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('message') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif ?>


<div class="form-group row">
    <label for="sosmed" class="col-sm-1 col-form-label">Cari :</label>
    <div class="col-xs-5">
        <input type="text" class="form-control" id="Input" onkeyup="myFunction()" placeholder="Nama">
    </div>
</div>

<table class="table table-bordered text-center" id="anggotaTable">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Foto</th>
            <th scope="col">Nama</th>
            <th scope="col">Nomor HP</th>
            <th scope="col">Alamat</th>
            <th scope="col">Sosial Media</th>
            <th>Asal Institusi</th>
            <th scope="col">Jurusan</th>
            <th scope="col">Lama Magang</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <?php if (isset($user['image'])) : ?>
                        <img src="<?= base_url('assets/img/image/' . $user['image']) ?>" style="height:100px; widht:100px;">
                    <?php else : ?>
                        No Photo
                    <?php endif; ?>
                </td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['nomorhp'] ?></td>
                <td><?= $user['alamat'] ?></td>
                <td><?= $user['sosmed'] ?></td>
                <td><?= $user['asal'] ?></td>
                <td><?= $user['jurusan'] ?></td>
                <td><?= $user['durasi'] ?> Bulan</td>
                <td>
                    <a href="<?= base_url('anggota/delete/' . $user['id_users']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus ini ?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    function myFunction() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("Input");
        filter = input.value.toUpperCase();
        table = document.getElementById("anggotaTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>