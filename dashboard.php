<?php
    include_once'db/connect_db.php';
    session_start();
    if($_SESSION['role']=="Admin"){
      include_once'inc/header_all.php';
    }else{
        include_once'inc/header_all_operator.php';
    }
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
      <div class="row">
        <!-- get alert stock -->
        <?php
        $select = $pdo->prepare("SELECT count(product_code) as total FROM tbl_product WHERE stock <= min_stock");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);
        $total1 = $row->total;
        ?>
        <!-- get alert notification -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-archive"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Persediaan Menipis</span>
              <?php if($total1==true){ ?>
              <span class="info-box-number"><small><?php echo $row->total;?></small></span>
              <?php }else{?>
              <span class="info-box-text"><strong>Tidak Ada</strong></span>
              <?php }?>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>


        <!-- get total products-->
        <?php
        $select = $pdo->prepare("SELECT count(product_code) as t FROM tbl_product");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);
        $total = $row->t;
        ?>

        <!-- get total products notification -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-cubes"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Produk</span>
              <span class="info-box-number"><small><?php echo $row->t ?></small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <!-- get today transactions -->
        <?php
        $select = $pdo->prepare("SELECT count(inv_id) as i FROM tbl_invoice WHERE order_date = CURDATE()");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);
        $invoice = $row->i ;
        ?>
         <!-- get today transactions notification -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-cart"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Transaksi Hari Ini</span>
              <span class="info-box-number"><small><?php echo $row->i ?></small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>


        <!-- get today income -->
        <?php
        $select = $pdo->prepare("SELECT sum(harga) as total FROM tbl_invoice WHERE order_date = CURDATE()");
        $select->execute();
        $row=$select->fetch(PDO::FETCH_OBJ);
        $total = $row->total ;
        ?>
         <!-- get today income -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Pendapatan Hari Ini</span>
              <span class="info-box-number"><small>Rp. <?php echo number_format($total,0); ?></small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

      </div>

      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header with-border">
              <h3 class="box-title">Daftar Produk Terlaku</h3>
          </div>
          <div class="box-body">
            <div class="col-md-12">
              <div style="overflow-x:auto;">
                  <table class="table table-striped" id="myBestProduct">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Produk</th>
                              <th>Kode</th>
                              <th>Terjual</th>
                              <th>Harga</th>
                              <th>Pendapatan</th>
                          </tr>

                      </thead>
                      <tbody>
                          <?php
                          $no = 1;
                          $select = $pdo->prepare("SELECT count(i.produk) as terjual, i.produk, i.harga, sum(i.harga) as pendapatan, p.product_name as nama, p.product_code as kode FROM tbl_invoice i INNER JOIN tbl_product p ON p.product_id = i.produk GROUP BY i.produk ORDER BY count(i.produk) DESC LIMIT 30");
                          $select->execute();
                          while($row=$select->fetch(PDO::FETCH_OBJ)){
                          ?>
                              <tr>
                              <td><?php echo $no++ ;?></td>
                              <td><?php echo $row->nama; ?></td>
                              <td><?php echo $row->kode; ?></td>
                              <td><?php echo $row->terjual; ?></td>
                              <td>Rp <?php echo number_format($row->harga); ?></td>
                              <td>Rp <?php echo number_format($row->pendapatan);?></td>
                              </tr>

                        <?php
                          }
                        ?>
                      </tbody>
                  </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
  $(document).ready( function () {
      $('#myBestProduct').DataTable();
  } );
  </script>


 <?php
    include_once'inc/footer_all.php';
 ?>