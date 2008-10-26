<?php
set_time_limit(0);
include('my_twitter.php');


class twitterTalk extends MyTwitter{
	var $sleeptime = 60;
	var $twitted = array();

	function startTalking(){
		while(1){
			
			$this->checkTwitter();
			echo '

------------   Checking Next Batch Of Messages ---------------				
';
			sleep($this->sleeptime);
		}	
	}

	function checkTwitter(){
		$twits = $this->FollowingTimeLine();
		foreach($twits as $twit){
			if (!in_array($twit['id'], $this->twitted)){
				//not been twitted

			//	if(ereg($this->getUsername(),$twit['username'])){

					$this->say($twit);
			//	}		
				
				//mark as twitted
				$this->twitted[] = $twit['id'];
			} 
		}
	}

	function say($twit){
		$msg = $twit['user']['name'] . ' says '. $twit['text'];
				echo $msg . '

';
		@exec('say '. $msg);
	sleep(4);
	@exec('clear');
	}
}


$twitterTalk = new twitterTalk('mydo','Quake3');
$twitterTalk->startTalking();
?>
