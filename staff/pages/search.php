<?php
include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Blog | Search Page</title>

  <!-- Bootstrap core CSS -->
  <link href="../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../../dist/css/modern-business.css" rel="stylesheet">
</head>

<body>
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
        if ($_POST['searchtitle'] != '') {
          $st = $_SESSION['searchtitle'] = $_POST['searchtitle'];
        }
        $st;

        if (isset($_GET['pageno'])) {
          $pageno = $_GET['pageno'];
        } else {
          $pageno = 1;
        }
        $no_of_records_per_page = 8;
        $offset = ($pageno - 1) * $no_of_records_per_page;

        $total_pages_sql = "SELECT COUNT(*) FROM blogs";
        $result = mysqli_query($connection, $total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);


        $query = mysqli_query($connection, "SELECT b.*, s.name as staffname from blogs as b inner join staff as s on b.staffid=s.id where b.title like '%$st%' and b.status=1 LIMIT $offset, $no_of_records_per_page");

        $rowcount = mysqli_num_rows($query);
        if ($rowcount == 0) {
          echo "No record found";
        } else {
          while ($row = mysqli_fetch_array($query)) {


        ?>

            <div class="card mb-4">

              <div class="card-body">
                <h2 class="card-title"><?php echo htmlentities($row['title']); ?></h2>
               
                <p><?php echo htmlentities($row['summary']); ?></p>
                <div class="card-footer text-muted">
                Posted by <?php echo htmlentities($row['staffname']); ?>

              </div><br>
                <a href="viewblog.php?nid=<?php echo htmlentities($row['id']) ?>" class="btn btn-primary">Read More &rarr;</a>
              </div>
              
            </div>
            <hr>
          <?php } ?>

          <ul class="pagination justify-content-center mb-4">
            <li class="page-item"><a href="?pageno=1" class="page-link">First</a></li>
            <li class="<?php if ($pageno <= 1) {
                          echo 'disabled';
                        } ?> page-item">
              <a href="<?php if ($pageno <= 1) {
                          echo '#';
                        } else {
                          echo "?pageno=" . ($pageno - 1);
                        } ?>" class="page-link">Prev</a>
            </li>
            <li class="<?php if ($pageno >= $total_pages) {
                          echo 'disabled';
                        } ?> page-item">
              <a href="<?php if ($pageno >= $total_pages) {
                          echo '#';
                        } else {
                          echo "?pageno=" . ($pageno + 1);
                        } ?> " class="page-link">Next</a>
            </li>
            <li class="page-item"><a href="?pageno=<?php echo $total_pages; ?>" class="page-link">Last</a></li>
          </ul>
        <?php } ?>




        <!-- Pagination -->




      </div>

      <!-- Sidebar Widgets Column -->
      <?php include('sidebar.php'); ?>
    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

  <!-- Bootstrap core JavaScript -->
  <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
  <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>


  </head>
</body>

</html>