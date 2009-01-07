	// Override method from ra_form
	function processSimpleCitation() {
		global $TBLPREFIX, $DBCONN;
		//-- delete any old census records
		$sql = "DELETE FROM ".$TBLPREFIX."taskfacts WHERE tf_t_id='".$DBCONN->escapeSimple($_REQUEST['taskid'])."' AND tf_factrec ".PGV_DB_LIKE." '1 %FACTTYPE%%'";
		$res = dbquery($sql);
		// Set our output to nothing, this supresses a warning that we would otherwise get.
		$out = "";
		$factrec = "1 %FACTTYPE%";
		$factrec .= "\r\n2 DATE ";
		$factrec .=!empty($_POST['EnumerationDate'])?$_POST['EnumerationDate']:"Unknown";
		$factrec .= "\r\n2 PLAC ".$_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", USA";
		$people = $this->getPeople();
		$pids = array_keys($people);
		//-- store the fact associations in the database
		$sql = "INSERT INTO ".$TBLPREFIX."taskfacts VALUES('".get_next_id("taskfacts", "tf_id")."'," .
			"'".$DBCONN->escapeSimple($_REQUEST['taskid'])."'," .
			"'".$DBCONN->escapeSimple($factrec)."'," .
			"'".$DBCONN->escapeSimple(implode(";", $pids))."', 'Y', 'indi')";
		$res = dbquery($sql);
		$rows = array();
		$text = $_POST['city'].", ".$_POST['county'].", ".$_POST['state'].", %FORMNAME%";
