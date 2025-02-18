<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "vidpathsinfo.php" ?>
<?php include_once "cruge_userinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$vidpaths_list = NULL; // Initialize page object first

class cvidpaths_list extends cvidpaths {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{95DDD5E1-EED3-4F75-9459-65662A38CD3B}";

	// Table name
	var $TableName = 'vidpaths';

	// Page object name
	var $PageObjName = 'vidpaths_list';

	// Grid form hidden field names
	var $FormName = 'fvidpathslist';
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
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
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

		// Table object (vidpaths)
		if (!isset($GLOBALS["vidpaths"]) || get_class($GLOBALS["vidpaths"]) == "cvidpaths") {
			$GLOBALS["vidpaths"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["vidpaths"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "vidpathsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "vidpathsdelete.php";
		$this->MultiUpdateUrl = "vidpathsupdate.php";

		// Table object (cruge_user)
		if (!isset($GLOBALS['cruge_user'])) $GLOBALS['cruge_user'] = new ccruge_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'vidpaths', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (cruge_user)
		if (!isset($UserTable)) {
			$UserTable = new ccruge_user();
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
		$this->FilterOptions->TagClassName = "ewFilterOption fvidpathslistsrch";

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
		if (!$Security->IsLoggedIn()) $this->Page_Terminate(ew_GetUrl("login.php"));

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();

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
		global $EW_EXPORT, $vidpaths;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($vidpaths);
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

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

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
		if (count($arrKeyFlds) >= 0) {
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idPrograma); // idPrograma
			$this->UpdateSort($this->idProyecto); // idProyecto
			$this->UpdateSort($this->idCalificacion); // idCalificacion
			$this->UpdateSort($this->idAdjudicacion); // idAdjudicacion
			$this->UpdateSort($this->idContratacion); // idContratacion
			$this->UpdateSort($this->idContratos); // idContratos
			$this->UpdateSort($this->idEjecucion); // idEjecucion
			$this->UpdateSort($this->idAvances); // idAvances
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

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idPrograma->setSort("");
				$this->idProyecto->setSort("");
				$this->idCalificacion->setSort("");
				$this->idAdjudicacion->setSort("");
				$this->idContratacion->setSort("");
				$this->idContratos->setSort("");
				$this->idEjecucion->setSort("");
				$this->idAvances->setSort("");
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
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
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

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt) {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
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
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fvidpathslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = FALSE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fvidpathslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = FALSE;
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fvidpathslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
		$this->idPrograma->setDbValue($rs->fields('idPrograma'));
		$this->idProyecto->setDbValue($rs->fields('idProyecto'));
		$this->idCalificacion->setDbValue($rs->fields('idCalificacion'));
		$this->idAdjudicacion->setDbValue($rs->fields('idAdjudicacion'));
		$this->idContratacion->setDbValue($rs->fields('idContratacion'));
		$this->idContratos->setDbValue($rs->fields('idContratos'));
		$this->idEjecucion->setDbValue($rs->fields('idEjecucion'));
		$this->idAvances->setDbValue($rs->fields('idAvances'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idPrograma->DbValue = $row['idPrograma'];
		$this->idProyecto->DbValue = $row['idProyecto'];
		$this->idCalificacion->DbValue = $row['idCalificacion'];
		$this->idAdjudicacion->DbValue = $row['idAdjudicacion'];
		$this->idContratacion->DbValue = $row['idContratacion'];
		$this->idContratos->DbValue = $row['idContratos'];
		$this->idEjecucion->DbValue = $row['idEjecucion'];
		$this->idAvances->DbValue = $row['idAvances'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;

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

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// idPrograma
		// idProyecto
		// idCalificacion
		// idAdjudicacion
		// idContratacion
		// idContratos
		// idEjecucion
		// idAvances

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idPrograma
		$this->idPrograma->ViewValue = $this->idPrograma->CurrentValue;
		$this->idPrograma->ViewCustomAttributes = "";

		// idProyecto
		$this->idProyecto->ViewValue = $this->idProyecto->CurrentValue;
		$this->idProyecto->ViewCustomAttributes = "";

		// idCalificacion
		$this->idCalificacion->ViewValue = $this->idCalificacion->CurrentValue;
		$this->idCalificacion->ViewCustomAttributes = "";

		// idAdjudicacion
		$this->idAdjudicacion->ViewValue = $this->idAdjudicacion->CurrentValue;
		$this->idAdjudicacion->ViewCustomAttributes = "";

		// idContratacion
		$this->idContratacion->ViewValue = $this->idContratacion->CurrentValue;
		$this->idContratacion->ViewCustomAttributes = "";

		// idContratos
		$this->idContratos->ViewValue = $this->idContratos->CurrentValue;
		$this->idContratos->ViewCustomAttributes = "";

		// idEjecucion
		$this->idEjecucion->ViewValue = $this->idEjecucion->CurrentValue;
		$this->idEjecucion->ViewCustomAttributes = "";

		// idAvances
		$this->idAvances->ViewValue = $this->idAvances->CurrentValue;
		$this->idAvances->ViewCustomAttributes = "";

			// idPrograma
			$this->idPrograma->LinkCustomAttributes = "";
			$this->idPrograma->HrefValue = "";
			$this->idPrograma->TooltipValue = "";

			// idProyecto
			$this->idProyecto->LinkCustomAttributes = "";
			$this->idProyecto->HrefValue = "";
			$this->idProyecto->TooltipValue = "";

			// idCalificacion
			$this->idCalificacion->LinkCustomAttributes = "";
			$this->idCalificacion->HrefValue = "";
			$this->idCalificacion->TooltipValue = "";

			// idAdjudicacion
			$this->idAdjudicacion->LinkCustomAttributes = "";
			$this->idAdjudicacion->HrefValue = "";
			$this->idAdjudicacion->TooltipValue = "";

			// idContratacion
			$this->idContratacion->LinkCustomAttributes = "";
			$this->idContratacion->HrefValue = "";
			$this->idContratacion->TooltipValue = "";

			// idContratos
			$this->idContratos->LinkCustomAttributes = "";
			$this->idContratos->HrefValue = "";
			$this->idContratos->TooltipValue = "";

			// idEjecucion
			$this->idEjecucion->LinkCustomAttributes = "";
			$this->idEjecucion->HrefValue = "";
			$this->idEjecucion->TooltipValue = "";

			// idAvances
			$this->idAvances->LinkCustomAttributes = "";
			$this->idAvances->HrefValue = "";
			$this->idAvances->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = FALSE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_vidpaths\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_vidpaths',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fvidpathslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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
if (!isset($vidpaths_list)) $vidpaths_list = new cvidpaths_list();

// Page init
$vidpaths_list->Page_Init();

// Page main
$vidpaths_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$vidpaths_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($vidpaths->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fvidpathslist = new ew_Form("fvidpathslist", "list");
fvidpathslist.FormKeyCountName = '<?php echo $vidpaths_list->FormKeyCountName ?>';

// Form_CustomValidate event
fvidpathslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fvidpathslist.ValidateRequired = true;
<?php } else { ?>
fvidpathslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($vidpaths->Export == "") { ?>
<div class="ewToolbar">
<?php if ($vidpaths->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($vidpaths_list->TotalRecs > 0 && $vidpaths_list->ExportOptions->Visible()) { ?>
<?php $vidpaths_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($vidpaths->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = $vidpaths_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($vidpaths_list->TotalRecs <= 0)
			$vidpaths_list->TotalRecs = $vidpaths->SelectRecordCount();
	} else {
		if (!$vidpaths_list->Recordset && ($vidpaths_list->Recordset = $vidpaths_list->LoadRecordset()))
			$vidpaths_list->TotalRecs = $vidpaths_list->Recordset->RecordCount();
	}
	$vidpaths_list->StartRec = 1;
	if ($vidpaths_list->DisplayRecs <= 0 || ($vidpaths->Export <> "" && $vidpaths->ExportAll)) // Display all records
		$vidpaths_list->DisplayRecs = $vidpaths_list->TotalRecs;
	if (!($vidpaths->Export <> "" && $vidpaths->ExportAll))
		$vidpaths_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$vidpaths_list->Recordset = $vidpaths_list->LoadRecordset($vidpaths_list->StartRec-1, $vidpaths_list->DisplayRecs);

	// Set no record found message
	if ($vidpaths->CurrentAction == "" && $vidpaths_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$vidpaths_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($vidpaths_list->SearchWhere == "0=101")
			$vidpaths_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$vidpaths_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$vidpaths_list->RenderOtherOptions();
?>
<?php $vidpaths_list->ShowPageHeader(); ?>
<?php
$vidpaths_list->ShowMessage();
?>
<?php if ($vidpaths_list->TotalRecs > 0 || $vidpaths->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fvidpathslist" id="fvidpathslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($vidpaths_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $vidpaths_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="vidpaths">
<div id="gmp_vidpaths" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($vidpaths_list->TotalRecs > 0) { ?>
<table id="tbl_vidpathslist" class="table ewTable">
<?php echo $vidpaths->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$vidpaths_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$vidpaths_list->RenderListOptions();

// Render list options (header, left)
$vidpaths_list->ListOptions->Render("header", "left");
?>
<?php if ($vidpaths->idPrograma->Visible) { // idPrograma ?>
	<?php if ($vidpaths->SortUrl($vidpaths->idPrograma) == "") { ?>
		<th data-name="idPrograma"><div id="elh_vidpaths_idPrograma" class="vidpaths_idPrograma"><div class="ewTableHeaderCaption"><?php echo $vidpaths->idPrograma->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idPrograma"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vidpaths->SortUrl($vidpaths->idPrograma) ?>',1);"><div id="elh_vidpaths_idPrograma" class="vidpaths_idPrograma">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vidpaths->idPrograma->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vidpaths->idPrograma->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vidpaths->idPrograma->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vidpaths->idProyecto->Visible) { // idProyecto ?>
	<?php if ($vidpaths->SortUrl($vidpaths->idProyecto) == "") { ?>
		<th data-name="idProyecto"><div id="elh_vidpaths_idProyecto" class="vidpaths_idProyecto"><div class="ewTableHeaderCaption"><?php echo $vidpaths->idProyecto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idProyecto"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vidpaths->SortUrl($vidpaths->idProyecto) ?>',1);"><div id="elh_vidpaths_idProyecto" class="vidpaths_idProyecto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vidpaths->idProyecto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vidpaths->idProyecto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vidpaths->idProyecto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vidpaths->idCalificacion->Visible) { // idCalificacion ?>
	<?php if ($vidpaths->SortUrl($vidpaths->idCalificacion) == "") { ?>
		<th data-name="idCalificacion"><div id="elh_vidpaths_idCalificacion" class="vidpaths_idCalificacion"><div class="ewTableHeaderCaption"><?php echo $vidpaths->idCalificacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idCalificacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vidpaths->SortUrl($vidpaths->idCalificacion) ?>',1);"><div id="elh_vidpaths_idCalificacion" class="vidpaths_idCalificacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vidpaths->idCalificacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vidpaths->idCalificacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vidpaths->idCalificacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vidpaths->idAdjudicacion->Visible) { // idAdjudicacion ?>
	<?php if ($vidpaths->SortUrl($vidpaths->idAdjudicacion) == "") { ?>
		<th data-name="idAdjudicacion"><div id="elh_vidpaths_idAdjudicacion" class="vidpaths_idAdjudicacion"><div class="ewTableHeaderCaption"><?php echo $vidpaths->idAdjudicacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idAdjudicacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vidpaths->SortUrl($vidpaths->idAdjudicacion) ?>',1);"><div id="elh_vidpaths_idAdjudicacion" class="vidpaths_idAdjudicacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vidpaths->idAdjudicacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vidpaths->idAdjudicacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vidpaths->idAdjudicacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vidpaths->idContratacion->Visible) { // idContratacion ?>
	<?php if ($vidpaths->SortUrl($vidpaths->idContratacion) == "") { ?>
		<th data-name="idContratacion"><div id="elh_vidpaths_idContratacion" class="vidpaths_idContratacion"><div class="ewTableHeaderCaption"><?php echo $vidpaths->idContratacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idContratacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vidpaths->SortUrl($vidpaths->idContratacion) ?>',1);"><div id="elh_vidpaths_idContratacion" class="vidpaths_idContratacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vidpaths->idContratacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vidpaths->idContratacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vidpaths->idContratacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vidpaths->idContratos->Visible) { // idContratos ?>
	<?php if ($vidpaths->SortUrl($vidpaths->idContratos) == "") { ?>
		<th data-name="idContratos"><div id="elh_vidpaths_idContratos" class="vidpaths_idContratos"><div class="ewTableHeaderCaption"><?php echo $vidpaths->idContratos->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idContratos"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vidpaths->SortUrl($vidpaths->idContratos) ?>',1);"><div id="elh_vidpaths_idContratos" class="vidpaths_idContratos">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vidpaths->idContratos->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vidpaths->idContratos->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vidpaths->idContratos->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vidpaths->idEjecucion->Visible) { // idEjecucion ?>
	<?php if ($vidpaths->SortUrl($vidpaths->idEjecucion) == "") { ?>
		<th data-name="idEjecucion"><div id="elh_vidpaths_idEjecucion" class="vidpaths_idEjecucion"><div class="ewTableHeaderCaption"><?php echo $vidpaths->idEjecucion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idEjecucion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vidpaths->SortUrl($vidpaths->idEjecucion) ?>',1);"><div id="elh_vidpaths_idEjecucion" class="vidpaths_idEjecucion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vidpaths->idEjecucion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vidpaths->idEjecucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vidpaths->idEjecucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($vidpaths->idAvances->Visible) { // idAvances ?>
	<?php if ($vidpaths->SortUrl($vidpaths->idAvances) == "") { ?>
		<th data-name="idAvances"><div id="elh_vidpaths_idAvances" class="vidpaths_idAvances"><div class="ewTableHeaderCaption"><?php echo $vidpaths->idAvances->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idAvances"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $vidpaths->SortUrl($vidpaths->idAvances) ?>',1);"><div id="elh_vidpaths_idAvances" class="vidpaths_idAvances">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $vidpaths->idAvances->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($vidpaths->idAvances->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($vidpaths->idAvances->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$vidpaths_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($vidpaths->ExportAll && $vidpaths->Export <> "") {
	$vidpaths_list->StopRec = $vidpaths_list->TotalRecs;
} else {

	// Set the last record to display
	if ($vidpaths_list->TotalRecs > $vidpaths_list->StartRec + $vidpaths_list->DisplayRecs - 1)
		$vidpaths_list->StopRec = $vidpaths_list->StartRec + $vidpaths_list->DisplayRecs - 1;
	else
		$vidpaths_list->StopRec = $vidpaths_list->TotalRecs;
}
$vidpaths_list->RecCnt = $vidpaths_list->StartRec - 1;
if ($vidpaths_list->Recordset && !$vidpaths_list->Recordset->EOF) {
	$vidpaths_list->Recordset->MoveFirst();
	$bSelectLimit = $vidpaths_list->UseSelectLimit;
	if (!$bSelectLimit && $vidpaths_list->StartRec > 1)
		$vidpaths_list->Recordset->Move($vidpaths_list->StartRec - 1);
} elseif (!$vidpaths->AllowAddDeleteRow && $vidpaths_list->StopRec == 0) {
	$vidpaths_list->StopRec = $vidpaths->GridAddRowCount;
}

// Initialize aggregate
$vidpaths->RowType = EW_ROWTYPE_AGGREGATEINIT;
$vidpaths->ResetAttrs();
$vidpaths_list->RenderRow();
while ($vidpaths_list->RecCnt < $vidpaths_list->StopRec) {
	$vidpaths_list->RecCnt++;
	if (intval($vidpaths_list->RecCnt) >= intval($vidpaths_list->StartRec)) {
		$vidpaths_list->RowCnt++;

		// Set up key count
		$vidpaths_list->KeyCount = $vidpaths_list->RowIndex;

		// Init row class and style
		$vidpaths->ResetAttrs();
		$vidpaths->CssClass = "";
		if ($vidpaths->CurrentAction == "gridadd") {
		} else {
			$vidpaths_list->LoadRowValues($vidpaths_list->Recordset); // Load row values
		}
		$vidpaths->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$vidpaths->RowAttrs = array_merge($vidpaths->RowAttrs, array('data-rowindex'=>$vidpaths_list->RowCnt, 'id'=>'r' . $vidpaths_list->RowCnt . '_vidpaths', 'data-rowtype'=>$vidpaths->RowType));

		// Render row
		$vidpaths_list->RenderRow();

		// Render list options
		$vidpaths_list->RenderListOptions();
?>
	<tr<?php echo $vidpaths->RowAttributes() ?>>
<?php

// Render list options (body, left)
$vidpaths_list->ListOptions->Render("body", "left", $vidpaths_list->RowCnt);
?>
	<?php if ($vidpaths->idPrograma->Visible) { // idPrograma ?>
		<td data-name="idPrograma"<?php echo $vidpaths->idPrograma->CellAttributes() ?>>
<span id="el<?php echo $vidpaths_list->RowCnt ?>_vidpaths_idPrograma" class="vidpaths_idPrograma">
<span<?php echo $vidpaths->idPrograma->ViewAttributes() ?>>
<?php echo $vidpaths->idPrograma->ListViewValue() ?></span>
</span>
<a id="<?php echo $vidpaths_list->PageObjName . "_row_" . $vidpaths_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($vidpaths->idProyecto->Visible) { // idProyecto ?>
		<td data-name="idProyecto"<?php echo $vidpaths->idProyecto->CellAttributes() ?>>
<span id="el<?php echo $vidpaths_list->RowCnt ?>_vidpaths_idProyecto" class="vidpaths_idProyecto">
<span<?php echo $vidpaths->idProyecto->ViewAttributes() ?>>
<?php echo $vidpaths->idProyecto->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vidpaths->idCalificacion->Visible) { // idCalificacion ?>
		<td data-name="idCalificacion"<?php echo $vidpaths->idCalificacion->CellAttributes() ?>>
<span id="el<?php echo $vidpaths_list->RowCnt ?>_vidpaths_idCalificacion" class="vidpaths_idCalificacion">
<span<?php echo $vidpaths->idCalificacion->ViewAttributes() ?>>
<?php echo $vidpaths->idCalificacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vidpaths->idAdjudicacion->Visible) { // idAdjudicacion ?>
		<td data-name="idAdjudicacion"<?php echo $vidpaths->idAdjudicacion->CellAttributes() ?>>
<span id="el<?php echo $vidpaths_list->RowCnt ?>_vidpaths_idAdjudicacion" class="vidpaths_idAdjudicacion">
<span<?php echo $vidpaths->idAdjudicacion->ViewAttributes() ?>>
<?php echo $vidpaths->idAdjudicacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vidpaths->idContratacion->Visible) { // idContratacion ?>
		<td data-name="idContratacion"<?php echo $vidpaths->idContratacion->CellAttributes() ?>>
<span id="el<?php echo $vidpaths_list->RowCnt ?>_vidpaths_idContratacion" class="vidpaths_idContratacion">
<span<?php echo $vidpaths->idContratacion->ViewAttributes() ?>>
<?php echo $vidpaths->idContratacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vidpaths->idContratos->Visible) { // idContratos ?>
		<td data-name="idContratos"<?php echo $vidpaths->idContratos->CellAttributes() ?>>
<span id="el<?php echo $vidpaths_list->RowCnt ?>_vidpaths_idContratos" class="vidpaths_idContratos">
<span<?php echo $vidpaths->idContratos->ViewAttributes() ?>>
<?php echo $vidpaths->idContratos->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vidpaths->idEjecucion->Visible) { // idEjecucion ?>
		<td data-name="idEjecucion"<?php echo $vidpaths->idEjecucion->CellAttributes() ?>>
<span id="el<?php echo $vidpaths_list->RowCnt ?>_vidpaths_idEjecucion" class="vidpaths_idEjecucion">
<span<?php echo $vidpaths->idEjecucion->ViewAttributes() ?>>
<?php echo $vidpaths->idEjecucion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($vidpaths->idAvances->Visible) { // idAvances ?>
		<td data-name="idAvances"<?php echo $vidpaths->idAvances->CellAttributes() ?>>
<span id="el<?php echo $vidpaths_list->RowCnt ?>_vidpaths_idAvances" class="vidpaths_idAvances">
<span<?php echo $vidpaths->idAvances->ViewAttributes() ?>>
<?php echo $vidpaths->idAvances->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$vidpaths_list->ListOptions->Render("body", "right", $vidpaths_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($vidpaths->CurrentAction <> "gridadd")
		$vidpaths_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($vidpaths->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($vidpaths_list->Recordset)
	$vidpaths_list->Recordset->Close();
?>
<?php if ($vidpaths->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($vidpaths->CurrentAction <> "gridadd" && $vidpaths->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($vidpaths_list->Pager)) $vidpaths_list->Pager = new cPrevNextPager($vidpaths_list->StartRec, $vidpaths_list->DisplayRecs, $vidpaths_list->TotalRecs) ?>
<?php if ($vidpaths_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($vidpaths_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $vidpaths_list->PageUrl() ?>start=<?php echo $vidpaths_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($vidpaths_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $vidpaths_list->PageUrl() ?>start=<?php echo $vidpaths_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $vidpaths_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($vidpaths_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $vidpaths_list->PageUrl() ?>start=<?php echo $vidpaths_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($vidpaths_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $vidpaths_list->PageUrl() ?>start=<?php echo $vidpaths_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $vidpaths_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $vidpaths_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $vidpaths_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $vidpaths_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vidpaths_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($vidpaths_list->TotalRecs == 0 && $vidpaths->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($vidpaths_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($vidpaths->Export == "") { ?>
<script type="text/javascript">
fvidpathslist.Init();
</script>
<?php } ?>
<?php
$vidpaths_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($vidpaths->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$vidpaths_list->Page_Terminate();
?>
