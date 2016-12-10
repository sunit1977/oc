<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "customer_infoinfo.php" ?>
<?php include_once "reg_usersinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$customer_info_edit = NULL; // Initialize page object first

class ccustomer_info_edit extends ccustomer_info {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}";

	// Table name
	var $TableName = 'customer_info';

	// Page object name
	var $PageObjName = 'customer_info_edit';

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

		// Table object (customer_info)
		if (!isset($GLOBALS["customer_info"]) || get_class($GLOBALS["customer_info"]) == "ccustomer_info") {
			$GLOBALS["customer_info"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["customer_info"];
		}

		// Table object (reg_users)
		if (!isset($GLOBALS['reg_users'])) $GLOBALS['reg_users'] = new creg_users();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'customer_info', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("customer_infolist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->customerid->SetVisibility();
		$this->customerid->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->customername->SetVisibility();
		$this->houseno->SetVisibility();
		$this->locality->SetVisibility();
		$this->city->SetVisibility();
		$this->state->SetVisibility();
		$this->remarks->SetVisibility();

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
		global $EW_EXPORT, $customer_info;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($customer_info);
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
		if (@$_GET["customerid"] <> "") {
			$this->customerid->setQueryStringValue($_GET["customerid"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->customerid->CurrentValue == "") {
			$this->Page_Terminate("customer_infolist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("customer_infolist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "customer_infolist.php")
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
		if (!$this->customerid->FldIsDetailKey)
			$this->customerid->setFormValue($objForm->GetValue("x_customerid"));
		if (!$this->customername->FldIsDetailKey) {
			$this->customername->setFormValue($objForm->GetValue("x_customername"));
		}
		if (!$this->houseno->FldIsDetailKey) {
			$this->houseno->setFormValue($objForm->GetValue("x_houseno"));
		}
		if (!$this->locality->FldIsDetailKey) {
			$this->locality->setFormValue($objForm->GetValue("x_locality"));
		}
		if (!$this->city->FldIsDetailKey) {
			$this->city->setFormValue($objForm->GetValue("x_city"));
		}
		if (!$this->state->FldIsDetailKey) {
			$this->state->setFormValue($objForm->GetValue("x_state"));
		}
		if (!$this->remarks->FldIsDetailKey) {
			$this->remarks->setFormValue($objForm->GetValue("x_remarks"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->customerid->CurrentValue = $this->customerid->FormValue;
		$this->customername->CurrentValue = $this->customername->FormValue;
		$this->houseno->CurrentValue = $this->houseno->FormValue;
		$this->locality->CurrentValue = $this->locality->FormValue;
		$this->city->CurrentValue = $this->city->FormValue;
		$this->state->CurrentValue = $this->state->FormValue;
		$this->remarks->CurrentValue = $this->remarks->FormValue;
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
		$this->customerid->setDbValue($rs->fields('customerid'));
		$this->customername->setDbValue($rs->fields('customername'));
		$this->houseno->setDbValue($rs->fields('houseno'));
		$this->locality->setDbValue($rs->fields('locality'));
		$this->city->setDbValue($rs->fields('city'));
		$this->state->setDbValue($rs->fields('state'));
		$this->remarks->setDbValue($rs->fields('remarks'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->customerid->DbValue = $row['customerid'];
		$this->customername->DbValue = $row['customername'];
		$this->houseno->DbValue = $row['houseno'];
		$this->locality->DbValue = $row['locality'];
		$this->city->DbValue = $row['city'];
		$this->state->DbValue = $row['state'];
		$this->remarks->DbValue = $row['remarks'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// customerid
		// customername
		// houseno
		// locality
		// city
		// state
		// remarks

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// customerid
		$this->customerid->ViewValue = $this->customerid->CurrentValue;
		$this->customerid->ViewCustomAttributes = "";

		// customername
		$this->customername->ViewValue = $this->customername->CurrentValue;
		$this->customername->ViewCustomAttributes = "";

		// houseno
		$this->houseno->ViewValue = $this->houseno->CurrentValue;
		$this->houseno->ViewCustomAttributes = "";

		// locality
		$this->locality->ViewValue = $this->locality->CurrentValue;
		$this->locality->ViewCustomAttributes = "";

		// city
		$this->city->ViewValue = $this->city->CurrentValue;
		$this->city->ViewCustomAttributes = "";

		// state
		if (strval($this->state->CurrentValue) <> "") {
			$this->state->ViewValue = $this->state->OptionCaption($this->state->CurrentValue);
		} else {
			$this->state->ViewValue = NULL;
		}
		$this->state->ViewCustomAttributes = "";

		// remarks
		$this->remarks->ViewValue = $this->remarks->CurrentValue;
		$this->remarks->ViewCustomAttributes = "";

			// customerid
			$this->customerid->LinkCustomAttributes = "";
			$this->customerid->HrefValue = "";
			$this->customerid->TooltipValue = "";

			// customername
			$this->customername->LinkCustomAttributes = "";
			$this->customername->HrefValue = "";
			$this->customername->TooltipValue = "";

			// houseno
			$this->houseno->LinkCustomAttributes = "";
			$this->houseno->HrefValue = "";
			$this->houseno->TooltipValue = "";

			// locality
			$this->locality->LinkCustomAttributes = "";
			$this->locality->HrefValue = "";
			$this->locality->TooltipValue = "";

			// city
			$this->city->LinkCustomAttributes = "";
			$this->city->HrefValue = "";
			$this->city->TooltipValue = "";

			// state
			$this->state->LinkCustomAttributes = "";
			$this->state->HrefValue = "";
			$this->state->TooltipValue = "";

			// remarks
			$this->remarks->LinkCustomAttributes = "";
			$this->remarks->HrefValue = "";
			$this->remarks->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// customerid
			$this->customerid->EditAttrs["class"] = "form-control";
			$this->customerid->EditCustomAttributes = "";
			$this->customerid->EditValue = $this->customerid->CurrentValue;
			$this->customerid->ViewCustomAttributes = "";

			// customername
			$this->customername->EditAttrs["class"] = "form-control";
			$this->customername->EditCustomAttributes = "";
			$this->customername->EditValue = ew_HtmlEncode($this->customername->CurrentValue);
			$this->customername->PlaceHolder = ew_RemoveHtml($this->customername->FldCaption());

			// houseno
			$this->houseno->EditAttrs["class"] = "form-control";
			$this->houseno->EditCustomAttributes = "";
			$this->houseno->EditValue = ew_HtmlEncode($this->houseno->CurrentValue);
			$this->houseno->PlaceHolder = ew_RemoveHtml($this->houseno->FldCaption());

			// locality
			$this->locality->EditAttrs["class"] = "form-control";
			$this->locality->EditCustomAttributes = "";
			$this->locality->EditValue = ew_HtmlEncode($this->locality->CurrentValue);
			$this->locality->PlaceHolder = ew_RemoveHtml($this->locality->FldCaption());

			// city
			$this->city->EditAttrs["class"] = "form-control";
			$this->city->EditCustomAttributes = "";
			$this->city->EditValue = ew_HtmlEncode($this->city->CurrentValue);
			$this->city->PlaceHolder = ew_RemoveHtml($this->city->FldCaption());

			// state
			$this->state->EditAttrs["class"] = "form-control";
			$this->state->EditCustomAttributes = "";
			$this->state->EditValue = $this->state->Options(TRUE);

			// remarks
			$this->remarks->EditAttrs["class"] = "form-control";
			$this->remarks->EditCustomAttributes = "";
			$this->remarks->EditValue = ew_HtmlEncode($this->remarks->CurrentValue);
			$this->remarks->PlaceHolder = ew_RemoveHtml($this->remarks->FldCaption());

			// Edit refer script
			// customerid

			$this->customerid->LinkCustomAttributes = "";
			$this->customerid->HrefValue = "";

			// customername
			$this->customername->LinkCustomAttributes = "";
			$this->customername->HrefValue = "";

			// houseno
			$this->houseno->LinkCustomAttributes = "";
			$this->houseno->HrefValue = "";

			// locality
			$this->locality->LinkCustomAttributes = "";
			$this->locality->HrefValue = "";

			// city
			$this->city->LinkCustomAttributes = "";
			$this->city->HrefValue = "";

			// state
			$this->state->LinkCustomAttributes = "";
			$this->state->HrefValue = "";

			// remarks
			$this->remarks->LinkCustomAttributes = "";
			$this->remarks->HrefValue = "";
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

			// customername
			$this->customername->SetDbValueDef($rsnew, $this->customername->CurrentValue, NULL, $this->customername->ReadOnly);

			// houseno
			$this->houseno->SetDbValueDef($rsnew, $this->houseno->CurrentValue, NULL, $this->houseno->ReadOnly);

			// locality
			$this->locality->SetDbValueDef($rsnew, $this->locality->CurrentValue, NULL, $this->locality->ReadOnly);

			// city
			$this->city->SetDbValueDef($rsnew, $this->city->CurrentValue, NULL, $this->city->ReadOnly);

			// state
			$this->state->SetDbValueDef($rsnew, $this->state->CurrentValue, NULL, $this->state->ReadOnly);

			// remarks
			$this->remarks->SetDbValueDef($rsnew, $this->remarks->CurrentValue, NULL, $this->remarks->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("customer_infolist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
		$table = 'customer_info';
		$usr = CurrentUserName();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'customer_info';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['customerid'];

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
if (!isset($customer_info_edit)) $customer_info_edit = new ccustomer_info_edit();

// Page init
$customer_info_edit->Page_Init();

// Page main
$customer_info_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$customer_info_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fcustomer_infoedit = new ew_Form("fcustomer_infoedit", "edit");

// Validate form
fcustomer_infoedit.Validate = function() {
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
fcustomer_infoedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcustomer_infoedit.ValidateRequired = true;
<?php } else { ?>
fcustomer_infoedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fcustomer_infoedit.Lists["x_state"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fcustomer_infoedit.Lists["x_state"].Options = <?php echo json_encode($customer_info->state->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$customer_info_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $customer_info_edit->ShowPageHeader(); ?>
<?php
$customer_info_edit->ShowMessage();
?>
<form name="fcustomer_infoedit" id="fcustomer_infoedit" class="<?php echo $customer_info_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($customer_info_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $customer_info_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="customer_info">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($customer_info_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($customer_info->customerid->Visible) { // customerid ?>
	<div id="r_customerid" class="form-group">
		<label id="elh_customer_info_customerid" class="col-sm-2 control-label ewLabel"><?php echo $customer_info->customerid->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $customer_info->customerid->CellAttributes() ?>>
<span id="el_customer_info_customerid">
<span<?php echo $customer_info->customerid->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $customer_info->customerid->EditValue ?></p></span>
</span>
<input type="hidden" data-table="customer_info" data-field="x_customerid" name="x_customerid" id="x_customerid" value="<?php echo ew_HtmlEncode($customer_info->customerid->CurrentValue) ?>">
<?php echo $customer_info->customerid->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customer_info->customername->Visible) { // customername ?>
	<div id="r_customername" class="form-group">
		<label id="elh_customer_info_customername" for="x_customername" class="col-sm-2 control-label ewLabel"><?php echo $customer_info->customername->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $customer_info->customername->CellAttributes() ?>>
<span id="el_customer_info_customername">
<input type="text" data-table="customer_info" data-field="x_customername" name="x_customername" id="x_customername" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($customer_info->customername->getPlaceHolder()) ?>" value="<?php echo $customer_info->customername->EditValue ?>"<?php echo $customer_info->customername->EditAttributes() ?>>
</span>
<?php echo $customer_info->customername->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customer_info->houseno->Visible) { // houseno ?>
	<div id="r_houseno" class="form-group">
		<label id="elh_customer_info_houseno" for="x_houseno" class="col-sm-2 control-label ewLabel"><?php echo $customer_info->houseno->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $customer_info->houseno->CellAttributes() ?>>
<span id="el_customer_info_houseno">
<input type="text" data-table="customer_info" data-field="x_houseno" name="x_houseno" id="x_houseno" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($customer_info->houseno->getPlaceHolder()) ?>" value="<?php echo $customer_info->houseno->EditValue ?>"<?php echo $customer_info->houseno->EditAttributes() ?>>
</span>
<?php echo $customer_info->houseno->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customer_info->locality->Visible) { // locality ?>
	<div id="r_locality" class="form-group">
		<label id="elh_customer_info_locality" for="x_locality" class="col-sm-2 control-label ewLabel"><?php echo $customer_info->locality->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $customer_info->locality->CellAttributes() ?>>
<span id="el_customer_info_locality">
<input type="text" data-table="customer_info" data-field="x_locality" name="x_locality" id="x_locality" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($customer_info->locality->getPlaceHolder()) ?>" value="<?php echo $customer_info->locality->EditValue ?>"<?php echo $customer_info->locality->EditAttributes() ?>>
</span>
<?php echo $customer_info->locality->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customer_info->city->Visible) { // city ?>
	<div id="r_city" class="form-group">
		<label id="elh_customer_info_city" for="x_city" class="col-sm-2 control-label ewLabel"><?php echo $customer_info->city->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $customer_info->city->CellAttributes() ?>>
<span id="el_customer_info_city">
<input type="text" data-table="customer_info" data-field="x_city" name="x_city" id="x_city" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($customer_info->city->getPlaceHolder()) ?>" value="<?php echo $customer_info->city->EditValue ?>"<?php echo $customer_info->city->EditAttributes() ?>>
</span>
<?php echo $customer_info->city->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customer_info->state->Visible) { // state ?>
	<div id="r_state" class="form-group">
		<label id="elh_customer_info_state" for="x_state" class="col-sm-2 control-label ewLabel"><?php echo $customer_info->state->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $customer_info->state->CellAttributes() ?>>
<span id="el_customer_info_state">
<select data-table="customer_info" data-field="x_state" data-value-separator="<?php echo $customer_info->state->DisplayValueSeparatorAttribute() ?>" id="x_state" name="x_state"<?php echo $customer_info->state->EditAttributes() ?>>
<?php echo $customer_info->state->SelectOptionListHtml("x_state") ?>
</select>
</span>
<?php echo $customer_info->state->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($customer_info->remarks->Visible) { // remarks ?>
	<div id="r_remarks" class="form-group">
		<label id="elh_customer_info_remarks" for="x_remarks" class="col-sm-2 control-label ewLabel"><?php echo $customer_info->remarks->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $customer_info->remarks->CellAttributes() ?>>
<span id="el_customer_info_remarks">
<textarea data-table="customer_info" data-field="x_remarks" name="x_remarks" id="x_remarks" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($customer_info->remarks->getPlaceHolder()) ?>"<?php echo $customer_info->remarks->EditAttributes() ?>><?php echo $customer_info->remarks->EditValue ?></textarea>
</span>
<?php echo $customer_info->remarks->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$customer_info_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $customer_info_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fcustomer_infoedit.Init();
</script>
<?php
$customer_info_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$customer_info_edit->Page_Terminate();
?>
