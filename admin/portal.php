<?php require_once('../includes/functions.php');
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
    <title>Admin portal</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        let email = "<?php echo $_POST['selection'] ?>";
        $(document).ready(function () {
            $("#searchBox").blur(function () {
                let value = $(this).val().trim();
                $.ajax({
                    url: 'search.php',
                    data: {
                        q: value
                    },
                    success: function (returnData) {
                        //Blank the search_results div.
                        $('#showServicesIcons').html('');
                        //Parse the result that we got back from search.php
                        var results = JSON.parse(returnData);
                        //Loop through the results array and append it to
                        //the search_results div.
                        $.each(results, function (key, value) {
                            $('#showServicesIcons').append('<div class="card w-50">' +
                                '<img src="../' + value['image_path'] + '" class="card-img-top" alt="icon">' +
                                '<div class="card-body">' +
                                '<a class="btn btn-primary" href="detail.php?name=' + value['name'] + '&email=' + email + '">' + value['name'] + '</a>' +
                                '</div>' +
                                '</div>');
                        });

                        //If the results array is empty, display a
                        //message saying that no results were found.
                        if (results.length == 0) {
                            $('#showServicesIcons').html('No results found!');
                        }
                    }
                });
            });
        });
    </script>
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
        <?php if (!isset($_POST['selection'])) { ?>
            <form method="post">
                <table class="table">
                    <thead>
                    <tr class="table-light">
                        <th scope="col" class="text-nowrap">#</th>
                        <th scope="col" class="text-nowrap">Email</th>
                        <th scope="col" class="text-nowrap">
                            <div class="d-flex">First name</div>
                        </th>
                        <th scope="col" class="text-nowrap">
                            <div class="d-flex">Last name</div>
                        </th>
                        <th scope="col" class="text-nowrap">Phone</th>
                        <th scope="col" class="text-nowrap">Age</th>
                        <th scope="col" class="text-nowrap">Is student</th>
                        <th scope="col" class="text-nowrap">Is employed</th>
                        <th scope="col" class="text-nowrap">Select</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php echo showUserRows() ?>
                    </tbody>
                </table>
                <?php if (isset($_POST['submit'])) { ?>
                    <div class='text-danger'>You must select a user.</div>
                <?php } ?>
                <button type="submit" name="submit" class="btn btn-light"> submit
                </button>
            </form>
        <?php } else { ?>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Search</span>
                <input type="text" id="searchBox" class="form-control" aria-label="Username"
                       aria-describedby="basic-addon1">
            </div>
            <div class="d-flex flex-wrap" id="showServicesIcons">
                <p>Try to have a search</p>
            </div>
            <form method="post">
                <button type="submit" name="back" class="btn btn-danger m-3">Back</button>
                <?php if (isset($_POST['back'])) {
                    redirect('portal.php');
                } ?>
            </form>

        <?php } ?>
    </div>
</div>
</body>

</html>