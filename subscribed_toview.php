<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "subscribed_toinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$subscribed_to_view = NULL; // Initialize page object first

class csubscribed_to_view extends csubscribed_to {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{57a20c3c-a4d2-4bc3-b7ca-fb4654c3c5cd}";

	// Table name
	var $TableName = 'subscribed_to';

	// Page object name
	var $PageObjName = 'subscribed_to_view';

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
		$KeyUrl = "";
		if (@$_GET["serviceid"] <> "") {
			$this->RecKey["serviceid"] = $_GET["serviceid"];
			$KeyUrl .= "&amp;serviceid=" . urlencode($this->RecKey["serviceid"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'subscribed_to', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["serviceid"] <> "") {
				$this->serviceid->setQueryStringValue($_GET["serviceid"]);
				$this->RecKey["serviceid"] = $this->serviceid->QueryStringValue;
			} elseif (@$_POST["serviceid"] <> "") {
				$this->serviceid->setFormValue($_POST["serviceid"]);
				$this->RecKey["serviceid"] = $this->serviceid->FormValue;
			} else {
				$sReturnUrl = "subscribed_tolist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "subscribed_tolist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "subscribed_tolist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "");

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "");

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		if ($this->AuditTrailOnView) $this->WriteAuditTrailOnView($row);
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

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
		$this->customerid->ViewValue = $this->customerid->CurrentValue;
		$this->customerid->ViewCustomAttributes = "";

		// servicename
		$this->servicename->ViewValue = $this->servicename->CurrentValue;
		$this->servicename->ViewCustomAttributes = "";

		// subscriberid
		$this->subscriberid->ViewValue = $this->subscriberid->CurrentValue;
		$this->subscriberid->ViewCustomAttributes = "";

		// regphone
		$this->regphone->ViewValue = $this->regphone->CurrentValue;
		$this->regphone->ViewCustomAttributes = "";

		// rechargedate
		$this->rechargedate->ViewValue = $this->rechargedate->CurrentValue;
		$this->rechargedate->ViewValue = ew_FormatDateTime($this->rechargedate->ViewValue, 0);
		$this->rechargedate->ViewCustomAttributes = "";

		// rechargeamount
		$this->rechargeamount->ViewValue = $this->rechargeamount->CurrentValue;
		$this->rechargeamount->ViewCustomAttributes = "";

		// rechargedue
		$this->rechargedue->ViewValue = $this->rechargedue->CurrentValue;
		$this->rechargedue->ViewValue = ew_FormatDateTime($this->rechargedue->ViewValue, 0);
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("subscribed_tolist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($subscribed_to_view)) $subscribed_to_view = new csubscribed_to_view();

// Page init
$subscribed_to_view->Page_Init();

// Page main
$subscribed_to_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$subscribed_to_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fsubscribed_toview = new ew_Form("fsubscribed_toview", "view");

// Form_CustomValidate event
fsubscribed_toview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fsubscribed_toview.ValidateRequired = true;
<?php } else { ?>
fsubscribed_toview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if (!$subscribed_to_view->IsModal) { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $subscribed_to_view->ExportOptions->Render("body") ?>
<?php
	foreach ($subscribed_to_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$subscribed_to_view->IsModal) { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php $subscribed_to_view->ShowPageHeader(); ?>
<?php
$subscribed_to_view->ShowMessage();
?>
<form name="fsubscribed_toview" id="fsubscribed_toview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($subscribed_to_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $subscribed_to_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="subscribed_to">
<?php if ($subscribed_to_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($subscribed_to->serviceid->Visible) { // serviceid ?>
	<tr id="r_serviceid">
		<td><span id="elh_subscribed_to_serviceid"><?php echo $subscribed_to->serviceid->FldCaption() ?></span></td>
		<td data-name="serviceid"<?php echo $subscribed_to->serviceid->CellAttributes() ?>>
<span id="el_subscribed_to_serviceid">
<span<?php echo $subscribed_to->serviceid->ViewAttributes() ?>>
<?php echo $subscribed_to->serviceid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($subscribed_to->customerid->Visible) { // customerid ?>
	<tr id="r_customerid">
		<td><span id="elh_subscribed_to_customerid"><?php echo $subscribed_to->customerid->FldCaption() ?></span></td>
		<td data-name="customerid"<?php echo $subscribed_to->customerid->CellAttributes() ?>>
<span id="el_subscribed_to_customerid">
<span<?php echo $subscribed_to->customerid->ViewAttributes() ?>>
<?php echo $subscribed_to->customerid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($subscribed_to->servicename->Visible) { // servicename ?>
	<tr id="r_servicename">
		<td><span id="elh_subscribed_to_servicename"><?php echo $subscribed_to->servicename->FldCaption() ?></span></td>
		<td data-name="servicename"<?php echo $subscribed_to->servicename->CellAttributes() ?>>
<span id="el_subscribed_to_servicename">
<span<?php echo $subscribed_to->servicename->ViewAttributes() ?>>
<?php echo $subscribed_to->servicename->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($subscribed_to->subscriberid->Visible) { // subscriberid ?>
	<tr id="r_subscriberid">
		<td><span id="elh_subscribed_to_subscriberid"><?php echo $subscribed_to->subscriberid->FldCaption() ?></span></td>
		<td data-name="subscriberid"<?php echo $subscribed_to->subscriberid->CellAttributes() ?>>
<span id="el_subscribed_to_subscriberid">
<span<?php echo $subscribed_to->subscriberid->ViewAttributes() ?>>
<?php echo $subscribed_to->subscriberid->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($subscribed_to->regphone->Visible) { // regphone ?>
	<tr id="r_regphone">
		<td><span id="elh_subscribed_to_regphone"><?php echo $subscribed_to->regphone->FldCaption() ?></span></td>
		<td data-name="regphone"<?php echo $subscribed_to->regphone->CellAttributes() ?>>
<span id="el_subscribed_to_regphone">
<span<?php echo $subscribed_to->regphone->ViewAttributes() ?>>
<?php echo $subscribed_to->regphone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($subscribed_to->rechargedate->Visible) { // rechargedate ?>
	<tr id="r_rechargedate">
		<td><span id="elh_subscribed_to_rechargedate"><?php echo $subscribed_to->rechargedate->FldCaption() ?></span></td>
		<td data-name="rechargedate"<?php echo $subscribed_to->rechargedate->CellAttributes() ?>>
<span id="el_subscribed_to_rechargedate">
<span<?php echo $subscribed_to->rechargedate->ViewAttributes() ?>>
<?php echo $subscribed_to->rechargedate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($subscribed_to->rechargeamount->Visible) { // rechargeamount ?>
	<tr id="r_rechargeamount">
		<td><span id="elh_subscribed_to_rechargeamount"><?php echo $subscribed_to->rechargeamount->FldCaption() ?></span></td>
		<td data-name="rechargeamount"<?php echo $subscribed_to->rechargeamount->CellAttributes() ?>>
<span id="el_subscribed_to_rechargeamount">
<span<?php echo $subscribed_to->rechargeamount->ViewAttributes() ?>>
<?php echo $subscribed_to->rechargeamount->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($subscribed_to->rechargedue->Visible) { // rechargedue ?>
	<tr id="r_rechargedue">
		<td><span id="elh_subscribed_to_rechargedue"><?php echo $subscribed_to->rechargedue->FldCaption() ?></span></td>
		<td data-name="rechargedue"<?php echo $subscribed_to->rechargedue->CellAttributes() ?>>
<span id="el_subscribed_to_rechargedue">
<span<?php echo $subscribed_to->rechargedue->ViewAttributes() ?>>
<?php echo $subscribed_to->rechargedue->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fsubscribed_toview.Init();
</script>
<?php
$subscribed_to_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$subscribed_to_view->Page_Terminate();
?>
