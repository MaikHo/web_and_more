<?php
class DB extends PDO	// Klasse erbt den Konstruktor von klasse PDO
{
	protected $host;
	protected $user;
	protected $password;
	protected $dbname;
	protected $dsn;
	protected $dbtype;

	
//======================================================================
// Konstruktor
//======================================================================	
	public function __construct($_dbtype,$_host,$_user,$_password,$_dbname)
	{
		$this->dbtype=$_dbtype;	
		$this->host=$_host;	
		$this->user=$_user; //."fehler";	
		$this->password=$_password;	
		$this->dbname=$_dbname;	
		/*		nicht gegeben beim UniformServer
		$methods= get_class_methods('Database');
		foreach($methods as $methodName)
			{ $methodList .= $methodName."();";}
		foreach(PDO::getAvailableDrivers() as $drivers)
			{$driverslist .= $drivers.'; ';}
			echo "<script>console.debug('Existing methods of Database:".$methodList."');".
			"console.debug('Available Drivers: ".$driverslist."' )</script>";
		$attributes = (array)$this;
		foreach($attributes as $key => $attribName)
			{ $attribList .= $key."=".$attribName.";";}
			echo "<script>console.debug('Existing attributes of Object in class ".get_class().":".$attribList."');</script>";	
		*/
		$dsn="$this->dbtype:host=$this->host;dbname=$this->dbname";
			//.$this->user.",".$this->password;
			echo "<script>console.debug('dsn: ".$dsn."');</script>";	
		parent::__construct($dsn,$this->user,$this->password);
			 
        try 
        { 
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        }
        catch (PDOException $e) 
        {
            die($e->getMessage());
        }
					
	}


	
	
	
}










?>