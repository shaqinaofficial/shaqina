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

if (isset($_POST['order_product'])) {
    $reseller_id = $_SESSION['user_id'];
    $nama = $_POST['nama'];
    $produk = $_POST['produk'];
    $harga = preg_replace('/[^0-9]/', '', $_POST['harga']);
    $provinsi = $_POST['provinsi'];
    $kota = $_POST['kota'];
    $kecamatan = $_POST['kecamatan'];
    $alamat = $_POST['alamat'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $ongkir = preg_replace('/[^0-9]/', '', $_POST['ongkir']);
    $fee = preg_replace('/[^0-9]/', '', $_POST['fee']);
    $total = preg_replace('/[^0-9]/', '', $_POST['total']);
    $insert = $pdo->prepare("INSERT INTO `tbl_invoice` (`reseller_id`, `nama`, `produk`, `harga`, `provinsi`, `kota`, `kecamatan`, `alamat`, `metode_pembayaran`, `ongkir`, `fee`, `total`)
    VALUES ('$reseller_id', '$nama', '$produk', '$harga', '$provinsi', '$kota', '$kecamatan', '$alamat', '$metode_pembayaran', '$ongkir', '$fee', '$total');");

    if ($insert->execute()) {
        echo '<script type="text/javascript">
                                        jQuery(function validation(){
                                        swal("Success", "Pesanan Berhasil Dibuat", "success", {
                                        button: "Continue",
                                            });
                                        });
                                        </script>';
    } else {
        print_r($insert->errorInfo());
        die();
        echo '<script type="text/javascript">
                                        jQuery(function validation(){
                                        swal("Error", "Terjadi Kesalahan", "error", {
                                        button: "Continue",
                                            });
                                        });
                                        </script>';;
    }
}

function fill_product($pdo)
{
    $output = '';

    $select = $pdo->prepare("SELECT * FROM tbl_product");
    $select->execute();
    $result = $select->fetchAll();

    foreach ($result as $row) {
        $output .= '<option value="' . $row['product_id'] . '">' . $row["product_name"] . ' (' . $row['product_berat'] . 'g)</option>';
    }
    return $output;
}

?>
<html>
<head>
<meta http-equiv="refresh" content="60">
</head>
</html>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-selection__rendered {
        line-height: 31px !important;
    }

    .select2-container .select2-selection--single {
        height: 35px !important;
    }

    .select2-selection__arrow {
        height: 34px !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 0 !important;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Masukkan Data Pesanan</h3>
            </div>
            <form action="" method="POST" name="form_product" autocomplete="off">
                <div class="box-body">
                    <input type="hidden" class="form-control pull-right" name="orderdate" value="<?php echo date("d-m-Y"); ?>" readonly data-date-format="yyyy-mm-dd" required>

                    <div class="col-md-12">

                        <div class="form-group">
                            <label for="reseller">Reseller</label><br>
                            <input type="text" id="reseller" class="form-control reseller" value="<?=$_SESSION['fullname']?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Pembeli</label><br>
                            <input type="text" id="nama" class="form-control nama" required>
                        </div>
                        <div class="form-group">
                            <label for="produk">Produk</label>
                            <select id="produk" class="form-control produk" name="produk" required>
                                <option></option>
                                <?php echo fill_product($pdo) ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="text" class="form-control harga" name="harga" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="provinsi">Provinsi</label><br>
                            <select id="provinsi" class="form-control provinsi" name="provinsi" disabled required>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kota">Kota</label><br>
                            <select id="kota" class="form-control kota" name="kota" disabled required>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan</label><br>
                            <select id="kecamatan" class="form-control kecamatan" name="kecamatan" disabled required>
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat Lengkap</label>
                            <input type="text" id="alamat" class="form-control alamat" name="alamat" disabled required>
                        </div>
                        <div class="form-group">
                            <label for="metode_pembayaran">Metode Pembayaran</label><br>
                            <select id="metode_pembayaran" class="form-control metode_pembayaran" name="metode_pembayaran" required>
                                <option value=""></option>
                                <option value="COD">COD</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ongkir">Ongkos Kirim</label>
                            <input id="ongkir" class="form-control ongkir" name="ongkir" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="fee">Fee</label>
                            <input id="fee" class="form-control fee" name="fee" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="total">Total</label>
                            <input id="total" class="form-control total" name="total" readonly required>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" name="order_product">Buat
                        Pesanan</button>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="asset/js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript">
    var harga = {
        produk: 0,
        fee: 0,
        ongkir: 0
    }

    var berat = 0;

    $(".harga, .ongkir, .fee, .total").val(rupiah(0));

    var list_provinsi = [{
            "id": 1,
            "text": "Bali"
        },
        {
            "id": 2,
            "text": "Bangka Belitung"
        },
        {
            "id": 3,
            "text": "Banten"
        },
        {
            "id": 4,
            "text": "Bengkulu"
        },
        {
            "id": 5,
            "text": "DI Yogyakarta"
        },
        {
            "id": 6,
            "text": "DKI Jakarta"
        },
        {
            "id": 7,
            "text": "Gorontalo"
        },
        {
            "id": 8,
            "text": "Jambi"
        },
        {
            "id": 9,
            "text": "Jawa Barat"
        },
        {
            "id": 10,
            "text": "Jawa Tengah"
        },
        {
            "id": 11,
            "text": "Jawa Timur"
        },
        {
            "id": 12,
            "text": "Kalimantan Barat"
        },
        {
            "id": 13,
            "text": "Kalimantan Selatan"
        },
        {
            "id": 14,
            "text": "Kalimantan Tengah"
        },
        {
            "id": 15,
            "text": "Kalimantan Timur"
        },
        {
            "id": 16,
            "text": "Kalimantan Utara"
        },
        {
            "id": 17,
            "text": "Kepulauan Riau"
        },
        {
            "id": 18,
            "text": "Lampung"
        },
        {
            "id": 19,
            "text": "Maluku"
        },
        {
            "id": 20,
            "text": "Maluku Utara"
        },
        {
            "id": 21,
            "text": "Nanggroe Aceh Darussalam (NAD)"
        },
        {
            "id": 22,
            "text": "Nusa Tenggara Barat (NTB)"
        },
        {
            "id": 23,
            "text": "Nusa Tenggara Timur (NTT)"
        },
        {
            "id": 24,
            "text": "Papua"
        },
        {
            "id": 25,
            "text": "Papua Barat"
        },
        {
            "id": 26,
            "text": "Riau"
        },
        {
            "id": 27,
            "text": "Sulawesi Barat"
        },
        {
            "id": 28,
            "text": "Sulawesi Selatan"
        },
        {
            "id": 29,
            "text": "Sulawesi Tengah"
        },
        {
            "id": 30,
            "text": "Sulawesi Tenggara"
        },
        {
            "id": 31,
            "text": "Sulawesi Utara"
        },
        {
            "id": 32,
            "text": "Sumatera Barat"
        },
        {
            "id": 33,
            "text": "Sumatera Selatan"
        },
        {
            "id": 34,
            "text": "Sumatera Utara"
        }
    ]

    list_provinsi.forEach((prov) => {
        $(".provinsi").append(`<option value="${prov.id}">${prov.text}</option>`);
    })

    $('.produk').on('change', function(e) {
        $(".provinsi").prop("disabled", false);
        var productid = this.value;
        $.ajax({
            url: "getproduct.php",
            method: "get",
            data: {
                id: productid
            },
            dataType: "json",
            success: (data) => {
                harga.produk = data["sell_price"];
                berat = data["product_berat"];
                update_total();
            }
        })
    })

    $(".provinsi").on("change", function(e) {
        let id = this.value;
        $.ajax({
            url: "https://api.orderonline.id/shipping/city",
            method: "get",
            data: {
                province_id: id
            },
            dataType: "json",
            success: (data) => {
                $(".kota, .kecamatan").html("<option><option>");
                $(".kota, .kecamatan, .alamat").prop("disabled", true);
                harga.ongkir = 0;
                data.data.forEach((data) => {
                    $(".kota").append(
                        `<option value="${data.city_id}">${data.city_name_with_type}</option>`
                    );
                });
                $(".kota").prop("disabled", false);
                update_total();
            }
        })
    })

    $(".kota").on("change", function(e) {
        let id = this.value;
        $.ajax({
            url: "https://api.orderonline.id/shipping/district",
            method: "get",
            data: {
                city_id: id
            },
            dataType: "json",
            success: (data) => {
                $(".kecamatan").html("<option><option>");
                $(".kecamatan, .alamat").prop("disabled", true);
                harga.ongkir = 0;
                data.data.forEach((data) => {
                    $(".kecamatan").append(
                        `<option value="${data.subdistrict_id}">${data.subdistrict_name}</option>`
                    );
                });
                $(".kecamatan").prop("disabled", false);
                update_total();
            }
        })
    })

    $(".kecamatan").on("change", function(e) {
        let id = this.value;
        let data = {
            origin: {
                id: 5936,
                type: "subdistrict",
                name: "Cisaat",
                province_id: 9,
                city_id: 430,
                city_name: "Kab. Sukabumi",
                subdistrict_id: 5936,
                subdistrict_name: "Cisaat"
            },
            destination: {
                id: id,
                type: "subdistrict"
            },
            couriers: ["jnt"],
            product: {
                weight: berat
            },
        };

        $.ajax({
            url: "https://api.orderonline.id/shipping/cost",
            method: "post",
            data: {
                origin: JSON.stringify(data.origin),
                destination: JSON.stringify(data.destination),
                couriers: JSON.stringify(data.couriers),
                product: JSON.stringify(data.product),
            },
            dataType: "json",
            success: (data) => {
                data.data.forEach((data) => {
                    harga.ongkir = Number(data.costs[0].cost[0].value);
                });
                update_total();

                $(".alamat").prop("disabled", false);
                update_total();
            }
        })
    })

    $(".metode_pembayaran").on("change", function(e) {
        if ($(this).val() == "COD") {
            let extra = (Number(harga.produk) + Number(harga.ongkir)) * 3 / 100;
            let round = Math.ceil(extra / 1000) * 1000;
            harga.fee = round;
            update_total();
        } else {
            harga.fee = 0;
            update_total(0);
        }
    })

    $("select").each(function(i, el) {
        $(this).select2({
            placeholder: `Pilih ${capitalize($(this).attr("name").replace("_", " "))}`
        });
    });

    function update_total() {
        let {
            produk,
            fee,
            ongkir
        } = harga;

        $(".harga").val(rupiah(produk));
        $(".ongkir").val(rupiah(ongkir));
        $(".fee").val(rupiah(fee));

        let kalkulasi = Number(produk) + Number(fee) + Number(ongkir);
        $(".total").val(rupiah(kalkulasi));
    }

    function capitalize(s) {
        if (typeof s !== 'string') return ''
        return s.charAt(0).toUpperCase() + s.slice(1)
    }

    function rupiah(angka) {
        var rupiah = "";
        var angkarev = angka.toString().split("").reverse().join("");
        for (var i = 0; i < angkarev.length; i++)
            if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + ".";
        return (
            "Rp. " +
            rupiah
            .split("", rupiah.length - 1)
            .reverse()
            .join("")
        );
    }
</script>

<?php
include_once 'inc/footer_all.php';
?>