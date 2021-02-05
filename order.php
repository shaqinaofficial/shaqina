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
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Transaksi
        </h1>
        <hr>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Daftar Transaksi</h3>
            </div>
            <div class="box-body">
                <div style="overflow-x:auto;">
                    <table class="table table-striped" id="myOrder">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Produk</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $select = $pdo->prepare("SELECT * FROM tbl_invoice i JOIN tbl_product p ON p.product_id = i.produk JOIN tbl_status s ON s.status_id = i.status ORDER BY i.inv_id DESC");
                            $select->execute();
                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $row->order_date; ?></td>
                                    <td class="text-uppercase"><?php echo $row->nama; ?></td>
                                    <td><?php echo $row->product_name; ?> (<?php echo $row->product_berat; ?>g)</td>
                                    <td>Rp. <?php echo number_format($row->total); ?></td>
                                    <td><?php echo $row->nama_status; ?></td>
                                    <td>
                                        <?php if ($row->status_id == 0) { ?>
                                            <a href="order.php?action=terima&id=<?php echo $row->inv_id; ?>" onclick="return confirm('Terima Transaksi?')" class="btn btn-success btn-xs"><i class="fa fa-check"></i> TERIMA</a>
                                            <a href="order.php?action=tolak&id=<?php echo $row->inv_id; ?>" onclick="return confirm('Tolak Transaksi?')" class="btn btn-warning btn-xs"><i class="fa fa-times"></i> TOLAK</a>
                                        <?php } ?>
                                        <?php if ($_SESSION['role'] == "Admin") { ?>
                                            <a href="order.php?action=delete&id=<?php echo $row->inv_id; ?>" onclick="return confirm('Hapus Transaksi?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> HAPUS</a>
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

<script>
    $(document).ready(function() {
        $('#myOrder').DataTable();
    });
</script>

<?php
include_once 'inc/footer_all.php';
?>