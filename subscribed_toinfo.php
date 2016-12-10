<?php

// Global variable for table object
$subscribed_to = NULL;

//
// Table class for subscribed_to
//
class csubscribed_to extends cTable {
	var $serviceid;
	var $customerid;
	var $servicename;
	var $subscriberid;
	var $regphone;
	var $rechargedate;
	var $rechargeamount;
	var $rechargedue;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'subscribed_to';
		$this->TableName = 'subscribed_to';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`subscribed_to`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// serviceid
		$this->serviceid = new cField('subscribed_to', 'subscribed_to', 'x_serviceid', 'serviceid', '`serviceid`', '`serviceid`', 3, -1, FALSE, '`serviceid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->serviceid->Sortable = TRUE; // Allow sort
		$this->serviceid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['serviceid'] = &$this->serviceid;

		// customerid
		$this->customerid = new cField('subscribed_to', 'subscribed_to', 'x_customerid', 'customerid', '`customerid`', '`customerid`', 3, -1, FALSE, '`customerid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->customerid->Sortable = TRUE; // Allow sort
		$this->customerid->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->customerid->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->customerid->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['customerid'] = &$this->customerid;

		// servicename
		$this->servicename = new cField('subscribed_to', 'subscribed_to', 'x_servicename', 'servicename', '`servicename`', '`servicename`', 3, -1, FALSE, '`servicename`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->servicename->Sortable = TRUE; // Allow sort
		$this->servicename->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->servicename->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->servicename->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['servicename'] = &$this->servicename;

		// subscriberid
		$this->subscriberid = new cField('subscribed_to', 'subscribed_to', 'x_subscriberid', 'subscriberid', '`subscriberid`', '`subscriberid`', 200, -1, FALSE, '`subscriberid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->subscriberid->Sortable = TRUE; // Allow sort
		$this->fields['subscriberid'] = &$this->subscriberid;

		// regphone
		$this->regphone = new cField('subscribed_to', 'subscribed_to', 'x_regphone', 'regphone', '`regphone`', '`regphone`', 20, -1, FALSE, '`regphone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->regphone->Sortable = TRUE; // Allow sort
		$this->regphone->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['regphone'] = &$this->regphone;

		// rechargedate
		$this->rechargedate = new cField('subscribed_to', 'subscribed_to', 'x_rechargedate', 'rechargedate', '`rechargedate`', ew_CastDateFieldForLike('`rechargedate`', 7, "DB"), 133, 7, FALSE, '`rechargedate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rechargedate->Sortable = TRUE; // Allow sort
		$this->rechargedate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['rechargedate'] = &$this->rechargedate;

		// rechargeamount
		$this->rechargeamount = new cField('subscribed_to', 'subscribed_to', 'x_rechargeamount', 'rechargeamount', '`rechargeamount`', '`rechargeamount`', 4, -1, FALSE, '`rechargeamount`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rechargeamount->Sortable = TRUE; // Allow sort
		$this->rechargeamount->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['rechargeamount'] = &$this->rechargeamount;

		// rechargedue
		$this->rechargedue = new cField('subscribed_to', 'subscribed_to', 'x_rechargedue', 'rechargedue', '`rechargedue`', ew_CastDateFieldForLike('`rechargedue`', 7, "DB"), 133, 7, FALSE, '`rechargedue`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->rechargedue->Sortable = TRUE; // Allow sort
		$this->rechargedue->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_SEPARATOR"], $Language->Phrase("IncorrectDateDMY"));
		$this->fields['rechargedue'] = &$this->rechargedue;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`subscribed_to`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('serviceid', $rs))
				ew_AddFilter($where, ew_QuotedName('serviceid', $this->DBID) . '=' . ew_QuotedValue($rs['serviceid'], $this->serviceid->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`serviceid` = @serviceid@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->serviceid->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@serviceid@", ew_AdjustSql($this->serviceid->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "subscribed_tolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "subscribed_tolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("subscribed_toview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("subscribed_toview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "subscribed_toadd.php?" . $this->UrlParm($parm);
		else
			$url = "subscribed_toadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("subscribed_toedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("subscribed_toadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("subscribed_todelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "serviceid:" . ew_VarToJson($this->serviceid->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->serviceid->CurrentValue)) {
			$sUrl .= "serviceid=" . urlencode($this->serviceid->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["serviceid"]))
				$arKeys[] = ew_StripSlashes($_POST["serviceid"]);
			elseif (isset($_GET["serviceid"]))
				$arKeys[] = ew_StripSlashes($_GET["serviceid"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->serviceid->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->serviceid->setDbValue($rs->fields('serviceid'));
		$this->customerid->setDbValue($rs->fields('customerid'));
		$this->servicename->setDbValue($rs->fields('servicename'));
		$this->subscriberid->setDbValue($rs->fields('subscriberid'));
		$this->regphone->setDbValue($rs->fields('regphone'));
		$this->rechargedate->setDbValue($rs->fields('rechargedate'));
		$this->rechargeamount->setDbValue($rs->fields('rechargeamount'));
		$this->rechargedue->setDbValue($rs->fields('rechargedue'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// serviceid
		// customerid

		$this->customerid->CellCssStyle = "white-space: nowrap;";

		// servicename
		// subscriberid
		// regphone
		// rechargedate
		// rechargeamount
		// rechargedue
		// serviceid

		$this->serviceid->ViewValue = $this->serviceid->CurrentValue;
		$this->serviceid->ViewCustomAttributes = "";

		// customerid
		if (strval($this->customerid->CurrentValue) <> "") {
			$sFilterWrk = "`customerid`" . ew_SearchString("=", $this->customerid->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `customerid`, `customername` AS `DispFld`, `houseno` AS `Disp2Fld`, `locality` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `customer_info`";
		$sWhereWrk = "";
		$this->customerid->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->customerid, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$arwrk[3] = $rswrk->fields('Disp3Fld');
				$this->customerid->ViewValue = $this->customerid->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->customerid->ViewValue = $this->customerid->CurrentValue;
			}
		} else {
			$this->customerid->ViewValue = NULL;
		}
		$this->customerid->ViewCustomAttributes = "";

		// servicename
		if (strval($this->servicename->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->servicename->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `valuename` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `services`";
		$sWhereWrk = "";
		$this->servicename->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->servicename, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->servicename->ViewValue = $this->servicename->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->servicename->ViewValue = $this->servicename->CurrentValue;
			}
		} else {
			$this->servicename->ViewValue = NULL;
		}
		$this->servicename->ViewCustomAttributes = "";

		// subscriberid
		$this->subscriberid->ViewValue = $this->subscriberid->CurrentValue;
		$this->subscriberid->ViewCustomAttributes = "";

		// regphone
		$this->regphone->ViewValue = $this->regphone->CurrentValue;
		$this->regphone->ViewCustomAttributes = "";

		// rechargedate
		$this->rechargedate->ViewValue = $this->rechargedate->CurrentValue;
		$this->rechargedate->ViewValue = ew_FormatDateTime($this->rechargedate->ViewValue, 7);
		$this->rechargedate->ViewCustomAttributes = "";

		// rechargeamount
		$this->rechargeamount->ViewValue = $this->rechargeamount->CurrentValue;
		$this->rechargeamount->ViewCustomAttributes = "";

		// rechargedue
		$this->rechargedue->ViewValue = $this->rechargedue->CurrentValue;
		$this->rechargedue->ViewValue = ew_FormatDateTime($this->rechargedue->ViewValue, 7);
		$this->rechargedue->ViewCustomAttributes = "";

		// serviceid
		$this->serviceid->LinkCustomAttributes = "";
		$this->serviceid->HrefValue = "";
		$this->serviceid->TooltipValue = "";

		// customerid
		$this->customerid->LinkCustomAttributes = "";
		$this->customerid->HrefValue = "";
		$this->customerid->TooltipValue = "";

		// servicename
		$this->servicename->LinkCustomAttributes = "";
		$this->servicename->HrefValue = "";
		$this->servicename->TooltipValue = "";

		// subscriberid
		$this->subscriberid->LinkCustomAttributes = "";
		$this->subscriberid->HrefValue = "";
		$this->subscriberid->TooltipValue = "";

		// regphone
		$this->regphone->LinkCustomAttributes = "";
		$this->regphone->HrefValue = "";
		$this->regphone->TooltipValue = "";

		// rechargedate
		$this->rechargedate->LinkCustomAttributes = "";
		$this->rechargedate->HrefValue = "";
		$this->rechargedate->TooltipValue = "";

		// rechargeamount
		$this->rechargeamount->LinkCustomAttributes = "";
		$this->rechargeamount->HrefValue = "";
		$this->rechargeamount->TooltipValue = "";

		// rechargedue
		$this->rechargedue->LinkCustomAttributes = "";
		$this->rechargedue->HrefValue = "";
		$this->rechargedue->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// serviceid
		$this->serviceid->EditAttrs["class"] = "form-control";
		$this->serviceid->EditCustomAttributes = "";
		$this->serviceid->EditValue = $this->serviceid->CurrentValue;
		$this->serviceid->ViewCustomAttributes = "";

		// customerid
		$this->customerid->EditAttrs["class"] = "form-control";
		$this->customerid->EditCustomAttributes = "";

		// servicename
		$this->servicename->EditAttrs["class"] = "form-control";
		$this->servicename->EditCustomAttributes = "";

		// subscriberid
		$this->subscriberid->EditAttrs["class"] = "form-control";
		$this->subscriberid->EditCustomAttributes = "";
		$this->subscriberid->EditValue = $this->subscriberid->CurrentValue;
		$this->subscriberid->PlaceHolder = ew_RemoveHtml($this->subscriberid->FldCaption());

		// regphone
		$this->regphone->EditAttrs["class"] = "form-control";
		$this->regphone->EditCustomAttributes = "";
		$this->regphone->EditValue = $this->regphone->CurrentValue;
		$this->regphone->PlaceHolder = ew_RemoveHtml($this->regphone->FldCaption());

		// rechargedate
		$this->rechargedate->EditAttrs["class"] = "form-control";
		$this->rechargedate->EditCustomAttributes = "";
		$this->rechargedate->EditValue = ew_FormatDateTime($this->rechargedate->CurrentValue, 7);
		$this->rechargedate->PlaceHolder = ew_RemoveHtml($this->rechargedate->FldCaption());

		// rechargeamount
		$this->rechargeamount->EditAttrs["class"] = "form-control";
		$this->rechargeamount->EditCustomAttributes = "";
		$this->rechargeamount->EditValue = $this->rechargeamount->CurrentValue;
		$this->rechargeamount->PlaceHolder = ew_RemoveHtml($this->rechargeamount->FldCaption());
		if (strval($this->rechargeamount->EditValue) <> "" && is_numeric($this->rechargeamount->EditValue)) $this->rechargeamount->EditValue = ew_FormatNumber($this->rechargeamount->EditValue, -2, -1, -2, 0);

		// rechargedue
		$this->rechargedue->EditAttrs["class"] = "form-control";
		$this->rechargedue->EditCustomAttributes = "";
		$this->rechargedue->EditValue = ew_FormatDateTime($this->rechargedue->CurrentValue, 7);
		$this->rechargedue->PlaceHolder = ew_RemoveHtml($this->rechargedue->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->serviceid->Exportable) $Doc->ExportCaption($this->serviceid);
					if ($this->servicename->Exportable) $Doc->ExportCaption($this->servicename);
					if ($this->subscriberid->Exportable) $Doc->ExportCaption($this->subscriberid);
					if ($this->regphone->Exportable) $Doc->ExportCaption($this->regphone);
					if ($this->rechargedate->Exportable) $Doc->ExportCaption($this->rechargedate);
					if ($this->rechargeamount->Exportable) $Doc->ExportCaption($this->rechargeamount);
					if ($this->rechargedue->Exportable) $Doc->ExportCaption($this->rechargedue);
				} else {
					if ($this->serviceid->Exportable) $Doc->ExportCaption($this->serviceid);
					if ($this->customerid->Exportable) $Doc->ExportCaption($this->customerid);
					if ($this->servicename->Exportable) $Doc->ExportCaption($this->servicename);
					if ($this->subscriberid->Exportable) $Doc->ExportCaption($this->subscriberid);
					if ($this->regphone->Exportable) $Doc->ExportCaption($this->regphone);
					if ($this->rechargedate->Exportable) $Doc->ExportCaption($this->rechargedate);
					if ($this->rechargeamount->Exportable) $Doc->ExportCaption($this->rechargeamount);
					if ($this->rechargedue->Exportable) $Doc->ExportCaption($this->rechargedue);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->serviceid->Exportable) $Doc->ExportField($this->serviceid);
						if ($this->servicename->Exportable) $Doc->ExportField($this->servicename);
						if ($this->subscriberid->Exportable) $Doc->ExportField($this->subscriberid);
						if ($this->regphone->Exportable) $Doc->ExportField($this->regphone);
						if ($this->rechargedate->Exportable) $Doc->ExportField($this->rechargedate);
						if ($this->rechargeamount->Exportable) $Doc->ExportField($this->rechargeamount);
						if ($this->rechargedue->Exportable) $Doc->ExportField($this->rechargedue);
					} else {
						if ($this->serviceid->Exportable) $Doc->ExportField($this->serviceid);
						if ($this->customerid->Exportable) $Doc->ExportField($this->customerid);
						if ($this->servicename->Exportable) $Doc->ExportField($this->servicename);
						if ($this->subscriberid->Exportable) $Doc->ExportField($this->subscriberid);
						if ($this->regphone->Exportable) $Doc->ExportField($this->regphone);
						if ($this->rechargedate->Exportable) $Doc->ExportField($this->rechargedate);
						if ($this->rechargeamount->Exportable) $Doc->ExportField($this->rechargeamount);
						if ($this->rechargedue->Exportable) $Doc->ExportField($this->rechargedue);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
