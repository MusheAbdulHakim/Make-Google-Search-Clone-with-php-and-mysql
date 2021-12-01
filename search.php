<?php
    include_once 'includes/config.php';
    include_once 'includes/ResultsProvider.php';

    if(isset($_GET['term'])){
        $term = $_GET['term'];
    }else{
        exit("You must enter a search term");
    }
    $type = isset($_GET["type"]) ? $_GET["type"] : "sites";
    $page = isset($_GET["page"]) ? $_GET["page"] : 1;

    $resultsProvider = new ResultsProvider($db);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search results</title>
    <link rel="stylesheet" href="assets/css/jquery.fancybox.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<script src="assets/js/jquery.min.js"></script>

</head>
<body>
<div class="wrapper">
	
    <div class="header">
        <div class="headerContent">

            <div class="logoContainer">
                <a href="index.php">
                    <img src="assets/images/logo.png">
                </a>
            </div>

            <div class="searchContainer">
                <form action="search.php" method="GET">
                    <div class="searchBarContainer">
                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                        <input class="searchBox" type="text" name="term" value="<?php echo $term; ?>" autocomplete="off">
                        <button class="searchButton">
                            <img src="assets/images/search.png">
                        </button>
                    </div>

                </form>

            </div>

        </div>
        <div class="tabsContainer">
            <ul class="tabList">
                <li class="<?php echo $type == 'sites' ? 'active' : '' ?>">
                    <a href='<?php echo "search.php?term=$term&type=sites"; ?>'>
                        Sites
                    </a>
                </li>

                <li class="<?php echo $type == 'images' ? 'active' : '' ?>">
                    <a href='<?php echo "search.php?term=$term&type=images"; ?>'>
                        Images
                    </a>
                </li>

                <li  class="<?php echo $type == 'videos' ? 'active' : '' ?>">
                    <a href="<?php echo "search.php?term=$term&type=videos"; ?>">Videos</a>
                </li>

            </ul>


        </div>
    </div>

    <div class="mainResultsSection">

        <?php

            $numResults = $resultsProvider->get_total_result($type,$term);

            echo "<p class='resultsCount'>$numResults results found</p>";
            $pageSize = 20;
            if($type=='sites'){
                echo $resultsProvider->site_html_result($page,$pageSize,$term);
            }
            if($type == 'images'){
                echo $resultsProvider->images_html_result($page,$pageSize,$term);
            }
            if($type == 'videos'){
                echo 'under dev';
            }
        ?>

    </div>

    <div class="paginationContainer">

        <div class="pageButtons">
            <div class="pageNumberContainer">
                <img src="assets/images/pageStart.png">
            </div>

            <?php

				$pagesToShow = 10;
				$numPages = ceil($numResults / $pageSize);
				$pagesLeft = min($pagesToShow, $numPages);

				$currentPage = $page - floor($pagesToShow / 2);

				if($currentPage < 1) {
					$currentPage = 1;
				}

				if($currentPage + $pagesLeft > $numPages + 1) {
					$currentPage = $numPages + 1 - $pagesLeft;
				}

				while($pagesLeft != 0 && $currentPage <= $numPages) {

					if($currentPage == $page) {
						echo "<div class='pageNumberContainer'>
								<img src='assets/images/pageSelected.png'>
								<span class='pageNumber'>$currentPage</span>
							</div>";
					}
					else {
						echo "<div class='pageNumberContainer'>
								<a href='search.php?term=$term&type=$type&page=$currentPage'>
									<img src='assets/images/page.png'>
									<span class='pageNumber'>$currentPage</span>
								</a>
						</div>";
					}


					$currentPage++;
					$pagesLeft--;

				}

			?>
            
            <div class="pageNumberContainer">
                <img src="assets/images/pageEnd.png">
            </div>

        </div>

    </div>

</div>
</body>
<script src="assets/js/jquery.fancybox.min.js"></script>
<script src="assets/js/masonry.pkgd.min.js"></script>
<script src="assets/js/script.js"></script>
</html>
