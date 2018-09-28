<?php
session_start();

// if (!isset($_POST["logged_in"])) {
//     header("Location: ../login.php");
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student View</title>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php
     include_once '../includes/header.php'; 
     ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 pt-5 text-center">
                <h1>ADMIN PAGE</h1>
                <a href="../logout.php"><button class="btn">LOGOUT</button></a>
            </div>
        </div>
    </div>
    <div class="container pt-5">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <form class="database-filler" action="../includes/FileParser.php" method="post" enctype="multipart/form-data">
                    <label for="file"><b>ROSTER</b><br>File should be in .csv format.</label>
                    <input type="file" name="roster" id="roster">
                    <button type="submit" name="submit" class="btn">Login</button>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
</body>
</html>