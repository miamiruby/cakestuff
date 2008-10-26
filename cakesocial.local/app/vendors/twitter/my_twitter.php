<?php

# My Twitter Class PHP, work with the Twitter API.
# author: Andres "Artux" Scheffer
# url: http://www.artux.com.ar

#**********************************************************************
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# ( at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# ERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Online: http://www.gnu.org/licenses/gpl.txt

# *****************************************************************


class MyTwitter
{
		
	private $Username;
	private $Password;
	
	//
	private $MaxLength = 140;
	private $UserTimeLine = array ();
	private $FollowingTimeLine = array ();
	private $PublicTimeLine = array ();
	private $Replies = array ();
	
	private $Following = array ();
	private $Followers = array ();
	private $Featured = array ();
	
	private $DirectMessages = array ();
	private $SentMessages = array ();
	private $ResponseMessage;
	
	private $UrlTwitter = 'http://twitter.com/';
	private $UrlStatus = 'http://twitter.com/statuses/';
	
	function __construct ($user, $password) 
	{
		$this->Username = $user;
		$this->Password = $password;
	}
	 		
/********************* Status Methods *****************************/

	public function getUsername(){
		return $this->Username;
	}
//Update Status
	public function updateStatus ($status='')
	{ 
		if(empty($this->Username) || empty($this->Password))
		{
			$this->Error(1);
		} else 
		{
			if(!empty($status) && strlen($status) <= $this->MaxLength)
			{
				$url = $this->UrlStatus;
				$url .= 'update.xml?status='. urlencode(stripslashes(urldecode($status)));
				
				$request = $this->requestToTwitter($url);
				return $request;
				
			}else
			{
				$this->Error(3);
			}
		}
	}
		
	
//Show User Statuses
	public function userTimeLine ($limit='20')
	{
		$url = $this->UrlStatus;
		$url .= 'user_timeline.xml?count='. $limit;
		$statuses = $this->myTimeLineParse($url, 'user');
		
		return $statuses;
		
	}
	
//Show updates from people you follow
	public function followingTimeLine ($page='1')
	{
			
		$url = $this->UrlStatus;
		$url .= 'friends_timeline.xml?page='. $page;
		$statuses = $this->myTimeLineParse($url, 'friends');
			
		return $statuses;
			
	}
	
//Show Public Statuses
	public function publicTimeLine ()
	{
		$url = $this->UrlStatus;
		$url .= 'public_timeline.xml';
		$statuses = $this->myTimeLineParse($url, 'public');	
			
		return $statuses;
			
	}
	
//Show Replies of your Updates
	public function repliesLine ($page='1')
	{
		$url = $this->UrlStatus;
		$url .= 'replies.xml?page='. $page;
		$statuses = $this->myTimeLineParse($url, 'replies');	
				
		return $statuses;
				
	}

//Show a single status, specified by the id parameter below
	public function showStatus ($id='')
	{
		if(!empty($id) && is_integer($id))
		{
			$url = $this->UrlStatus;
			$url .= 'show/'. $id .'.xml';
		
			if(function_exists(curl_init))
			{
				$ch = curl_init();
					
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
		
				$xml = curl_exec($ch);
				$Headers = curl_getinfo($ch);
		
				curl_close($ch);
			
				if($Headers['http_code'] == 200)
				{
		
					$data = simplexml_load_string($xml);
					$protc = "protected";	
					$showstatus = array ();
					$showstatus['created_at'] = (string) $data->created_at;
					$showstatus['id'] =  (string) $data->id;
					$showstatus['text'] = (string) $data->text ;
					$showstatus['source'] = (string) $data->source ;
					$showstatus['user']['userid'] = (string) $data->user->id;
					$showstatus['user']['name'] = (string) $data->user->name;
					$showstatus['user']['screen_name'] = (string) $data->user->screen_name;
					$showstatus['user']['location'] = (string) $data->user->location;
					$showstatus['user']['description'] = (string) $data->user->description;
					$showstatus['user']['profile_image_url'] = (string) $data->user->profile_image_url;
					$showstatus['user']['url'] = (string) $data->user->url;
					$showstatus['user']['protected'] = (string) $data->user->$protc;
				
					return $showstatus;
				
				}else
				{
					if($Headers['http_code'] == 401)
					{
						$this->Error(4);
					
					} elseif($Headers['http_code'] == 404)
					{
						$this->Error(5);
					}
				}
			
			}else
			{
				$this->Error(2);
			}
		}else
		{
			$this->Error(6);
		}	
	}
	
//Destroy a single status, specified by the id parameter below
	public function destroyStatus ($id='')
	{
		if(!empty($id) && is_integer($id))
		{
			$url = $this->UrlStatus;
			$url .= 'destroy/'. $id .'.xml';
		
			$request = $this->requestToTwitter($url);
			return $request;
		}else
		{
			$this->Error(6);
		}	
	}	
/******************End Status Methods***************************/


/********************* User Methods *****************************/

//Returns up to 100 of the authenticating users you follow who have most recently updated.
	public function userFollowing ()
	{
			
		$url = $this->UrlStatus;
		$url .= 'friends.xml';
		$statuses = $this->myUserParse($url, 'following');
			
		return $statuses;
			
	}

//Returns the authenticating user's followers.
	public function userFollowers ()
	{
			
		$url = $this->UrlStatus;
		$url .= 'followers.xml';
		$statuses = $this->myUserParse($url, 'followers');
			
		return $statuses;
			
	}

//Returns a list of the users currently featured on the site with their current statuses inline.
	public function userFeatured ()
	{
			
		$url = $this->UrlStatus;
		$url .= 'featured.xml';
		$statuses = $this->myUserParse($url, 'featured');
			
		return $statuses;
			
	}
	
	
//Show a single status, specified by the id parameter below
	public function userShow ($id='')
	{
		if(!empty($id))
		{
			$url = 'http://twitter.com/users/show/'. $id .'.xml';
		
			if(function_exists(curl_init))
			{
				$ch = curl_init();
					
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_VERBOSE, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_USERPWD, "$this->Username:$this->Password");
				curl_setopt($ch, CURLOPT_POST, 1);		
		
				$xml = curl_exec($ch);
				$Headers = curl_getinfo($ch);
		
				curl_close($ch);
			
				if($Headers['http_code'] == 200)
				{
		
					$data = simplexml_load_string($xml);
					$protc = "protected";	
					$usershow = array ();
					$usershow['userid'] = (string) $data->id;
					$usershow['name'] =  (string) $data->name;
					$usershow['screen_name'] = (string) $data->screen_name ;
					$usershow['location'] = (string) $data->location ;
					$usershow['description'] = (string) $data->description;
					$usershow['profile_image_url'] = (string) $data->profile_image_url;
					$usershow['url'] = (string) $data->url;
					$usershow['protected'] = (string) $data->$protc;
					$usershow['bg_color'] = (string) $data->profile_background_color;
					$usershow['text_color'] = (string) $data->profile_text_color;
					$usershow['link_color'] = (string) $data->profile_link_color;
					$usershow['sidebar_bg_color'] = (string) $data->profile_sidebar_fill_color;
					$usershow['sidebar_border_color'] = (string) $data->profile_sidebar_border_color;
					$usershow['following_count'] = (string) $data->friends_count;
					$usershow['followers_count'] = (string) $data->followers_count;
					$usershow['favourites_count'] = (string) $data->favourites_count;
					$usershow['utc_offset'] = (string) $data->utc_offset;
					$usershow['bg_image'] = (string) $data->profile_background_image;
					$usershow['bg_tile'] = (string) $data->profile_background_tile;
					$usershow['statuses_count'] = (string) $data->statuses_count;
					
					$usershow['status']['created_at'] = (string) $data->status->created_at;
					$usershow['status']['id'] = (string) $data->status->id;
					$usershow['status']['text'] = (string) $data->status->text;
					$usershow['status']['source'] = (string) $data->status->source;
				
					return $usershow;
				
				}else
				{
					if($Headers['http_code'] == 401)
					{
						$this->Error(4);
					
					} elseif($Headers['http_code'] == 404)
					{
						$this->Error(5);
					}
				}
			
			}else
			{
				$this->Error(2);
			}
		}else
		{
			$this->Error(6);
		}	
	}
/**********************End User Methods*****************************/


/******************** Direct Message Methods************************/

//Returns a list of the 20 most recent direct messages sent to the authenticating user
	public function directMessages ()
	{
		$url = 'http://twitter.com/direct_messages.xml';
		$statuses = $this->myMessagesParse($url, 'direct');	
				
		return $statuses;			
	}
	
//Returns a list of the 20 most recent direct messages sent by the authenticating user
	public function sentMessages ()
	{
		$url = $this->UrlTwitter;
		$url .= 'direct_messages/sent.xml';
		$statuses = $this->myMessagesParse($url, 'sent');	
				
		return $statuses;			
	}
	
//Sends a new direct message to the specified user from the authenticating user
	public function newMessage ($for='', $message='')
	{
	
		if(empty($for) || empty($message))
		{
			$this->Error(9);
		}else
		{
			if (strlen($message) >= $this->MaxLength)
			{
				$this->Error(9);
			}else
			{	
	
				$url = $this->UrlTwitter;
				$url .= 'direct_messages/new.xml?user='. $for .'&text='. urlencode(stripslashes(urldecode($message)));
				
				$request = $this->requestToTwitter($url);
							
				return $request;
			}
		}		
	 }
	
//Destroy a Message, specified by the id parameter below
	public function destroyMessage ($id='')
	{
		if(!empty($id) && is_integer($id))
		{
			$url = $this->UrlTwitter;
			$url .= 'direct_messages/destroy/'. $id .'.xml';
		
			$request = $this->requestToTwitter($url);
			
			return $request;
		}else
		{
			$this->Error(6);
		}	
	}	
/*********************End Direct Message Methods**********************/

/********************** Following Methods****************************/

//Destroy a Message, specified by the id parameter below
	public function follow ($id='')
	{
		if(!empty($id))
		{
			$url = $this->UrlTwitter;
			$url .= 'friendships/create/'. $id .'.xml';
		
			$request = $this->requestToTwitter($url);
			
			return $request;
		}else
		{
			$this->Error(6);
		}	
	}
	
//Destroy a Message, specified by the id parameter below
	public function destroyFollow ($id='')
	{
		if(!empty($id))
		{
			$url = $this->UrlTwitter;
			$url .= 'friendships/destroy/'. $id .'.xml';
		
			$request = $this->requestToTwitter($url);
			
			return $request;
		}else
		{
			$this->Error(6);
		}	
	}


/*********************End Following Methods**************************/

//////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////
	
/********************* Private Methods *****************************/	

//Time Line XML Parse
	private function myTimeLineParse ( $url='', $type='' )
	{
		if(empty($this->Username) || empty($this->Password))
		{
			$this->Error(1);
		} else {
	
		if(function_exists(curl_init)){
		
		$ch = curl_init();	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERPWD, "$this->Username:$this->Password");
		curl_setopt($ch, CURLOPT_POST, true);
		
		$xml = curl_exec($ch);
		
		$Headers = curl_getinfo($ch);
		curl_close($ch);
			
			if($Headers['http_code'] == 200){
			} else{
			
					if($Headers['http_code'] == 401){
							$this->Error(4);
						} elseif($Headers['http_code'] == 404){
						
							$this->Error(5);
						}
					}//Check Response
		} else{
			$this->Error(2);
		}//CURL Library installed	
		 $data = simplexml_load_string($xml);
		 $totalcount = count ($data);
		
		$dato = array ();
		$protc = "protected";
		
		for ( $i=0; $i < $totalcount ; $i++ )
		{ 
			$dato[$i]['created_at'] = (string) $data->status[$i]->created_at;
			$dato[$i]['id'] =  (string) $data->status[$i]->id;
			$dato[$i]['text'] = (string) $data->status[$i]->text ;
			$dato[$i]['source'] = (string) $data->status[$i]->source ;
			$dato[$i]['user']['userid'] = (string) $data->status[$i]->user->id;
			$dato[$i]['user']['name'] = (string) $data->status[$i]->user->name;
			$dato[$i]['user']['screen_name'] = (string) $data->status[$i]->user->screen_name;
			$dato[$i]['user']['location'] = (string) $data->status[$i]->user->location;
			$dato[$i]['user']['description'] = (string) $data->status[$i]->user->description;
			$dato[$i]['user']['profile_image_url'] = (string) $data->status[$i]->user->profile_image_url;
			$dato[$i]['user']['url'] = (string) $data->status[$i]->user->url;
			$dato[$i]['user']['protected'] = (string) $data->status[$i]->user->$protc;
		}

		
		switch ( $type )
		{
			case 'replies':
				return $this->Replies = $dato;
			break;
			
			case 'user':
				return $this->UserTimeLine = $dato;
			break;
			
			case 'friends':
				return $this->FollowingTimeLine = $dato;
			break;
			
			case 'public':
				return $this->PublicTimeLine = $dato;
			break;
			
		}
	
	  }		
	} //end Time Line XML Parse
	
	
//User XML Parse
	private function myUserParse ( $url='', $type='' )
	{
		if(empty($this->Username) || empty($this->Password))
		{
			$this->Error(1);
		} else 
		{
			if(function_exists(curl_init))
			{	
			$ch = curl_init();	
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERPWD, "$this->Username:$this->Password");
			curl_setopt($ch, CURLOPT_POST, true);
	
			$xml = curl_exec($ch);
	
			$Headers = curl_getinfo($ch);
			curl_close($ch);
		
				if($Headers['http_code'] == 200)
				{
				} else
				{
					if($Headers['http_code'] == 401)
					{
						$this->Error(4);
					} elseif($Headers['http_code'] == 404)
					{
						$this->Error(5);
					}
				}//Check Response
			} else
			{
			$this->Error(2);
			}//CURL Library installed	
			$data = simplexml_load_string($xml);
			$totalcount = count ($data);
	
			$dato = array ();
			$protc = "protected";
	
			for ( $i=0; $i < $totalcount ; $i++ )
			{ 
				$dato[$i]['userid'] = (string) $data->user[$i]->id;
				$dato[$i]['name'] =  (string) $data->user[$i]->name;
				$dato[$i]['screen_name'] = (string) $data->user[$i]->screen_name ;
				$dato[$i]['location'] = (string) $data->user[$i]->location ;
				$dato[$i]['description'] = (string) $data->user[$i]->description;
				$dato[$i]['profile_image_url'] = (string) $data->user[$i]->profile_image_url;
				$dato[$i]['url'] = (string) $data->user[$i]->url;
				$dato[$i]['protected'] = (string) $data->user[$i]->$protc;
				$dato[$i]['status']['created_at'] = (string) $data->user[$i]->status->created_at;
				$dato[$i]['status']['id'] = (string) $data->user[$i]->status->id;
				$dato[$i]['status']['text'] = (string) $data->user[$i]->status->text;
				$dato[$i]['status']['source'] = (string) $data->user[$i]->status->source;
			}

	
			switch ( $type )
			{
				case 'following':
					return $this->Following = $dato;
				break;
		
				case 'followers':
					return $this->Followers = $dato;
				break;
		
				case 'featured':
					return $this->Featured = $dato;
				break;
		
			}
		}		
   } //end User XML Parse


//Messages XML Parse
	private function myMessagesParse ( $url='', $type='' )
	{
		if(empty($this->Username) || empty($this->Password))
		{
			$this->Error(1);
		} else 
		{
			if(function_exists(curl_init))
			{	
				$ch = curl_init();	
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_USERPWD, "$this->Username:$this->Password");
				curl_setopt($ch, CURLOPT_POST, true);
						
				$xml = curl_exec($ch);
	
				$Headers = curl_getinfo($ch);
				curl_close($ch);
							
				if($Headers['http_code'] == 200)
				{
					
				} else
				{
					if($Headers['http_code'] == 401)
					{
						$this->Error(4);
					} elseif($Headers['http_code'] == 404)
					{
						$this->Error(5);
					}
				}//Check Response
			} else
			{
				$this->Error(2);
			}//CURL Library installed	
			
			$data = simplexml_load_string($xml);
			$totalcount = count ($data);
						
			$dato = array ();
						
			for ( $i=0; $i < $totalcount ; $i++ )
			{ 
				$dato[$i]['id'] = (string) $data->direct_message[$i]->id;
				$dato[$i]['text'] =  (string) $data->direct_message[$i]->text;
				$dato[$i]['sender_id'] = (string) $data->direct_message[$i]->sender_id;
				$dato[$i]['recipient_id'] = (string) $data->direct_message[$i]->recipient_id;
				$dato[$i]['created_at'] = (string) $data->direct_message[$i]->created_at;
				$dato[$i]['sender_screen_name'] = (string) $data->direct_message[$i]->sender_screen_name;
				$dato[$i]['recipient_screen_name'] = (string) $data->direct_message[$i]->recipient_screen_name;
			}							
									
			switch ( $type )
			{
				case 'direct':
					return $this->DirectMessages = $dato;
				break;
		
				case 'sent':
					return $this->SentMessages = $dato;
				break;	
				
			}
		}		
   } //end Messages XML Parse



//Request to Twitter with cURL lib
	private function requestToTwitter ( $url='')
	{
		if(empty($this->Username) || empty($this->Password))
		{
			$this->Error(1);
		} else 
		{
			if(function_exists(curl_init))
			{	
				$ch = curl_init();	
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_USERPWD, "$this->Username:$this->Password");
				curl_setopt($ch, CURLOPT_POST, true);
						
				curl_exec($ch);
	
				$Headers = curl_getinfo($ch);
				curl_close($ch);
							
				if($Headers['http_code'] == 200)
				{
					return 1;
				} else
				{
					if($Headers['http_code'] == 401)
					{
						$this->Error(4);
					} elseif($Headers['http_code'] == 404)
					{
						$this->Error(5);
					}
				}//Check Response
			} else
			{
				$this->Error(2);
			}//CURL Library installed	
			
		}		
   } //end Messages XML Parse



//Function to be called if error occurs
	private function Error($Error){
		//List of Errors
		$e[1] = 'Username and/or password not set';
		$e[2] = 'CURL library not installed';
		$e[3] = 'Post value too long/not set';
		$e[4] = 'Invalid username/password';
		$e[5] = 'Invalud URL for CURL request';
		$e[6] = 'Invalid ID value entered';
		$e[7] = 'You are not authorized to view this page';
		$e[8] = 'All variables for requested function not set';
		$e[9] = 'For and/or Message not set';
		//Display Error
		if(array_key_exists($Error, $e)){
			echo $e[$Error];
		} else{
			echo 'Invalid Error Code';
		}
	}//End Error()
	
}//end My Twitter Class

?>

