<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/summernote-image-list.min.css') ?>">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px;
        }

        .note-editor .dropdown-toggle::after {
            all: unset;
        }

        .note-editor .note-dropdown-menu {
            box-sizing: content-box;
        }

        .note-editor .note-modal-footer {
            box-sizing: content-box;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <div class="card">
            <div class="card-header">
                Create / Edit Berita
            </div>
            <div class="card-body">
                <?php
                if (session()->getFlashdata('sukses')) {
                ?>
                    <div class="alert alert-primary" role="alert">
                        <?php echo session()->getFlashdata('sukses') ?>
                    </div>
                <?php
                }
                ?>
                <?php
                if (session()->getFlashdata('error')) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php foreach (session()->getFlashdata('error') as $value) { ?>
                                <li><?php echo $value ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="judul" class="col-sm-2 col-form-label">Judul</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="judul" value="<?php echo $judul ?>" name="judul">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="isi" class="col-sm-2 col-form-label">Isi</label>
                        <div class="col-sm-10">
                            <textarea class="form-control summernote" name="isi" id="isi"><?php echo $isi ?></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Berita
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Judul</th>
                            <th style="width:200px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($isiBerita as $value) {
                            $link_edit = site_url("berita/index/edit/" . $value['id']);
                            $link_hapus = site_url("berita/index/hapus/" . $value['id']);
                        ?>
                            <tr>
                                <td><?php echo $value['judul'] ?></td>
                                <td>
                                    <a href="<?php echo $link_edit ?>" class="btn btn-sm btn-warning">EDIT</a>
                                    <a href="#" onclick="konfirmasi('<?php echo $link_hapus ?>')" class="btn btn-sm btn-danger">HAPUS</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="<?php echo base_url('assets/js/summernote-image-list.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                callbacks: {
                    onImageUpload: function(files) {
                        for (let i = 0; i < files.length; i++) {
                            $.upload(files[i]);
                        }
                    },
                    onMediaDelete: function(target) {
                        $.delete(target[0].src);
                    }
                },
                height: 200,
                toolbar: [
                    ["style", ["bold", "italic", "underline", "clear"]],
                    ["fontname", ["fontname"]],
                    ["fontsize", ["fontsize"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["height", ["height"]],
                    ["insert", ["link", "picture", "imageList", "video", "hr"]],

                ],
                dialogsInBody: true,
                imageList: {
                    endpoint: "<?php echo site_url('berita/listGambar') ?>",
                    fullUrlPrefix: "<?php echo base_url('uploads/berkas') ?>/",
                    thumbUrlPrefix: "<?php echo base_url('uploads/berkas') ?>/"
                }
            });

            $.upload = function(file) {
                let out = new FormData();
                out.append('file', file, file.name);
                $.ajax({
                    method: 'POST',
                    url: '<?php echo site_url('berita/uploadGambar') ?>',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: out,
                    success: function(img) {
                        $('.summernote').summernote('insertImage', img);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error(textStatus + " " + errorThrown);
                    }
                });
            };
            $.delete = function(src) {
                $.ajax({
                    method: 'POST',
                    url: '<?php echo site_url('berita/deleteGambar') ?>',
                    cache: false,
                    data: {
                        src: src
                    },
                    success: function(response) {
                        console.log(response);
                    }

                });
            };
        });

        function konfirmasi(url) {
            var result = confirm("Want to delete?");
            if (result) {
                window.location.href = url;
            }
        }
    </script>
</body>
</html>
