  <div class="col-md-4">

    <!-- Search Widget -->
    <div class="card mb-4">
      <h5 class="card-header">Search</h5>
      <div class="card-body">
        <form name="search" action="search.php" method="post">
          <div class="input-group">

            <input type="text" name="searchtitle" class="form-control" placeholder="Search for..." required>
            <span class="input-group-btn">
              <button class="btn btn-secondary" type="submit">Go!</button>
            </span>
        </form>
      </div>
    </div>
  </div>

  <!-- Categories Widget --> 

  <!-- Side Widget -->
  <div class="card my-4">
    <h5 class="card-header">Recent Post by staff</h5>
    <div class="card-body">
      <ul class="mb-0">
        <?php
        $query = mysqli_query($connection, "SELECT b.*, s.name as staffname from blogs as b inner join staff as s on b.staffid=s.id where b.status='1' order by b.id desc limit 8");
        while ($row = mysqli_fetch_array($query)) {

        ?>

          <li>
            <a href="viewblog.php?nid=<?php echo htmlentities($row['id']) ?>"><?php echo htmlentities($row['title']); ?></a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
  
  </div>