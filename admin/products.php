<?php
require_once ("navbar.php");
require_once("controlller.php");
$db_handle = new DBController();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cvdb";
$datatable = "products"; // MySQL table name
$results_per_page = 10; // number of results per page

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Admin</p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="active treeview">
                <a href="home.php">
                    <i class="fa fa-home"></i> <span>Home</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="home.php"><i class="fa fa-circle-o"></i> Dashboard</a></li>
                    <li class="active"><a href="#"><i class="fa fa-circle-o"></i> Products</a></li>
                    <li><a href="orders.php"><i class="fa fa-circle-o"></i> Orders</a></li>
                    <li><a href="user_reg.php"><i class="fa fa-circle-o"></i> User Registrations</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-cog"></i>
                <span>Config</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="add.php"><i class="fa fa-circle-o"></i> Add new products</a></li>
                <li><a href="update.php"><i class="fa fa-circle-o"></i> Update stocks</a></li>
                <li><a href="outOfStock.php"><i class="fa fa-circle-o"></i> Out of stock</a></li>
            </ul>
        </li>
        <li><a><i class="fa fa-dashboard"></i>
            <span>Status</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="pending.php"><i class="fa fa-circle-o"></i> Pending</a></li>
            <li><a href="approved.php"><i class="fa fa-circle-o"></i> Approved</a></li>
            <li><a href="shipped.php"><i class="fa fa-circle-o"></i> Shipped</a></li>
            <li><a href="delivered.php"><i class="fa fa-circle-o"></i> Delivered</a></li>
        </ul>
    </li>
    <li><a href="settings.php"><i class="fa fa-book"></i> <span>Settings</span></a></li>
</ul>
</section>
<!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Products </h1>
        <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="home.php">Dashboard</a></li>
            <li class="active">Products</li>
        </ol>
    </section>
    
    <!-- Main content -->
    <section class="content">

      <!-- Custom Tabs -->
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_6" data-toggle="tab">ALL</a></li>
          <li class="pull-right">
          </li>
      </ul>
      <div class="tab-content">
          <div class="tab-pane active" id="tab_6">
            <div class="row">
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <input type="text" id="search" placeholder="Search">
                </div>
            </div>
            <br>
            <div id="display">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><center>Name</center></th>
                            <th><center>Description</center></th>
                            <th><center>Supplier</center></th>
                            <th><center>Price</center></th>
                            <th><center>Stocks</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
                            
                            $start_from = ($page-1) * $results_per_page;
                            $sql = "SELECT * FROM ".$datatable." ORDER BY productID ASC LIMIT $start_from, ".$results_per_page;
                            $rs_result = $conn->query($sql);
                            while($row = $rs_result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><center><?php echo $row["name"]; ?></center></td>
                                    <td><center><?php echo $row["description"]; ?></center></td>
                                    <td><center><?php echo $row["supplier"]; ?></center></td>
                                    <td><center>&#8369;<?php echo $row["price"]; ?></center></td>
                                    <td><center><?php echo $row["stock"]; ?></center></td>
                                </tr>
                                <?php 
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php 
            $sql = "SELECT COUNT(productID) AS total FROM ".$datatable;
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
                    $total_pages = ceil($row["total"] / $results_per_page); // calculate total pages with results
                    ?>
                    <br>
                    <div class="row">
                        <center>
                            <?php
                        for ($i=1; $i<=$total_pages; $i++) {  // print links for all pages
                            echo "<button class='btn'><a href='products.php?page=".$i."'";
                            if ($i==$page)  echo " class='curPage'";
                            echo ">".$i."</a></button> "; 
                        }
                        ?>
                    </center>
                </div>
            </div>
            <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->
    <!-- /.box -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; 2017 <a href="localhost/cv/user">CV Online Shopping</a>.</strong> All rights reserved.
</footer>
</div>
<!-- ./wrapper -->
</body>
</html>