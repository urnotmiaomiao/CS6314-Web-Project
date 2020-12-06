<?php

if (!isset($_SESSION)) {
    session_start();
}

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}
// if(!isset($_SESSION['user']))
// {
//     header("Location: index.php");
// }
// if(isset($_GET['logout']) == 'yes')
// {
//     session_destroy();
//     header("Location: index.php");
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Coffee & Tea Distributor">
    <meta name="keywords" content="Web Project, Coffee & Tea Distributor">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <link href="css/index.css" rel="stylesheet">
    <script src="js/index.js"></script>

    <title>Web Final Project</title>
    <style>
        body {
            padding-top: 3.5rem;
        }

        .item-img {
            float: left;
            width: 200px;
            padding-right: 1rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">Coffee & Tea</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#coffee-row">Coffee</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tea-row">Tea</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
            </ul>
            <p><?php echo "hello," . htmlspecialchars($_SESSION["username"]); ?></p>
            <!-- <?php echo "<a class='btn btn-outline-success my-2 my-sm-0' href='./cart.php?id=" . $_SESSION["id"] . "'>My cart</a>'>" ?> -->
            <a class="btn btn-outline-success my-2 my-sm-0" href="cart.php">My cart</a>
            <a class="btn btn-outline-success my-2 my-sm-0" href="logout.php">Logout</a>
        </div>
    </nav>

    <main role="main">
        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="jumbotron">
            <div class="container">
                <h1 class="display-3">Coffee & Tea Distributor</h1>
                <p>Coffee & Tea Distributor description. Coffee & Tea Distributor description. Coffee & Tea Distributor
                    description. Coffee & Tea Distributor description. Coffee & Tea Distributor description. Coffee &
                    Tea Distributor description. Coffee & Tea Distributor description. </p>
                <!-- <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p> -->
                <div class="container">
                    <!-- <form action="search.php" method="post" class="form-inline my-2 my-lg-0"> -->
                    <form action="" method="post" class="form-inline my-2 my-lg-0">
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="*">Any</option>
                            <option value="coffee">Coffee</option>
                            <option value="tea">Tea</option>
                        </select>
                        <br>
                        <input class="form-control mr-sm-2" type="text" name="search" placeholder="Search for a product..." aria-label="Search">
                        <!-- <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button> -->
                        <input type="submit" value="Search">
                    </form>

                </div>
            </div>
        </div>

        <div class="container">
            <?php
            session_start();
            // for paging
            if (isset($_GET['pageno'])) {
                $pageno = $_GET['pageno'];
            } else {
                $pageno = 1;
            }
            $no_of_records_per_page = 10;
            $offset = ($pageno - 1) * $no_of_records_per_page;

            $ini = parse_ini_file("info.ini");
            $conn = mysqli_connect($ini["servername"], $ini["username"], $ini["password"], $ini["dbname"], $ini["portid"]) or die("cannot connect to database");

            $output = "";
            $output = "<div class='row' id='products'>";
            $category = $_POST['category'];

            if (isset($_POST['search'])) {
                $total_pages_sql = "SELECT COUNT(*) FROM product WHERE ";
                $sql = "SELECT * FROM product WHERE ";
                $s = "";

                $search_query = $_POST['search'];
                if ($search_query != "") {
                    $search_query = preg_replace("#[^0-9a-z]#i", "", $search_query);
                    $s .= "name LIKE '%$search_query%' AND ";
                }

                if ($category != "*") {
                    $s .= "category='$category'";
                } else {
                    $s .= "1";
                }
                $total_pages_sql .= $s;
                $sql .= $s;

                // echo $sql;
                $result = mysqli_query($conn, $total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);

                $search_result = mysqli_query($conn, $sql) or die("cannot search");
                $r = mysqli_num_rows($search_result);
            } else {
                $total_pages_sql = "SELECT COUNT(*) FROM product";
                $sql = "SELECT * FROM product";

                $result = mysqli_query($conn, $total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);

                $search_result = mysqli_query($conn, $sql) or die("cannot search");
                $r = mysqli_num_rows($search_result);
            }
            if ($r == 0) {
                $output = "<p>There was no products</p>";
            } else {
                while ($row = mysqli_fetch_array($search_result)) {
                    $ProductID = $row['ProductID'];
                    $Name = $row['Name'];
                    $Price = $row['Price'];
                    $Description = $row['Description'];
                    $Image = $row['Image'];
                    $Num = $row['Num'];
                    $output .= "<div class='col-md-4'>";
                    $output .= "<h2>" . $Name . "</h2>";
                    $output .= "<img class='item-img' src='img/" . $Image . "' alt='" . $Name . "'>";
                    $output .= "<p>" . $Description . "</p>";
                    $output .= "<p><a class='btn btn-secondary' href='./detail.php?pid=" . $ProductID . "' role='button'>View details &raquo;</a></p>
                        </div>";
                }
            }

            $output .= "</div>";
            mysqli_close($conn);
            echo $output;


            ?>

            <hr>

        </div> <!-- /container -->

        <ul class="pagination">
            <li><a href="?pageno=1">First</a></li>
            <li class="<?php if ($pageno <= 1) {
                            echo 'disabled';
                        } ?>">
                <a href="<?php if ($pageno <= 1) {
                                echo '#';
                            } else {
                                echo "?pageno=" . ($pageno - 1);
                            } ?>">Prev</a>
            </li>
            <li class="<?php if ($pageno >= $total_pages) {
                            echo 'disabled';
                        } ?>">
                <a href="<?php if ($pageno >= $total_pages) {
                                echo '#';
                            } else {
                                echo "?pageno=" . ($pageno + 1);
                            } ?>">Next</a>
            </li>
            <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
        </ul>

    </main>

    <footer class="container">
        <p>Web Final Project - 2020 Fall - CS6314.002</p>
    </footer>
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>

</html>