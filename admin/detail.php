<?php
require_once('../includes/functions.php');
if (!isset($_GET['name'])||!isset($_GET['email'])){
    redirect('portal.php');
}
$name = (string)$_GET['name'];
$email = (string)$_GET['email'];
$service_id=getServiceByName($name)['service_id'];
require_once('../includes/functions.php');
$url = "https://reg.bom.gov.au/fwo/IDV60901/IDV60901.95936.json";
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);

//default format in which API returns result is JSON
$response = curl_exec($ch);
//convert it into an array
$data = json_decode($response, true);
?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>detail</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="h-100 text-center text-white bg-primary">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand">LIFE</a>
        <a class="navbar-brand"><?php
            $temperature = $data["observations"]["data"][0]['air_temp'];
            if ($temperature <= 18) {
                echo $temperature .= " Celsius, Please wear more clothes when you go out";
            } elseif ($temperature >= 19 && $temperature <= 28) {
                echo $temperature .= " Celsius, Moderate temperature";
            } elseif ($temperature <= 29) {
                echo $temperature .= " Celsius, Be careful when going out for heatstroke";
            }
            ?></a>
    </div>
</nav>
<div class="cover-container d-flex p-3 mx-auto flex-column w-100 h-100 m-md-2">
    <div class="position-absolute top-10 start-50 translate-middle-x" id="ShowInformation">
        <?php if ($name!='Healthy habits') { ?>
        <table class="table">
            <thead>
            <tr class="table-light">
                <th scope="col" class="text-nowrap">#</th>
                <th scope="col" class="text-nowrap">Service type</th>
                <th scope="col" class="text-nowrap">date performed</th>
                <th scope="col" class="text-nowrap">duration minutes</th>
            </tr>
            </thead>
            <tbody>
            <?php
            echo showServiceRows($email, $service_id) ?>
            </tbody>
        </table>
            <div class="bg-white text-dark">
                <canvas id="myChart"></canvas>
            </div>
        <?php } else { ?>
                <div class="d-flex flex-wrap">
                    <?php
                    echo showMealRows($email)?>
                </div>
        <?php } ?>
    </div>
</div>
<script>
    const labels = <?php echo showLabel($email, $service_id)?>;
    const data = {
        labels: labels,
        datasets: [{
            label: 'Service',
            backgroundColor: 'rgb(1,1,1)',
            borderColor: 'rgb(1,1,1)',
            color: 'rgb(1,1,1)',
            data: <?php echo showData($email, $service_id);?>,
        }]
    };
    const config = {
        type: 'line',
        data: data,
        options: {}
    };
    var myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
</script>
</body>

</html>