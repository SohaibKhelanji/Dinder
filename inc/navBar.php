<header>
    <div class="header-area ">
        <div id="sticky-header" class="main-header-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-3 col-lg-3">
                        <div class="logo">
                            <a href="index.php">
                                <img src="img/logo.png" alt="Dinder logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9">
                        <div class="main-menu  d-none d-lg-block">
                            <nav>
                                <ul id="navigation">
                                    <?php
                                    if (!isset($_SESSION['userId'])) {
                                        echo "<li><a href=\"index.php\">Home</a></li>
                                        <li><a href=\"#\">The experience</a></li>
                                        <li><a href=\"login.php\">Login</a></li>
                                        ";
                                    }
                                    else {
                                        include_once 'class/dbh.php';
                                        $database = new dbh();
                                        $sql = "SELECT * FROM requests WHERE receiver = ? and status ='In afwachting'";
                                        $result = $database->connection()->prepare($sql);

                                        if (!$result->execute(array($userId))) {
                                            $result = null;
                                            header("location:profile.php?error=stmtGetRandomUserDogFailed");
                                            exit();
                                            }

                                            $flags = $result->rowCount();

                                            if (!$flags > 0) {
                                                echo "
                                        <li><a href=\"index.php\">Home</a></li>
                                        <li><a href=\"zoekPartner.php\">Zoek Partner</a></li>
                                          <li><a href=\"profile.php\">Profiel <i class=\"ti-angle-down\"></i></a>
                                            <ul class=\"submenu\">
                                                 <li><a href=\"inbox.php\">Inbox</a></li>  
                                                 <li><a href=\"logout.php\">Loguit</a></li>  
                                            </ul>";
                                            }
                                            else {
                                                echo "
                                        <li><a href=\"index.php\">Home</a></li>
                                        <li><a href=\"zoekPartner.php\">Zoek Partner</a></li>
                                          <li><a href=\"profile.php\" style='color: #C40000FF'><b>Profiel ($flags)</b><i class=\"ti-angle-down\"></i></a>
                                            <ul class=\"submenu\">
                                                 <li><a href=\"inbox.php\" style='color: #C40000FF'><b>Inbox ($flags)</b></a></li>  
                                                 <li><a href=\"logout.php\">Loguit</a></li>  
                                            </ul>";
                                            }
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mobile_menu d-block d-lg-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>