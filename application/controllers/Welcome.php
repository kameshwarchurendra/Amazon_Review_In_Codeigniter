<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Welcome extends CI_Controller {

//======================Start================================//
	public function index(){
		$dt['title'] = 'Add Amazon Review '; // Replace with the actual product reviews URL
		// phpinfo();
		$this->load->view('welcome',$dt);
	}


public function getlist(){
	$dt['user']= $this->User_Model->getlist();
				
	$this->load->view('list',$dt);		
}

public function store(){
	if($_SERVER['REQUEST_METHOD']=="POST"){

		if($this->User_Model->store()){
			$info= array('status'  => 'success',
						 'message' => 'Insert Successfully',
						  'class'  => 'alert alert-success fade in'
						);

		}else{

			$info= array('status'  => 'error',
						 'message' => 'Insert Not Successfully',
						 'class'   => 'alert alert-danger fade in'
						);
		}
		$this->session->set_flashdata('item', $info);
		redirect('Welcome');
		//echo json_encode($info);

	}
}

public function add(){
	if($_SERVER['REQUEST_METHOD']=="POST"){

		if($this->User_Model->add()){
			$info= array('status'  => 'success',
						 'message' => 'Insert Successfully',
						  'class'  => 'alert alert-success fade in'
						);

		}else{

			$info= array('status'  => 'error',
						 'message' => 'Insert Not Successfully',
						 'class'   => 'alert alert-danger fade in'
						);
		}
		$this->session->set_flashdata('item', $info);
		redirect('Welcome');
		//echo json_encode($info);

	}
}

public function edit(){

	$dt['id']= $this->input->get('id');
    
	//print_r($dt['id']);die();
	$dt['user']= $this->User_Model->GetAlist($dt['id']);
				
	$this->load->view('edit',$dt);		
}

public function update(){
	if($_SERVER['REQUEST_METHOD']=="POST"){

		if($this->User_Model->update()){
			$info= array('status'=> 'success',
						'message'=> 'Update Successfully',
						'class' => 'alert alert-success fade in'

						);

		}else{
			$info= array('status'=> 'error',
			'message'=> 'Update Not Successfully',
			'class' => 'alert alert-danger fade in'
			);
		}
		$this->session->set_flashdata('item', $info);
		redirect('Welcome/edit');
		//echo json_encode($info);

	}
}


public function delete(){
	if($_SERVER['REQUEST_METHOD']=="GET"){

		$dt['id']= $this->input->get('id');

		if($this->User_Model->delete($dt['id'])){
			$info= array('status'=> 'success',
						'message'=> 'Delete Successfully',
						'class' => 'alert alert-success fade in'
						);
		}else{

			$info= array('status'=> 'error',
						'message'=> 'Delete Not Successfully',
						'class' => 'alert alert-danger fade in'
						);

		}
		$this->session->set_flashdata('item', $info);
         redirect('Welcome/getlist');
	}
}

//===============================Amazon Scrap=========================================//
// public function get_page_content($url) {
// 	$headers = [
// 		"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"
// 	];

// 	$ch = curl_init();
// 	curl_setopt($ch, CURLOPT_URL, $url);
// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// 	$output = curl_exec($ch);

// 	if (curl_errno($ch)) {
// 		echo 'Error:' . curl_error($ch);
// 	}

// 	curl_close($ch);
// 	return $output;
// }

// public function parse_reviews($page_content) {
// 	$reviews = [];
// 	$dom = new DOMDocument();
// 	@$dom->loadHTML($page_content);
// 	$xpath = new DOMXPath($dom);
// 	$review_divs = $xpath->query("//div[@data-hook='review']");

// 	foreach ($review_divs as $review) {
// 		$title = $xpath->query(".//a[@data-hook='review-title']", $review)->item(0);
// 		$rating = $xpath->query(".//i[@data-hook='review-star-rating']", $review)->item(0);
// 		$body = $xpath->query(".//span[@data-hook='review-body']", $review)->item(0);

// 		$reviews[] = [
// 			'title' => $title ? trim($title->nodeValue) : null,
// 			'rating' => $rating ? trim($rating->nodeValue) : null,
// 			'body' => $body ? trim($body->nodeValue) : null
// 		];
// 	}

// 	return $reviews;
// }

// public function get_all_reviews($product_url, $max_pages = 5) {
// 	$all_reviews = [];
// 	$page_number = 1;

// 	while ($page_number <= $max_pages) {
// 		$url = $product_url . "/ref=cm_cr_getr_d_paging_btm_next_" . $page_number . "?pageNumber=" . $page_number;
// 		$page_content = $this->get_page_content($url);
// 		$reviews = $this->parse_reviews($page_content);

// 		if (empty($reviews)) {
// 			break;
// 		}

// 		$all_reviews = array_merge($all_reviews, $reviews);
// 		$page_number++;
// 		sleep(1); // Be respectful and avoid hammering the server
// 	}

// 	return $all_reviews;
// }
//====================================/Amazon Scrap===================================//
  // Function to get reviews from a single page of ASIN reviews
  private function get_reviews_from_page($asin, $page_number) {
    // $reviews_url = "https://www.amazon.co.uk/product-reviews/{$asin}/?pageNumber={$page_number}";
	$reviews_url = "https://www.amazon.in/product-reviews/{$asin}/?pageNumber={$page_number}";
    $headers = array(
         "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
         "Accept-Encoding: gzip"  // Request gzipped content
	);

    $ch = curl_init($reviews_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");  // Set cURL to handle gzip automatically
    $response = curl_exec($ch);
    curl_close($ch);

    // Convert response encoding to UTF-8
    $response = mb_convert_encoding($response, 'UTF-8', 'auto');

    // Debug response
    file_put_contents('debug_full_response.html', $response); // Save full response to inspect manually

    // Load HTML into Simple HTML DOM
    $soup = new simple_html_dom();
    $soup->load($response);

    $reviews = [];

    // Find review elements
    $review_elements = $soup->find('div[data-hook=review]');
    file_put_contents('debug_review_elements.html', implode("\n", array_map(function($element) {
        return $element->outertext;
    }, $review_elements)));  // Save individual review elements to a file

    foreach ($review_elements as $review_element) {
        // Debug the review element
        file_put_contents('debug_single_review.html', $review_element->outertext);  // Save the current review element

        $rating_element = $review_element->find('i[data-hook=review-star-rating] span', 0);
        $title_element = $review_element->find('a[data-hook=review-title]', 0);
        $content_element = $review_element->find('span[data-hook=review-body]', 0);
        $date_element = $review_element->find('span[data-hook=review-date]', 0);
        $reviewer_element = $review_element->find('span.a-profile-name', 0);

        $rating_text = $rating_element ? $rating_element->plaintext : "0.0 out of 5 stars";
        $rating_value = floatval(explode(" ", $rating_text)[0]);

        $reviews[] = array(
            'rating' => $rating_value,
            'title' => $title_element ? trim($title_element->plaintext) : "No title",
            'content' => $content_element ? trim($content_element->plaintext) : "No content",
            'date' => $date_element ? trim($date_element->plaintext) : "No date",
            'reviewer' => $reviewer_element ? trim($reviewer_element->plaintext) : "No reviewer"
        );
    }

    unset($soup);
    return $reviews;
}





// Function to get all reviews from ASIN, handling pagination
private function get_all_reviews_from_asin($asin) {
	$all_reviews = [];
	$page_number = 1;
    $max_pages = 3; // Limit to 10 pages

    while ($page_number <= $max_pages) {
		// echo $page_number;
		$reviews = $this->get_reviews_from_page($asin, $page_number);
		if (empty($reviews)) {
			break;
		}
		$all_reviews = array_merge($all_reviews, $reviews);
		$page_number++;
	}

	return $all_reviews;
}

// Function to calculate average review data
private function calculate_average_reviews($reviews) {
	if (empty($reviews)) {
		return array("average_rating" => 0, "total_reviews" => 0);
	}

	$total_rating = array_sum(array_column($reviews, 'rating'));
	$average_rating = $total_rating / count($reviews);

	return array(
		"average_rating" => $average_rating,
		"total_reviews" => count($reviews)
	);
}

// Function to handle the /reviews endpoint
public function get_all_reviews() {
	$asins = $this->input->get('asin');
	if (empty($asins)) {
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array("error" => "At least one ASIN is required")))
			->set_status_header(400);
		return;
	}

	$all_reviews = [];
	foreach ($asins as $asin) {
		$reviews = $this->get_all_reviews_from_asin($asin);
		// print_r($reviews);die();
		$all_reviews = array_merge($all_reviews, $reviews);
	}

	$average_reviews = $this->calculate_average_reviews($all_reviews);

	$this->output
		->set_content_type('application/json')
		->set_output(json_encode(array(
			"reviews" => $all_reviews,
			"average_reviews" => $average_reviews
		)));
}


//===============================Amazon Scrap=========================================//
	

//======================End================================//

}
