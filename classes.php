<?php
	/**
	* 
	*/
	class PartyMember
	{
		public $_alive = true; #alive or dead
		public $_name;         #party member's first name
		public $_health = 100; #health, maximum 100, death at 0, 
								#illness cured 90 up,
								#illness possible at 75 bellow

		public $_illness = false; #is ill/ is not ill
		public $_illnessName = null; #name of illness
		public $_damage = 0; #damage done by illness

		#constructor
		public function __construct($name)
		{
			$this->_name = $name;
		}

		#induces injury or illness
		public function catchCold($name, $damage)
		{
			$this->_illness = true;
			$this->_illnessName = $name;
			$this->_damage = $damage;
		}

		#heals occur at rests
		public function heal($healRate)
		{
			if ($this->_health >= 90)
			{
				$this->_illness = false;
				$this->_illnessName = null;
			}
			if ($this->_health < 100)
			{
				$this->_health += $healRate;
			}
		}

		#member takes damage when ill or durring event
		public function takeDamage()
		{
			if ($this->_illness)
			{
				$this->_health -= $this->_damage; 
			}
		}

		#kills member
		public function killMember()
		{
			$this->_alive = false;
		}
	}


	#class for the stack or supplies avalable to the party
	class Supplies 
	{
		# amount of each supply
		public $_food;
		public $_money;
		public $_bait;
		public $_clothes;
		public $_wagonWheels;
		public $_wagonAxle;
		public $_wagonTongue;
		public $_oxen;
	

		public function __construct($jobCash)
		{
			$this->_food = 0;
			$this->_money = $jobCash; #Job
			$this->_bait = 0;
			$this->_clothes = 0;
			$this->_wagonAxle = 0;
			$this->_wagonWheels = 0;
			$this->_wagonTongue = 0;
			$this->_oxen = 0;
		}

		public function eat($rate)
		{
			$this->_food -= $rate;
		}

		public function setItem($ID, $amount)
		{
			$changeAmt = $amount;

			switch ($ID)
			{ 
			case 0:
				# Prevents amounts from going below 0
				if ( -$amount <= $this->_food)
				{
					$this->_food += $amount;
				}
				else
				{
					$changeAmt = $this->_food;
					$this->_food = 0;
				}
				break;
				
			case 1:
                if ( -$amount  <= $this->_money)
				{
					$this->_money += $amount;
				}
				else
				{
					$changeAmt = $this->_money;
					$this->_money = 0;
				}
				break;

			case 2:
                if ( -$amount  <= $this->_bait)
				{
					$this->_bait += $amount;
				}
				else
				{
					$changeAmt = $this->_bait;
					$this->_bait = 0;
				}
				break;

			case 3:
				if ( -$amount  <= $this->_clothes)
				{
					$this->_clothes += $amount;
				}
				else
				{
					$changeAmt = $this->_clothes;
					$this->_clothes = 0;
				}
				break;

			case 4:
				if ( -$amount  <= $this->_wagonWheels)
				{
					$this->_wagonWheels += $amount;
				}
				else
				{
					$changeAmt = $this->_wagonWheels;
					$this->_wagonWheels = 0;
				}
				break;

			case 5:
				if ( -$amount  <= $this->_wagonAxle)
				{
					$this->_wagonAxle += $amount;
				}
				else
				{
					$changeAmt = $this->_wagonAxle;
					$this->_wagonAxle = 0;
				}
				break;

			case 6:
                if ( -$amount  <= $this->_wagonTongue)
				{
					$this->_wagonTongue += $amount;
				}
				else
				{
					$changeAmt = $this->_wagonTongue;
					$this->_wagonTongue = 0;
				}
				break;

			case 7:
                if ( -$amount  <= $this->_oxen)
				{
					$this->_oxen += $amount;
				}
				else
				{
					$changeAmt = $this->_oxen;
					$this->_oxen = 0;
				}
				break;
			}

			return $changeAmt;
		}

	}

	#class for the whole party
	class Party
	{
		public $_members = []; #array containing all party members
		public $_health = "Good";	#general health of the party
		public $_supplies;	#supplies class instant
		public $_livingMembers = 5; #licing members of the party
		public $_leader;
		public $_rate = 3; #rate at which food is eaten
		public $_job;
		public $_jobVal = [["empty",0],["Banker", 1600.00], 
							["Carpenter", 800.00],["Farmer",400.00]];

		public function __construct($names, $job)
		{
			# names are given to constructor and made into party members
			$this->_members[0] = new PartyMember($names[0]);
			$this->_members[1] = new PartyMember($names[1]);
			$this->_members[2] = new PartyMember($names[2]);
			$this->_members[3] = new PartyMember($names[3]);
			$this->_members[4] = new PartyMember($names[4]);

			$this->_leader = $names[0];
			#supplies made by passing in starting money
			$this->_job = $job;

			$this->_supplies = new Supplies($this->_jobVal[$job][1]);



		}

		#set ration rate
		public function setRate($newRate)
		{
			$this->_rate = $newRate;
		}

		#food is reduced based on living party members and ration rate
		public function eat()
		{
			foreach ($this->_members as $member)
			{
				if ($member->_alive)
				{
					if ($this->_rate <= $this->_supplies->_food)
					{
						$this->_supplies->eat($this->_rate);
					}
					else
					{
						# Party member harmed when they don't eat
						$member->_health -= 15;
					}
				}
			}
		}

		# Applies sickness damage to the party
		public function applyDamage()
		{
			foreach ($this->_members as $member)
			{
				if ($member->_alive)
				{
					$member->takeDamage();
				}
			}
		}

		#checks if any members can be killed (has 0 health)
		public function killCheck()
		{
			$checkLeader = false;
			$diedList = array();
			foreach($this->_members as $body)
			{
				if($body->_alive)
				{
					if($body->_health <= 0)
					{
						$checkLeader = true;
						$body->_alive = false;
						$body->_health = 0;
						$this->_livingMembers-=1;
						array_push($diedList, $body->_name);
					}
				}
			}
			if ($checkLeader)
			{
				foreach($this->_members as $body)
				{
					if($body->_alive)
					{
						$this->_leader = $body->_name;
						break;
					}
				}
			}

			return $diedList;
		}

		#averages health and assigns a rating
		public function health()
		{
			$sum = 0;
			$average = 0;
			foreach ($this->_members as $body) {
				if($body->_alive)
				{
					$sum += $body->_health;
				}
			}

			if ($this->_livingMembers != 0)
			{
				$average = $sum / $this->_livingMembers;
			}
			else
			{
				$average = 0;
			}


			if($average <= 0)
			{
				$this->_health = "Game Over";
			}
			else if($average >= 75)
			{
				$this->_health = "Good";
			}
			else if($average > 50)
			{
				$this->_health = "Fair";
			}
			else if($average > 25)
			{
				$this->_health = "Poor";
			}
			else 
			{
				$this->_health = "Bad";
			}

		}

		#rest function that calls each member's heal function
		public function rest($healRate)
		{
			foreach ($this->_members as $body) 
			{
				if($body->_alive)
				{
					$body->heal($healRate);
				}
			}	
		}

	}

	/**
	*shops that can be seen at landmarks
	*/
	class Shop
	{
		#costs for various objects
		public $_clothes; 
		public $_food;
		public $_bait;
		public $_parts;
		public $_yoke;

		public function __construct($local)
		{
		#costs are set based on a base cost and increase 
		#with each new location at a set rate
		$this->_clothes = 10 + 2.5 * $local;
		$this->_food  = .2 + .1 * $local;
		$this->_bait  =  2 + 2.5 * $local;
		$this->_parts  = 10 + 2.5 * $local;
		$this->_yoke  = 40 + 5 * $local;
		}
	}

	/**
	*Landmarks are stops allong the trail
	*/
	class Landmark
	{
		public static $_numShops = 0;
		public $_hasShop; #has a shop or not
		public $_shopIndex; # if this has a shop, the index of the shop
		public $_name; #name of the landmark
		public $_distance;	#distance along the trail
		public $_image;

		public function __construct($hasShop, $name, $distance, $image)
		{
			$this->_hasShop = $hasShop;
			$this->_name = $name;
			$this->_distance = $distance;
			$this->_image = $image;

			
			if ($this->_hasShop == TRUE)
			{
				self::$_numShops++;
				$this->_shopIndex = self::$_numShops;
			}
			else
			{
				$this->_shopIndex = -1;
			}
		}
	}

	/**
	*Type of Landmark, adds depth and hasFerry
	*/
	class River extends Landmark
	{
		public $_depth; #depth of the river, invluences fording and rafting
		public $_hasFerry; #wheter a ferry is avalable or not

		public function __construct($name, $distance, $image, $depth, $hasFerry)
		{
			#notice the super ensures there is no shop avalable
			parent::__construct(false, $name, $distance, $image);

			$this->_depth = $depth;
			$this->_hasFerry = $hasFerry;
		}

		#states: return 0 for no chance of bad event
		#1 -  chance of minor event
		#2 - chance of major bad event
		#chance based on river depth
		public function ford()
		{
			if ($this->_depth <= 2.5)
			{
				return 0;
			}
			elseif ($this->_depth < 3.0) {
				return 1;
			}
			else{return 2;}
		}

	}

	/**
	* contains values related to traveling
	*/
	class Journey
	{
		public $_maxDate = array('January' => 31,
						  'Febuary' => 28,
						  'March' => 31,
						  'April' => 30,
						  'May' => 31,
						  'June' => 30,
						  'July' => 31,
						  'August' => 31,
						  'September' => 30,
						  'October' => 31,
						  'November' => 30,
						  'December' => 31);
		public $_months = array('January','Febuary','March','April',
						  'May','June','July','August',
						  'September','October','November','December');

		public $_date; #current date, constantlly increments 
		public $_month;
		public $_distance; #distance traveled
		public $_speed; # Travel rate, either 20, 30, or 40 depending on pace
		public $_weather; #current weather, effects events
		public $_locations; #array of all landmarks
		public function __construct($date, $month)
		{
			$this->_date = $date;
			$this->_month = $this->_months[$month - 1];
			$this->_speed = 20;
			$this->_distance = 0;
			$this->_weather = "sunny";
			$this->_locations = array( new River("Kansas River Crossing", 102, 
								"assets/river1.jpg", 2, TRUE),
					      new River("Big Blue River Crossing", 185, 
					      		"assets/river2.jpg", 2.3, FALSE),
					      new Landmark(TRUE, "Fort Kearney", 304, "assets/land1.jpg"),
					      new Landmark(FALSE, "Chimney Rock", 554, "assets/land2.jpg"),
						  new Landmark(TRUE, "Fort Laramie", 640, "assets/land3.jpg"),
						  new Landmark(FALSE, "Independence Rock", 830, "assets/land4.jpg"),
						  new Landmark(FALSE, "South Pass", 932, "assets/land5.jpg"),
						  new River("Green River Crossing", 989, 
						  		"assets/river3.jpg",2.6, TRUE),
						  new Landmark(FALSE, "Soda Springs", 1133, "assets/land6.jpg"),
						  new Landmark(TRUE, "Fort Hall", 1190, "assets/land7.jpg"),
						  new River("Snake River Crossing", 1372, 
						  	"assets/river4.jpg", 3.0, FALSE),
						  new Landmark(TRUE, "Fort Boise", 1486, "assets/land8.jpg"),
						  new Landmark(FALSE, "Blue Mountains", 1646, "assets/land9.jpg"),
						  new Landmark(FALSE, "The Dalles", 1771, "assets/land10.jpg"),
						  new Landmark(TRUE, "Williamette Valley", 1871, "assets/land11.jpg"),
						  new Landmark(FALSE, "Oregon City", 2000, null));
		}

		public function nextLandmark()
		{
			#searches the array for the next highest location based on
			#current location
			$count = 0;
			foreach ($this->_locations as $local) {
				if($this->_distance < $local->_distance)
					{
						return $count;
					}
					$count +=1;
				}
		}

		# Advances the party via their speed
		public function progress($slowfactor = 1.0)
		{
			$nextLandmark = $this->nextLandmark();
			$this->_distance += $this->_speed / $slowfactor;
			if ($this->_distance > $this->_locations[$nextLandmark]->_distance)
			{
				$this->_distance = $this->_locations[$nextLandmark]->_distance;
			}

			return $this->_distance;
		}

		#increments the day
		public function incrementDay()
		{
			#increase day
			$this->_date += 1;
			#checks if Day is higher then max days for the month
			if($this->_date > $this->_maxDate[$this->_month])
			{
				#resset date to 1, move to next month
				$this->_date = 1;
				$monthI = array_search($this->_month, $this->_months) + 1;

				#loop month back to 0
				if($monthI > 11){$monthI = 0;}

				$this->_month = $this->_months[$monthI];
			}
		}

		# Binary searches the locations array for a specified landmark given a distance
		# Gets returned in the format [index, object] or null if nothing was found
		public function getLandmark($distance)
		{
			$hi = count($this->_locations) - 1;
			$lo = 0;

			while ($lo != $hi)
			{
				$mid = floor(($hi + $lo) / 2);

				if ($this->_locations[$mid]->_distance == $distance)
				{
					return array($mid, $this->_locations[$mid]);
				}
				else if ($distance < $this->_locations[$mid]->_distance)
				{
					$hi = $mid - 1;
				}
				else if ($distance > $this->_locations[$mid]->_distance)
				{
					$lo = $mid + 1;
				}
			}

			// Check a size-1 partition just in case
			if ($this->_locations[$hi]->_distance == $distance)
			{
				return array( $hi, $this->_locations[$hi] );
			}
			else
			{
				return null;
			}
		}
	}

# Found on the PHP website
function erase_session()
{

	// Initialize the session.
	// If you are using session_name("something"), don't forget it now!
	session_start();

	// Unset all of the session variables.
	$_SESSION = array();

	// If it's desired to kill the session, also delete the session cookie.
	// Note: This will destroy the session, and not just the session data!
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}

	// Finally, destroy the session.
	session_destroy();
}

# Since this is a pure PHP file, it's best practice to leave the end tag off
# to avoid extraneous whitespace issues
