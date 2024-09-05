<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class User_Model extends CI_Model {
//========================================Start============================================//

public function getlist(){
     $this->db->select('*');
    return $this->db->get('review_tbl')->result();
    
}

public function GetAlist($id){
    $this->db->select('*');
    $this->db->where('review_id',$id);
   return $this->db->get('review_tbl')->result();
   
}




public function store(){
  
    $csv = array();
    $csv_file   = $_FILES['csv_file'];
    $data[]='';
    $file[]='';
    $files[]='';
    $csvfile= $this->upload_multiple_files('csv_file', 'uploads/', 'csv|xls|xlsx|ods|tsv');
    // print_r($csvfile);die();
    if(!empty($csvfile) && $csvfile!=''){
        foreach($csvfile as $j => $filename){
                $file_path = './uploads/' .  $filename;
                $spreadsheet = IOFactory::load($file_path);
                $files = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                //$files = $this->get_csv_headers($file_path);
                //  print_r($files);exit;
                // $data[]='';
                if(!empty($files) && $files!=''){
                    foreach(array_slice($files, 1) as $i => $vl){   
                        // print_r($vl['B']);exit;
                        // $response = $this->AmazonReviewEAN($vl[1]);
                        $response = $this->AmazonReviewEAN($vl['B']);

                        $res = json_decode($response);
                        // print_r($res);die();
                        if($res->request_info->success){
                            $dt= array('asin_no' =>$res->product->asin,
                                        'ean_no'  =>$res->request_parameters->gtin,
                                        'average_rating' =>$res->summary->rating,
                                        'total_ratings' =>$res->summary->ratings_total,
                                        'total_reviews' =>$res->summary->reviews_total,
                                        'five_star_ratings' =>  json_encode($res->summary->rating_breakdown->five_star),
                                        'four_star_ratings' =>json_encode($res->summary->rating_breakdown->four_star),
                                        'three_star_ratings' =>json_encode($res->summary->rating_breakdown->three_star),
                                        'two_star_ratings' =>json_encode($res->summary->rating_breakdown->two_star),
                                        'one_star_ratings' =>json_encode($res->summary->rating_breakdown->one_star),
                                        'reviews' =>json_encode($res->reviews),
                                        'top_positive' =>json_encode(isset($res->top_positive)),
                                        'top_critical'=>json_encode(isset($res->top_critical)),
                                        'reviews_positive' =>isset($res->summary->reviews_positive),
                                        'reviews_critical'=>isset($res->summary->reviews_critical)
                                    );
                               $result=$this->db->insert('review_tbl', $dt); 
                            // $data[$i]=$dt;
                            // $file[$j]= $data[$i];
                        
                        }
                
                    }
                
                }

        }
       return $result;
        // print_r($files);die();
    }
    return 0;
  
    // if (!empty($data)) {
    //     $this->db->trans_start(); // Start transaction
    //     $this->db->insert_batch('review_tbl', $data); // Batch insert
    //     $this->db->trans_complete(); // Complete transaction

    //     if ($this->db->trans_status() === FALSE) {
    //         // Transaction failed
    //         return 0;
    //     } else {
    //         // Transaction successful
    //         return $this->db->affected_rows();
    //     }
    // }
   
}


public function add(){
  
    $csv = array();
    $csv_file   = $_FILES['csv_file'];
    $data[]='';
    $file[]='';
    $files[]='';
    $res[]='';
    $csvfile= $this->upload_multiple_files('csv_file', 'uploads/', 'csv|xls|xlsx|ods|tsv');
    // print_r($csvfile);die();
    if(!empty($csvfile) && $csvfile!=''){
        foreach($csvfile as $j => $filename){
                $file_path = './uploads/' .  $filename;
                $spreadsheet = IOFactory::load($file_path);
                $files = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                //$files = $this->get_csv_headers($file_path);
                //  print_r($files);exit;
                // $data[]='';
                if(!empty($files) && $files!=''){
                    foreach(array_slice($files, 1) as $i => $vl){   
                        // print_r($vl['L']);exit;
                        // $response = $this->AmazonReviewEAN($vl[1]);
                     
                        
                        // $res = json_decode($vl['L']);
                        //    print_r($res);exit;
                        //  if(!empty($res) &&  $res!=""){
                        //     foreach($res  as $j=> $val){

                        //         // print_r($val);exit;
                        //         $dt= array('asin_no' =>$vl['C'],
                        //                         'ean_no'  =>$vl['B'],
                        //                         'TITLE' =>$val->title,
                        //                         'NICKNAME' =>$val->profile->name,
                        //                         'DETAIL' =>$val->body,
                        //                         'RATING' =>$val->rating,
                        //                         'DATE' => $val->date->raw,
                        //                         'STATUS' =>1
                                            
                        //                     );
                        //             $result=$this->db->insert('new_review_tbl', $dt); 
                        //             // $data[$i]=$dt;
                        //             // $file[$j]= $data[$i];
    
                        //       }
                        //  }
                         

                        $dt = array(
                            'sku' =>$vl['A']
                        );
                        
                        // Update the record where 'user_id' equals 123
                        $this->db->where('ean_no', $vl['B']);
                        
                        $result = $this->db->update('review_tbl', $dt);
                        
                           
                        
                        
                
                    }
                
                }

        }
       return $result;
        // print_r($files);die();
    }
    return 0;
  
    // if (!empty($data)) {
    //     $this->db->trans_start(); // Start transaction
    //     $this->db->insert_batch('review_tbl', $data); // Batch insert
    //     $this->db->trans_complete(); // Complete transaction

    //     if ($this->db->trans_status() === FALSE) {
    //         // Transaction failed
    //         return 0;
    //     } else {
    //         // Transaction successful
    //         return $this->db->affected_rows();
    //     }
    // }
   
}

private function get_csv_headers($file_path) {
    $headers = [];
    
    if (($handle = fopen($file_path, "r")) !== FALSE) {
        // Read the first row which contains the headers
        $headers = fgetcsv($handle, 1000, ",");

        // print_r($headers);die();
      
       $count= count($headers);
       $i=0;
       $data = [];
       while(($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
           $data[] = $row;
       }
       fclose($handle);
        // foreach ($headers as &$header) {

        //     // Remove unwanted characters or patterns
        //     if($i <= $count - 2){
        //         $header = trim(preg_replace('/\d+$/', '', $header));
        //     }
               
            
        //   $i++;
        // }
        // print_r($data);die();
        // unset($headers[0]);
        // unset($headers[1]);
    
        return array_values($data);
    }
    
   
}


public function update(){
    $data= array('name'=> $this->input->post('name'),
                 'email'=> $this->input->post('email'),
                 'mobile'=> $this->input->post('mobile'),
                 'image'=> $this->upload_a_file('image', 'uploads/', 'gif|jpg|png|jpeg')
           );
    return $this->db->update('review_tbl',$data);
    
}


public function delete($id){     
    $this->db->where('review_id',$id);
    return $this->db->delete('review_tbl');
    
}


//=================================File Upload=================================//
public function upload_a_file($in_title, $file_location, $file_type='*', $out_title=''){
    // print_r($_FILES[$in_title]['name']);die();
    if (!empty($file_location)){
      if (!file_exists($file_location)) mkdir($file_location, 0777, true);
    }
    /* NOTE here $out_title = previous file title   while updating only */
    if (!empty($in_title) && !empty($file_location))
    if(!empty($_FILES[$in_title]['name'])) {  
      $config['upload_path']   = $file_location;
      $config['allowed_types'] = $file_type;
      $config['remove_spaces'] = TRUE;
      $config['file_name']     = $_FILES[$in_title]['name'];
      //Load upload library and initialize configuration
      $this->load->library('upload', $config);
      $this->upload->initialize($config);
  
      if($this->upload->do_upload($in_title)){
        $uploadData = $this->upload->data();
        if (!empty($out_title)) if(file_exists($config['upload_path'].$out_title))
          unlink($config['upload_path'].$out_title); /* Deleting previous File */
        $out_title = $uploadData['file_name'];
    } } return $out_title;
}


public function upload_multiple_files($in_title, $file_location, $file_type='*', $existing_files = array()) {
    if (!empty($file_location)){
        if (!file_exists($file_location)) mkdir($file_location, 0777, true);
    }

    if (!empty($in_title) && !empty($file_location)) {
        $uploaded_files = array();

        // Load upload library and initialize configuration
        $this->load->library('upload');

        // Loop through all files
        $file_count = count($_FILES[$in_title]['name']);
        for ($i = 0; $i < $file_count; $i++) {
            if (!empty($_FILES[$in_title]['name'][$i])) {
                $_FILES['file']['name'] = $_FILES[$in_title]['name'][$i];
                $_FILES['file']['type'] = $_FILES[$in_title]['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES[$in_title]['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES[$in_title]['error'][$i];
                $_FILES['file']['size'] = $_FILES[$in_title]['size'][$i];

                $config['upload_path'] = $file_location;
                $config['allowed_types'] = $file_type;
                $config['remove_spaces'] = TRUE;
                $config['file_name'] = $_FILES['file']['name'];
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $uploadData = $this->upload->data();
                    // Delete the existing file if it exists
                    if (!empty($existing_files) && in_array($uploadData['file_name'], $existing_files)) {
                        unlink($config['upload_path'] . $uploadData['file_name']);
                    }
                    $uploaded_files[] = $uploadData['file_name'];
                } else {
                    // Handle upload error if needed
                    $error = $this->upload->display_errors();
                    // You can log or display the error message as needed
                    echo "Error uploading file: " . $error;
                }
            }
        }
        return $uploaded_files;
    }
}

  
//================================/File Upload==================================//

//=======================================API Call=========================================//
public function AmazonReview(){
	$apiKey  = '6d6570bef88529dea72c7a190da24b92';
	$asin    = 'B0BHX83YLH';//'B0CMF3QY5J';
	$country = 'in';//'uk';
	$tld     = 'in';//'com';
	
	
	// Construct the URL for the API request
	$url = "https://api.scraperapi.com/structured/amazon/review?api_key={$apiKey}&asin={$asin}&country={$country}&tld={$tld}";
	
	// Initialize cURL session
	$ch = curl_init();
	
	// Set cURL options
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	
	// Execute cURL request
	$response = curl_exec($ch);
	
	// Check for errors
	if ($response === false) {
		echo "Error occurred: " . curl_error($ch);
	} else {
		// Decode the JSON response
		$reviewData = json_decode($response, true);
	    //return $reviewData;
		// Print the response data
		print_r($reviewData);
	}
	
	// Close cURL session
	curl_close($ch);
}


public function AmazonReviewEAN($ean_no){
	# set up the request parameters
	$queryString = http_build_query([
		'api_key' => '0E2A28134EE54107A39C180D94C618B8',//DDCC35CBA3E84878B6917C456C64A269 //'68E17192093E41CE9718C65BFCBF0F80',
		'type' => 'reviews',             // Change to the appropriate Amazon domain if needed
		'amazon_domain' => 'amazon.co.uk',  // 'amazon_domain' => 'amazon.com',    
		'gtin' => $ean_no,// '802535436121',//'8714789260532',        // 'asin' => 'B00891PV0G', all_critical
		'review_stars' => 'all_stars',	  // all_stars, five_star, four_star,three_star,two_star,one_star,all_positive,all_critical
		'sort_by' => 'most_recent'         // most_helpful/most_recent
		
	]);
	
	# make the http GET request to Rainforest API
	$ch = curl_init(sprintf('%s?%s', 'https://api.rainforestapi.com/request', $queryString));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	# the following options are required if you're using an outdated OpenSSL version
	# more details: https://www.openssl.org/blog/blog/2021/09/13/LetsEncryptRootCertExpire/
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_TIMEOUT, 180);
	
	$api_result = curl_exec($ch);
	curl_close($ch);
	
	# print the JSON response from Rainforest API
	 return $api_result;
}
//=======================================/API Call=========================================//
public function productreview(){
    $params = array(
        'target' => 'amazon_reviews',
        'query' => 'B09H74FXNW',
        'parse' => True
    );

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://scraper-api.smartproxy.com/v2/scrape');
    curl_setopt($ch, CURLOPT_USERPWD, 'SPusername' . ':' . 'SPpassword');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_POST, 1);

    $headers = array();
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    echo $result;

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close ($ch);

}







// private function get_all_reviews($product_url) {
//     $all_reviews = [];
//     $page_number = 1;

//     while (true) {
//             $reviews = $this->get_reviews_from_page($product_url, $page_number);
//             if (empty($reviews)) {
//                 break;
//             }
//             $all_reviews = array_merge($all_reviews, $reviews);
//             $page_number++;
//     }

//     return $all_reviews;
// }

// private function get_reviews_from_page($product_url, $page_number) {
//         $reviews_url = "{$product_url}&pageNumber={$page_number}";
//         $headers = [
//             "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"
//         ];

//         $options = [
//             'http' => [
//                 'header' => $headers
//             ]
//         ];

//         $context = stream_context_create($options);
//         $response = file_get_contents($reviews_url, false, $context);

//         if ($response === FALSE) {
//             return [];
//         }

//         $soup = new DOMDocument();
//         @$soup->loadHTML($response);
//         $xpath = new DOMXPath($soup);

//         $reviews = [];
//         $review_elements = $xpath->query('//div[@data-hook="review"]');

//         foreach ($review_elements as $review_element) {
//             $rating_element = $xpath->query('.//i[@data-hook="review-star-rating"]', $review_element)->item(0);
//             $title_element = $xpath->query('.//a[@data-hook="review-title"]', $review_element)->item(0);
//             $content_element = $xpath->query('.//span[@data-hook="review-body"]', $review_element)->item(0);
//             $date_element = $xpath->query('.//span[@data-hook="review-date"]', $review_element)->item(0);
//             $reviewer_element = $xpath->query('.//span[@class="a-profile-name"]', $review_element)->item(0);

//             $rating_text = $rating_element ? $rating_element->textContent : "0.0 out of 5 stars";
//             $rating_value = (float) str_replace(",", ".", explode(" ", $rating_text)[0]);

//             $reviews[] = [
//                 'rating' => $rating_value,
//                 'title' => $title_element ? trim($title_element->textContent) : "No title",
//                 'content' => $content_element ? trim($content_element->textContent) : "No content",
//                 'date' => $date_element ? trim($date_element->textContent) : "No date",
//                 'reviewer' => $reviewer_element ? trim($reviewer_element->textContent) : "No reviewer"
//             ];
//         }

//         return $reviews;
// }

// private function calculate_average_reviews($reviews) {
//     if (empty($reviews)) {
//         return ["average_rating" => 0, "total_reviews" => 0];
//     }

//     $total_rating = array_sum(array_column($reviews, 'rating'));
//     $average_rating = $total_rating / count($reviews);

//     return [
//         "average_rating" => $average_rating,
//         "total_reviews" => count($reviews)
//     ];
// }




//=========================================/End============================================//

}
