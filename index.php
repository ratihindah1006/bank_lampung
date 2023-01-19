<?php

$server = "localhost";
$username = "root";
$pass = "";
$db = "bank_lampung_mp";

$database = mysqli_connect($server, $username, $pass, $db);
if (!$database){
    die("connect failed" . mysqli_connect_error());
}

$table = "dummy";
$data = data($database, $table);
$data2 = data2($database, $table);

function data($database, $table){
    $query = "SELECT SUBSTRING(merchant_city,6) AS city, COUNT(CASE WHEN trx_status='SUCCESS' THEN 1 END) AS success FROM $table GROUP BY merchant_city HAVING COUNT(CASE WHEN trx_status='SUCCESS' THEN 1 END) > 10000";
    $res = $database->query($query);

    if($res==true){
        if($res->num_rows > 0){
            $row = mysqli_fetch_all($res, MYSQLI_ASSOC);
            $msg= $row;
        }
        else {
            $msg = "tidak ada data";
        }
      return $msg;
    }
}

function data2($database, $table){
    $query = "SELECT delivery_channel, COUNT(CASE WHEN trx_status='SUCCESS' THEN 1 END) AS success FROM $table GROUP BY delivery_channel";
    $res = $database->query($query);
    if($res==true){
        if($res->num_rows > 0){
            $row = mysqli_fetch_all($res, MYSQLI_ASSOC);
            $msg= $row;
        }
        else {
            $msg = "tidak ada data";
        }
        return $msg;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/css/bootstrap.min.css"
        integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <title>Index</title>
</head>

<body style="background-color:gainsboro;">


    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:black;">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" width="170">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link active" href="dashboard.php">Dashboard</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="row-md mt-4 mr-3">
                    <!-- /.card-header -->
                    <div class="card mb-3" style="width: 35rem;">
                        <div class="card-header bg-secondary">
                            <p class="card-title text-center text-light"><b>Tabel Frequensi Transaksi Sukses Berdasarkan
                                    Merchant City Lebih dari 10.000</b></p>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"> No</th>
                                        <th scope="col">Merchant City</th>
                                        <th scope="col">Frekuensi Trx Sukses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($data as $dt) : ?>
                                    <tr>
                                        <th scope="row"><?= $i++ ?></th>
                                        <td><?= $dt['city'] ?></td>
                                        <td><?= $dt['success'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row-md mt-4">
                    <div class="card mb-3" style="width: 35rem;">
                        <div class="card-body">
                            <div id="chart"></div>
                            <table class="table table-bordered">
                                <?php foreach ($data2 as $dt) : ?>
                                <tr>
                                    <td scope="row"><?= $dt['delivery_channel'] ?></td>
                                    <td scope="col"><?= $dt['success'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js"
        integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous">
    </script>
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script>
    // Data retrieved from https://netmarketshare.com
    Highcharts.chart('chart', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Chart Frekuensi Transaksi Sukses Berdasarkan Delivery Channel'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            name: 'Amount',
            colorByPoint: true,
            data: [
                <?php foreach ($data2 as $dt) {
          ?>[
                    '<?php echo $dt['delivery_channel'] ?>', <?php echo $dt['success']; ?>
                ],
                <?php
          }
          ?>
            ],
        }]
    });
    </script>
</body>

</html>