<?php
class DBConnection
{
	var $username, $password, $host, $database;
	private $conn;

	public function __construct()
	{
		if (!defined('APP_KEY')) {
    		define('APP_KEY', '8eocgc57jnxfskl6');
		}
		
		require_once dirname(__FILE__)."/BanglalinkCrypt/BanglalinkCrypt.php";
		BanglalinkCrypt::setEncryptionKey(APP_KEY);

		$password = 'rF29dt0IFb7OdtsupCDR2w==';
		
		$this->username = 'ccdmis_app';
		$this->password = BanglalinkCrypt::decrypt($password);
		$this->host = '172.16.11.209';
		$this->database = 'ameyo';

		$conn = mysql_connect($this->host, $this->username, $this->password) or die("Could not connect to host");

		$this->conn = $conn;

		$db = mysql_select_db($this->database, $conn) or die("Could not connect to the database.");

		mysql_query("SET NAMES 'utf8'");
		mysql_query("SET CHARACTER SET utf8");
		mysql_query("SET SESSION collation_connection = 'utf8_unicode_ci'");
	}

	public function fetchAll()
	{
		//$sql = "SELECT * FROM canned_copy WHERE publish = 1"; //source, category LIMIT 0,10

		$sql = "SELECT c.Id, c.source, c.category, c.subcategory, c.label, c.language, c.content FROM canned_copy c WHERE publish = 1"; //source, category LIMIT 0,10

		$result = $this->execute_query($sql);

		$arr = $this->db_result_to_array($result);

		return json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	}

	
	public function fetchAllAdmin()
	{
		$sql = "SELECT
				  `c`.`Id`,
				  `c`.`source`,
				  `c`.`category`,
				  `c`.`subcategory`,
				  `c`.`label`,
				  `c`.`language`,
				  `c`.`content`,
				  `c`.`agentID`,
				  `c`.`entryDate`,
				  IF(`c`.`publish` = 1, 'Yes', 'No') AS 'publish',
				  `l`.`user`,
				  `l`.`modifyDate`
				FROM
				  `canned_copy` c
				LEFT JOIN `canned_log` l ON `c`.`last_modify_id` = `l`.`Id`
				ORDER BY `c`.`Id` ASC"; //source, category LIMIT 0,10

		$result = $this->execute_query($sql);

		$arr = $this->db_result_to_array($result);

		return json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

	}

	public function insertMessage($channel,$category,$subcategory,$labelQ,$language,$content,$agentID,$today,$publish)
	{
		$sql = "INSERT INTO canned_copy (`source`,  `category`,  `subcategory`,  `label`,  `language`,  `content`,  `agentID`,  `entryDate`, `publish`)
				VALUES ('$channel', '$category', '$subcategory', '$labelQ', '$language', '$content', '$agentID', '$today', '$publish')";
		//exit;

		$result = $this->execute_query($sql);

		if(!$result) 
			return false;
		else 
			return true;
	}

	public function insertHit($id, $userId)
	{
		$sql = "INSERT INTO hit_log VALUES('$id', '$userId', curdate())";

		$result = $this->execute_query($sql);
	}

	public function reportHitCount($startdate,$enddate)
	{
		$sql = "SELECT
				  `a`.`Id`,
				  `a`.`source` AS 'Source',
				  `a`.`category` AS 'Category',
				  `a`.`subcategory` AS 'Subcategory',
				  `a`.`label` AS 'Label',
				  `a`.`language` AS 'Language',
				  `a`.`content` AS 'Content',
					IF(`a`.`publish` = 1, 'Yes', 'No') AS 'Published',	
				  IFNULL(`b`.`cnt`, 0) AS 'Count'
				FROM
				  `canned_copy` a
				    LEFT JOIN 
					    (SELECT
					      `Id`, COUNT(`Id`) AS 'cnt'
					    FROM
					      `hit_log` h
					    WHERE
					      `h`.`date` BETWEEN '$startdate' AND '$enddate'
					    GROUP BY
					      `Id`) b ON `a`.`Id` = `b`.`Id`
				ORDER BY cnt DESC ";

		$result = $this->execute_query($sql);

		return $result;
	}


	public function getMessageById($id)
	{
		$sql = "SELECT * FROM canned_copy WHERE publish in (1,0) AND Id = '$id'"; //source, category LIMIT 0,10

		$result = $this->execute_query($sql);

		$arr = $this->db_result_to_array($result);

		return json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	}

	public function updateMessage($id,$channel,$category,$subcategory,$labelQ,$language,$content, $agentID, $today, $publish, $updateLogId)
	{
		$sql = "UPDATE canned_copy SET `source`='$channel', `category`='$category',`subcategory`='$subcategory', `label`='$labelQ', `language`='$language',`content`='$content', `publish`='$publish', `last_modify_id`='$updateLogId' WHERE `Id`='$id'";
		//exit;

		$result = $this->execute_query($sql);

		if(!$result) 
			return false;
		else 
			return true;
	}

	public function updateLog($id, $agentID, $today)
	{
		$sql = "INSERT INTO canned_log (messageId, user, modifyDate) VALUES('$id', '$agentID', now())";

		$result = $this->execute_query($sql);

		if(!$result) 
			return false;
		else 
			return mysql_insert_id();
	}

	public function unPublishMessage($id, $userId, $publish)
	{
		$sql = "UPDATE canned_copy SET `publish`='$publish' WHERE `Id`='$id'";

		$result = $this->execute_query($sql);

		$this->updateLog($id, $userId, $today);

		if(!$result) 
			return false;
		else 
			return true;
	}


	public function getCategory()
	{

		$sql = "SELECT * FROM category ORDER BY value ASC";

		$result = $this->execute_query($sql);

		$arr = $this->db_result_to_array($result);

		return json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	}

	public function insertNewCategory($newCat, $agentID, $today)
	{
		$sql = "INSERT INTO category (value, entry_date, entry_by) VALUES('$newCat', '$today', '$agentID')";

		$result = $this->execute_query($sql);

		if(!$result) 
			return false;
		else 
			return true;
	}

	public function getSubCategory()
	{

		$sql = "SELECT * FROM subcategory ORDER BY value ASC";

		$result = $this->execute_query($sql);

		$arr = $this->db_result_to_array($result);

		return json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	}

	public function insertNewSubCategory($newSubCat, $agentID, $today)
	{
		$sql = "INSERT INTO subcategory (value, entry_date, entry_by) VALUES ('$newSubCat', '$today', '$agentID')";

		$result = $this->execute_query($sql);

		if(!$result) 
			return false;
		else 
			return true;
	}

	public function getSource()
	{

		$sql = "SELECT * FROM source ORDER BY value ASC";

		$result = $this->execute_query($sql);

		$arr = $this->db_result_to_array($result);

		return json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
	}

	public function insertNewSource($newSource, $agentID, $today)
	{
		$sql = "INSERT INTO source (value, entry_date, entry_by) VALUES ('$newSource', '$today', '$agentID')";

		$result = $this->execute_query($sql);

		if(!$result) 
			return false;
		else 
			return true;
	}


	public function updateCategory($catId, $updateCat, $agentID, $today)
	{
		$sql = "UPDATE category SET `value`='$updateCat', `update_by`='$agentID', `update_date`='$today' WHERE `id`=$catId";

		$result = $this->execute_query($sql);

		if(!$result) 
			return false;
		else 
			return true;
	}


	public function updateSubCategory($subCatId, $updateSubCat, $agentID, $today)
	{
		$sql = "UPDATE subcategory SET `value`='$updateSubCat', `update_by`='$agentID', `update_date`='$today' WHERE `id`=$subCatId";

		$result = $this->execute_query($sql);

		if(!$result) 
			return false;
		else 
			return true;
	}

	public function fetchDimeloData($startdate,$enddate,$source,$cat,$txt,$authName)
	{
		$sql = "SELECT *
				FROM
				  `_dimelo_interaction`
				WHERE
				  STR_TO_DATE(`created_at`, '%m/%d/%Y') BETWEEN STR_TO_DATE('$startdate', '%m-%d-%Y') AND STR_TO_DATE('$enddate', '%m-%d-%Y')
				    AND `source_type` = '$source'
				    AND `author_name` like '%$authName%'";
				    
				    /*AND `categories` LIKE '%$cat%'
				    AND `body_as_text` LIKE '%$txt%' ";*/

		$result = $this->execute_query($sql);

		return $result;
	}

	/**Helper functions**/
	public function execute_query($sql)
	{
		return mysql_query($sql, $this->conn);
	}


	private function db_result_to_array($result)
	{
   		$res_array = array();
	    for ($count=0; $row = @mysql_fetch_array($result, MYSQL_ASSOC); $count++)
     		$res_array[$count] = $row;

     	// echo '<pre>';
     	// print_r($res_array);
     	// echo '</pre>';
     	// exit;

	    return $res_array;
	}

	public function var_dump($value)
	{
		echo '<pre>';
		var_dump($value);
		echo '</pre>';
	}

}

?>