<?php
require_once ("navbar.php");
require_once("controlller.php");
$db_handle = new DBController();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cvdb";

$datatable = "orders"; // MySQL table name
$results_per_page = 10; // number of results per page

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['submit'])) {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";

    $conn = mysql_connect($dbhost, $dbuser, $dbpass);

    if(! $conn ) {
       die('Could not connect: ' . mysql_error());
   }

   if(gettype($_POST['id'])=="array"){
    foreach($_POST['id'] as $val){
        $id_c=$val;
        $sql = "UPDATE orders SET status='delivered' WHERE id='".$id_c."'";
        mysql_select_db('cvdb');
        $retval = mysql_query( $sql, $conn );

        if( $retval ) {
            $message = "Order Delivered!!!";
            echo "<script type='text/javascript'>alert('$message');</script>";
            echo "<script>window.open('shipped.php','_self')</script>";
        }
        else{
            die('Could not update data: ' . mysql_error());
        }
    }
}
mysql_close($conn);
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
            <li>
                <a href="home.php">
                    <i class="fa fa-home"></i> <span>Home</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="home.php"><i class="fa fa-circle-o"></i> Dashboard</a></li>
                    <li><a href="products.php"><i class="fa fa-circle-o"></i> Products</a></li>
                    <li><a href="orders.php"><i class="fa fa-circle-o"></i> Orders</a></li>
                    <li><a href="user_reg.php"><i class="fa fa-circle-o"></i> Registered Users</a></li>
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
        <li class="active treeview"><a><i class="fa fa-dashboard"></i>
            <span>Status</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="pending.php"><i class="fa fa-circle-o"></i> Pending</a></li>
            <li><a href="approved.php"><i class="fa fa-circle-o"></i> Approved</a></li>
            <li class="active"><a href="#"><i class="fa fa-circle-o"></i> Shipped</a></li>
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
        <h1> Shipped Orders </h1>
        <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Status</li>
            <li class="active">Shipped</li>
        </ol>
    </section>
    <section class="content">
        <form method="post" action="<?php $_PHP_SELF ?>">
            <!-- TABLE: LATEST ORDERS -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-11"></div>
                        <div class="col-md-1">
                            <i class="fa fa-print"></i> Print
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><center>Customer ID</center></th>
                                    <th><center>Total Cost</center></th>
                                    <th><center>Date Ordered</center></th>
                                    <th><center>Status</center></th>
                                    <th><center>Delivered</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
                                    $start_from = ($page-1) * $results_per_page;
                                    $sql = "SELECT * FROM ".$datatable." WHERE status='shipped' ORDER BY created DESC LIMIT $start_from, ".$results_per_page;
                                    $rs_result = $conn->query($sql);
                                    if($row = $rs_result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td><center><?php echo $row["customer_id"]; ?></center></td>
                                            <td><center>&#8369;<?php echo $row["total_price"]; ?></center></td>
                                            <td><center><?php echo $row["created"]; ?></center></td>
                                            <td><center><?php if($row["status"] == 'shipped'){ echo " <span class='label label-success'>Shipped</span>";} ?></center></td>
                                            <td><center><input type="checkbox" name="id[]" value="<?php echo $row["id"] ?>"></center></td>
                                        </tr>
                                        <?php
                                    }
                                    else{
                                        echo '<tr><td><center>NO</center></td>';
                                        echo '<td><center>RE</center></td>';
                                        echo '<td><center>CO</center></td>';
                                        echo '<td><center>RD</center></td>';
                                        echo '<td><center>S!</center></td>';
                                    }
                                    ?>
                                </tr>
                            </tbody>
                        </table>
                        <?php 
                        $sql = "SELECT COUNT(id) AS total FROM ".$datatable." WHERE status='shipped' ORDER BY created DESC";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                    $total_pages = ceil($row["total"] / $results_per_page); // calculate total pages with results
                    ?>
                </div>
                <!-- /.table-responsive -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-block btn-success" name="submit">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <center>
                        <?php
                        for ($i=1; $i<=$total_pages; $i++) {  // print links for all pages
                            echo "<button class='btn'><a href='shipped.php?page=".$i."'";
                            if ($i==$page)  echo " class='curPage'";
                            echo ">".$i."</a></button> "; 
                        }
                        ?>
                    </center>
                </div>
            </div>
        </div>
        <!-- /.box -->
    </form>
</section>
</div>

<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; 2017 <a href="localhost/cv/user">CV Online Shopping</a>.</strong> All rights reserved.
</footer>
</div>
<!-- ./wrapper -->
</body>
</html>