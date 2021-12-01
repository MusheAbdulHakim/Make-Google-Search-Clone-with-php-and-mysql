<?php 

class ResultsProvider {

    private $db;


    public function __construct($db){
        $this->db = $db;
    }

	public function get_total_result($type,$term){
		if($type == 'sites'){
			return $this->total_sites_result($term);
		}
		if($type == 'images'){
			return $this->total_images_result($term);
		}
	}

	public function site_result($page,$pageSize,$term){
		$fromLimit = ($page - 1) * $pageSize;
		$query = $this->db->prepare("SELECT * 
										 FROM sites WHERE title LIKE :term 
										 OR url LIKE :term 
										 OR keywords LIKE :term 
										 OR description LIKE :term
										 ORDER BY clicks DESC
										 LIMIT :fromLimit, :pageSize");

		$searchTerm = "%". $term . "%";
		$query->bindParam(":term", $searchTerm);
		$query->bindParam(":fromLimit", $fromLimit, PDO::PARAM_INT);
		$query->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);
		$query->execute();
		return $query->fetch(PDO::FETCH_ASSOC);
	}

    public function total_sites_result($term){
        $query = $this->db->prepare("SELECT COUNT(*) as total 
										 FROM sites WHERE title LIKE :term 
										 OR url LIKE :term 
										 OR keywords LIKE :term 
										 OR description LIKE :term");

		$searchTerm = "%". $term . "%";
		$query->bindParam(":term", $searchTerm);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row["total"];
    }

    public function site_html_result($page, $pageSize, $term){
        $fromLimit = ($page - 1) * $pageSize;
		$query = $this->db->prepare("SELECT * 
										 FROM sites WHERE title LIKE :term 
										 OR url LIKE :term 
										 OR keywords LIKE :term 
										 OR description LIKE :term
										 ORDER BY clicks DESC
										 LIMIT :fromLimit, :pageSize");

		$searchTerm = "%". $term . "%";
		$query->bindParam(":term", $searchTerm);
		$query->bindParam(":fromLimit", $fromLimit, PDO::PARAM_INT);
		$query->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);
		$query->execute();
		$resultsHtml = "<div class='siteResults'>";
		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$id = $row["id"];
			$url = $row["url"];
			$title = $row["title"];
			$description = $row["description"];

			$title = $this->trimField($title, 55);
			$description = $this->trimField($description, 230);
		
			$resultsHtml .= "<div class='resultContainer'>
								<h3 class='title'>
									<a class='result' href='$url' data-linkId='$id'>
										$title
									</a>
								</h3>
								<span class='url'>$url</span>
								<span class='description'>$description</span>
							</div>";


		}
		$resultsHtml .= "</div>";
		return $resultsHtml;
    }

    public function total_images_result($term){
        $query = $this->db->prepare("SELECT COUNT(*) as total 
										 FROM images 
										 WHERE (title LIKE :term 
										 OR alt LIKE :term)
										 AND broken=0");

		$searchTerm = "%". $term . "%";
		$query->bindParam(":term", $searchTerm);
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);
		return $row["total"];
    }

    public function images_html_result($page, $pageSize, $term){
        $fromLimit = ($page - 1) * $pageSize;

		$query = $this->db->prepare("SELECT * 
										 FROM images 
										 WHERE (title LIKE :term 
										 OR alt LIKE :term)
										 AND broken=0
										 ORDER BY clicks DESC
										 LIMIT :fromLimit, :pageSize");

		$searchTerm = "%". $term . "%";
		$query->bindParam(":term", $searchTerm);
		$query->bindParam(":fromLimit", $fromLimit, PDO::PARAM_INT);
		$query->bindParam(":pageSize", $pageSize, PDO::PARAM_INT);
		$query->execute();
		$resultsHtml = "<div class='imageResults'>";
		$count = 0;
		while($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$count++;
			$id = $row["id"];
			$imageUrl = $row["imageUrl"];
			$siteUrl = $row["siteUrl"];
			$title = $row["title"];
			$alt = $row["alt"];

			if($title) {
				$displayText = $title;
			}
			else if($alt) {
				$displayText = $alt;
			}
			else {
				$displayText = $imageUrl;
			}
			$resultsHtml .= "<div class='gridItem image$count'>
								<a href='$imageUrl' data-fancybox data-caption='$displayText'
									data-siteurl='$siteUrl'>
									<script>
									$(document).ready(function() {
										loadImage(\"$imageUrl\", \"image$count\");
									});
									</script>
									<span class='details'>$displayText</span>
								</a>
							</div>";


		}
		$resultsHtml .= "</div>";
		return $resultsHtml;
    }


    private function trimField($string, $characterLimit) {
		$dots = strlen($string) > $characterLimit ? "..." : "";
		return substr($string, 0, $characterLimit) . $dots;
	}
    
}




