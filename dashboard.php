<?php

$data = file_get_contents('data-dummy-trx-qris.json');
$qris = json_decode($data, true);
$response = [
  "DELIVERY_CHANNEL" => [
    "ATM" => [
      "SUCCESS" => 0,
      "FAILED" => 0,
      "AMOUNT" => 0,
      "AMOUNT2" => 0
    ],
    "MOBILE BANKING" => [
      "SUCCESS" => 0,
      "FAILED" => 0,
      "AMOUNT" => 0,
      "AMOUNT2" => 0
    ],
    "RITEL" => [
      "SUCCESS" => 0,
      "FAILED" => 0,
      "AMOUNT" => 0,
      "AMOUNT2" => 0
    ],
  ],
  "MERCHANT_CITY" => [],
];

foreach ($qris as $data) {
  $response['DELIVERY_CHANNEL'][$data['delivery_channel']][$data['trx_status']] += 1;
  if ($data['trx_status'] == "SUCCESS") {
    $response['DELIVERY_CHANNEL'][$data['delivery_channel']]['AMOUNT'] += $data['amount'];
    if (array_key_exists($data['merchant_city'], $response['MERCHANT_CITY'])) {
      $response['MERCHANT_CITY'][$data['merchant_city']] += 1;
    } else {
      $response['MERCHANT_CITY'][$data['merchant_city']] = 1;
    }
  }
  else {
    $response['DELIVERY_CHANNEL'][$data['delivery_channel']]['AMOUNT2'] += $data['amount'];
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

  <title>Dashboard</title>
</head>

<body style="background-color:gainsboro;">


  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:black;">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="logo.png" width="170">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
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
          <div class="card mb-3" style="width: 35rem;">
            <div class="card-header bg-secondary">
              <p class="card-title text-center text-light"><b>Tabel Frequensi Transaksi Gagal dan Sukses Berdasarkan Delivery Channel</b></p>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col"> No</th>
                    <th scope="col">Delivery Channel</th>
                    <th scope="col">Frekuensi Trx Sukses</th>
                    <th scope="col">Frekuensi Trx Gagal</th>
                    <th scope="col">Total Amount Sukses</th>
                    <th scope="col">Total Amount Gagal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; ?>
                  <?php foreach ($response['DELIVERY_CHANNEL'] as $type => $val) : ?>
                    <tr>
                      <th scope="row"><?= $i++ ?></th>
                      <td><?= $type ?></td>
                      <td><?= $val['SUCCESS'] ?></td>
                      <td><?= $val['FAILED'] ?></td>
                      <td><?= number_format($val['AMOUNT']) ?></td>
                      <td><?= number_format($val['AMOUNT2']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card mb-3" style="width: 35rem;">
            <div class="card-header bg-secondary">
              <p class="card-title text-center text-light"><b>Tabel Frequensi Transaksi Sukses Berdasarkan Merchant City</b></p>
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
                  <?php foreach ($response['MERCHANT_CITY'] as $city => $val) : ?>
                    <tr>
                      <th scope="row"><?= $i++ ?></th>
                      <td><?= explode("#", $city)[1] ?></td>
                      <td><?= $val ?></td>
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
                <thead>
                  <tr class="text-center">
                    <th scope="col"> ATM</th>
                    <th scope="col">MOBILE BANKING</th>
                    <th scope="col">RITEL</th>
                  </tr>
                </thead>
                <tr>
                  <td>Rp<?= number_format($response['DELIVERY_CHANNEL']["ATM"]["AMOUNT"], 2, ',', '.')  ?></td>
                  <td>Rp <?= number_format($response['DELIVERY_CHANNEL']["MOBILE BANKING"]["AMOUNT"], 2, ',', '.')  ?></td>
                  <td>Rp<?= number_format($response['DELIVERY_CHANNEL']["RITEL"]["AMOUNT"], 2, ',', '.') ?></td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card mb-3" style="width: 35rem;">
            <div class="card-body">
              <div id="grafik"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
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
        text: 'Chart Total Amount Transaksi Sukses Berdasarkan Delivery Channel'
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
          <?php foreach ($response['DELIVERY_CHANNEL'] as $type => $val) {
          ?>[
              '<?php echo $type ?>', <?php echo $val['AMOUNT']; ?>
            ],
          <?php
          }
          ?>
        ],
      }]
    });
  </script>
  <script>
    Highcharts.chart('grafik', {
      chart: {
        type: 'column'
      },
      title: {
        text: 'Grafik Batang Frequensi Transaksi Sukses Berdasarkan Merchant City'
      },
      xAxis: {
        categories: [
          <?php foreach ($response['MERCHANT_CITY'] as $city => $val) {
            $cityName = explode("#", $city)[1];
            echo "'$cityName',";
          } ?>
        ],
        crosshair: true
      },
      yAxis: {
        min: 0,
        title: {
          text: 'Frekuensi Transaksi Sukses'
        }
      },
      tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
          '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
      },
      plotOptions: {
        column: {
          pointPadding: 0.2,
          borderWidth: 0
        }
      },
      series: [{
        name: 'Frekuensi',
        data: [
          <?php foreach ($response['MERCHANT_CITY'] as $city => $val) {
            echo $val . ",";
          } ?>
        ]
      }]
    });
  </script>
</body>

</html>