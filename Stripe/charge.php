<?php include("includes/connection.php");
 	  include("includes/function.php"); 	
	
	$file_path = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']).'/';
	
//Login with ussername and passwordget_offers_upcomming
 if(isset($_GET['postUserLogin']))
{
	if($_POST['phone']!=="" and $_POST['password']!=="")
	{
			
		$email_id = $_POST['phone'];
		//$password = encryptIt($_POST['password']);
		$password = $_POST['password'];
		//echo $password;die;
	  
	    $qry = "SELECT * FROM tbl_users WHERE phone = '".$email_id."' and password = '".$password."' and status = 1 "; 
		$result = mysqli_query($mysqli,$qry);
		$num_rows = mysqli_num_rows($result);
		$row = mysqli_fetch_assoc($result);
		
    	if ($num_rows > 0)
		{ 
		    if($row['ban'] == 1)
		    {
		     	$set['JSON_DATA'][]=array('msg' =>'You are Banned!! Sorry','success'=>'0');
		    }else
		    {
		        
		    
		    
		    
			$set['JSON_DATA'][]	= array(  
						  'msg'			=>	'successfully Logged in',
						  'success'=>'1',
						  'id' 	=>	$row['id'],
 						  'name'	=>	$row['name'],
 						  'email'	=>	$row['email'],
 						   'password'	=>	$row['password'],
 						  'image'	=>	$row['image'],
 						  'phone'	=>	$row['phone'],
 						  'wallet'	=>	$row['wallet'],
 						  'code'	=>	$row['code'],
 						   'refferal_code'	=>	$row['refferal_code'],
 						  'network'	=>	$row['network'],
 						  'ban'	=>	$row1['ban'],
 						  'status'	=>	$row['status']
	     								
	     								);
		    }
		}		 
		else
		{
			$set['JSON_DATA'][]=array('msg' =>'Invalid mobile number and password','success'=>'0');
 	
		}
	}

			header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
}

//users update profile
else if(isset($_GET['postUserProfileUpdate']))
{

		$sql = "SELECT * FROM tbl_users where id = '".$_POST['id']."' ";
		$res = mysqli_query($mysqli,$sql);
		$row = mysqli_fetch_assoc($res);
		
		if($_FILES['image']['name'] != "")
		{	
			if($row['image'] !== "") 
			{
			 	unlink('images/'.$row['image']); 
			 	unlink('images/thumbs/'.$row['image']); 
			}

			$facility_image=rand(0,99999)."_".$_FILES['image']['name'];
		   //Main Image
		   	$tpath1='images/'.$facility_image; 		
			$pic1=compress_image($_FILES["image"]["tmp_name"], $tpath1, 80);
		 	$thumbpath='images/thumbs/'.$facility_image;		
	       	$thumb_pic1=create_thumb_image($tpath1,$thumbpath,'200','200');   
 		}
 		else
 		{
 			$facility_image = $row['image'] ;
 		}
			//INSERT INTO `tbl_users`(`id`, `login_type`, `name`, `name`, `image`, `password`, `phone`, `device_id`, `wallet`, `first_level_earn`, `second_level_earn`, `third_level_earn`, `four_level_earn`, `fifth_level_earn`, `six_level_earn`, `seven_level_earn`, `eight_level_earn`, `nine_level_earn`, `ten_level_earn`, `code`, `refferal_code`, `network`, `status`) 

	 
			$user_edit= "UPDATE tbl_users SET 
			name='".$_POST['name']."',
			email='".$_POST['email']."',
			phone='".$_POST['phone']."',
			password='".$_POST['password']."',
			image = '".$facility_image."'
			WHERE id = '".$_POST['id']."'";	 
   		
   		$user_res = mysqli_query($mysqli,$user_edit);	
	  	
		$set['JSON_DATA'][]=array('msg'=>'Updated','success'=>'1');
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
}



//get all settings
else if(isset($_GET['settings']))
 	{
  		 $jsonObj4= array();	

		$query="SELECT * FROM tbl_settings WHERE id='1'";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());


		while($data = mysqli_fetch_assoc($sql))
		{
			 
			$row['app_name'] = $data['app_name'];
			$row['app_logo'] = $data['app_logo'];
			$row['app_version'] = $data['app_version'];
			$row['app_author'] = $data['app_author'];
			$row['app_contact'] = $data['app_contact'];
			$row['app_email'] = $data['app_email'];
			$row['app_website'] = $data['app_website'];
			$row['app_description'] = $data['app_description'];
			$row['app_developed_by'] = $data['app_developed_by'];

			$row['app_privacy_policy'] = stripslashes($data['app_privacy_policy']);
 			
 			$row['publisher_id'] = $data['publisher_id'];
 			$row['interstital_ad'] = $data['interstital_ad'];
			$row['interstital_ad_id'] = $data['interstital_ad_id'];
 			$row['banner_ad'] = $data['banner_ad'];
 			$row['banner_ad_id'] = $data['banner_ad_id'];
			$row['rewarded_ad_id'] = $data['rewarded_ad_id'];
			$row['how_to_play'] = stripslashes($data['how_to_play']);
			$row['about_us'] = stripslashes($data['about_us']);

			array_push($jsonObj4,$row); 
			}
		
		$set['JSON_DATA'] = $jsonObj4;	

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }

else if(isset($_GET['postUserRegister']))
 	{

		$email_id = $_POST['email'];

		$qry = "SELECT * FROM tbl_users WHERE email = '".$email_id."' "; 
		$result = mysqli_query($mysqli,$qry);
		$num_rows = mysqli_num_rows($result);
		$row = mysqli_fetch_assoc($result);
		
    	if ($num_rows > 0)
		{
			$set['JSON_DATA'][]=array('msg' => "Email address already used!",'success'=>'0');
		}
		else
		{ 

        if($_FILES['image']['name'] != "")
		{
              $category_image=rand(0,99999)."_".$_FILES['image']['name'];
       
       //Main Image
     $tpath1='images/'.$category_image;        
       $pic1=compress_image($_FILES["image"]["tmp_name"], $tpath1, 80);
   
    //Thumb Image 
     $thumbpath='images/thumbs/'.$category_image;   
       $thumb_pic1=create_thumb_image($tpath1,$thumbpath,'300','300');   
		}else{
		    $image='';
		}
       
			$refferal_code = $_POST['refferal_code'];
			
		    $rand=rand(1000,9999);

		     $qry1="INSERT INTO tbl_users 
				  (`login_type`,
				  `name`,
				  `email`,
				   `image`,
				   password,
				   `phone`,
				    `device_id`,
				   `wallet`,
				    `code`,
				    `refferal_code`,
				  `status`
				) VALUES (
					'1',
					'".trim($_POST['name'])."',
					'".trim($_POST['email'])."',
					'".$image."',
					'".trim($_POST['password'])."',
					'".trim($_POST['phone'])."',
					'',
					'0',						
					'$rand',
					'".$_POST['refferal_code']."',
					'0'
				)"; 
            
            $result1 = mysqli_query($mysqli,$qry1);
            $last_id = mysqli_insert_id($mysqli);  
            
            $qrys = "SELECT * FROM tbl_users WHERE id = '".$last_id."'"; 
			$results = mysqli_query($mysqli,$qrys);
			$row = mysqli_fetch_assoc($results);
			$firstid=$row['id'];
												 
			$set['JSON_DATA'][]	=	array(    
                           'msg' 	=>	"succecesfull login",
 						  'login_type' 	=>	$row['login_type'],
 						  'id' 	=>	$row['id'],
 						  'name'	=>	$row['name'],
 						  'email'	=>	$row['email'],
 						   'password'	=>	$row['password'],
 						  'image'	=>	$row['image'],
 						  'phone'	=>	$row['phone'],
 						  'wallet'	=>	$row['wallet'],
 						  'code'	=>	$row['code'],
 						   'refferal_code'	=>	$row['refferal_code'],
 						  'network'	=>	$row['network'],
 						  'status'	=>	$row['status']
		     								);
		    		 
				
			if($refferal_code!="")
		    {
			   $qry = "SELECT * FROM tbl_users WHERE code = '".$refferal_code."'";	 
	    		$result = mysqli_query($mysqli,$qry);
	    		$num_rows = mysqli_num_rows($result);
	    		$row = mysqli_fetch_assoc($result);
	 	    	$r2 = $row['refferal_code'];
	 	    	$c2 = $row['id'];

		        if ($num_rows > 0)
	    		{
	    		     $id=$row['id'];

	    		     $wallet=$row['wallet'];
	    		     $first1=2;   
	    		     $newwallet=$wallet+$first1 ;
	    		     

	    		     $network=$row['network'];
	    		     $newnetwork=$network+1; 
				
				 $network_qry="INSERT INTO tbl_network (`user_id`,`level`,`money`,`refferal_user_id`,`status`) 
				VALUES ('$id','1','$first1','$r2','1')"; 

					$result1=mysqli_query($mysqli,$network_qry); 
	            

	                $first_level= "UPDATE tbl_users SET wallet='".$newwallet."' WHERE id = '".$id."'";	 
	    		    $first_level_earn1 = mysqli_query($mysqli,$first_level);
	    		    
		                  $id=$row['id'];
	    		      $date = date("M-d-Y h:i:s");  
	    		      
	    		     
	    		     	$qry1="INSERT INTO tbl_transaction (`user_id`,`type`,`type_no`,`date`,`money`) 
				VALUES ('$id',2,'$r2','$date','$first1')"; 
	            
	            $result1=mysqli_query($mysqli,$qry1);  		

		
	    		}
	    		

	    	}


		
	
}
	header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
	}

else if(isset($_GET['postuserstatus']))
 	{
 			$email_id = $_POST['email'];
		
		$qry = "SELECT * FROM tbl_users WHERE email = '".$email_id."'"; 
		$result = mysqli_query($mysqli,$qry);
		$num_rows = mysqli_num_rows($result);
		$row = mysqli_fetch_assoc($result);
		
    	if ($num_rows > 0)
		{
			$set['JSON_DATA'][]	=	array( 
 						  
 			'user_status'		=>'1',	  
 			'id' => $row['id'],
	        'login_type'=>$row['login_type'],
	        'name'=>$row['name'],
	        'email'=>$row['email'],
	        'image'=>$row['image'],
	        'phone'=>$row['phone'],
	        'device_id'=>$row['device_id'],
	        'wallet'=>$row['wallet'],
	         'network'=>$row['network'],
            'ban'	=>	$row['ban'],
	        'code'=>$row['code'],	       
	        'status'=>$row['status'],
		     								
		     								
		     								
		   );
		}		 
		else
		{
		    	$set['JSON_DATA'][]	=	array( 
		     								  'user_status'	=>'0'
		     								);
		    		 
        }
 			header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 	}

 	else if(isset($_GET['getUserProfile']))
{

		$qry = "SELECT * FROM tbl_users WHERE id = '".$_POST['id']."'"; 
		$result = mysqli_query($mysqli,$qry);
		 
		$row = mysqli_fetch_assoc($result);
	  			
	    $set['JSON_DATA'][]	=	array(
	    	'id' => $row['id'],
	        'login_type'=>$row['login_type'],
	        'name'=>$row['name'],
	        'email'=>$row['email'],
	         'password'=>$row['password'],
	        'image'=>$row['image'],
	        'phone'=>$row['phone'],
	        'device_id'=>$row['device_id'],
	        'wallet'=>$row['wallet'],
	         'network'=>$row['network'],
	    'ban'	=>	$row['ban'],
	        'code'=>$row['code'],	       
	        'status'=>$row['status'],
	    							);

	   	header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
}	

//user wallet update
 	else if(isset($_GET['postUserwalletUpdate']))
{
        	if($_POST['user_id']!="")
			{
			    $qry = "SELECT * FROM tbl_users WHERE id = '".$_POST['user_id']."'";	 
				$result = mysqli_query($mysqli,$qry);
				$num_rows = mysqli_num_rows($result);
				$row = mysqli_fetch_assoc($result);
		
		        if ($num_rows > 0)
				{
				    
				    
			         $id=$row['id'];
	    		     $wallet=$row['wallet'];
	    		     $update_amount=$_POST['wallet'];
	    		     $newwallet=$wallet+$update_amount;   
			    
				     $user_edit= "UPDATE tbl_users SET wallet='".$newwallet."' WHERE id = '".$id."'";	 
				     $user_res = mysqli_query($mysqli,$user_edit);	
				    
				     date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
                     $date = date('Y-m-d H:i:s');

				     
				     $qry2 ="INSERT INTO tbl_wallet_passbook ( `wp_user`, `wp_package_id`, `wp_order_id`, `wp_date`, `wp_status`)
			VALUES ('".$_POST['user_id']."','".$_POST['package_id']."','".$_POST['order_id']."','".$date."',1 )"; 
            
            $result2 = mysqli_query($mysqli,$qry2);  		
				     
				     $set['JSON_DATA'][]=array('msg'=>'Updated','success'=>'1');

				}
			}
    	header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
}

// else if(isset($_GET['postUsertransactionadd']))
//  	{
//  		if($_POST['user_id']!="" and $_POST['type']!="" and $_POST['date']!="" and $_POST['money']!="")
// 		{ 
  
// 			$qry1="INSERT INTO tbl_transaction (`user_id`,`type`,`date`,`money`) 
// 			VALUES ('".$_POST['user_id']."','".$_POST['type']."','".$_POST['date']."','".$_POST['money']."')"; 
            
//             $result1=mysqli_query($mysqli,$qry1);  									 
					 
				
// 			$set['JSON_DATA'][]=array('msg' => "transaction add successflly...!",'success'=>'1');
					
// 		}

//  		header( 'Content-Type: application/json; charset=utf-8' );
// 	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
// 		die();
//  	}

//get all offers
else if(isset($_GET['get_offers']))
 	{
  		 $jsonObj4= array();	

		$query="SELECT * FROM tbl_offers WHERE o_status='1' ";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());

//INSERT INTO `tbl_offers`(`o_id`, `o_name`, `o_image`, `o_desc`, `o_time`, `o_status`) 
		while($data = mysqli_fetch_assoc($sql))
		{
			 
			$row['o_id'] = $data['o_id'];
			$row['o_name'] = $data['o_name'];
			$row['o_image'] =  $file_path.'images/'.$data['o_image'];
			$row['o_desc'] = $data['o_desc'];
			$row['o_date'] = $data['o_date'];
			$row['o_edate'] = $data['o_edate'];
			$row['o_stime'] = $data['o_stime'];
			$row['o_etime'] = $data['o_etime'];
			$row['o_amount'] = $data['o_amount'];
			$row['o_type'] = $data['o_type'];
			$row['o_min'] = $data['o_min'];
			$row['o_max'] = $data['o_max'];
			$row['o_price'] = $data['o_price'];
			$row['o_status'] = $data['o_status'];
		
			
			array_push($jsonObj4,$row); 
			}
		
		$set['JSON_DATA'] = $jsonObj4;	

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }


//get all offers
else if(isset($_GET['get_coin_list']))
 	{
  		 $jsonObj4= array();	

		$query="SELECT * FROM `tbl_coin_list` ORDER BY `tbl_coin_list`.`c_id` ASC ";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());

		while($data = mysqli_fetch_assoc($sql))
		{
			 
			$row['c_id'] = $data['c_id'];
			$row['c_name'] = $data['c_name'];
			$row['c_coin'] = $data['c_coin'];
			$row['c_amount'] = $data['c_amount'];
			$row['c_status'] = $data['c_status'];
			
			array_push($jsonObj4,$row); 
			}
		
		$set['JSON_DATA'] = $jsonObj4;	

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }


//get all offers
else if(isset($_GET['get_wallet_passbook']))
 	{
  		 $jsonObj4= array();	

		$query="SELECT * FROM tbl_wallet_passbook 
		Left join tbl_coin_list on tbl_coin_list.c_id= tbl_wallet_passbook.wp_package_id
		WHERE wp_user = '".$_POST['user_id']."'";	 
		
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());


		while($data = mysqli_fetch_assoc($sql))
		{
			 
			$row['wp_id'] = $data['wp_id'];
			$row['wp_user'] = $data['wp_user'];
			$row['wp_package_id'] = $data['wp_package_id'];
			
				$row['c_name'] = $data['c_name'];
					$row['c_coin'] = $data['c_coin'];
						$row['c_amount'] = $data['c_amount'];
			
			$row['wp_order_id'] = $data['wp_order_id'];
			$row['wp_date'] = $data['wp_date'];
			$row['wp_status'] = $data['wp_status'];

			
			array_push($jsonObj4,$row); 
			}
		
		$set['JSON_DATA'] = $jsonObj4;	

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }




    //add bid
    else if(isset($_GET['add_bid']))
 	{

			date_default_timezone_set("Asia/Calcutta"); //India time (GMT+5:30)
			$date = date('Y-m-d');

            $qry1 = "SELECT * FROM tbl_offers WHERE o_id = '".$_POST['o_id']."'"; 
        	$result1 = mysqli_query($mysqli,$qry1);
        	$num_rows1 = mysqli_num_rows($result1);
	        $row1 = mysqli_fetch_assoc($result1);


            $o_date = $row1['o_date'];
		    $o_stime = $row1['o_stime'];
		    
		    $o_edate = $row1['o_edate'];
		    $o_etime =  $row1['o_etime'];
		    
		    date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
            $datetime = date('Y-m-d H:i:s');

		    $start = $o_date." ".$o_stime;
		    $end = $o_edate." ".$o_etime;

			if( $start <= $datetime  && $end >= $datetime )
			{
			    
			
			    $qry = "SELECT * FROM tbl_users WHERE id = '".$_POST['u_id']."'"; 
	        	$result = mysqli_query($mysqli,$qry);
	        	$num_rows = mysqli_num_rows($result);
	        	$row = mysqli_fetch_assoc($result);
        		       
		         $id=$row['id'];
    		     $wallet=$row['wallet'];
    		     $update_amount=$_POST['bd_amount'];
        		
        		if ($num_rows > 0 and $wallet >= $update_amount )
        		{
            		       
                		    $newwallet=$wallet-$update_amount;   
            		    
            			 	$user_edit= "UPDATE tbl_users SET wallet='".$newwallet."' WHERE id = '".$id."'";	
            			    $result1=mysqli_query($mysqli,$user_edit);  
            			  
                	        $qry1="INSERT INTO tbl_bid 
                					(`u_id`,
                					`o_id`,
                					`bd_value`,
                					`bd_amount`,
                					`bd_date`,
                					bd_status
                				 
                				) VALUES (
                					'".trim($_POST['u_id'])."',
                					'".trim($_POST['o_id'])."',
                					'".$_POST['bd_value']."',
                					'".$_POST['bd_amount']."',
                					'$date',
                					1
                				)"; 
                            
                            $result1=mysqli_query($mysqli,$qry1);  	


		$qry11="INSERT INTO tbl_transaction (`user_id`,`type`,`type_no`,`date`,`money`) 
				VALUES ('".trim($_POST['u_id'])."',1,'".trim($_POST['o_id'])."','$date','".$_POST['bd_amount']."')"; 
	            
	            $result11=mysqli_query($mysqli,$qry11);  		
 		
		                
		    			$set['JSON_DATA'][]=array('msg' => "Your Bid has been Submitted",'success'=>'1');
                		        
    		     }else
    		     {
    		         	$set['JSON_DATA'][]=array('msg' => "some thing went wrong ...!",'success'=>'0');
    		     }
			} else
    		     {
    		         	$set['JSON_DATA'][]=array('msg' => "some thing went wrong ...!",'success'=>'0');
    		     }
		      

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die(); 
	}


    //add bid
    else if(isset($_GET['add_bid_multi']))
 	{

			date_default_timezone_set("Asia/Calcutta"); //India time (GMT+5:30)
			$date = date('Y-m-d');

  if($_POST['add_bid_multi'] != "")
    {

	   $someJSON = $_POST['add_bid_multi'] ;

    $someArray1 = json_decode($someJSON, true);


    for ($x = 0; $x <= count($someArray1)-1 ; $x++) 
    {
	
	
			    $qry = "SELECT * FROM tbl_users WHERE id = '".$someArray1[$x]["u_id"]."'"; 
	        	$result = mysqli_query($mysqli,$qry);
	        	$num_rows = mysqli_num_rows($result);
	        	$row = mysqli_fetch_assoc($result);
        		       
		         $id=$row['id'];
    		     $wallet=$row['wallet'];
    		     $update_amount=$someArray1[$x]["bd_amount"] ;
        		
        		if ($num_rows > 0 and $wallet >= $update_amount )
        		{
            		       
                		    $newwallet=$wallet-$update_amount;   
            		    
            			 	$user_edit= "UPDATE tbl_users SET wallet='".$newwallet."' WHERE id = '".$id."'";	
            			    $result1=mysqli_query($mysqli,$user_edit);  
            			  
                	        $qry1="INSERT INTO tbl_bid 
                					(`u_id`,
                					`o_id`,
                					`bd_value`,
                					`bd_amount`,
                					`bd_date`,
                					bd_status
                				 
                				) VALUES (
                					'".trim($someArray1[$x]["u_id"])."',
                					'".trim($someArray1[$x]["o_id"])."',
                					'".$someArray1[$x]["bd_value"]."',
                					'".$someArray1[$x]["bd_amount"]."',
                					'$date',
                					1
                				)"; 
                            
                            $result1=mysqli_query($mysqli,$qry1);  	

 		
 		
 			$qry11="INSERT INTO tbl_transaction (`user_id`,`type`,`type_no`,`date`,`money`) 
				VALUES ('".$someArray1[$x]["u_id"]."',1,'".$someArray1[$x]["o_id"]."','$date','".$someArray1[$x]["bd_amount"]."')"; 
	            
	            $result11=mysqli_query($mysqli,$qry11);  
	            
		                
		    			$set['JSON_DATA'][]=array('msg' => "Your Bid has been Submitted",'success'=>'1');
        		}		        
     }
    }
     else
     {
         	$set['JSON_DATA'][]=array('msg' => "some thing went wrong 1...!",'success'=>'0');
     }
         

		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die(); 
	}

	else if(isset($_GET['get_bid']))
 	{
 		 $jsonObj4= array();

		$qry = "SELECT *,MIN(bd_value) as mins  FROM tbl_bid WHERE o_id = '".$_POST['o_id']."'"; 
		$result = mysqli_query($mysqli,$qry);
		// $row = mysqli_fetch_assoc($result);
		while($data = mysqli_fetch_assoc($result))
		{
			echo $data['mins'];
				$qry1 = "SELECT * FROM tbl_bid WHERE o_id = '".$_POST['o_id']."'"; 
		$result1 = mysqli_query($mysqli,$qry1);
		// $row = mysqli_fetch_assoc($result);
		while($data1 = mysqli_fetch_assoc($result1))
		{
			$input = array($data1['u_id']);

			$result = array_unique($input);
			var_dump($result);
		
			
		}
			array_push($jsonObj4,$row); 
		}
		
		$set['JSON_DATA'] = $jsonObj4;	

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
}


//get all offers
else if(isset($_GET['get_bid_user']))
 	{
  		 $jsonObj4= array();	

		$query="SELECT * FROM tbl_bid 
		left join tbl_users on tbl_users.id= tbl_bid.u_id
		left join tbl_offers on tbl_offers.o_id = tbl_bid.o_id
		WHERE u_id='".$_POST['u_id']."' ";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());


		while($data = mysqli_fetch_assoc($sql))
		{
			 
			$row['bd_id'] = $data['bd_id'];
			$row['u_id'] = $data['u_id'];
			$row['name'] = $data['name'];
			$row['o_id'] = $data['o_id'];
			$row['o_name'] = $data['o_name'];
			$row['o_image'] = $file_path.'images/'.$data['o_image'];
			$row['o_type'] = $data['o_type'];
			$row['o_min'] = $data['o_min'];
			$row['o_max'] = $data['o_max'];
			$row['o_price'] = $data['o_price'];
			$row['bd_value'] = $data['bd_value'];
			$row['bd_amount'] = $data['bd_amount'];
			$row['bd_date'] = $data['bd_date'];
			$row['bd_status'] = $data['bd_status'];
		
			
			array_push($jsonObj4,$row); 
			}
		
		$set['JSON_DATA'] = $jsonObj4;	

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }

//get all transaction
 else if(isset($_GET['get_transaction']))
 	{
  	   $jsonObj= array();	

		$query="SELECT * FROM tbl_transaction
		WHERE user_id = '".$_POST['user_id']."' ORDER BY `tbl_transaction`.`id` DESC"; 
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());


		while($data = mysqli_fetch_assoc($sql))
		{
    
 				$row['id'] = $data['id'];

 			    if($data['type'] == 1)
 			    {
 			        $qry1 = "SELECT * FROM tbl_offers WHERE o_id = '".$data['type_no']."'"; 
            		$result1 = mysqli_query($mysqli,$qry1);
            		$row1 = mysqli_fetch_assoc($result1);
            		
            		$row['type_name'] = "Bid Placed" ;
            		$row['type_details'] = $row1['o_name'] ;
            		$row['type_image'] = $row1['o_image'] ;
            	
 			    }
 			    
 			     if($data['type'] == 2)
 			    {
 			        $qry2 = "SELECT * FROM tbl_users WHERE id = '".$data['type_no']."'"; 
            		$result2 = mysqli_query($mysqli,$qry2);
            		$row2 = mysqli_fetch_assoc($result2);
            		
            		$row['type_name'] = "Refer Earning" ;
            		$row['type_details'] = $row2['name'] ;
            	    	$row['type_image'] = $row1['image'] ;
 			    }
 			    
 				$row['date'] = $data['date'];
 				$row['money'] = $data['money'];
		   
		   array_push($jsonObj,$row);
		
		}
 
		$set['JSON_DATA'] = $jsonObj;

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }
//get all offers live
else if(isset($_GET['get_offers_live']))
 	{
  		 $jsonObj4= array();	
  		
  		date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
		 $time = date('H:i:s');

		 $date1 = date('Y-m-d');
		
		

		 $query="SELECT * FROM tbl_offers WHERE ( o_date <= '".$date1."' and o_edate >= '".$date1."' ) and o_status = 1 ORDER BY `tbl_offers`.`o_id` ASC";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());


		while($data = mysqli_fetch_assoc($sql))
		{
		    $o_date = $data['o_date'];
		    $o_stime = $data['o_stime'];
		    
		    $o_edate = $data['o_edate'];
		    $o_etime =  $data['o_etime'];
		    //echo $datetime ;
		      date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        $datetime = date('Y-m-d H:i:s');

		    $start = $o_date." ".$o_stime;
		    $end = $o_edate." ".$o_etime;
		//    exit();
			if( $start <= $datetime  && $end >= $datetime )
			 {
			 

			 
			 	  $query111="SELECT *, COUNT(*)
 as num2 FROM tbl_bid 
 where tbl_bid.o_id='".$data['o_id']."' ";

		    $result111 = mysqli_query($mysqli,$query111)or die(mysqli_error());

		   $row111=mysqli_fetch_assoc($result111);
    

			if($row111['num2']== null)
			{
		             $row['num2'] = "0";
		          
			}else
			{	
			  	    $row['total_bids'] = 	$row111['num2'];
			     
			}             
			     
			$row['o_id'] = $data['o_id'];
			$row['o_name'] = $data['o_name'];
			$row['o_image'] =  $file_path.'images/'.$data['o_image'];
			$row['o_desc'] = $data['o_desc'];
			$row['o_date'] = $data['o_date'];
			$row['o_edate'] = $data['o_edate'];
			$row['o_stime'] = $data['o_stime'];
			$row['o_etime'] = $data['o_etime'];
			$row['o_amount'] = $data['o_amount'];
				$row['o_type'] = $data['o_type'];
			$row['o_min'] = $data['o_min'];
			$row['o_max'] = $data['o_max'];
			$row['o_price'] = $data['o_price'];
			$row['o_status'] = $data['o_status'];
		
		array_push($jsonObj4,$row); 
		
			 }
		
			
			
			}
		
		$set['JSON_DATA'] = $jsonObj4;	

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }

//get all offers upcomming
else if(isset($_GET['get_offers_upcomming']))
 	{
  		 $jsonObj4= array();	
  		
  		date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
		 $time = date('H:i:s');

		 $date1 = date('Y-m-d');
		 
		 
		  date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        $datetime = date('Y-m-d H:i:s');
		

		 $query="SELECT * FROM tbl_offers WHERE  o_date >= '".$date1."' and o_status = 1 ORDER BY `tbl_offers`.`o_id` ASC";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());


		while($data = mysqli_fetch_assoc($sql))
		{
		    $o_date = $data['o_date'];
		    $o_stime = $data['o_stime'];
		    
		    $o_edate = $data['o_edate'];
		    $o_etime =  $data['o_etime'];

		    $start = $o_date." ".$o_stime;
		    $end = $o_edate." ".$o_etime;
		//    exit();
			if( $start >= $datetime )
			 {
			 

			 
			 	  $query111="SELECT *, COUNT(*)
 as num2 FROM tbl_bid 
 where tbl_bid.o_id='".$data['o_id']."' ";

		    $result111 = mysqli_query($mysqli,$query111)or die(mysqli_error());

		   $row111=mysqli_fetch_assoc($result111);
    

			if($row111['num2']== null)
			{
		             $row['num2'] = "0";
		          
			}else
			{	
			  	    $row['total_bids'] = 	$row111['num2'];
			     
			}             
			     
			$row['o_id'] = $data['o_id'];
			$row['o_name'] = $data['o_name'];
			$row['o_image'] =  $file_path.'images/'.$data['o_image'];
			$row['o_desc'] = $data['o_desc'];
			$row['o_date'] = $data['o_date'];
			$row['o_edate'] = $data['o_edate'];
			$row['o_stime'] = $data['o_stime'];
			$row['o_etime'] = $data['o_etime'];
			$row['o_amount'] = $data['o_amount'];
				$row['o_type'] = $data['o_type'];
			$row['o_min'] = $data['o_min'];
			$row['o_max'] = $data['o_max'];
			$row['o_price'] = $data['o_price'];
			$row['o_status'] = $data['o_status'];
		
		array_push($jsonObj4,$row); 
		
			 }
		
			
			
			}
		
		$set['JSON_DATA'] = $jsonObj4;	

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }
 
 //get all raffles winners
 else if(isset($_GET['get_raffle_winners']))
 {
	   $jsonObj4= array();	

	$query="SELECT * FROM tbl_raffle WHERE w_status='1' ";
	$sql = mysqli_query($mysqli,$query)or die(mysqli_error());

	while($data = mysqli_fetch_assoc($sql))
	{
		 
		$row['w_id'] = $data['w_id'];
		$row['w_oname'] = $data['w_oname'];
		$row['w_image'] =  $file_path.'images/'.$data['w_image'];
		$row['w_name'] = $data['w_name'];
		$row['w_ticket'] = $data['w_ticket'];
		$row['w_time'] = $data['w_time'];
		$row['w_status'] = $data['w_status'];
	
		
		array_push($jsonObj4,$row); 
		}
	
	$set['JSON_DATA'] = $jsonObj4;	

	
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
	die();
}


  //get all offers winner
else if(isset($_GET['get_offers_winner']))
 	{
  		 $jsonObj4= array();	
  		
	date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
		 $time = date('H:i:s');

		 $date1 = date('Y-m-d');
		 
		 
		  date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
        $datetime = date('Y-m-d H:i:s');
		

	 $query="SELECT * FROM tbl_offers WHERE o_edate <= '".$date1."' and o_status = 1 ORDER BY `tbl_offers`.`o_edate` DESC";
	 
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());

		while($data = mysqli_fetch_assoc($sql))
		{
				    $o_date = $data['o_date'];
		    $o_stime = $data['o_stime'];
		    
		    $o_edate = $data['o_edate'];
		    $o_etime =  $data['o_etime'];

		    $start = $o_date." ".$o_stime;
		    $end = $o_edate." ".$o_etime;
		
			if( $end <= $datetime )
		  {

		    $query1="SELECT *, COUNT(*)
 as num1 FROM tbl_bid 
 left join tbl_users on tbl_users.id = tbl_bid.u_id
 where tbl_bid.o_id='".$data['o_id']."'
 GROUP BY bd_value having num1 = 1  ORDER BY `tbl_bid`.`bd_value` ASC LIMIT 0,1 ";

		    $result1 = mysqli_query($mysqli,$query1)or die(mysqli_error());

		   $row1=mysqli_fetch_assoc($result1);
    

			if($row1['u_id']== 0)
			{
		             $row['lu_id'] = "0";
		             $row['lname'] = "";
		               $row['limage'] = "";
		             $row['lbd_value'] = ""; 
		             $row['lbd_amount'] = "0";
		             $row['l_won'] = 	"0";
			}else
			{	
			  	    $row['lu_id'] = 	$row1['u_id'];
			        $row['lname'] = 	$row1['name'];
			           $row['limage'] = 	$row1['image'];
			        $row['lbd_value'] = 	$row1['bd_value'];
			        $row['lbd_amount'] = 	$row1['bd_amount'];
			        
			      if( $row1['u_id'] == $_POST['u_id'] )
			        {
			             $row['l_won'] = 	"1";
			        }else
			        {
			             $row['l_won'] = 	"0";
			        }
			        
			}    
		      
		 		    $query11="SELECT *, COUNT(*)
 as num1 FROM tbl_bid 
 left join tbl_users on tbl_users.id = tbl_bid.u_id
 where tbl_bid.o_id='".$data['o_id']."'
 GROUP BY bd_value having num1 = 1  ORDER BY `tbl_bid`.`bd_value` DESC LIMIT 0,1 ";

		    $result11 = mysqli_query($mysqli,$query11)or die(mysqli_error());

		   $row11=mysqli_fetch_assoc($result11);
    

			if($row11['u_id']== 0)
			{
		             $row['hu_id'] = "0";
		             $row['hname'] = "";
		               $row['himage'] = 	"";
		             $row['hbd_value'] = ""; 
		             $row['hbd_amount'] = "0";
		              $row['h_won'] = 	"0";
			}else
			{	
			  	    $row['hu_id'] = 	$row11['u_id'];
			        $row['hname'] = 	$row11['name'];
			           $row['himage'] = 	$row11['image'];
			        $row['hbd_value'] = 	$row11['bd_value'];
			        $row['hbd_amount'] = 	$row11['bd_amount'];
			        
			          if( $row11['u_id'] == $_POST['u_id'] )
			        {
			             $row['h_won'] = 	"1";
			        }else
			        {
			            $row['h_won'] = 	"0";
			        }
			        
			}  
			//raffle
			$query15="SELECT *, COUNT(*) FROM tbl_bid 
 left join tbl_users on tbl_users.id = tbl_bid.u_id
 where tbl_bid.o_id='".$data['o_id']."'
 GROUP BY bd_value ORDER BY `tbl_bid`.`bd_value` DESC LIMIT 0,1 ";

		    $result15 = mysqli_query($mysqli,$query11)or die(mysqli_error());

		   $row15=mysqli_fetch_assoc($result11);
    

			if($row15['u_id']== 0)
			{
		             $row['ru_id'] = "0";
		             $row['rname'] = "Winner will be Announced Soon..";
		               $row['rimage'] = 	"";
		             $row['rbd_value'] = ""; 
		             $row['rbd_amount'] = "0";
		              $row['r_won'] = 	"0";
			}else
			{	
			  	    $row['ru_id'] = 	$row11['u_id'];
			        $row['rname'] = 	$row11['name'];
			           $row['rimage'] = 	$row11['image'];
			        $row['rbd_value'] = 	$row11['bd_value'];
			        $row['rbd_amount'] = 	$row11['bd_amount'];
			        
			          if( $row15['u_id'] == $_POST['u_id'] )
			        {
			             $row['r_won'] = 	"1";
			        }else
			        {
			            $row['r_won'] = 	"0";
			        }
			        
			}  
			
			
			$query12="SELECT COUNT(*) as num1 , SUM(bd_amount) as bd_amount1 FROM tbl_bid 
             where tbl_bid.o_id='".$data['o_id']."' and tbl_bid.u_id='".$_POST['u_id']."' ";

		    $result12 = mysqli_query($mysqli,$query12)or die(mysqli_error());

		   $row12=mysqli_fetch_assoc($result12);
    

			if($row12['num1']== 0)
			{
		             $row['total_bids'] = "0";
		              $row['total_amount'] = "0";
		          
			}else
			{	
			  	    $row['total_bids'] = 	$row12['num1'];
			  	     $row['total_amount'] = 	$row12['bd_amount1'];
			     
			}        
			
			
			
		    	$rate4="SELECT * FROM tbl_order
            where tbl_order.offer_id='".$data['o_id']."' ";

	   $rateresult4 = mysqli_query($mysqli,$rate4)or die(mysqli_error());
	   	$num_rows4 = mysqli_num_rows($rateresult4);

		    if($num_rows4 > 0)
		    {
		        	$row['o_click'] = 1;
		    }else
		    {
		        	$row['o_click'] = 0;
		    }
		
			$row['o_id'] = $data['o_id'];
			$row['o_name'] = $data['o_name'];
			$row['o_image'] =  $file_path.'images/'.$data['o_image'];
			$row['o_desc'] = $data['o_desc'];
			$row['o_date'] = $data['o_date'];
			$row['o_edate'] = $data['o_edate'];
			$row['o_stime'] = $data['o_stime'];
			$row['o_etime'] = $data['o_etime'];
			$row['o_amount'] = $data['o_amount'];
				$row['o_type'] = $data['o_type'];
			$row['o_min'] = $data['o_min'];
			$row['o_max'] = $data['o_max'];
			$row['o_price'] = $data['o_price'];
			$row['o_status'] = $data['o_status'];
			
		array_push($jsonObj4,$row); 
			
			}
		
		    
		}
		
		$set['JSON_DATA'] = $jsonObj4;	

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }
 
 //get all offers
else if(isset($_GET['get_offers_id']))
 	{
  		 $jsonObj4= array();	

		$query="SELECT * FROM tbl_offers WHERE o_status='1' and o_id='".$_POST['o_id']."' ";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());

		while($data = mysqli_fetch_assoc($sql))
		{
		      $query11="SELECT *, COUNT(*)
 as num1 , COUNT(tbl_bid.u_id) AS total_user FROM tbl_bid 
 where tbl_bid.o_id='".$data['o_id']."' 
 GROUP BY tbl_bid.u_id";

		    $result11 = mysqli_query($mysqli,$query11)or die(mysqli_error());

		   $row11=mysqli_fetch_assoc($result11);
    

			if($row11['num1']== null)
			{
		             $row['total_bids'] = "0";
		          
			}else
			{	
			  	    $row['total_bids'] = 	$row11['num1'];
			     
			}  
			
					      $query111="SELECT COUNT(DISTINCT tbl_bid.u_id) as total_users FROM tbl_bid 
 where tbl_bid.o_id='".$data['o_id']."' ";

		    $result111 = mysqli_query($mysqli,$query111)or die(mysqli_error());

		   $row111=mysqli_fetch_assoc($result111);
    

			if($row111['total_users']== null)
			{
		             $row['total_users'] = "0";
		          
			}else
			{	
			  	    $row['total_users'] = 	$row111['total_users'];
			     
			} 
			
			$response = array();   
			  
			$rate2="SELECT *,SUM(tbl_bid.bd_amount) as amount FROM tbl_bid 
			left join tbl_users on tbl_users.id= tbl_bid.u_id
	    	left join tbl_offers on tbl_offers.o_id = tbl_bid.o_id
             where tbl_bid.o_id='".$data['o_id']."'
             GROUP BY tbl_bid.u_id
             ORDER BY `amount` DESC";

		   $rateresult2 = mysqli_query($mysqli,$rate2)or die(mysqli_error());
	   
    	   while($data1 = mysqli_fetch_assoc($rateresult2))
    		{
    		    	$rate22="SELECT * FROM tbl_bid 
             where tbl_bid.o_id='".$data1['o_id']."' and tbl_bid.u_id='".$data1['u_id']."' ";

		   $rateresult22 = mysqli_query($mysqli,$rate22)or die(mysqli_error());
		   	   	$num_rows = mysqli_num_rows($rateresult22);
		      
		   
                        $row1['bd_id'] = $data1['bd_id'];
                        $row1['u_id'] = $data1['u_id'];
                        $row1['name'] = $data1['name'];
                        $row1['image'] = $data1['image'];
                        $row1['o_id'] = $data1['o_id'];
	 	            	$row1['bd_value'] = $data1['bd_value'];   
                        $row1['bd_amount'] = $data1['amount'];
	 	            	$row1['bd_date'] = $data1['bd_date'];           
                        $row1['Total_amount'] = $num_rows;           
    		              array_push($response, $row1);   
    		         
    		}
			 
				
			$response1 = array();   
			  
			$rate3="SELECT * FROM tbl_bid 
			left join tbl_users on tbl_users.id= tbl_bid.u_id
		left join tbl_offers on tbl_offers.o_id = tbl_bid.o_id
 where tbl_bid.o_id='".$data['o_id']."' and tbl_bid.u_id='".$_POST['u_id']."' ORDER BY `tbl_bid`.`bd_value` ASC";

		   $rateresult3 = mysqli_query($mysqli,$rate3)or die(mysqli_error());
	   
    	   while($data2 = mysqli_fetch_assoc($rateresult3))
    		{
    		    // o_typee
    		    	if($data['o_type'] == 2)
			{
    		    
    		    	$rate4="SELECT * FROM tbl_bid
 where tbl_bid.o_id='".$data2['o_id']."' and tbl_bid.bd_value='".$data2['bd_value']."' ORDER BY `tbl_bid`.`bd_value` ASC";
		   $rateresult4 = mysqli_query($mysqli,$rate4)or die(mysqli_error());
		   	$num_rows = mysqli_num_rows($rateresult4);
		   	$row4=mysqli_fetch_assoc($rateresult4);
		   	
    		    if($num_rows > 1)
    		    {
    		       
    		                   $query1="SELECT *, COUNT(*)
             as num1 FROM tbl_bid 
             where tbl_bid.o_id='".$data2['o_id']."'
             GROUP BY bd_value having num1 = 1  ORDER BY `tbl_bid`.`bd_value` ASC LIMIT 0,1 ";

	            	    $result1 = mysqli_query($mysqli,$query1)or die(mysqli_error());
                	$num_rows1 = mysqli_num_rows($result1);
		               $row1=mysqli_fetch_assoc($result1);
    		        	
        		    if($num_rows1 > 0)
        		    {
        		    
        		          if( $row4['bd_value'] == $row1['bd_value'] )
        		          {
        		                  $row2['value'] = "Unique";
        		          
        		          }else
        		          {
        		             $row2['value'] = "Not Unique";
        		          }
        		          
        		    }else{
        		          $row2['value'] = "Not Unique";
        		      
        		    }  
        		    
    		    }else{
    		        
    		                     $query1="SELECT *, COUNT(*)
             as num1 FROM tbl_bid 
             where tbl_bid.o_id='".$data2['o_id']."'
             GROUP BY bd_value having num1 = 1  ORDER BY `tbl_bid`.`bd_value` ASC LIMIT 0,1 ";

	            	    $result1 = mysqli_query($mysqli,$query1)or die(mysqli_error());
                	$num_rows1 = mysqli_num_rows($result1);
		               $row1=mysqli_fetch_assoc($result1);
    		        	
        		    if($num_rows1 > 0)
        		    {
        		         
        		            
        		            if( $row4['bd_value'] == $row1['bd_value'] )
        		          {
        		                  $row2['value'] = "Unique";
        		          
        		          }else
        		          {
        		             $row2['value'] = "Unique not lowest";
        		          }
        		           
        		    }else{
        		          $row2['value'] = "Not Unique";
        		      
        		    } 
    		        

    		    }
    		    
    		    
			}
			
			// o_typee
				    	if($data['o_type'] == 2)
			{
    		    
    		    	$rate4="SELECT * FROM tbl_bid
 where tbl_bid.o_id='".$data2['o_id']."' and tbl_bid.bd_value='".$data2['bd_value']."' ORDER BY `tbl_bid`.`bd_value` ASC";
		   $rateresult4 = mysqli_query($mysqli,$rate4)or die(mysqli_error());
		   	$num_rows = mysqli_num_rows($rateresult4);
		   	$row4=mysqli_fetch_assoc($rateresult4);
		   	
    		    if($num_rows > 1)
    		    {
    		       
    		                   $query1="SELECT *, COUNT(*)
             as num1 FROM tbl_bid 
             where tbl_bid.o_id='".$data2['o_id']."'
             GROUP BY bd_value having num1 = 1  ORDER BY `tbl_bid`.`bd_value` DESC LIMIT 0,1 ";

	            	    $result1 = mysqli_query($mysqli,$query1)or die(mysqli_error());
                	$num_rows1 = mysqli_num_rows($result1);
		               $row1=mysqli_fetch_assoc($result1);
    		        	
        		    if($num_rows1 > 0)
        		    {
        		         
        		    
        		          if( $row4['bd_value'] == $row1['bd_value'] )
        		          {
        		                  $row2['value'] = "Unique";
        		          
        		          }else
        		          {
        		             $row2['value'] = "Not Unique";
        		          }
        		          
        		    }else{
        		          $row2['value'] = "Not Unique";
        		      
        		    }  
        		    
    		    }else{
    		        
    		                     $query1="SELECT *, COUNT(*)
             as num1 FROM tbl_bid 
             where tbl_bid.o_id='".$data2['o_id']."'
             GROUP BY bd_value having num1 = 1  ORDER BY `tbl_bid`.`bd_value` DESC LIMIT 0,1 ";

	            	    $result1 = mysqli_query($mysqli,$query1)or die(mysqli_error());
                	$num_rows1 = mysqli_num_rows($result1);
		               $row1=mysqli_fetch_assoc($result1);
    		        	
        		    if($num_rows1 > 0)
        		    {
        		    
        		            
        		            if( $row4['bd_value'] == $row1['bd_value'] )
        		          {
        		                  $row2['value'] = "Unique";
        		          
        		          }else
        		          {
        		             $row2['value'] = "Unique not Higest";
        		          }
        		           
        		    }else{
        		          $row2['value'] = "Not Unique";
        		      
        		    } 
    		        

    		    }
    		    
    		    
			}

    		    

    		
    		
    		
    		
    		
                        $row2['bd_id'] = $data2['bd_id'];
                        $row2['u_id'] = $data2['u_id'];
                          $row2['name'] = $data2['name'];
                            $row2['image'] = $data2['image'];
                        $row2['o_id'] = $data2['o_id'];
	 	            	$row2['bd_value'] = $data2['bd_value'];   
                        $row2['bd_amount'] = $data2['bd_amount'];
	 	            	$row2['bd_date'] = $data2['bd_date'];           
                                    
    		              array_push($response1, $row2);   
    		    // exit();    
    		} 
    		
    		
    		
    		
    		
    		
    		
    		
    		
    		
			// o_typee
			if($data['o_type'] == 1)
			{
			   $query1="SELECT *, COUNT(*)
 as num1 FROM tbl_bid 
 left join tbl_users on tbl_users.id = tbl_bid.u_id
 where tbl_bid.o_id='".$data['o_id']."'
 GROUP BY bd_value having num1 = 1  ORDER BY `tbl_bid`.`bd_value` ASC LIMIT 0,1 ";

		    $result1 = mysqli_query($mysqli,$query1)or die(mysqli_error());

		   $row1=mysqli_fetch_assoc($result1);
		   
		   if($row1['name'] == null and $row1['id'] == null)
		   {
		       $row['won_name'] = '';
		       $row['won_id'] = 0;
		   }else{
		       $row['won_id'] = $row1['id'];
		       $row['won_name'] = $row1['name'];
		   }
		   
			}
			// o_typee
			if($data['o_type'] == 2)
			{
			     $query1="SELECT *, COUNT(*)
 as num1 FROM tbl_bid 
 left join tbl_users on tbl_users.id = tbl_bid.u_id
 where tbl_bid.o_id='".$data['o_id']."'
 GROUP BY bd_value having num1 = 1  ORDER BY `tbl_bid`.`bd_value` DESC LIMIT 0,1 ";

		    $result1 = mysqli_query($mysqli,$query1)or die(mysqli_error());

		   $row1=mysqli_fetch_assoc($result1);
    		     if($row1['name'] == null and $row1['id'] == null)
		   {
		       $row['won_name'] = '';
		       $row['won_id'] = 0;
		   }else{
		       $row['won_id'] = $row1['id'];
		       $row['won_name'] = $row1['name'];
		   }
			}
          
          
          
			$row['o_id'] = $data['o_id'];
			$row['o_name'] = $data['o_name'];
			$row['o_image'] =  $file_path.'images/'.$data['o_image'];
			$row['o_desc'] = $data['o_desc'];
			$row['o_date'] = $data['o_date'];
			$row['o_edate'] = $data['o_edate'];
			$row['o_stime'] = $data['o_stime'];
			$row['o_etime'] = $data['o_etime'];
			$row['o_amount'] = $data['o_amount'];
			$row['o_type'] = $data['o_type'];
			$row['o_min'] = $data['o_min'];
			$row['o_max'] = $data['o_max'];
			$row['o_price'] = $data['o_price'];
			$row['all_bid'] = $response;
			$row['user_bid'] = $response1;
			$row['o_status'] = $data['o_status'];
	    
			array_push($jsonObj4,$row); 
			}
		
		$set['JSON_DATA'] = $jsonObj4;	

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }
 
else if(isset($_GET['add_order']))
 	{
  	    date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
  			 
        $date = date('d-m-Y H:i:s');
		$qry1="INSERT INTO tbl_order 
                (u_id,
                `offer_id`,
                `total_amount`,
                `dis_amount`,
                `pay_amount`,
                `o_address`,
                `o_date`,
                `o_status`
				) VALUES (
                    '".$_POST['u_id']."',
                    '".$_POST['offer_id']."',
                    '".trim($_POST['total_amount'])."',
		    		'".trim($_POST['dis_amount'])."',
					'".trim($_POST['pay_amount'])."',
					'".trim($_POST['o_address'])."',
					'".$date."',
						'1'
				)"; 
            
            $result1 = mysqli_query($mysqli,$qry1);
            $last_id = mysqli_insert_id($mysqli);  
            

            $qrys = "SELECT * FROM tbl_order WHERE o_id = '".$last_id."'"; 
			$results = mysqli_query($mysqli,$qrys);
			$row = mysqli_fetch_assoc($results);
			
		 // INSERT INTO `tbl_order`(`o_id`, `u_id`, `offer_id`, `total_amount`, `dis_amount`, `pay_amount`, `o_address`, `o_date`, `o_status`)

												 
			$set['JSON_DATA'][]	=	array(
                                  'msg' 	=>	"Order done Successfuly",
			                      'o_id' 	=>	$row['o_id'],
			                      'u_id' 	=>	$row['u_id'],
 								 'offer_id'	=>	$row['offer_id'],
 								 'total_amount'	=>	$row['total_amount'],
 							     'dis_amount'	=>	$row['dis_amount'],
 							     'pay_amount'	=>	$row['pay_amount'],
 							 	 'o_address'	=>	$row['o_address'],
 							     'o_date'	=>	$row['o_date'],
 						       	 'o_status'	=>	$row['o_status'],
		     								);			 
            
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
}

//get all offers
else if(isset($_GET['get_order_user']))
 	{
  		 $jsonObj4= array();	

		$query="SELECT * FROM tbl_order 
		left join tbl_users on tbl_users.id = tbl_order.u_id
		left join tbl_offers on tbl_offers.o_id = tbl_order.offer_id
		WHERE u_id='".$_POST['u_id']."' order by tbl_order.o_status=1";
		$sql = mysqli_query($mysqli,$query)or die(mysqli_error());

		 // INSERT INTO `tbl_order`(`o_id`, `u_id`, `offer_id`, `total_amount`, `dis_amount`, `pay_amount`, `o_address`, `o_date`, `o_status`)
		 
		while($data = mysqli_fetch_assoc($sql))
		{
			 
			$row['o_id'] = $data['o_id'];
			$row['u_id'] = $data['u_id'];
			$row['name'] = $data['name'];
			$row['offer_id'] = $data['offer_id'];
			$row['o_name'] = $data['o_name'];
			$row['o_image'] =  $file_path.'images/'.$data['o_image'];
			$row['total_amount'] = $data['total_amount'];
			$row['dis_amount'] = $data['dis_amount'];
			$row['pay_amount'] = $data['pay_amount'];
			$row['o_address'] = $data['o_address'];
			$row['o_date'] = $data['o_date'];
			$row['o_status'] = $data['o_status'];
		
			
			array_push($jsonObj4,$row); 
			}
		
		$set['JSON_DATA'] = $jsonObj4;	

		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
 }
 
 else  if(isset($_GET['forgotpassword'])) 
{
            $phone = $_POST['phone'];
            
              $text=rand(4499,4499);
     
             $qry1 = "SELECT * FROM tbl_users WHERE phone = '".$phone."' and status='1'"; 	 
    			$result1 = mysqli_query($mysqli,$qry1);
    			$row1 = mysqli_fetch_assoc($result1);
    			$num_rows = mysqli_num_rows($result1);
		        $password = $row1['password'];
		
        	        if ($num_rows > 0 )
        			{
        			    
        			     $user_edit= "UPDATE tbl_users SET confirm_code='".$text."'  WHERE phone = '".$phone."' and status='1'"; 	 
    		           
        		           $user_res = mysqli_query($mysqli,$user_edit);	

        		        	
                        $YourAPIKey='42a7256b-fa80-11eb-a13b-0200cd936042';
                        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
                        
                        $url = "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=$YourAPIKey&to=$phone&from=BETMZA&templatename=otpmsg&var1=$text";
                        
                        
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL,$url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                         $result1= curl_exec($ch);
                          $result = json_decode($result1, true);
        			   if($result['Status'] == "Success"){
        				   $set['JSON_DATA'][]=array('msg' => "otp sent successfully... ",'success'=>'1');
        			   }
        			   else{
        			    	$set['JSON_DATA'][]=array('msg' => "Mobile Number is not Registered",'success'=>'0');
        			   }
        			   	curl_close($ch);
          
                        	 	 header( 'Content-Type: application/json; charset=utf-8' );
                    	          echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                    	    	 die();
                    
        			}else
        			{
        			    $set['JSON_DATA'][]=array('msg' => "Mobile Number is not Registered",'success'=>'0');
        			    
        			     header( 'Content-Type: application/json; charset=utf-8' );
                    	          echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                    	    	 die();
        			}
        		
}

    //user verify otp from setting screen
	else if(isset($_GET['mobilenumberverify_setting'])) 
	{
	   
      		$phone = $_POST['phone'];
      		$confirm_code = $_POST['confirm_code'];

             $qry1 = "SELECT * FROM tbl_users WHERE phone = '".$phone."'  and confirm_code='".$confirm_code."' ";	 
			$result1 = mysqli_query($mysqli,$qry1);
			$row = mysqli_fetch_assoc($result1);
			 $num_rows = mysqli_num_rows($result1);

	        
	        if ($num_rows > 0 )
			{
			    
			      $user_edit1= "UPDATE tbl_users SET status = 1 WHERE phone = '".$phone."' and confirm_code='".$confirm_code."' ";
	           $user_res1 = mysqli_query($mysqli,$user_edit1);	
         
			     $qry2 = "SELECT * FROM tbl_users WHERE phone = '".$phone."'  and status = 1 ";
    			$result2 = mysqli_query($mysqli,$qry2);
    			$row1 = mysqli_fetch_assoc($result2);
    			$refferal_code = $row1['refferal_code'];
    			
    			if($refferal_code!="")
		        {
    			    $qry2 = "SELECT * FROM tbl_users WHERE code = '".$refferal_code."'";	 
    	    		$result2 = mysqli_query($mysqli,$qry2);
    	    		$num_rows = mysqli_num_rows($result2);
    	    		$row2 = mysqli_fetch_assoc($result2);
    	 	    
    	 	    	$c2 = $row2['id'];

    		        if ($num_rows > 0)
    	    		{
    	    		     $id=$row1['id'];
    
    	    		     $wallet=$row1['wallet'];
    	    		     $first1=5;   
    	    		     $newwallet=$wallet+$first1 ;
    	    		    
    
    	    		     $network=$row1['network'];
    	    		     $newnetwork=$network+1; 
    	    		     
    	    		       $wallet2=$row2['wallet'];
    	    		     $first2=10;   
    	    		     $newwallet2=$wallet2+$first2 ;
				
        				 $network_qry="INSERT INTO tbl_network (`user_id`,`level`,`money`,`refferal_user_id`,`status`) 
        				VALUES ('$c2','1','$first1','$id','1')"; 
        
        				$result1=mysqli_query($mysqli,$network_qry); 
        	            

        	            $first_level= "UPDATE tbl_users SET wallet='".$newwallet2."' , network='".$newnetwork."' WHERE id = '".$c2."'";	 
        	    		$first_level_earn1 = mysqli_query($mysqli,$first_level);
        	    	
        	    	  $wallet2=$row2['wallet'];
    	    		     $first2=10;   
    	    		     $newwallet2=$wallet2+$first2 ;
        	    	
        	    	  $first_level1= "UPDATE tbl_users SET wallet='".$newwallet."' WHERE id = '".$id."'";	 
        	    		$first_level_earn2 = mysqli_query($mysqli,$first_level1);
        	    		    
        		        $id=$row['id'];
        	    	  $date = date('Y-m-d');
	    		     
	    		     	$qry1="INSERT INTO tbl_transaction (`user_id`,`type`,`type_no`,`date`,`money`) 
				        VALUES ('$c2',2,'$id','$date','$first1')"; 
	            
	                    $result1=mysqli_query($mysqli,$qry1);  		

		
	    		}
	    		

	    	}
    			
                 $set['JSON_DATA'][]	=	array(
                     'msg' => "Profile verify successfully",    
                     'success'=>'1',
                        'id' 	=>	$row1['id'],
                     	  'login_type' 	=>	$row1['login_type'],
 						  'name'	=>	$row1['name'],
 						  'email'	=>	$row1['email'],
 						   'password'	=>	$row1['password'],
 						  'image'	=>	$row1['image'],
 						  'phone'	=>	$row1['phone'],
 						  'wallet'	=>	$row1['wallet'],
 						  'code'	=>	$row1['code'],
 						  'refferal_code'	=>	$row1['refferal_code'],
 						  'confirm_code'	=>	$row1['confirm_code'],
 						  'network'	=>	$row1['network'],
 						   'ban'	=>	$row1['ban'],
 						  'status'	=>	$row1['status']
	     								
						);
	    							
				   
            		
        	}
	        else
	        {
	            $set['JSON_DATA'][]=array('msg' => "Please enter a valid OTP",'success'=>'0');
        

	        }
	        
	        	 	 header( 'Content-Type: application/json; charset=utf-8' );
    	          echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    	    	 die();
	}
    else if(isset($_GET['change_password']))
 	{

	    $phone = $_POST['phone'];
	   
		$user_edit= "UPDATE tbl_users SET password='".$_POST['password']."' WHERE phone = '".$phone."' and status='1'"; 	 
		
   		$user_res = mysqli_query($mysqli,$user_edit);	
	  	
	  	$set['JSON_DATA'][]=array('msg'=>'Updated','success'=>'1');		 
		
		header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();	
	}
	
	
		//user registration
    	 if(isset($_GET['postUsermobileRegister'])) 
	{
	   
      	    $phone = $_POST['phone'];
      	     $device_id = $_POST['device_id'];
            $text=rand(4499,4499);
             $rand=rand(1000,9999);
             
            if($_POST['phone']!=""  )
	        {
    			    
                $qry1 = "SELECT * FROM tbl_users WHERE ( phone = '".$phone."' or device_id = '".$device_id."' ) and status = 1 "; 	 
    			$result1 = mysqli_query($mysqli,$qry1);
    			$row1 = mysqli_fetch_assoc($result1);
    			$num_rows = mysqli_num_rows($result1);
    
    			
        	        if ($num_rows > 0 )
        			{
        			    if($row1['ban'] == 1)
        			    {
        			        	$set['JSON_DATA'][]=array('msg' => "You are Banned!! Sorry",'success'=>'0');
        		

                	 	 header( 'Content-Type: application/json; charset=utf-8' );
            	          echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            	    	 die();
        			    }else
        			    {
        			        	$set['JSON_DATA'][]=array('msg' => "Your mobile number or Device is already registered. Please click Login to continue.",'success'=>'0');
        		

                	 	 header( 'Content-Type: application/json; charset=utf-8' );
            	          echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            	    	 die();
        			    }
        			    
                    
        
        			}else
        			{
        			    
        			    $qry2 = "SELECT * FROM tbl_users WHERE phone = '".$phone."' and status='0'"; 	 
            			$result2 = mysqli_query($mysqli,$qry2);
            			$row2 = mysqli_fetch_assoc($result2);
            			$num_rows2 = mysqli_num_rows($result2);
    
    			
        	        if ($num_rows2 > 0 )
        			{
            			    
            			   
    		          $user_edit= "UPDATE tbl_users SET confirm_code='".$text."' WHERE phone = '".$phone."' and status ='0'"; 	
    		           
        		      $user_res = mysqli_query($mysqli,$user_edit);	
        		    
                       	$YourAPIKey='42a7256b-fa80-11eb-a13b-0200cd936042';
                        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
                        
                        $url = "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=$YourAPIKey&to=$phone&from=BETMZA&templatename=otpmsg&var1=$text";
                        
                        
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL,$url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                         $result1= curl_exec($ch);
                          $result = json_decode($result1, true);
        			   if($result['Status'] == "Success"){
        				   $set['JSON_DATA'][]=array('msg' => "otp sent successfully... ",'success'=>'1');
        			   }
        			   else{
        			    	$set['JSON_DATA'][]=array('msg' => "Please enter a valid phone number",'success'=>'0');
        			   }
        			   	curl_close($ch);
        		
        			   	header( 'Content-Type: application/json; charset=utf-8' );
            	          echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            	    	 die();
            			
        			  
        			}else
        			{
        			    

    			        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
                         $date = date('d-m-Y H:i:s');
        
        		        
          			$insert_user1="INSERT INTO tbl_users 
        				  (
        				   `login_type`,
        				  `name`,
        				  `email`,
        				    `image`,
        				  `password`,
        				   `phone`,
        				     `device_id`,
        				     wallet,
        				     code,
        				     refferal_code,
        				    `confirm_code`,
        				      `ban`,
        				   `status`
        				) VALUES (
        					'1',
        					'".trim($_POST['name'])."',
        					'".trim($_POST['email'])."',
        					'',
        					'".$_POST['password']."',
                            '".trim($_POST['phone'])."',
                            '".trim($_POST['device_id'])."',
                            5,
                            '$rand',
                            '".$_POST['refferal_code']."',
                            '".$text."',
                            	0,
                            	0
			            	)"; 	    
            
           
        	            $result1=mysqli_query($mysqli,$insert_user1); 
        	            $last_id = mysqli_insert_id($mysqli);
        	            
                       	$YourAPIKey='42a7256b-fa80-11eb-a13b-0200cd936042';
                        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
                        
                        $url = "https://2factor.in/API/R1/?module=TRANS_SMS&apikey=$YourAPIKey&to=$phone&from=BETMZA&templatename=otpmsg&var1=$text";
                        
                        
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL,$url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                         $result1= curl_exec($ch);
                          $result = json_decode($result1, true);
        			   if($result['Status'] == "Success"){
        				   $set['JSON_DATA'][]=array('msg' => "otp sent successfully... ",'success'=>'1');
        			   }
        			   else{
        			    	$set['JSON_DATA'][]=array('msg' => "Please enter a valid phone number",'success'=>'0');
        			   }
        			   	curl_close($ch);
  
            	 	 header( 'Content-Type: application/json; charset=utf-8' );
        	          echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        	    	 die();
        			
        			    
        			}
                
	        }
            

	}else{
        			    
	    	$set['JSON_DATA'][]=array('msg' => "Please enter correct phone number...",'success'=>'0');


	 	 header( 'Content-Type: application/json; charset=utf-8' );
          echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    	 die();
    
        
    }
}


//user wallet update
 	else if(isset($_GET['post_user_wallet_update']))
{
        	if($_POST['user_id']!="")
			{
			    $qry = "SELECT * FROM tbl_users WHERE id = '".$_POST['user_id']."'";	 
				$result = mysqli_query($mysqli,$qry);
				$num_rows = mysqli_num_rows($result);
				$row = mysqli_fetch_assoc($result);
		
		        if ($num_rows > 0)
				{
				    
				    
			         $id=$row['id'];
	    		     $wallet=$row['wallet'];
	    		     $update_amount=$_POST['wallet'];
	    		     $newwallet=$wallet+$update_amount;   
			    
				     $user_edit= "UPDATE tbl_users SET wallet='".$newwallet."' WHERE id = '".$id."'";	 
				     $user_res = mysqli_query($mysqli,$user_edit);	


				     $set['JSON_DATA'][]=array('msg'=>'Updated','success'=>'1');

				}
			}
    	header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
}

?>
