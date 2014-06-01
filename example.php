<?php
				/**
					EasyDBManager TEST
				*/
					require_once 'MySQLiManager.php';
					$db = new EasyDBManager('localhost','root','','test');
					
					/**
						Insert Example
					*
					*$data = array("username"=>"Pablo","password"=>"1234","email"=>"pabhoz@gmail.com");
					*if($db->insert('user',$data)){echo "Insertado correctamente";}
					*/

					/**
						Update Example
					*
					*$data = array("username"=>"Pedro","password"=>"5678");
					*if($db->update('user',$data,'email = "pabhoz@gmail.com"')){echo 'Updated';}
					*/

					/**
						Update Example
					*
					*$data = array("username"=>"Pedro");
					*if($db->delete('user',$data)){echo 'Deleted';}
					*Advanced
					*$data = "id = 5";
					*if($db->delete('user',$data,true)){echo 'Deleted';}
					*/

					/**
						Check Example
					*
					*$data = array("username"=>"Pedro");
					*if($db->check('username','user',$data)){echo 'Exist';}else{echo 'Empty';}
					*Advanced
					*$data = "id = 3";
					*if($db->check('username','user',$data,true)){echo 'Exist';}else{echo 'Empty';}
					*/

			?>	