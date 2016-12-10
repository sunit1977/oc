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

$subscribed_to_edit = NULL; // Initialize page object first

class csubscribed_to_edit extends csubscribed_to {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}";

	// Table name
	var $TableName = 'subscribed_to';

	// Page object name
	var $PageObjName = 'subscribed_to_edit';

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
	var $AuditTrailOnAdd = FALSE;
	var $AuditTrailOnEdit = TRUE;
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

		// Table object (reg_users)
		if (!isset($GLOBALS['reg_users'])) $GLOBALS['reg_users'] = new creg_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("subscribed_tolist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->serviceid->SetVisibility();
		$this->serviceid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->customerid->SetVisibility();
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load key from QueryString
		if (@$_GET["serviceid"] <> "") {
			$this->serviceid->setQueryStringValue($_GET["serviceid"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->serviceid->CurrentValue == "") {
			$this->Page_Terminate("subscribed_tolist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("subscribed_tolist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "subscribed_tolist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->serviceid->FldIsDetailKey)
			$this->serviceid->setFormValue($objForm->GetValue("x_serviceid"));
		if (!$this->customerid->FldIsDetailKey) {
			$this->customerid->setFormValue($objForm->GetValue("x_customerid"));
		}
		if (!$this->servicename->FldIsDetailKey) {
			$this->servicename->setFormValue($objForm->GetValue("x_servicename"));
		}
		if (!$this->subscriberid->FldIsDetailKey) {
			$this->subscriberid->setFormValue($objForm->GetValue("x_subscriberid"));
		}
		if (!$this->regphone->FldIsDetailKey) {
			$this->regphone->setFormValue($objForm->GetValue("x_regphone"));
		}
		if (!$this->rechargedate->FldIsDetailKey) {
			$this->rechargedate->setFormValue($objForm->GetValue("x_rechargedate"));
			$this->rechargedate->CurrentValue = ew_UnFormatDateTime($this->rechargedate->CurrentValue, 7);
		}
		if (!$this->rechargeamount->FldIsDetailKey) {
			$this->rechargeamount->setFormValue($objForm->GetValue("x_rechargeamount"));
		}
		if (!$this->rechargedue->FldIsDetailKey) {
			$this->rechargedue->setFormValue($objForm->GetValue("x_rechargedue"));
			$this->rechargedue->CurrentValue = ew_UnFormatDateTime($this->rechargedue->CurrentValue, 7);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->serviceid->CurrentValue = $this->serviceid->FormValue;
		$this->customerid->CurrentValue = $this->customerid->FormValue;
		$this->servicename->CurrentValue = $this->servicename->FormValue;
		$this->subscriberid->CurrentValue = $this->subscriberid->FormValue;
		$this->regphone->CurrentValue = $this->regphone->FormValue;
		$this->rechargedate->CurrentValue = $this->rechargedate->FormValue;
		$this->rechargedate->CurrentValue = ew_UnFormatDateTime($this->rechargedate->CurrentValue, 7);
		$this->rechargeamount->CurrentValue = $this->rechargeamount->FormValue;
		$this->rechargedue->CurrentValue = $this->rechargedue->FormValue;
		$this->rechargedue->CurrentValue = ew_UnFormatDateTime($this->rechargedue->CurrentValue, 7);
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

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->rechargeamount->FormValue == $this->rechargeamount->CurrentValue && is_numeric(ew_StrToFloat($this->rechargeamount->CurrentValue)))
			$this->rechargeamount->CurrentValue = ew_StrToFloat($this->rechargeamount->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// serviceid
		// customerid
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// serviceid
			$this->serviceid->EditAttrs["class"] = "form-control";
			$this->serviceid->EditCustomAttributes = "";
			$this->serviceid->EditValue = $this->serviceid->CurrentValue;
			$this->serviceid->ViewCustomAttributes = "";

			// customerid
			$this->customerid->EditAttrs["class"] = "form-control";
			$this->customerid->EditCustomAttributes = "";
			if (trim(strval($this->customerid->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`customerid`" . ew_SearchString("=", $this->customerid->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `customerid`, `customername` AS `DispFld`, `houseno` AS `Disp2Fld`, `locality` AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `customer_info`";
			$sWhereWrk = "";
			$this->customerid->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->customerid, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->customerid->EditValue = $arwrk;

			// servicename
			$this->servicename->EditAttrs["class"] = "form-control";
			$this->servicename->EditCustomAttributes = "";
			if (trim(strval($this->servicename->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->servicename->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `valuename` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `services`";
			$sWhereWrk = "";
			$this->servicename->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->servicename, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->servicename->EditValue = $arwrk;

			// subscriberid
			$this->subscriberid->EditAttrs["class"] = "form-control";
			$this->subscriberid->EditCustomAttributes = "";
			$this->subscriberid->EditValue = ew_HtmlEncode($this->subscriberid->CurrentValue);
			$this->subscriberid->PlaceHolder = ew_RemoveHtml($this->subscriberid->FldCaption());

			// regphone
			$this->regphone->EditAttrs["class"] = "form-control";
			$this->regphone->EditCustomAttributes = "";
			$this->regphone->EditValue = ew_HtmlEncode($this->regphone->CurrentValue);
			$this->regphone->PlaceHolder = ew_RemoveHtml($this->regphone->FldCaption());

			// rechargedate
			$this->rechargedate->EditAttrs["class"] = "form-control";
			$this->rechargedate->EditCustomAttributes = "";
			$this->rechargedate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->rechargedate->CurrentValue, 7));
			$this->rechargedate->PlaceHolder = ew_RemoveHtml($this->rechargedate->FldCaption());

			// rechargeamount
			$this->rechargeamount->EditAttrs["class"] = "form-control";
			$this->rechargeamount->EditCustomAttributes = "";
			$this->rechargeamount->EditValue = ew_HtmlEncode($this->rechargeamount->CurrentValue);
			$this->rechargeamount->PlaceHolder = ew_RemoveHtml($this->rechargeamount->FldCaption());
			if (strval($this->rechargeamount->EditValue) <> "" && is_numeric($this->rechargeamount->EditValue)) $this->rechargeamount->EditValue = ew_FormatNumber($this->rechargeamount->EditValue, -2, -1, -2, 0);

			// rechargedue
			$this->rechargedue->EditAttrs["class"] = "form-control";
			$this->rechargedue->EditCustomAttributes = "";
			$this->rechargedue->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->rechargedue->CurrentValue, 7));
			$this->rechargedue->PlaceHolder = ew_RemoveHtml($this->rechargedue->FldCaption());

			// Edit refer script
			// serviceid

			$this->serviceid->LinkCustomAttributes = "";
			$this->serviceid->HrefValue = "";

			// customerid
			$this->customerid->LinkCustomAttributes = "";
			$this->customerid->HrefValue = "";

			// servicename
			$this->servicename->LinkCustomAttributes = "";
			$this->servicename->HrefValue = "";

			// subscriberid
			$this->subscriberid->LinkCustomAttributes = "";
			$this->subscriberid->HrefValue = "";

			// regphone
			$this->regphone->LinkCustomAttributes = "";
			$this->regphone->HrefValue = "";

			// rechargedate
			$this->rechargedate->LinkCustomAttributes = "";
			$this->rechargedate->HrefValue = "";

			// rechargeamount
			$this->rechargeamount->LinkCustomAttributes = "";
			$this->rechargeamount->HrefValue = "";

			// rechargedue
			$this->rechargedue->LinkCustomAttributes = "";
			$this->rechargedue->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->regphone->FormValue)) {
			ew_AddMessage($gsFormError, $this->regphone->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->rechargedate->FormValue)) {
			ew_AddMessage($gsFormError, $this->rechargedate->FldErrMsg());
		}
		if (!ew_CheckNumber($this->rechargeamount->FormValue)) {
			ew_AddMessage($gsFormError, $this->rechargeamount->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->rechargedue->FormValue)) {
			ew_AddMessage($gsFormError, $this->rechargedue->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// customerid
			$this->customerid->SetDbValueDef($rsnew, $this->customerid->CurrentValue, NULL, $this->customerid->ReadOnly);

			// servicename
			$this->servicename->SetDbValueDef($rsnew, $this->servicename->CurrentValue, NULL, $this->servicename->ReadOnly);

			// subscriberid
			$this->subscriberid->SetDbValueDef($rsnew, $this->subscriberid->CurrentValue, NULL, $this->subscriberid->ReadOnly);

			// regphone
			$this->regphone->SetDbValueDef($rsnew, $this->regphone->CurrentValue, NULL, $this->regphone->ReadOnly);

			// rechargedate
			$this->rechargedate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->rechargedate->CurrentValue, 7), NULL, $this->rechargedate->ReadOnly);

			// rechargeamount
			$this->rechargeamount->SetDbValueDef($rsnew, $this->rechargeamount->CurrentValue, NULL, $this->rechargeamount->ReadOnly);

			// rechargedue
			$this->rechargedue->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->rechargedue->CurrentValue, 7), NULL, $this->rechargedue->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		if ($EditRow) {
			$this->WriteAuditTrailOnEdit($rsold, $rsnew);
		}
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("subscribed_tolist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_customerid":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `customerid` AS `LinkFld`, `customername` AS `DispFld`, `houseno` AS `Disp2Fld`, `locality` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `customer_info`";
			$sWhereWrk = "";
			$this->customerid->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`customerid` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->customerid, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
		case "x_servicename":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `id` AS `LinkFld`, `valuename` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `services`";
			$sWhereWrk = "";
			$this->servicename->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`id` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->servicename, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'subscribed_to';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['serviceid'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserName();
		foreach (array_keys($rsnew) as $fldname) {
			if ($this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($subscribed_to_edit)) $subscribed_to_edit = new csubscribed_to_edit();

// Page init
$subscribed_to_edit->Page_Init();

// Page main
$subscribed_to_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$subscribed_to_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fsubscribed_toedit = new ew_Form("fsubscribed_toedit", "edit");

// Validate form
fsubscribed_toedit.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_regphone");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subscribed_to->regphone->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_rechargedate");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subscribed_to->rechargedate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_rechargeamount");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subscribed_to->rechargeamount->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_rechargedue");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($subscribed_to->rechargedue->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fsubscribed_toedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsubscribed_toedit.ValidateRequired = true;
<?php } else { ?>
fsubscribed_toedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fsubscribed_toedit.Lists["x_customerid"] = {"LinkField":"x_customerid","Ajax":true,"AutoFill":false,"DisplayFields":["x_customername","x_houseno","x_locality",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"customer_info"};
fsubscribed_toedit.Lists["x_servicename"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_valuename","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"services"};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$subscribed_to_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $subscribed_to_edit->ShowPageHeader(); ?>
<?php
$subscribed_to_edit->ShowMessage();
?>
<form name="fsubscribed_toedit" id="fsubscribed_toedit" class="<?php echo $subscribed_to_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($subscribed_to_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $subscribed_to_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="subscribed_to">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($subscribed_to_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($subscribed_to->serviceid->Visible) { // serviceid ?>
	<div id="r_serviceid" class="form-group">
		<label id="elh_subscribed_to_serviceid" class="col-sm-2 control-label ewLabel"><?php echo $subscribed_to->serviceid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $subscribed_to->serviceid->CellAttributes() ?>>
<span id="el_subscribed_to_serviceid">
<span<?php echo $subscribed_to->serviceid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $subscribed_to->serviceid->EditValue ?></p></span>
</span>
<input type="hidden" data-table="subscribed_to" data-field="x_serviceid" name="x_serviceid" id="x_serviceid" value="<?php echo ew_HtmlEncode($subscribed_to->serviceid->CurrentValue) ?>">
<?php echo $subscribed_to->serviceid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subscribed_to->customerid->Visible) { // customerid ?>
	<div id="r_customerid" class="form-group">
		<label id="elh_subscribed_to_customerid" for="x_customerid" class="col-sm-2 control-label ewLabel"><?php echo $subscribed_to->customerid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $subscribed_to->customerid->CellAttributes() ?>>
<span id="el_subscribed_to_customerid">
<select data-table="subscribed_to" data-field="x_customerid" data-value-separator="<?php echo $subscribed_to->customerid->DisplayValueSeparatorAttribute() ?>" id="x_customerid" name="x_customerid"<?php echo $subscribed_to->customerid->EditAttributes() ?>>
<?php echo $subscribed_to->customerid->SelectOptionListHtml("x_customerid") ?>
</select>
<input type="hidden" name="s_x_customerid" id="s_x_customerid" value="<?php echo $subscribed_to->customerid->LookupFilterQuery() ?>">
</span>
<?php echo $subscribed_to->customerid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subscribed_to->servicename->Visible) { // servicename ?>
	<div id="r_servicename" class="form-group">
		<label id="elh_subscribed_to_servicename" for="x_servicename" class="col-sm-2 control-label ewLabel"><?php echo $subscribed_to->servicename->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $subscribed_to->servicename->CellAttributes() ?>>
<span id="el_subscribed_to_servicename">
<select data-table="subscribed_to" data-field="x_servicename" data-value-separator="<?php echo $subscribed_to->servicename->DisplayValueSeparatorAttribute() ?>" id="x_servicename" name="x_servicename"<?php echo $subscribed_to->servicename->EditAttributes() ?>>
<?php echo $subscribed_to->servicename->SelectOptionListHtml("x_servicename") ?>
</select>
<input type="hidden" name="s_x_servicename" id="s_x_servicename" value="<?php echo $subscribed_to->servicename->LookupFilterQuery() ?>">
</span>
<?php echo $subscribed_to->servicename->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subscribed_to->subscriberid->Visible) { // subscriberid ?>
	<div id="r_subscriberid" class="form-group">
		<label id="elh_subscribed_to_subscriberid" for="x_subscriberid" class="col-sm-2 control-label ewLabel"><?php echo $subscribed_to->subscriberid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $subscribed_to->subscriberid->CellAttributes() ?>>
<span id="el_subscribed_to_subscriberid">
<input type="text" data-table="subscribed_to" data-field="x_subscriberid" name="x_subscriberid" id="x_subscriberid" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($subscribed_to->subscriberid->getPlaceHolder()) ?>" value="<?php echo $subscribed_to->subscriberid->EditValue ?>"<?php echo $subscribed_to->subscriberid->EditAttributes() ?>>
</span>
<?php echo $subscribed_to->subscriberid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subscribed_to->regphone->Visible) { // regphone ?>
	<div id="r_regphone" class="form-group">
		<label id="elh_subscribed_to_regphone" for="x_regphone" class="col-sm-2 control-label ewLabel"><?php echo $subscribed_to->regphone->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $subscribed_to->regphone->CellAttributes() ?>>
<span id="el_subscribed_to_regphone">
<input type="text" data-table="subscribed_to" data-field="x_regphone" name="x_regphone" id="x_regphone" size="30" placeholder="<?php echo ew_HtmlEncode($subscribed_to->regphone->getPlaceHolder()) ?>" value="<?php echo $subscribed_to->regphone->EditValue ?>"<?php echo $subscribed_to->regphone->EditAttributes() ?>>
</span>
<?php echo $subscribed_to->regphone->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subscribed_to->rechargedate->Visible) { // rechargedate ?>
	<div id="r_rechargedate" class="form-group">
		<label id="elh_subscribed_to_rechargedate" for="x_rechargedate" class="col-sm-2 control-label ewLabel"><?php echo $subscribed_to->rechargedate->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $subscribed_to->rechargedate->CellAttributes() ?>>
<span id="el_subscribed_to_rechargedate">
<input type="text" data-table="subscribed_to" data-field="x_rechargedate" data-format="7" name="x_rechargedate" id="x_rechargedate" placeholder="<?php echo ew_HtmlEncode($subscribed_to->rechargedate->getPlaceHolder()) ?>" value="<?php echo $subscribed_to->rechargedate->EditValue ?>"<?php echo $subscribed_to->rechargedate->EditAttributes() ?>>
<?php if (!$subscribed_to->rechargedate->ReadOnly && !$subscribed_to->rechargedate->Disabled && !isset($subscribed_to->rechargedate->EditAttrs["readonly"]) && !isset($subscribed_to->rechargedate->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsubscribed_toedit", "x_rechargedate", 7);
</script>
<?php } ?>
</span>
<?php echo $subscribed_to->rechargedate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subscribed_to->rechargeamount->Visible) { // rechargeamount ?>
	<div id="r_rechargeamount" class="form-group">
		<label id="elh_subscribed_to_rechargeamount" for="x_rechargeamount" class="col-sm-2 control-label ewLabel"><?php echo $subscribed_to->rechargeamount->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $subscribed_to->rechargeamount->CellAttributes() ?>>
<span id="el_subscribed_to_rechargeamount">
<input type="text" data-table="subscribed_to" data-field="x_rechargeamount" name="x_rechargeamount" id="x_rechargeamount" size="30" placeholder="<?php echo ew_HtmlEncode($subscribed_to->rechargeamount->getPlaceHolder()) ?>" value="<?php echo $subscribed_to->rechargeamount->EditValue ?>"<?php echo $subscribed_to->rechargeamount->EditAttributes() ?>>
</span>
<?php echo $subscribed_to->rechargeamount->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($subscribed_to->rechargedue->Visible) { // rechargedue ?>
	<div id="r_rechargedue" class="form-group">
		<label id="elh_subscribed_to_rechargedue" for="x_rechargedue" class="col-sm-2 control-label ewLabel"><?php echo $subscribed_to->rechargedue->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $subscribed_to->rechargedue->CellAttributes() ?>>
<span id="el_subscribed_to_rechargedue">
<input type="text" data-table="subscribed_to" data-field="x_rechargedue" data-format="7" name="x_rechargedue" id="x_rechargedue" placeholder="<?php echo ew_HtmlEncode($subscribed_to->rechargedue->getPlaceHolder()) ?>" value="<?php echo $subscribed_to->rechargedue->EditValue ?>"<?php echo $subscribed_to->rechargedue->EditAttributes() ?>>
<?php if (!$subscribed_to->rechargedue->ReadOnly && !$subscribed_to->rechargedue->Disabled && !isset($subscribed_to->rechargedue->EditAttrs["readonly"]) && !isset($subscribed_to->rechargedue->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("fsubscribed_toedit", "x_rechargedue", 7);
</script>
<?php } ?>
</span>
<?php echo $subscribed_to->rechargedue->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$subscribed_to_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $subscribed_to_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fsubscribed_toedit.Init();
</script>
<?php
$subscribed_to_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$subscribed_to_edit->Page_Terminate();
?>
