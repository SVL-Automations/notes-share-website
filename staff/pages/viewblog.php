<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];

if (!isset($_GET['nid'])) {
    header("location:index.php");
}

$postid = intval($_GET['nid']);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blog portal</title>

    <!-- Bootstrap core CSS -->
    <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../dist/css/modern-business.css" rel="stylesheet">

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="../../dist/img/small.png" height="50"></a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">



        <div class="row" style="margin-top: 4%">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <!-- Blog Post -->
                <?php
                $pid = intval($_GET['nid']);
                // $currenturl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];;
                $query = mysqli_query($connection, "SELECT b.*, s.name as staffname from blogs as b inner join staff as s on b.staffid=s.id where b.id='$pid'");
                while ($row = mysqli_fetch_array($query)) {
                ?>

                    <div class="card mb-4">

                        <div class="card-body">
                            <h2 class="card-title"><?php echo htmlentities($row['title']); ?></h2>
                            <p>

                                <b>Posted by </b> <?php echo htmlentities($row['staffname']); ?>
                            </p>
                                <hr />

                            <p>

                                <b>Summary </b> <?php echo htmlentities($row['summary']); ?>
                            </p>
                                <hr />

                                

                            <p class="card-text"><?php
                                                    $pt = $row['details'];
                                                    echo (substr($pt, 0)); ?></p>

                        </div>
                        <div class="card-footer text-muted">


                        </div>
                    </div>
                <?php } ?>

            </div>

            <!-- Sidebar Widgets Column -->
            <?php include('sidebar.php'); ?>
        </div>
        <!-- /.row -->
        <!---Comment Section --->

    </div>


   


    <!-- Bootstrap core JavaScript -->
    <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
    <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</body>

</html>