<?php
session_start();
//error_reporting(0);
include('../includes/connect.php');
include('../functions/functions.php');
if (!isset($_SESSION['User'])) {
    header('location:../login.php');
} else {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <?php include('../includes/headsettings.php'); ?>
        <!-- Jquery -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js" integrity="sha512-42PE0rd+wZ2hNXftlM78BSehIGzezNeQuzihiBCvUEB3CVxHvsShF86wBWwQORNxNINlBPuq7rG4WWhNiTVHFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    </head>

    <body>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <?php include('../includes/sidebar.php'); ?>
                <div class="layout-page">
                    <?php include('../includes/header.php'); ?>
                    <div class="content-wrapper">
                        <!-- Content -->

                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="row">
                                <div class="col-lg-12 col-md-4 order-1">
                                    <div class="row">
                                        <div class="col-lg-12 mb-4 order-0">
                                            <div class="card">
                                                <div class="d-flex align-items-end row">
                                                    <div class="col-sm-7">
                                                        <div class="card-body">
                                                            <h5 class="card-title text-primary">Stay Motivated
                                                                <?php
                                                                $userID = $_SESSION['User'];
                                                                $username = getUsername($userID);

                                                                echo "$username";

                                                                ?>
                                                                🎉</h5>
                                                            <?php
                                                            echo '<input id="kioskID" value="' . $userID . '" hidden>';
                                                            ?>

                                                            <p class="mb-4">
                                                                Rezeki itu hadir kepada mereka yang <b>berusaha</b>, bukan untuk mereka yang <b>berputus asa</b>.
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5 text-center text-sm-left">
                                                        <div class="card-body pb-0 px-0 px-md-4">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 mb-1">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                                        <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                                            <div class="card-title">
                                                                <h5 class="text-nowrap mb-2">Online Sales Report</h5>
                                                                <span class="badge bg-label-warning rounded-pill">Year 2024</span>
                                                            </div>
                                                            <div id="totalSalesGraph">Loading Data...</div>
                                                            <div class="mt-sm-auto">
                                                                <!-- <small class="text-success text-nowrap fw-semibold"><i class="bx bx-chevron-up"></i> 68.2%</small> -->

                                                            </div>
                                                        </div>
                                                        <!-- <div id="profileReportChart"></div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-6 mb-4">
                                            <div class="row mb-3">
                                                <div class="card col">
                                                    <div class="card-body">
                                                        <!-- <div class="card-title d-flex align-items-start justify-content-between">
                                                            <div class="avatar flex-shrink-0">
                                                            </div>
                                                        </div> -->
                                                        <span>Overall Total Sales This Month</span>
                                                        <h3 id="totalCombineSalespan" class="card-title text-nowrap mb-1">Loading Data...</h3>
                                                        <div id="chart"></div>
                                                        <!-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.42%</small> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="card col">
                                                    <div class="card-body">
                                                        <span>Overall Total Online Sales</span>
                                                        <h3 id="totalSalespan" class="card-title text-nowrap mb-1">Loading Data...</h3>
                                                        <!-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.42%</small> -->
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12 col-6 mb-0">
                                            <div class="card col">
                                                <div class="card-body">
                                                    <h5 class="text-nowrap mb-2">In-Store Purchase Sales Report</h5>
                                                    <div id="totalInpurchaseSalesGraph">Loading Data...</div>
                                                    <!-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.42%</small> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-6 mb-4">
                                            <div class="row mb-4">
                                                <div class="card col">
                                                    <div class="card-body">
                                                        <span>Overall In-Store Purchase Sales</span>
                                                        <h3 id="totalIPSalespan" class="card-title text-nowrap mb-1">Loading Data...</h3>
                                                        <!-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.42%</small> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="card col">
                                                    <div class="card-body">
                                                        <span>Overall Total Sales</span>
                                                        <h3 id="totalALLSalespan" class="card-title text-nowrap mb-1">Loading Data...</h3>
                                                        <!-- <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.42%</small> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="card col">
                                                    <div class="card-body">
                                                        <h4 id="totalVendor">Total Approved Vendor : Loading Data...</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / Content -->
                    </div>
                </div>
            </div>
        </div>
        <!-- <script src="../assets/js/dashboards-analytics.js"></script> -->
    </body>

    <script>
        $(document).ready(function() {

            var totalAllSales = 0;
            var totalSales = 0;

            $.post('../apiAdmin.php?getCombinedSales=1', function(res) {
                console.log(res);

                if (res.length > 0) {
                    

                    // Iterate through the response data to get the total
                    res.forEach(function(item) {
                        totalSales += item.value;
                    });

                    // Display the total in the specified element
                    $('#totalCombineSalespan').html("RM " + totalSales.toFixed(2));

                    var options = {
                        dataLabels: {
                            enabled: false,
                            formatter: function(val) {
                                return val + "%"
                            }
                        },
                        series: res.map(item => item.value),
                        chart: {
                            type: 'donut',
                            width: 390,
                        },
                        labels: res.map(item => item.label),
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 200,
                                },
                                legend: {
                                    position: 'bottom',
                                },
                            },
                        }],
                        tooltip: {
                            y: {
                                formatter: function(value) {
                                    return "RM " + value;
                                },
                            },
                        },
                    };

                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                }
            }, 'json');

            // Get Total Sales
            $.post('../apiAdmin.php?getSales=1', function(res) {
                console.log(res)

                if (res.totalSales != null) {
                    $('#totalSalespan').html(res.totalSales)
                    $('#totalSalesGraph').html("")
                    var options = {
                        series: [{
                            name: "Total Sales",
                            data: res.OrderTotal
                        }],
                        chart: {
                            height: 300,
                            type: 'line',
                            zoom: {
                                enabled: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'straight'
                        },
                        title: {
                            text: 'Total Sales by Month',
                            align: 'left'
                        },
                        grid: {
                            row: {
                                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                opacity: 0.5
                            },
                        },
                        xaxis: {
                            width: 100,
                            categories: res.OrderDate,
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#totalSalesGraph"), options);
                    chart.render();
                }
            }, 'json')

            // Get Inpurchase Sales Report
            $.post('../apiAdmin.php?getIPSales=1', function(res) {
                console.log(res)

                $('#totalInpurchaseSalesGraph').html("")
                if (res.totalSales != null) {

                    $('#totalIPSalespan').html(res.totalSales)
                    var inpurchaseOptions = {
                        series: [{
                            name: "Total Sales",
                            data: res.OrderTotal
                        }],
                        chart: {
                            height: 300,
                            type: 'line',
                            zoom: {
                                enabled: false
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: 'straight'
                        },
                        title: {
                            text: 'All Vendor Sales by Month',
                            align: 'left'
                        },
                        grid: {
                            row: {
                                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                                opacity: 0.5
                            },
                        },
                        xaxis: {
                            categories: res.OrderDate,
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#totalInpurchaseSalesGraph"), inpurchaseOptions);
                    chart.render();
                }
            }, 'json')

            $.post('../apiAdmin.php?getVendorCount=1', function(res) {
                console.log(res)

                if (res.vendorCount != null) {

                    $('#totalVendor').html("Total Approved Vendor : "+ res.vendorCount)
                    
                }
            }, 'json')

        })
    </script>

    <script>
        setTimeout(() => {
            var InpurchaseSales = document.getElementById('totalIPSalespan').innerHTML;
            var onlineSales = document.getElementById('totalSalespan').innerHTML;

            var retInpurchaseSales = parseFloat(InpurchaseSales.replace('RM ',''));
            var retonlineSales = parseFloat(onlineSales.replace('RM ','')); 

            
            var totalSales
            var TotalAllSales = retInpurchaseSales + retonlineSales;
            $('#totalALLSalespan').html("RM " + TotalAllSales.toFixed(2))
        }, 5000);
    </script>
    </html>
<?php } ?>