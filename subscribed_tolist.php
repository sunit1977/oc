<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "subscribed_toinfo.php" ?>
<?php include_once "reg_usersinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$subscribed_to_list = NULL; // Initialize page object first

class csubscribed_to_list extends csubscribed_to {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}";

	// Table name
	var $TableName = 'subscribed_to';

	// Page object name
	var $PageObjName = 'subscribed_to_list';

	// Grid form hidden field names
	var $FormName = 'fsubscribed_tolist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;
	var $AuditTrailOnAdd = FALSE;
	var $AuditTrailOnEdit = FALSE;
	var $AuditTrailOnDelete = FALSE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (subscribed_to)
		if (!isset($GLOBALS["subscribed_to"]) || get_class($GLOBALS["subscribed_to"]) == "csubscribed_to") {
			$GLOBALS["subscribed_to"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["subscribed_to"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "subscribed_toadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "subscribed_todelete.php";
		$this->MultiUpdateUrl = "subscribed_toupdate.php";

		// Table object (reg_users)
		if (!isset($GLOBALS['reg_users'])) $GLOBALS['reg_users'] = new creg_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'subscribed_to', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (reg_users)
		if (!isset($UserTable)) {
			$UserTable = new creg_users();
			$UserTableConn = Conn($UserTable->DBID);
		}

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fsubscribed_tolistsrch";

		// List actions
		$this->ListActions = new cListActions();
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->serviceid->SetVisibility();
		$this->serviceid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->servicename->SetVisibility();
		$this->subscriberid->SetVisibility();
		$this->regphone->SetVisibility();
		$this->rechargedate->SetVisibility();
		$this->rechargeamount->SetVisibility();
		$this->rechargedue->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $subscribed_to;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($subscribed_to);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->serviceid->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->serviceid->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fsubscribed_tolistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->serviceid->AdvancedSearch->ToJSON(), ","); // Field serviceid
		$sFilterList = ew_Concat($sFilterList, $this->customerid->AdvancedSearch->ToJSON(), ","); // Field customerid
		$sFilterList = ew_Concat($sFilterList, $this->servicename->AdvancedSearch->ToJSON(), ","); // Field servicename
		$sFilterList = ew_Concat($sFilterList, $this->subscriberid->AdvancedSearch->ToJSON(), ","); // Field subscriberid
		$sFilterList = ew_Concat($sFilterList, $this->regphone->AdvancedSearch->ToJSON(), ","); // Field regphone
		$sFilterList = ew_Concat($sFilterList, $this->rechargedate->AdvancedSearch->ToJSON(), ","); // Field rechargedate
		$sFilterList = ew_Concat($sFilterList, $this->rechargeamount->AdvancedSearch->ToJSON(), ","); // Field rechargeamount
		$sFilterList = ew_Concat($sFilterList, $this->rechargedue->AdvancedSearch->ToJSON(), ","); // Field rechargedue
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["cmd"] == "savefilters") {
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "fsubscribed_tolistsrch", $filters);
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field serviceid
		$this->serviceid->AdvancedSearch->SearchValue = @$filter["x_serviceid"];
		$this->serviceid->AdvancedSearch->SearchOperator = @$filter["z_serviceid"];
		$this->serviceid->AdvancedSearch->SearchCondition = @$filter["v_serviceid"];
		$this->serviceid->AdvancedSearch->SearchValue2 = @$filter["y_serviceid"];
		$this->serviceid->AdvancedSearch->SearchOperator2 = @$filter["w_serviceid"];
		$this->serviceid->AdvancedSearch->Save();

		// Field customerid
		$this->customerid->AdvancedSearch->SearchValue = @$filter["x_customerid"];
		$this->customerid->AdvancedSearch->SearchOperator = @$filter["z_customerid"];
		$this->customerid->AdvancedSearch->SearchCondition = @$filter["v_customerid"];
		$this->customerid->AdvancedSearch->SearchValue2 = @$filter["y_customerid"];
		$this->customerid->AdvancedSearch->SearchOperator2 = @$filter["w_customerid"];
		$this->customerid->AdvancedSearch->Save();

		// Field servicename
		$this->servicename->AdvancedSearch->SearchValue = @$filter["x_servicename"];
		$this->servicename->AdvancedSearch->SearchOperator = @$filter["z_servicename"];
		$this->servicename->AdvancedSearch->SearchCondition = @$filter["v_servicename"];
		$this->servicename->AdvancedSearch->SearchValue2 = @$filter["y_servicename"];
		$this->servicename->AdvancedSearch->SearchOperator2 = @$filter["w_servicename"];
		$this->servicename->AdvancedSearch->Save();

		// Field subscriberid
		$this->subscriberid->AdvancedSearch->SearchValue = @$filter["x_subscriberid"];
		$this->subscriberid->AdvancedSearch->SearchOperator = @$filter["z_subscriberid"];
		$this->subscriberid->AdvancedSearch->SearchCondition = @$filter["v_subscriberid"];
		$this->subscriberid->AdvancedSearch->SearchValue2 = @$filter["y_subscriberid"];
		$this->subscriberid->AdvancedSearch->SearchOperator2 = @$filter["w_subscriberid"];
		$this->subscriberid->AdvancedSearch->Save();

		// Field regphone
		$this->regphone->AdvancedSearch->SearchValue = @$filter["x_regphone"];
		$this->regphone->AdvancedSearch->SearchOperator = @$filter["z_regphone"];
		$this->regphone->AdvancedSearch->SearchCondition = @$filter["v_regphone"];
		$this->regphone->AdvancedSearch->SearchValue2 = @$filter["y_regphone"];
		$this->regphone->AdvancedSearch->SearchOperator2 = @$filter["w_regphone"];
		$this->regphone->AdvancedSearch->Save();

		// Field rechargedate
		$this->rechargedate->AdvancedSearch->SearchValue = @$filter["x_rechargedate"];
		$this->rechargedate->AdvancedSearch->SearchOperator = @$filter["z_rechargedate"];
		$this->rechargedate->AdvancedSearch->SearchCondition = @$filter["v_rechargedate"];
		$this->rechargedate->AdvancedSearch->SearchValue2 = @$filter["y_rechargedate"];
		$this->rechargedate->AdvancedSearch->SearchOperator2 = @$filter["w_rechargedate"];
		$this->rechargedate->AdvancedSearch->Save();

		// Field rechargeamount
		$this->rechargeamount->AdvancedSearch->SearchValue = @$filter["x_rechargeamount"];
		$this->rechargeamount->AdvancedSearch->SearchOperator = @$filter["z_rechargeamount"];
		$this->rechargeamount->AdvancedSearch->SearchCondition = @$filter["v_rechargeamount"];
		$this->rechargeamount->AdvancedSearch->SearchValue2 = @$filter["y_rechargeamount"];
		$this->rechargeamount->AdvancedSearch->SearchOperator2 = @$filter["w_rechargeamount"];
		$this->rechargeamount->AdvancedSearch->Save();

		// Field rechargedue
		$this->rechargedue->AdvancedSearch->SearchValue = @$filter["x_rechargedue"];
		$this->rechargedue->AdvancedSearch->SearchOperator = @$filter["z_rechargedue"];
		$this->rechargedue->AdvancedSearch->SearchCondition = @$filter["v_rechargedue"];
		$this->rechargedue->AdvancedSearch->SearchValue2 = @$filter["y_rechargedue"];
		$this->rechargedue->AdvancedSearch->SearchOperator2 = @$filter["w_rechargedue"];
		$this->rechargedue->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->subscriberid, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual && $Fld->FldVirtualSearch) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->serviceid, $bCtrl); // serviceid
			$this->UpdateSort($this->servicename, $bCtrl); // servicename
			$this->UpdateSort($this->subscriberid, $bCtrl); // subscriberid
			$this->UpdateSort($this->regphone, $bCtrl); // regphone
			$this->UpdateSort($this->rechargedate, $bCtrl); // rechargedate
			$this->UpdateSort($this->rechargeamount, $bCtrl); // rechargeamount
			$this->UpdateSort($this->rechargedue, $bCtrl); // rechargedue
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->serviceid->setSort("");
				$this->servicename->setSort("");
				$this->subscriberid->setSort("");
				$this->regphone->setSort("");
				$this->rechargedate->setSort("");
				$this->rechargeamount->setSort("");
				$this->rechargedue->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = TRUE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->serviceid->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fsubscribed_tolistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fsubscribed_tolistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fsubscribed_tolist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fsubscribed_tolistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->serviceid->setDbValue($rs->fields('serviceid'));
		$this->customerid->setDbValue($rs->fields('customerid'));
		$this->servicename->setDbValue($rs->fields('servicename'));
		$this->subscriberid->setDbValue($rs->fields('subscriberid'));
		$this->regphone->setDbValue($rs->fields('regphone'));
		$this->rechargedate->setDbValue($rs->fields('rechargedate'));
		$this->rechargeamount->setDbValue($rs->fields('rechargeamount'));
		$this->rechargedue->setDbValue($rs->fields('rechargedue'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->serviceid->DbValue = $row['serviceid'];
		$this->customerid->DbValue = $row['customerid'];
		$this->servicename->DbValue = $row['servicename'];
		$this->subscriberid->DbValue = $row['subscriberid'];
		$this->regphone->DbValue = $row['regphone'];
		$this->rechargedate->DbValue = $row['rechargedate'];
		$this->rechargeamount->DbValue = $row['rechargeamount'];
		$this->rechargedue->DbValue = $row['rechargedue'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("serviceid")) <> "")
			$this->serviceid->CurrentValue = $this->getKey("serviceid"); // serviceid
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Convert decimal values if posted back
		if ($this->rechargeamount->FormValue == $this->rechargeamount->CurrentValue && is_numeric(ew_StrToFloat($this->rechargeamount->CurrentValue)))
			$this->rechargeamount->CurrentValue = ew_StrToFloat($this->rechargeamount->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// serviceid
		// customerid

		$this->customerid->CellCssStyle = "white-space: nowrap;";

		// servicename
		// subscriberid
		// regphone
		// rechargedate
		// rechargeamount
		// rechargedue

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'subscribed_to';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($subscribed_to_list)) $subscribed_to_list = new csubscribed_to_list();

// Page init
$subscribed_to_list->Page_Init();

// Page main
$subscribed_to_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$subscribed_to_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fsubscribed_tolist = new ew_Form("fsubscribed_tolist", "list");
fsubscribed_tolist.FormKeyCountName = '<?php echo $subscribed_to_list->FormKeyCountName ?>';

// Form_CustomValidate event
fsubscribed_tolist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsubscribed_tolist.ValidateRequired = true;
<?php } else { ?>
fsubscribed_tolist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsubscribed_tolist.Lists["x_servicename"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_valuename","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"services"};

// Form object for search
var CurrentSearchForm = fsubscribed_tolistsrch = new ew_Form("fsubscribed_tolistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php if ($subscribed_to_list->TotalRecs > 0 && $subscribed_to_list->ExportOptions->Visible()) { ?>
<?php $subscribed_to_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($subscribed_to_list->SearchOptions->Visible()) { ?>
<?php $subscribed_to_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($subscribed_to_list->FilterOptions->Visible()) { ?>
<?php $subscribed_to_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $subscribed_to_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($subscribed_to_list->TotalRecs <= 0)
			$subscribed_to_list->TotalRecs = $subscribed_to->SelectRecordCount();
	} else {
		if (!$subscribed_to_list->Recordset && ($subscribed_to_list->Recordset = $subscribed_to_list->LoadRecordset()))
			$subscribed_to_list->TotalRecs = $subscribed_to_list->Recordset->RecordCount();
	}
	$subscribed_to_list->StartRec = 1;
	if ($subscribed_to_list->DisplayRecs <= 0 || ($subscribed_to->Export <> "" && $subscribed_to->ExportAll)) // Display all records
		$subscribed_to_list->DisplayRecs = $subscribed_to_list->TotalRecs;
	if (!($subscribed_to->Export <> "" && $subscribed_to->ExportAll))
		$subscribed_to_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$subscribed_to_list->Recordset = $subscribed_to_list->LoadRecordset($subscribed_to_list->StartRec-1, $subscribed_to_list->DisplayRecs);

	// Set no record found message
	if ($subscribed_to->CurrentAction == "" && $subscribed_to_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$subscribed_to_list->setWarningMessage(ew_DeniedMsg());
		if ($subscribed_to_list->SearchWhere == "0=101")
			$subscribed_to_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$subscribed_to_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($subscribed_to_list->AuditTrailOnSearch && $subscribed_to_list->Command == "search" && !$subscribed_to_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $subscribed_to_list->getSessionWhere();
		$subscribed_to_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
$subscribed_to_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($subscribed_to->Export == "" && $subscribed_to->CurrentAction == "") { ?>
<form name="fsubscribed_tolistsrch" id="fsubscribed_tolistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($subscribed_to_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fsubscribed_tolistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="subscribed_to">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($subscribed_to_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($subscribed_to_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $subscribed_to_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($subscribed_to_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($subscribed_to_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($subscribed_to_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($subscribed_to_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $subscribed_to_list->ShowPageHeader(); ?>
<?php
$subscribed_to_list->ShowMessage();
?>
<?php if ($subscribed_to_list->TotalRecs > 0 || $subscribed_to->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid subscribed_to">
<form name="fsubscribed_tolist" id="fsubscribed_tolist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($subscribed_to_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $subscribed_to_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="subscribed_to">
<div id="gmp_subscribed_to" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($subscribed_to_list->TotalRecs > 0) { ?>
<table id="tbl_subscribed_tolist" class="table ewTable">
<?php echo $subscribed_to->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$subscribed_to_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$subscribed_to_list->RenderListOptions();

// Render list options (header, left)
$subscribed_to_list->ListOptions->Render("header", "left");
?>
<?php if ($subscribed_to->serviceid->Visible) { // serviceid ?>
	<?php if ($subscribed_to->SortUrl($subscribed_to->serviceid) == "") { ?>
		<th data-name="serviceid"><div id="elh_subscribed_to_serviceid" class="subscribed_to_serviceid"><div class="ewTableHeaderCaption"><?php echo $subscribed_to->serviceid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="serviceid"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subscribed_to->SortUrl($subscribed_to->serviceid) ?>',2);"><div id="elh_subscribed_to_serviceid" class="subscribed_to_serviceid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subscribed_to->serviceid->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subscribed_to->serviceid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subscribed_to->serviceid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($subscribed_to->servicename->Visible) { // servicename ?>
	<?php if ($subscribed_to->SortUrl($subscribed_to->servicename) == "") { ?>
		<th data-name="servicename"><div id="elh_subscribed_to_servicename" class="subscribed_to_servicename"><div class="ewTableHeaderCaption"><?php echo $subscribed_to->servicename->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="servicename"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subscribed_to->SortUrl($subscribed_to->servicename) ?>',2);"><div id="elh_subscribed_to_servicename" class="subscribed_to_servicename">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subscribed_to->servicename->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subscribed_to->servicename->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subscribed_to->servicename->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($subscribed_to->subscriberid->Visible) { // subscriberid ?>
	<?php if ($subscribed_to->SortUrl($subscribed_to->subscriberid) == "") { ?>
		<th data-name="subscriberid"><div id="elh_subscribed_to_subscriberid" class="subscribed_to_subscriberid"><div class="ewTableHeaderCaption"><?php echo $subscribed_to->subscriberid->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="subscriberid"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subscribed_to->SortUrl($subscribed_to->subscriberid) ?>',2);"><div id="elh_subscribed_to_subscriberid" class="subscribed_to_subscriberid">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subscribed_to->subscriberid->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($subscribed_to->subscriberid->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subscribed_to->subscriberid->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($subscribed_to->regphone->Visible) { // regphone ?>
	<?php if ($subscribed_to->SortUrl($subscribed_to->regphone) == "") { ?>
		<th data-name="regphone"><div id="elh_subscribed_to_regphone" class="subscribed_to_regphone"><div class="ewTableHeaderCaption"><?php echo $subscribed_to->regphone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="regphone"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subscribed_to->SortUrl($subscribed_to->regphone) ?>',2);"><div id="elh_subscribed_to_regphone" class="subscribed_to_regphone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subscribed_to->regphone->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subscribed_to->regphone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subscribed_to->regphone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($subscribed_to->rechargedate->Visible) { // rechargedate ?>
	<?php if ($subscribed_to->SortUrl($subscribed_to->rechargedate) == "") { ?>
		<th data-name="rechargedate"><div id="elh_subscribed_to_rechargedate" class="subscribed_to_rechargedate"><div class="ewTableHeaderCaption"><?php echo $subscribed_to->rechargedate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rechargedate"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subscribed_to->SortUrl($subscribed_to->rechargedate) ?>',2);"><div id="elh_subscribed_to_rechargedate" class="subscribed_to_rechargedate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subscribed_to->rechargedate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subscribed_to->rechargedate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subscribed_to->rechargedate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($subscribed_to->rechargeamount->Visible) { // rechargeamount ?>
	<?php if ($subscribed_to->SortUrl($subscribed_to->rechargeamount) == "") { ?>
		<th data-name="rechargeamount"><div id="elh_subscribed_to_rechargeamount" class="subscribed_to_rechargeamount"><div class="ewTableHeaderCaption"><?php echo $subscribed_to->rechargeamount->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rechargeamount"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subscribed_to->SortUrl($subscribed_to->rechargeamount) ?>',2);"><div id="elh_subscribed_to_rechargeamount" class="subscribed_to_rechargeamount">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subscribed_to->rechargeamount->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subscribed_to->rechargeamount->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subscribed_to->rechargeamount->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($subscribed_to->rechargedue->Visible) { // rechargedue ?>
	<?php if ($subscribed_to->SortUrl($subscribed_to->rechargedue) == "") { ?>
		<th data-name="rechargedue"><div id="elh_subscribed_to_rechargedue" class="subscribed_to_rechargedue"><div class="ewTableHeaderCaption"><?php echo $subscribed_to->rechargedue->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="rechargedue"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $subscribed_to->SortUrl($subscribed_to->rechargedue) ?>',2);"><div id="elh_subscribed_to_rechargedue" class="subscribed_to_rechargedue">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $subscribed_to->rechargedue->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($subscribed_to->rechargedue->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($subscribed_to->rechargedue->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$subscribed_to_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($subscribed_to->ExportAll && $subscribed_to->Export <> "") {
	$subscribed_to_list->StopRec = $subscribed_to_list->TotalRecs;
} else {

	// Set the last record to display
	if ($subscribed_to_list->TotalRecs > $subscribed_to_list->StartRec + $subscribed_to_list->DisplayRecs - 1)
		$subscribed_to_list->StopRec = $subscribed_to_list->StartRec + $subscribed_to_list->DisplayRecs - 1;
	else
		$subscribed_to_list->StopRec = $subscribed_to_list->TotalRecs;
}
$subscribed_to_list->RecCnt = $subscribed_to_list->StartRec - 1;
if ($subscribed_to_list->Recordset && !$subscribed_to_list->Recordset->EOF) {
	$subscribed_to_list->Recordset->MoveFirst();
	$bSelectLimit = $subscribed_to_list->UseSelectLimit;
	if (!$bSelectLimit && $subscribed_to_list->StartRec > 1)
		$subscribed_to_list->Recordset->Move($subscribed_to_list->StartRec - 1);
} elseif (!$subscribed_to->AllowAddDeleteRow && $subscribed_to_list->StopRec == 0) {
	$subscribed_to_list->StopRec = $subscribed_to->GridAddRowCount;
}

// Initialize aggregate
$subscribed_to->RowType = EW_ROWTYPE_AGGREGATEINIT;
$subscribed_to->ResetAttrs();
$subscribed_to_list->RenderRow();
while ($subscribed_to_list->RecCnt < $subscribed_to_list->StopRec) {
	$subscribed_to_list->RecCnt++;
	if (intval($subscribed_to_list->RecCnt) >= intval($subscribed_to_list->StartRec)) {
		$subscribed_to_list->RowCnt++;

		// Set up key count
		$subscribed_to_list->KeyCount = $subscribed_to_list->RowIndex;

		// Init row class and style
		$subscribed_to->ResetAttrs();
		$subscribed_to->CssClass = "";
		if ($subscribed_to->CurrentAction == "gridadd") {
		} else {
			$subscribed_to_list->LoadRowValues($subscribed_to_list->Recordset); // Load row values
		}
		$subscribed_to->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$subscribed_to->RowAttrs = array_merge($subscribed_to->RowAttrs, array('data-rowindex'=>$subscribed_to_list->RowCnt, 'id'=>'r' . $subscribed_to_list->RowCnt . '_subscribed_to', 'data-rowtype'=>$subscribed_to->RowType));

		// Render row
		$subscribed_to_list->RenderRow();

		// Render list options
		$subscribed_to_list->RenderListOptions();
?>
	<tr<?php echo $subscribed_to->RowAttributes() ?>>
<?php

// Render list options (body, left)
$subscribed_to_list->ListOptions->Render("body", "left", $subscribed_to_list->RowCnt);
?>
	<?php if ($subscribed_to->serviceid->Visible) { // serviceid ?>
		<td data-name="serviceid"<?php echo $subscribed_to->serviceid->CellAttributes() ?>>
<span id="el<?php echo $subscribed_to_list->RowCnt ?>_subscribed_to_serviceid" class="subscribed_to_serviceid">
<span<?php echo $subscribed_to->serviceid->ViewAttributes() ?>>
<?php echo $subscribed_to->serviceid->ListViewValue() ?></span>
</span>
<a id="<?php echo $subscribed_to_list->PageObjName . "_row_" . $subscribed_to_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($subscribed_to->servicename->Visible) { // servicename ?>
		<td data-name="servicename"<?php echo $subscribed_to->servicename->CellAttributes() ?>>
<span id="el<?php echo $subscribed_to_list->RowCnt ?>_subscribed_to_servicename" class="subscribed_to_servicename">
<span<?php echo $subscribed_to->servicename->ViewAttributes() ?>>
<?php echo $subscribed_to->servicename->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($subscribed_to->subscriberid->Visible) { // subscriberid ?>
		<td data-name="subscriberid"<?php echo $subscribed_to->subscriberid->CellAttributes() ?>>
<span id="el<?php echo $subscribed_to_list->RowCnt ?>_subscribed_to_subscriberid" class="subscribed_to_subscriberid">
<span<?php echo $subscribed_to->subscriberid->ViewAttributes() ?>>
<?php echo $subscribed_to->subscriberid->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($subscribed_to->regphone->Visible) { // regphone ?>
		<td data-name="regphone"<?php echo $subscribed_to->regphone->CellAttributes() ?>>
<span id="el<?php echo $subscribed_to_list->RowCnt ?>_subscribed_to_regphone" class="subscribed_to_regphone">
<span<?php echo $subscribed_to->regphone->ViewAttributes() ?>>
<?php echo $subscribed_to->regphone->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($subscribed_to->rechargedate->Visible) { // rechargedate ?>
		<td data-name="rechargedate"<?php echo $subscribed_to->rechargedate->CellAttributes() ?>>
<span id="el<?php echo $subscribed_to_list->RowCnt ?>_subscribed_to_rechargedate" class="subscribed_to_rechargedate">
<span<?php echo $subscribed_to->rechargedate->ViewAttributes() ?>>
<?php echo $subscribed_to->rechargedate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($subscribed_to->rechargeamount->Visible) { // rechargeamount ?>
		<td data-name="rechargeamount"<?php echo $subscribed_to->rechargeamount->CellAttributes() ?>>
<span id="el<?php echo $subscribed_to_list->RowCnt ?>_subscribed_to_rechargeamount" class="subscribed_to_rechargeamount">
<span<?php echo $subscribed_to->rechargeamount->ViewAttributes() ?>>
<?php echo $subscribed_to->rechargeamount->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($subscribed_to->rechargedue->Visible) { // rechargedue ?>
		<td data-name="rechargedue"<?php echo $subscribed_to->rechargedue->CellAttributes() ?>>
<span id="el<?php echo $subscribed_to_list->RowCnt ?>_subscribed_to_rechargedue" class="subscribed_to_rechargedue">
<span<?php echo $subscribed_to->rechargedue->ViewAttributes() ?>>
<?php echo $subscribed_to->rechargedue->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$subscribed_to_list->ListOptions->Render("body", "right", $subscribed_to_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($subscribed_to->CurrentAction <> "gridadd")
		$subscribed_to_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($subscribed_to->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($subscribed_to_list->Recordset)
	$subscribed_to_list->Recordset->Close();
?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($subscribed_to->CurrentAction <> "gridadd" && $subscribed_to->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($subscribed_to_list->Pager)) $subscribed_to_list->Pager = new cPrevNextPager($subscribed_to_list->StartRec, $subscribed_to_list->DisplayRecs, $subscribed_to_list->TotalRecs) ?>
<?php if ($subscribed_to_list->Pager->RecordCount > 0 && $subscribed_to_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($subscribed_to_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $subscribed_to_list->PageUrl() ?>start=<?php echo $subscribed_to_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($subscribed_to_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $subscribed_to_list->PageUrl() ?>start=<?php echo $subscribed_to_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $subscribed_to_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($subscribed_to_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $subscribed_to_list->PageUrl() ?>start=<?php echo $subscribed_to_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($subscribed_to_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $subscribed_to_list->PageUrl() ?>start=<?php echo $subscribed_to_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $subscribed_to_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $subscribed_to_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $subscribed_to_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $subscribed_to_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($subscribed_to_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($subscribed_to_list->TotalRecs == 0 && $subscribed_to->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($subscribed_to_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fsubscribed_tolistsrch.FilterList = <?php echo $subscribed_to_list->GetFilterList() ?>;
fsubscribed_tolistsrch.Init();
fsubscribed_tolist.Init();
</script>
<?php
$subscribed_to_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$subscribed_to_list->Page_Terminate();
?>
