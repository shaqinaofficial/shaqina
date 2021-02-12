<?php
include_once 'db/connect_db.php';
session_start();
if ($_SESSION['username'] == "") {
    header('location:index.php');
} else {
    if ($_SESSION['role'] == "Admin") {
        include_once 'inc/header_all.php';
    } else {
        include_once 'inc/header_all_operator.php';
    }
}

error_reporting(0);

$id = @$_GET['id'];
$action = @$_GET['action'];

if ($action && $id) {
    if ($action == "delete") {
        $delete_query = "DELETE FROM tbl_invoice WHERE inv_id = $id";
        $delete = $pdo->prepare($delete_query);
        if ($delete->execute()) {
            echo '<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "Transaksi Telah Dihapus", "info", {
            button: "Continue",
                });
            });
            </script>';
        } else {
            print_r($delete->errorInfo());
        }
    } else if ($action == "terima") {
        $acc = $pdo->prepare("UPDATE `tbl_invoice` SET `status` = '1' WHERE inv_id = $id;");
        $select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE inv_id = $id");
        $select->execute();
        while ($row = $select->fetch(PDO::FETCH_OBJ)) {
            $update_stock = $pdo->prepare("UPDATE `tbl_product` SET `stock` = `stock`-1 WHERE `tbl_product`.`product_id` = {$row->produk};");
            $update_stock->execute();
        }

        if ($acc->execute()) {
            echo '<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "Transaksi Berhasil Diterima", "info", {
            button: "Continue",
                });
            });
            </script>';
        } else {
            print_r($delete->errorInfo());
        }
    } else if ($action == "tolak") {
        $acc = $pdo->prepare("UPDATE `tbl_invoice` SET `status` = '2' WHERE inv_id = $id;");
        if ($acc->execute()) {
            echo '<script type="text/javascript">
            jQuery(function validation(){
            swal("Info", "Transaksi Berhasil Ditolak", "info", {
            button: "Continue",
                });
            });
            </script>';
        } else {
            print_r($delete->errorInfo());
        }
    }
}
?>

<html>

<head>
    <meta http-equiv="refresh" content="60">
</head>

</html>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">


    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Daftar Transaksi</h3>
                <a href="new_order.php" class="btn btn-success btn-sm pull-right">Tambah Transaksi</a>
            </div>
            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-striped" id="myOrder">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Reseller</th>
                                <th>Nama</th>
                                <th>Produk</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $user_id = $_SESSION['user_id'];
                            $no = 1;
                            if ($_SESSION['role'] == "Reseller") {
                                $select = $pdo->prepare("SELECT * FROM tbl_invoice i JOIN tbl_product p ON p.product_id = i.produk JOIN tbl_status s ON s.status_id = i.status JOIN tbl_user u ON u.user_id = i.reseller_id WHERE i.reseller_id = $user_id ORDER BY i.inv_id DESC");
                            } else {
                                $select = $pdo->prepare("SELECT * FROM tbl_invoice i JOIN tbl_product p ON p.product_id = i.produk JOIN tbl_status s ON s.status_id = i.status JOIN tbl_user u ON u.user_id = i.reseller_id ORDER BY i.inv_id DESC");
                            }
                            $select->execute();
                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row->order_date; ?></td>
                                <td><?php echo $row->fullname; ?></td>
                                <td class="text-uppercase"><?php echo $row->nama; ?></td>
                                <td><?php echo $row->product_name; ?> (<?php echo $row->product_berat; ?>g)</td>
                                <td>Rp. <?php echo number_format($row->total); ?></td>
                                <td><?php echo $row->nama_status; ?></td>
                                <td>
                                    <?php if ($row->status_id == 0) { ?>
                                    <?php if ($_SESSION['role'] != "Reseller") { ?>
                                    <a href="order.php?action=terima&id=<?php echo $row->inv_id; ?>"
                                        onclick="return confirm('Terima Transaksi?')" class="btn btn-success btn-xs"><i
                                            class="fa fa-check"></i> TERIMA</a>
                                    <a href="order.php?action=tolak&id=<?php echo $row->inv_id; ?>"
                                        onclick="return confirm('Tolak Transaksi?')" class="btn btn-warning btn-xs"><i
                                            class="fa fa-times"></i> TOLAK</a>
                                    <?php } ?>
                                    <?php } else if ($row->status_id == 1) { ?>
                                    <a href="#" class="btn btn-info btn-xs" id="update_resi"
                                        data-id="<?php echo $row->inv_id; ?>" data-resi="<?php echo $row->no_resi; ?>"
                                        data-tanggal="<?php echo $row->order_date; ?>"
                                        data-alamat="<?php echo $row->alamat; ?>" data-nama="<?php echo $row->nama; ?>"
                                        data-produk="<?php echo $row->product_name; ?> (<?php echo $row->product_berat; ?>g)"><i
                                            class="fa fa-pencil"></i> UPDATE RESI</a>
                                    <?php }?>
                                    <?php if ($_SESSION['role'] == "Admin") { ?>
                                    <a href="order.php?action=delete&id=<?php echo $row->inv_id; ?>"
                                        onclick="return confirm('Hapus Transaksi?')" class="btn btn-danger btn-xs"><i
                                            class="fa fa-trash"></i> HAPUS</a>
                                    <?php } ?>
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


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>

<script type="module">
$(document).ready(function() {
    $('#myOrder').DataTable();
    $("#update_resi").on("click", function(e) {
        var id_inv = $(this).data("id");
        var alamat = $(this).data("alamat");
        var tanggal = $(this).data("tanggal");
        var nama = $(this).data("nama");
        var produk = $(this).data("produk");
        var no_resi = $(this).data("resi");
        var dialog = bootbox.dialog({
            title: 'Custom Dialog Example',
            message: `
            <form id="update_resi">
            <div class="form-group">
            <label for="">Tanggal</label>
            <input type="text" class="form-control" value="${tanggal}" readonly>
            </div>
            <div class="form-group">
            <label for="">Nama</label>
            <input type="text" class="form-control" value="${nama}" readonly>
            </div>
            <div class="form-group">
            <label for="">Produk</label>
            <input type="text" class="form-control" value="${produk}" readonly>
            </div>
            <div class="form-group">
            <label for="">Alamat Lengkap</label>
            <input type="text" class="form-control" value="${alamat}" readonly>
            </div>
            <div class="form-group">
            <label for="">No Resi</label>
            <input type="text" class="form-control no_resi" value="${no_resi}">
            </div>
            </form>`,
            closeButton: false,
            buttons: {
                close: {
                    label: 'Close',
                    className: 'btn-danger',
                    callback: function() {
                        dialog.modal('hide')
                    }
                },
                submit: {
                    label: 'Submit',
                    className: 'btn-primary',
                    callback: function() {
                        $.ajax({
                            url: "update_resi.php",
                            method: "post",
                            dataType: "json",
                            data: {
                                id: id_inv,
                                resi: $(".no_resi").val()
                            },
                            success: function(data) {
                                if (data.status == 1) {
                                    swal("Sukses", "Berhasil Update Resi", "success");
                                } else {
                                    swal("Gagal", "Gagal Update Resi", "error");
                                }
                                location.reload();
                            }
                        })
                    }
                },
            }
        })
    })
});
</script>

<?php
include_once 'inc/footer_all.php';
?>