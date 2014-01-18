<?

class updater
{
	private $_table;
	private $_condition;
	private $_firstRun = true;
	private $_insert = false;
	
	private $_method = 0;
	
	private $fields;
	private $insertfields;
	
	private $values;
	private $insertvalues;
	
	private $_dbh;
	
	public $insertid;
	public $numrows;

	public function __construct($table, $condition=null, $method=2, $dbh=null)
	{
		if($dbh==null)
		{
		$dsn = "mysql:host=".DBHOST.";dbname=".DBNAME;
		$dbh = new PDO($dsn,DBUSER,DBPASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\''));
//		$dsn = "mysql:host=".$UTMDB_HOST.";dbname=tech";
//		$dbh = new PDO($dsn,$ROOT_LOGIN,$ROOT_PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\''));
		}

		$this->_table = $table;
		$this->_condition = $condition;
		$this->_method = $method;
		$this->_dbh = $dbh;		
	}
						
	public function AddParameter($pname, $pvalue)
	{
		$this->fields[$pname]=$pvalue;
		//$this->values[$pname]=$pvalue;
	}
	
	public function AddDate($pname)
	{
		$this->fields[$pname]=date("Y-m-d H:i");
	}

	public function AddInsertParameter($pname, $pvalue)
	{
		$this->insertfields[$pname]=$pvalue;
	}
	
	public function CountParameters()
	{
		return count($this->fields)+count($this->insertfields);
	}
	
	protected function GetQuery()
	{
		$sqlquery = $this->_insert ? ("INSERT INTO ".$this->_table." ("):("UPDATE ".$this->_table." SET ");
		$insquery="";
		$first = true;

		foreach($this->fields as $field => $value)
		{
			if($this->_insert)
			{
				$sqlquery.= ($first ? "" : ", ").$field;
				$insquery .= ($first ? "" : ", ").":".$field;
			}
			else 
				$sqlquery .= ($first ? "" : ", ").$field."=:".$field;
				
				$first = false;
		}
		
		if($this->_insert)
		{
			if(count($this->insertfields)>0)
				foreach($this->insertfields as $field => $value)
				{
				$sqlquery.= ($first ? "" : ", ").$field;
				$insquery .= ($first ? "" : ", ").":".$field;
				}
			
				/*if($this->_firstRun)
					$values[":".$field] = $this->insertvalues[$field]; */			
			$sqlquery .= ") VALUES (".$insquery.")";	
		}
		else 
			$sqlquery .= " WHERE ".$this->_condition;
		
		return $sqlquery;		
		 
	}
	
	protected function DefineQueryMethod()
	{
		switch($this->_method)
		{
			case 0: // для вставки данных
				$this->_insert = true; break;
			case 1: // для обновления
				$this->_insert = false; break;
			case 2: //
				try {

				if($this->_condition == null)
					$this->_insert = true;
				else
				{
			

				$query = "SELECT COUNT(*) FROM ".$this->_table." WHERE ".$this->_condition;
				$res = $this->_dbh->query($query);

				if($res!=null)
				{
					$row=$res->fetch();
					$this->_insert = $row[0]==0;
				}
				else 
					throw new Exception("Ошибка в Check-запросе  $query "); 

				}
				
		} catch(Exception $e)
		{	
			echo $e;
			//123
		}
				
				break;
		}	
	}
	
	public function Run()
	{
		if(count($this->fields)==0)
			return 0;

			
		$this->DefineQueryMethod();
		$query = $this->GetQuery();
		
		if(DEBUG==2)
		{
			echo $query."\n";
			print_r($this->fields);
			return;
		}     
		try {
		$sth = $this->_dbh->prepare($query);

		if($this->_insert && count($this->insertfields)>0)
			$this->fields = array_merge($this->fields,$this->insertfields);


		$sth->execute($this->fields);
		} catch(Exception $e) { 
		echo "Exception: ";
		print_r($e); 
		}


		if( $sth->errorCode()!="00000" )
		{
			echo "Error info: <br>";
			echo $query."<br>";
			print_r($sth->errorInfo());
			print_r($this->fields);
			die();
		}

		$this->insertid = $this->_dbh->lastInsertId();

		if($this->_insert)
			$this->_firstRun=false;
			
		return $this->_insert?0:1;
	}
	
	public function RunInsert()
	{
		
	}

	public function Query($query)
	{
		return $this->_dbh->query($query);
	}
	
	public function Table()
	{
	}
	
	public function UpdMethod()
	{
		
	}
}


?>