<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Have extends REST_Controller
{
   function __construct()
   {
      parent::__construct();
      $this->load->model('HAVE_m');
      #$this->load->library('');
      #$this->load->helper('');
   }

   function sessionLogin_post()
   {
      $this->response(array('errorCode' => '0', 'errorCause' => '', 'resultEntity' => $this->session->userdata('member_id')));
   }

   function login_post()
   {
      $data = $this->post();

      /*
      $key = 'amdasdklkljlk123laksdjalsdk15lgd';
      $userId = openssl_decrypt(base64_decode($data['userId']), "aes-256-cbc", $key, true, str_repeat(chr(0), 16));
      $password = openssl_decrypt(base64_decode($data['password']), "aes-256-cbc", $key, true, str_repeat(chr(0), 16));
      */

      $name = $data['name'];
      $password = $data['password'];

      $member = $this->HAVE_m->login($name, $password);

      if(!empty($member))
      {
         $new_session = array(
           'name' => $name,
           'member_id' => $member['member_id'],
           'loggedin' => TRUE
         );

         $this->session->set_userdata($new_session);
         $this->response(array('errorCode' => '0', 'errorCause' => ''));
      }
      else
      {
         $this->response(array('errorCode' => '-1', 'errorCause' => 'wrong ID or password!'));
      }
   }

   function training_post()
   {
      $training = $this->HAVE_m->get_rating_all();

      if(!empty($training))
      {
        $training = json_encode($training);
        $unique_member = json_encode($this->HAVE_m->get_unique_member());
        $unique_travel = json_encode($this->HAVE_m->get_unique_travel());

        $fp1 = fopen("/home/rstudio/training.json", "w");
        $fp2 = fopen("/home/rstudio/unique_member.json", "w");
        $fp3 = fopen("/home/rstudio/unique_travel.json", "w");
        fwrite($fp1, $training); fclose($fp1);
        fwrite($fp2, $unique_member); fclose($fp2);
        fwrite($fp3, $unique_travel); fclose($fp3);

        exec("Rscript /home/rstudio/training.R");
        $this->response(array('errorCode' => '0', 'errorCause' => ''));
      }
      else
      {
        $this->response(array('errorCode' => '-1', 'errorCause' => 'Training failure!'));
      }
   }

   function predict_post()
   {
      $data = $this->post();
      $member_id = $data['member_id'];

      $member_pred = $this->HAVE_m->get_rating_by($member_id);

      if(!empty($member_pred))
      {
        $member_pred = json_encode($member_pred);
        $member_pred = "'" . $member_pred . "'";
        exec("Rscript /home/rstudio/predict.R $member_pred $member_id", $response);

        $travel_id_array = json_decode($response[0], TRUE);
        $result = json_encode($this->HAVE_m->get_travel_by_array($travel_id_array[$member_id]));
        //$result = iconv("EUC-KR", "UTF-8", $result);

        $this->response(array('errorCode' => '0', 'errorCause' => '', 'resultList' => $result));
      }
      else
      {
        $this->response(array('errorCode' => '-1', 'errorCause' => 'No data!'));
      }
   }

   function travelList_post()
   {
      $travel_list = $this->HAVE_m->get_travel_all();
      //$travel_list = iconv("EUC-KR", "UTF-8", $travel_list);

      if(!empty($travel_list))
      {
        $travel_list = json_encode($travel_list);
        $this->response(array('errorCode' => '0', 'errorCause' => '', 'resultList' => $travel_list));
      }
      else
      {
        $this->response(array('errorCode' => '-1', 'errorCause' => 'No data!'));
      }
   }

   function myTravelList_post()
   {
      $data = $this->post();
      $member_id = $data['member_id'];

      $travel_list = $this->HAVE_m->get_member_travel_by($member_id);
      //$travel_list = iconv("EUC-KR", "UTF-8", $travel_list);

      if(!empty($travel_list))
      {
        $travel_list = json_encode($travel_list);
        $this->response(array('errorCode' => '0', 'errorCause' => '', 'resultList' => $travel_list));
      }
      else
      {
        $this->response(array('errorCode' => '-1', 'errorCause' => 'No data!'));
      }
   }

   function searchTravelList_post()
   {
      $data = $this->post();
      $nation_name = $data['nation_name'];
      $city_name = $data['city_name'];
      $dept_date = $data['dept_date'];
      $price_min = $data['price_min'];
      $departure = $data['departure'];

      $travel_list = $this->HAVE_m->get_search_travel_by($nation_name, $city_name, $dept_date, $price_min, $departure);

      if(!empty($travel_list))
      {
        $travel_list = json_encode($travel_list);
        $this->response(array('errorCode' => '0', 'errorCause' => '', 'resultList' => $travel_list));
      }
      else
      {
        $this->response(array('errorCode' => '-1', 'errorCause' => 'No data!'));
      }
   }

   function ratingTravel_post()
   {
      $data = $this->post();
      $member_id = $data['member_id'];
      $travel_id = $data['travel_id'];
      $rating = $data['rating'];

      for($i=0;$i<count($travel_id);$i++)
      {
        $this->HAVE_m->insert_rating_travel($member_id, $travel_id[$i], $rating[$i]);
      }

      $this->response(array('errorCode' => '0', 'errorCause' => $travel_id));
   }

   function writeReview_post()
   {
      $data = $this->post();
      $member_id = $data['member_id'];
      $travel_id = $data['travel_id'];
      $content = $data['content'];

      $this->HAVE_m->insert_review_by($member_id, $travel_id, $content);
      $this->response(array('errorCode' => '0', 'errorCause' => ''));
   }

   function reviewList_post()
   {
      $data = $this->post();
      $travel_id = $data['travel_id'];

      $review_list = $this->HAVE_m->get_review_by($travel_id);
      //$travel_list = iconv("EUC-KR", "UTF-8", $travel_list);

      if(!empty($review_list))
      {
        $review_list = json_encode($review_list);
        $this->response(array('errorCode' => '0', 'errorCause' => '', 'resultList' => $review_list));
      }
      else
      {
        $this->response(array('errorCode' => '-1', 'errorCause' => 'No data!'));
      }
   }

   function ratingList_post()
   {
      $data = $this->post();
      $member_id = $data['member_id'];

      $travel_list = $this->HAVE_m->get_except_member_travel_by($member_id);
      //$travel_list = iconv("EUC-KR", "UTF-8", $travel_list);

      if(!empty($travel_list))
      {
        $travel_list = json_encode($travel_list);
        $this->response(array('errorCode' => '0', 'errorCause' => '', 'resultList' => $travel_list));
      }
      else
      {
        $this->response(array('errorCode' => '-1', 'errorCause' => 'No data!'));
      }
   }
}
