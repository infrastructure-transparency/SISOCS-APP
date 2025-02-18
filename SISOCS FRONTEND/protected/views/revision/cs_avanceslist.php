<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cs_avancesinfo.php" ?>
<?php include_once "cruge_userinfo.php" ?>
<?php include_once "cs_inicio_ejecucioninfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cs_avances_list = NULL; // Initialize page object first

class ccs_avances_list extends ccs_avances {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{95DDD5E1-EED3-4F75-9459-65662A38CD3B}";

	// Table name
	var $TableName = 'cs_avances';

	// Page object name
	var $PageObjName = 'cs_avances_list';

	// Grid form hidden field names
	var $FormName = 'fcs_avanceslist';
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

		// Table object (cs_avances)
		if (!isset($GLOBALS["cs_avances"]) || get_class($GLOBALS["cs_avances"]) == "ccs_avances") {
			$GLOBALS["cs_avances"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cs_avances"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "cs_avancesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "cs_avancesdelete.php";
		$this->MultiUpdateUrl = "cs_avancesupdate.php";

		// Table object (cruge_user)
		if (!isset($GLOBALS['cruge_user'])) $GLOBALS['cruge_user'] = new ccruge_user();

		// Table object (cs_inicio_ejecucion)
		if (!isset($GLOBALS['cs_inicio_ejecucion'])) $GLOBALS['cs_inicio_ejecucion'] = new ccs_inicio_ejecucion();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cs_avances', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fcs_avanceslistsrch";

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
		$this->codigo->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $cs_avances;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cs_avances);
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

			// Set up master detail parameters
			$this->SetUpMasterParms();

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

			// Restore filter list
			$this->RestoreFilterList();

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

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "cs_inicio_ejecucion") {
			global $cs_inicio_ejecucion;
			$rsmaster = $cs_inicio_ejecucion->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("cs_inicio_ejecucionlist.php"); // Return to master page
			} else {
				$cs_inicio_ejecucion->LoadListRowValues($rsmaster);
				$cs_inicio_ejecucion->RowType = EW_ROWTYPE_MASTER; // Master row
				$cs_inicio_ejecucion->RenderListRow();
				$rsmaster->Close();
			}
		}

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
		if (count($arrKeyFlds) >= 1) {
			$this->codigo->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->codigo->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->codigo->AdvancedSearch->ToJSON(), ","); // Field codigo
		$sFilterList = ew_Concat($sFilterList, $this->codigo_inicio_ejecucion->AdvancedSearch->ToJSON(), ","); // Field codigo_inicio_ejecucion
		$sFilterList = ew_Concat($sFilterList, $this->porcent_programado->AdvancedSearch->ToJSON(), ","); // Field porcent_programado
		$sFilterList = ew_Concat($sFilterList, $this->porcent_real->AdvancedSearch->ToJSON(), ","); // Field porcent_real
		$sFilterList = ew_Concat($sFilterList, $this->finan_programado->AdvancedSearch->ToJSON(), ","); // Field finan_programado
		$sFilterList = ew_Concat($sFilterList, $this->finan_real->AdvancedSearch->ToJSON(), ","); // Field finan_real
		$sFilterList = ew_Concat($sFilterList, $this->fecha_registro->AdvancedSearch->ToJSON(), ","); // Field fecha_registro
		$sFilterList = ew_Concat($sFilterList, $this->estado->AdvancedSearch->ToJSON(), ","); // Field estado
		$sFilterList = ew_Concat($sFilterList, $this->user_registro->AdvancedSearch->ToJSON(), ","); // Field user_registro
		$sFilterList = ew_Concat($sFilterList, $this->desc_problemas->AdvancedSearch->ToJSON(), ","); // Field desc_problemas
		$sFilterList = ew_Concat($sFilterList, $this->desc_temas->AdvancedSearch->ToJSON(), ","); // Field desc_temas
		$sFilterList = ew_Concat($sFilterList, $this->idEjecucion->AdvancedSearch->ToJSON(), ","); // Field idEjecucion
		$sFilterList = ew_Concat($sFilterList, $this->fecha_avance->AdvancedSearch->ToJSON(), ","); // Field fecha_avance
		$sFilterList = ew_Concat($sFilterList, $this->idContratacion->AdvancedSearch->ToJSON(), ","); // Field idContratacion
		$sFilterList = ew_Concat($sFilterList, $this->estado_sol->AdvancedSearch->ToJSON(), ","); // Field estado_sol
		$sFilterList = ew_Concat($sFilterList, $this->adj_garantias->AdvancedSearch->ToJSON(), ","); // Field adj_garantias
		$sFilterList = ew_Concat($sFilterList, $this->adj_avances->AdvancedSearch->ToJSON(), ","); // Field adj_avances
		$sFilterList = ew_Concat($sFilterList, $this->adj_supervicion->AdvancedSearch->ToJSON(), ","); // Field adj_supervicion
		$sFilterList = ew_Concat($sFilterList, $this->adj_evaluacion->AdvancedSearch->ToJSON(), ","); // Field adj_evaluacion
		$sFilterList = ew_Concat($sFilterList, $this->adj_tecnica->AdvancedSearch->ToJSON(), ","); // Field adj_tecnica
		$sFilterList = ew_Concat($sFilterList, $this->adj_financiero->AdvancedSearch->ToJSON(), ","); // Field adj_financiero
		$sFilterList = ew_Concat($sFilterList, $this->adj_recepcion->AdvancedSearch->ToJSON(), ","); // Field adj_recepcion
		$sFilterList = ew_Concat($sFilterList, $this->adj_disconformidad->AdvancedSearch->ToJSON(), ","); // Field adj_disconformidad
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"psearch\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"psearchtype\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}

		// Return filter list in json
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field codigo
		$this->codigo->AdvancedSearch->SearchValue = @$filter["x_codigo"];
		$this->codigo->AdvancedSearch->SearchOperator = @$filter["z_codigo"];
		$this->codigo->AdvancedSearch->SearchCondition = @$filter["v_codigo"];
		$this->codigo->AdvancedSearch->SearchValue2 = @$filter["y_codigo"];
		$this->codigo->AdvancedSearch->SearchOperator2 = @$filter["w_codigo"];
		$this->codigo->AdvancedSearch->Save();

		// Field codigo_inicio_ejecucion
		$this->codigo_inicio_ejecucion->AdvancedSearch->SearchValue = @$filter["x_codigo_inicio_ejecucion"];
		$this->codigo_inicio_ejecucion->AdvancedSearch->SearchOperator = @$filter["z_codigo_inicio_ejecucion"];
		$this->codigo_inicio_ejecucion->AdvancedSearch->SearchCondition = @$filter["v_codigo_inicio_ejecucion"];
		$this->codigo_inicio_ejecucion->AdvancedSearch->SearchValue2 = @$filter["y_codigo_inicio_ejecucion"];
		$this->codigo_inicio_ejecucion->AdvancedSearch->SearchOperator2 = @$filter["w_codigo_inicio_ejecucion"];
		$this->codigo_inicio_ejecucion->AdvancedSearch->Save();

		// Field porcent_programado
		$this->porcent_programado->AdvancedSearch->SearchValue = @$filter["x_porcent_programado"];
		$this->porcent_programado->AdvancedSearch->SearchOperator = @$filter["z_porcent_programado"];
		$this->porcent_programado->AdvancedSearch->SearchCondition = @$filter["v_porcent_programado"];
		$this->porcent_programado->AdvancedSearch->SearchValue2 = @$filter["y_porcent_programado"];
		$this->porcent_programado->AdvancedSearch->SearchOperator2 = @$filter["w_porcent_programado"];
		$this->porcent_programado->AdvancedSearch->Save();

		// Field porcent_real
		$this->porcent_real->AdvancedSearch->SearchValue = @$filter["x_porcent_real"];
		$this->porcent_real->AdvancedSearch->SearchOperator = @$filter["z_porcent_real"];
		$this->porcent_real->AdvancedSearch->SearchCondition = @$filter["v_porcent_real"];
		$this->porcent_real->AdvancedSearch->SearchValue2 = @$filter["y_porcent_real"];
		$this->porcent_real->AdvancedSearch->SearchOperator2 = @$filter["w_porcent_real"];
		$this->porcent_real->AdvancedSearch->Save();

		// Field finan_programado
		$this->finan_programado->AdvancedSearch->SearchValue = @$filter["x_finan_programado"];
		$this->finan_programado->AdvancedSearch->SearchOperator = @$filter["z_finan_programado"];
		$this->finan_programado->AdvancedSearch->SearchCondition = @$filter["v_finan_programado"];
		$this->finan_programado->AdvancedSearch->SearchValue2 = @$filter["y_finan_programado"];
		$this->finan_programado->AdvancedSearch->SearchOperator2 = @$filter["w_finan_programado"];
		$this->finan_programado->AdvancedSearch->Save();

		// Field finan_real
		$this->finan_real->AdvancedSearch->SearchValue = @$filter["x_finan_real"];
		$this->finan_real->AdvancedSearch->SearchOperator = @$filter["z_finan_real"];
		$this->finan_real->AdvancedSearch->SearchCondition = @$filter["v_finan_real"];
		$this->finan_real->AdvancedSearch->SearchValue2 = @$filter["y_finan_real"];
		$this->finan_real->AdvancedSearch->SearchOperator2 = @$filter["w_finan_real"];
		$this->finan_real->AdvancedSearch->Save();

		// Field fecha_registro
		$this->fecha_registro->AdvancedSearch->SearchValue = @$filter["x_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->SearchOperator = @$filter["z_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->SearchCondition = @$filter["v_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->SearchValue2 = @$filter["y_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_registro"];
		$this->fecha_registro->AdvancedSearch->Save();

		// Field estado
		$this->estado->AdvancedSearch->SearchValue = @$filter["x_estado"];
		$this->estado->AdvancedSearch->SearchOperator = @$filter["z_estado"];
		$this->estado->AdvancedSearch->SearchCondition = @$filter["v_estado"];
		$this->estado->AdvancedSearch->SearchValue2 = @$filter["y_estado"];
		$this->estado->AdvancedSearch->SearchOperator2 = @$filter["w_estado"];
		$this->estado->AdvancedSearch->Save();

		// Field user_registro
		$this->user_registro->AdvancedSearch->SearchValue = @$filter["x_user_registro"];
		$this->user_registro->AdvancedSearch->SearchOperator = @$filter["z_user_registro"];
		$this->user_registro->AdvancedSearch->SearchCondition = @$filter["v_user_registro"];
		$this->user_registro->AdvancedSearch->SearchValue2 = @$filter["y_user_registro"];
		$this->user_registro->AdvancedSearch->SearchOperator2 = @$filter["w_user_registro"];
		$this->user_registro->AdvancedSearch->Save();

		// Field desc_problemas
		$this->desc_problemas->AdvancedSearch->SearchValue = @$filter["x_desc_problemas"];
		$this->desc_problemas->AdvancedSearch->SearchOperator = @$filter["z_desc_problemas"];
		$this->desc_problemas->AdvancedSearch->SearchCondition = @$filter["v_desc_problemas"];
		$this->desc_problemas->AdvancedSearch->SearchValue2 = @$filter["y_desc_problemas"];
		$this->desc_problemas->AdvancedSearch->SearchOperator2 = @$filter["w_desc_problemas"];
		$this->desc_problemas->AdvancedSearch->Save();

		// Field desc_temas
		$this->desc_temas->AdvancedSearch->SearchValue = @$filter["x_desc_temas"];
		$this->desc_temas->AdvancedSearch->SearchOperator = @$filter["z_desc_temas"];
		$this->desc_temas->AdvancedSearch->SearchCondition = @$filter["v_desc_temas"];
		$this->desc_temas->AdvancedSearch->SearchValue2 = @$filter["y_desc_temas"];
		$this->desc_temas->AdvancedSearch->SearchOperator2 = @$filter["w_desc_temas"];
		$this->desc_temas->AdvancedSearch->Save();

		// Field idEjecucion
		$this->idEjecucion->AdvancedSearch->SearchValue = @$filter["x_idEjecucion"];
		$this->idEjecucion->AdvancedSearch->SearchOperator = @$filter["z_idEjecucion"];
		$this->idEjecucion->AdvancedSearch->SearchCondition = @$filter["v_idEjecucion"];
		$this->idEjecucion->AdvancedSearch->SearchValue2 = @$filter["y_idEjecucion"];
		$this->idEjecucion->AdvancedSearch->SearchOperator2 = @$filter["w_idEjecucion"];
		$this->idEjecucion->AdvancedSearch->Save();

		// Field fecha_avance
		$this->fecha_avance->AdvancedSearch->SearchValue = @$filter["x_fecha_avance"];
		$this->fecha_avance->AdvancedSearch->SearchOperator = @$filter["z_fecha_avance"];
		$this->fecha_avance->AdvancedSearch->SearchCondition = @$filter["v_fecha_avance"];
		$this->fecha_avance->AdvancedSearch->SearchValue2 = @$filter["y_fecha_avance"];
		$this->fecha_avance->AdvancedSearch->SearchOperator2 = @$filter["w_fecha_avance"];
		$this->fecha_avance->AdvancedSearch->Save();

		// Field idContratacion
		$this->idContratacion->AdvancedSearch->SearchValue = @$filter["x_idContratacion"];
		$this->idContratacion->AdvancedSearch->SearchOperator = @$filter["z_idContratacion"];
		$this->idContratacion->AdvancedSearch->SearchCondition = @$filter["v_idContratacion"];
		$this->idContratacion->AdvancedSearch->SearchValue2 = @$filter["y_idContratacion"];
		$this->idContratacion->AdvancedSearch->SearchOperator2 = @$filter["w_idContratacion"];
		$this->idContratacion->AdvancedSearch->Save();

		// Field estado_sol
		$this->estado_sol->AdvancedSearch->SearchValue = @$filter["x_estado_sol"];
		$this->estado_sol->AdvancedSearch->SearchOperator = @$filter["z_estado_sol"];
		$this->estado_sol->AdvancedSearch->SearchCondition = @$filter["v_estado_sol"];
		$this->estado_sol->AdvancedSearch->SearchValue2 = @$filter["y_estado_sol"];
		$this->estado_sol->AdvancedSearch->SearchOperator2 = @$filter["w_estado_sol"];
		$this->estado_sol->AdvancedSearch->Save();

		// Field adj_garantias
		$this->adj_garantias->AdvancedSearch->SearchValue = @$filter["x_adj_garantias"];
		$this->adj_garantias->AdvancedSearch->SearchOperator = @$filter["z_adj_garantias"];
		$this->adj_garantias->AdvancedSearch->SearchCondition = @$filter["v_adj_garantias"];
		$this->adj_garantias->AdvancedSearch->SearchValue2 = @$filter["y_adj_garantias"];
		$this->adj_garantias->AdvancedSearch->SearchOperator2 = @$filter["w_adj_garantias"];
		$this->adj_garantias->AdvancedSearch->Save();

		// Field adj_avances
		$this->adj_avances->AdvancedSearch->SearchValue = @$filter["x_adj_avances"];
		$this->adj_avances->AdvancedSearch->SearchOperator = @$filter["z_adj_avances"];
		$this->adj_avances->AdvancedSearch->SearchCondition = @$filter["v_adj_avances"];
		$this->adj_avances->AdvancedSearch->SearchValue2 = @$filter["y_adj_avances"];
		$this->adj_avances->AdvancedSearch->SearchOperator2 = @$filter["w_adj_avances"];
		$this->adj_avances->AdvancedSearch->Save();

		// Field adj_supervicion
		$this->adj_supervicion->AdvancedSearch->SearchValue = @$filter["x_adj_supervicion"];
		$this->adj_supervicion->AdvancedSearch->SearchOperator = @$filter["z_adj_supervicion"];
		$this->adj_supervicion->AdvancedSearch->SearchCondition = @$filter["v_adj_supervicion"];
		$this->adj_supervicion->AdvancedSearch->SearchValue2 = @$filter["y_adj_supervicion"];
		$this->adj_supervicion->AdvancedSearch->SearchOperator2 = @$filter["w_adj_supervicion"];
		$this->adj_supervicion->AdvancedSearch->Save();

		// Field adj_evaluacion
		$this->adj_evaluacion->AdvancedSearch->SearchValue = @$filter["x_adj_evaluacion"];
		$this->adj_evaluacion->AdvancedSearch->SearchOperator = @$filter["z_adj_evaluacion"];
		$this->adj_evaluacion->AdvancedSearch->SearchCondition = @$filter["v_adj_evaluacion"];
		$this->adj_evaluacion->AdvancedSearch->SearchValue2 = @$filter["y_adj_evaluacion"];
		$this->adj_evaluacion->AdvancedSearch->SearchOperator2 = @$filter["w_adj_evaluacion"];
		$this->adj_evaluacion->AdvancedSearch->Save();

		// Field adj_tecnica
		$this->adj_tecnica->AdvancedSearch->SearchValue = @$filter["x_adj_tecnica"];
		$this->adj_tecnica->AdvancedSearch->SearchOperator = @$filter["z_adj_tecnica"];
		$this->adj_tecnica->AdvancedSearch->SearchCondition = @$filter["v_adj_tecnica"];
		$this->adj_tecnica->AdvancedSearch->SearchValue2 = @$filter["y_adj_tecnica"];
		$this->adj_tecnica->AdvancedSearch->SearchOperator2 = @$filter["w_adj_tecnica"];
		$this->adj_tecnica->AdvancedSearch->Save();

		// Field adj_financiero
		$this->adj_financiero->AdvancedSearch->SearchValue = @$filter["x_adj_financiero"];
		$this->adj_financiero->AdvancedSearch->SearchOperator = @$filter["z_adj_financiero"];
		$this->adj_financiero->AdvancedSearch->SearchCondition = @$filter["v_adj_financiero"];
		$this->adj_financiero->AdvancedSearch->SearchValue2 = @$filter["y_adj_financiero"];
		$this->adj_financiero->AdvancedSearch->SearchOperator2 = @$filter["w_adj_financiero"];
		$this->adj_financiero->AdvancedSearch->Save();

		// Field adj_recepcion
		$this->adj_recepcion->AdvancedSearch->SearchValue = @$filter["x_adj_recepcion"];
		$this->adj_recepcion->AdvancedSearch->SearchOperator = @$filter["z_adj_recepcion"];
		$this->adj_recepcion->AdvancedSearch->SearchCondition = @$filter["v_adj_recepcion"];
		$this->adj_recepcion->AdvancedSearch->SearchValue2 = @$filter["y_adj_recepcion"];
		$this->adj_recepcion->AdvancedSearch->SearchOperator2 = @$filter["w_adj_recepcion"];
		$this->adj_recepcion->AdvancedSearch->Save();

		// Field adj_disconformidad
		$this->adj_disconformidad->AdvancedSearch->SearchValue = @$filter["x_adj_disconformidad"];
		$this->adj_disconformidad->AdvancedSearch->SearchOperator = @$filter["z_adj_disconformidad"];
		$this->adj_disconformidad->AdvancedSearch->SearchCondition = @$filter["v_adj_disconformidad"];
		$this->adj_disconformidad->AdvancedSearch->SearchValue2 = @$filter["y_adj_disconformidad"];
		$this->adj_disconformidad->AdvancedSearch->SearchOperator2 = @$filter["w_adj_disconformidad"];
		$this->adj_disconformidad->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter["psearch"]);
		$this->BasicSearch->setType(@$filter["psearchtype"]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->estado, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->user_registro, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->desc_problemas, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->desc_temas, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->estado_sol, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->adj_garantias, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->adj_avances, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->adj_supervicion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->adj_evaluacion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->adj_tecnica, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->adj_financiero, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->adj_recepcion, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->adj_disconformidad, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$sCond = $sDefCond;
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
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
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

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->codigo); // codigo
			$this->UpdateSort($this->codigo_inicio_ejecucion); // codigo_inicio_ejecucion
			$this->UpdateSort($this->porcent_programado); // porcent_programado
			$this->UpdateSort($this->porcent_real); // porcent_real
			$this->UpdateSort($this->finan_programado); // finan_programado
			$this->UpdateSort($this->finan_real); // finan_real
			$this->UpdateSort($this->fecha_registro); // fecha_registro
			$this->UpdateSort($this->estado); // estado
			$this->UpdateSort($this->user_registro); // user_registro
			$this->UpdateSort($this->desc_problemas); // desc_problemas
			$this->UpdateSort($this->desc_temas); // desc_temas
			$this->UpdateSort($this->idEjecucion); // idEjecucion
			$this->UpdateSort($this->fecha_avance); // fecha_avance
			$this->UpdateSort($this->idContratacion); // idContratacion
			$this->UpdateSort($this->estado_sol); // estado_sol
			$this->UpdateSort($this->adj_garantias); // adj_garantias
			$this->UpdateSort($this->adj_avances); // adj_avances
			$this->UpdateSort($this->adj_supervicion); // adj_supervicion
			$this->UpdateSort($this->adj_evaluacion); // adj_evaluacion
			$this->UpdateSort($this->adj_tecnica); // adj_tecnica
			$this->UpdateSort($this->adj_financiero); // adj_financiero
			$this->UpdateSort($this->adj_recepcion); // adj_recepcion
			$this->UpdateSort($this->adj_disconformidad); // adj_disconformidad
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->codigo_inicio_ejecucion->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->codigo->setSort("");
				$this->codigo_inicio_ejecucion->setSort("");
				$this->porcent_programado->setSort("");
				$this->porcent_real->setSort("");
				$this->finan_programado->setSort("");
				$this->finan_real->setSort("");
				$this->fecha_registro->setSort("");
				$this->estado->setSort("");
				$this->user_registro->setSort("");
				$this->desc_problemas->setSort("");
				$this->desc_temas->setSort("");
				$this->idEjecucion->setSort("");
				$this->fecha_avance->setSort("");
				$this->idContratacion->setSort("");
				$this->estado_sol->setSort("");
				$this->adj_garantias->setSort("");
				$this->adj_avances->setSort("");
				$this->adj_supervicion->setSort("");
				$this->adj_evaluacion->setSort("");
				$this->adj_tecnica->setSort("");
				$this->adj_financiero->setSort("");
				$this->adj_recepcion->setSort("");
				$this->adj_disconformidad->setSort("");
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

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

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

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->codigo->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fcs_avanceslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fcs_avanceslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fcs_avanceslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fcs_avanceslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->codigo->setDbValue($rs->fields('codigo'));
		$this->codigo_inicio_ejecucion->setDbValue($rs->fields('codigo_inicio_ejecucion'));
		$this->porcent_programado->setDbValue($rs->fields('porcent_programado'));
		$this->porcent_real->setDbValue($rs->fields('porcent_real'));
		$this->finan_programado->setDbValue($rs->fields('finan_programado'));
		$this->finan_real->setDbValue($rs->fields('finan_real'));
		$this->fecha_registro->setDbValue($rs->fields('fecha_registro'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->user_registro->setDbValue($rs->fields('user_registro'));
		$this->desc_problemas->setDbValue($rs->fields('desc_problemas'));
		$this->desc_temas->setDbValue($rs->fields('desc_temas'));
		$this->idEjecucion->setDbValue($rs->fields('idEjecucion'));
		$this->fecha_avance->setDbValue($rs->fields('fecha_avance'));
		$this->idContratacion->setDbValue($rs->fields('idContratacion'));
		$this->estado_sol->setDbValue($rs->fields('estado_sol'));
		$this->adj_garantias->setDbValue($rs->fields('adj_garantias'));
		$this->adj_avances->setDbValue($rs->fields('adj_avances'));
		$this->adj_supervicion->setDbValue($rs->fields('adj_supervicion'));
		$this->adj_evaluacion->setDbValue($rs->fields('adj_evaluacion'));
		$this->adj_tecnica->setDbValue($rs->fields('adj_tecnica'));
		$this->adj_financiero->setDbValue($rs->fields('adj_financiero'));
		$this->adj_recepcion->setDbValue($rs->fields('adj_recepcion'));
		$this->adj_disconformidad->setDbValue($rs->fields('adj_disconformidad'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->codigo->DbValue = $row['codigo'];
		$this->codigo_inicio_ejecucion->DbValue = $row['codigo_inicio_ejecucion'];
		$this->porcent_programado->DbValue = $row['porcent_programado'];
		$this->porcent_real->DbValue = $row['porcent_real'];
		$this->finan_programado->DbValue = $row['finan_programado'];
		$this->finan_real->DbValue = $row['finan_real'];
		$this->fecha_registro->DbValue = $row['fecha_registro'];
		$this->estado->DbValue = $row['estado'];
		$this->user_registro->DbValue = $row['user_registro'];
		$this->desc_problemas->DbValue = $row['desc_problemas'];
		$this->desc_temas->DbValue = $row['desc_temas'];
		$this->idEjecucion->DbValue = $row['idEjecucion'];
		$this->fecha_avance->DbValue = $row['fecha_avance'];
		$this->idContratacion->DbValue = $row['idContratacion'];
		$this->estado_sol->DbValue = $row['estado_sol'];
		$this->adj_garantias->DbValue = $row['adj_garantias'];
		$this->adj_avances->DbValue = $row['adj_avances'];
		$this->adj_supervicion->DbValue = $row['adj_supervicion'];
		$this->adj_evaluacion->DbValue = $row['adj_evaluacion'];
		$this->adj_tecnica->DbValue = $row['adj_tecnica'];
		$this->adj_financiero->DbValue = $row['adj_financiero'];
		$this->adj_recepcion->DbValue = $row['adj_recepcion'];
		$this->adj_disconformidad->DbValue = $row['adj_disconformidad'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("codigo")) <> "")
			$this->codigo->CurrentValue = $this->getKey("codigo"); // codigo
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
		if ($this->porcent_programado->FormValue == $this->porcent_programado->CurrentValue && is_numeric(ew_StrToFloat($this->porcent_programado->CurrentValue)))
			$this->porcent_programado->CurrentValue = ew_StrToFloat($this->porcent_programado->CurrentValue);

		// Convert decimal values if posted back
		if ($this->porcent_real->FormValue == $this->porcent_real->CurrentValue && is_numeric(ew_StrToFloat($this->porcent_real->CurrentValue)))
			$this->porcent_real->CurrentValue = ew_StrToFloat($this->porcent_real->CurrentValue);

		// Convert decimal values if posted back
		if ($this->finan_programado->FormValue == $this->finan_programado->CurrentValue && is_numeric(ew_StrToFloat($this->finan_programado->CurrentValue)))
			$this->finan_programado->CurrentValue = ew_StrToFloat($this->finan_programado->CurrentValue);

		// Convert decimal values if posted back
		if ($this->finan_real->FormValue == $this->finan_real->CurrentValue && is_numeric(ew_StrToFloat($this->finan_real->CurrentValue)))
			$this->finan_real->CurrentValue = ew_StrToFloat($this->finan_real->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// codigo
		// codigo_inicio_ejecucion
		// porcent_programado
		// porcent_real
		// finan_programado
		// finan_real
		// fecha_registro
		// estado
		// user_registro
		// desc_problemas
		// desc_temas
		// idEjecucion
		// fecha_avance
		// idContratacion
		// estado_sol
		// adj_garantias
		// adj_avances
		// adj_supervicion
		// adj_evaluacion
		// adj_tecnica
		// adj_financiero
		// adj_recepcion
		// adj_disconformidad

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// codigo
		$this->codigo->ViewValue = $this->codigo->CurrentValue;
		$this->codigo->ViewCustomAttributes = "";

		// codigo_inicio_ejecucion
		$this->codigo_inicio_ejecucion->ViewValue = $this->codigo_inicio_ejecucion->CurrentValue;
		$this->codigo_inicio_ejecucion->ViewCustomAttributes = "";

		// porcent_programado
		$this->porcent_programado->ViewValue = $this->porcent_programado->CurrentValue;
		$this->porcent_programado->ViewCustomAttributes = "";

		// porcent_real
		$this->porcent_real->ViewValue = $this->porcent_real->CurrentValue;
		$this->porcent_real->ViewCustomAttributes = "";

		// finan_programado
		$this->finan_programado->ViewValue = $this->finan_programado->CurrentValue;
		$this->finan_programado->ViewCustomAttributes = "";

		// finan_real
		$this->finan_real->ViewValue = $this->finan_real->CurrentValue;
		$this->finan_real->ViewCustomAttributes = "";

		// fecha_registro
		$this->fecha_registro->ViewValue = $this->fecha_registro->CurrentValue;
		$this->fecha_registro->ViewValue = ew_FormatDateTime($this->fecha_registro->ViewValue, 5);
		$this->fecha_registro->ViewCustomAttributes = "";

		// estado
		$this->estado->ViewValue = $this->estado->CurrentValue;
		$this->estado->ViewCustomAttributes = "";

		// user_registro
		$this->user_registro->ViewValue = $this->user_registro->CurrentValue;
		$this->user_registro->ViewCustomAttributes = "";

		// desc_problemas
		$this->desc_problemas->ViewValue = $this->desc_problemas->CurrentValue;
		$this->desc_problemas->ViewCustomAttributes = "";

		// desc_temas
		$this->desc_temas->ViewValue = $this->desc_temas->CurrentValue;
		$this->desc_temas->ViewCustomAttributes = "";

		// idEjecucion
		$this->idEjecucion->ViewValue = $this->idEjecucion->CurrentValue;
		$this->idEjecucion->ViewCustomAttributes = "";

		// fecha_avance
		$this->fecha_avance->ViewValue = $this->fecha_avance->CurrentValue;
		$this->fecha_avance->ViewValue = ew_FormatDateTime($this->fecha_avance->ViewValue, 5);
		$this->fecha_avance->ViewCustomAttributes = "";

		// idContratacion
		$this->idContratacion->ViewValue = $this->idContratacion->CurrentValue;
		$this->idContratacion->ViewCustomAttributes = "";

		// estado_sol
		$this->estado_sol->ViewValue = $this->estado_sol->CurrentValue;
		$this->estado_sol->ViewCustomAttributes = "";

		// adj_garantias
		$this->adj_garantias->ViewValue = $this->adj_garantias->CurrentValue;
		$this->adj_garantias->ViewCustomAttributes = "";

		// adj_avances
		$this->adj_avances->ViewValue = $this->adj_avances->CurrentValue;
		$this->adj_avances->ViewCustomAttributes = "";

		// adj_supervicion
		$this->adj_supervicion->ViewValue = $this->adj_supervicion->CurrentValue;
		$this->adj_supervicion->ViewCustomAttributes = "";

		// adj_evaluacion
		$this->adj_evaluacion->ViewValue = $this->adj_evaluacion->CurrentValue;
		$this->adj_evaluacion->ViewCustomAttributes = "";

		// adj_tecnica
		$this->adj_tecnica->ViewValue = $this->adj_tecnica->CurrentValue;
		$this->adj_tecnica->ViewCustomAttributes = "";

		// adj_financiero
		$this->adj_financiero->ViewValue = $this->adj_financiero->CurrentValue;
		$this->adj_financiero->ViewCustomAttributes = "";

		// adj_recepcion
		$this->adj_recepcion->ViewValue = $this->adj_recepcion->CurrentValue;
		$this->adj_recepcion->ViewCustomAttributes = "";

		// adj_disconformidad
		$this->adj_disconformidad->ViewValue = $this->adj_disconformidad->CurrentValue;
		$this->adj_disconformidad->ViewCustomAttributes = "";

			// codigo
			$this->codigo->LinkCustomAttributes = "";
			$this->codigo->HrefValue = "";
			$this->codigo->TooltipValue = "";

			// codigo_inicio_ejecucion
			$this->codigo_inicio_ejecucion->LinkCustomAttributes = "";
			$this->codigo_inicio_ejecucion->HrefValue = "";
			$this->codigo_inicio_ejecucion->TooltipValue = "";

			// porcent_programado
			$this->porcent_programado->LinkCustomAttributes = "";
			$this->porcent_programado->HrefValue = "";
			$this->porcent_programado->TooltipValue = "";

			// porcent_real
			$this->porcent_real->LinkCustomAttributes = "";
			$this->porcent_real->HrefValue = "";
			$this->porcent_real->TooltipValue = "";

			// finan_programado
			$this->finan_programado->LinkCustomAttributes = "";
			$this->finan_programado->HrefValue = "";
			$this->finan_programado->TooltipValue = "";

			// finan_real
			$this->finan_real->LinkCustomAttributes = "";
			$this->finan_real->HrefValue = "";
			$this->finan_real->TooltipValue = "";

			// fecha_registro
			$this->fecha_registro->LinkCustomAttributes = "";
			$this->fecha_registro->HrefValue = "";
			$this->fecha_registro->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// user_registro
			$this->user_registro->LinkCustomAttributes = "";
			$this->user_registro->HrefValue = "";
			$this->user_registro->TooltipValue = "";

			// desc_problemas
			$this->desc_problemas->LinkCustomAttributes = "";
			$this->desc_problemas->HrefValue = "";
			$this->desc_problemas->TooltipValue = "";

			// desc_temas
			$this->desc_temas->LinkCustomAttributes = "";
			$this->desc_temas->HrefValue = "";
			$this->desc_temas->TooltipValue = "";

			// idEjecucion
			$this->idEjecucion->LinkCustomAttributes = "";
			$this->idEjecucion->HrefValue = "";
			$this->idEjecucion->TooltipValue = "";

			// fecha_avance
			$this->fecha_avance->LinkCustomAttributes = "";
			$this->fecha_avance->HrefValue = "";
			$this->fecha_avance->TooltipValue = "";

			// idContratacion
			$this->idContratacion->LinkCustomAttributes = "";
			$this->idContratacion->HrefValue = "";
			$this->idContratacion->TooltipValue = "";

			// estado_sol
			$this->estado_sol->LinkCustomAttributes = "";
			$this->estado_sol->HrefValue = "";
			$this->estado_sol->TooltipValue = "";

			// adj_garantias
			$this->adj_garantias->LinkCustomAttributes = "";
			$this->adj_garantias->HrefValue = "";
			$this->adj_garantias->TooltipValue = "";

			// adj_avances
			$this->adj_avances->LinkCustomAttributes = "";
			$this->adj_avances->HrefValue = "";
			$this->adj_avances->TooltipValue = "";

			// adj_supervicion
			$this->adj_supervicion->LinkCustomAttributes = "";
			$this->adj_supervicion->HrefValue = "";
			$this->adj_supervicion->TooltipValue = "";

			// adj_evaluacion
			$this->adj_evaluacion->LinkCustomAttributes = "";
			$this->adj_evaluacion->HrefValue = "";
			$this->adj_evaluacion->TooltipValue = "";

			// adj_tecnica
			$this->adj_tecnica->LinkCustomAttributes = "";
			$this->adj_tecnica->HrefValue = "";
			$this->adj_tecnica->TooltipValue = "";

			// adj_financiero
			$this->adj_financiero->LinkCustomAttributes = "";
			$this->adj_financiero->HrefValue = "";
			$this->adj_financiero->TooltipValue = "";

			// adj_recepcion
			$this->adj_recepcion->LinkCustomAttributes = "";
			$this->adj_recepcion->HrefValue = "";
			$this->adj_recepcion->TooltipValue = "";

			// adj_disconformidad
			$this->adj_disconformidad->LinkCustomAttributes = "";
			$this->adj_disconformidad->HrefValue = "";
			$this->adj_disconformidad->TooltipValue = "";
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
		$item->Body = "<button id=\"emf_cs_avances\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_cs_avances',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fcs_avanceslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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

		// Export master record
		if (EW_EXPORT_MASTER_RECORD && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "cs_inicio_ejecucion") {
			global $cs_inicio_ejecucion;
			if (!isset($cs_inicio_ejecucion)) $cs_inicio_ejecucion = new ccs_inicio_ejecucion;
			$rsmaster = $cs_inicio_ejecucion->LoadRs($this->DbMasterFilter); // Load master record
			if ($rsmaster && !$rsmaster->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("v"); // Change to vertical
				if ($this->Export <> "csv" || EW_EXPORT_MASTER_RECORD_FOR_CSV) {
					$Doc->Table = &$cs_inicio_ejecucion;
					$cs_inicio_ejecucion->ExportDocument($Doc, $rsmaster, 1, 1);
					$Doc->ExportEmptyRow();
					$Doc->Table = &$this;
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsmaster->Close();
			}
		}
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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "cs_inicio_ejecucion") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idContratacion"] <> "") {
					$GLOBALS["cs_inicio_ejecucion"]->idContratacion->setQueryStringValue($_GET["fk_idContratacion"]);
					$this->codigo_inicio_ejecucion->setQueryStringValue($GLOBALS["cs_inicio_ejecucion"]->idContratacion->QueryStringValue);
					$this->codigo_inicio_ejecucion->setSessionValue($this->codigo_inicio_ejecucion->QueryStringValue);
					if (!is_numeric($GLOBALS["cs_inicio_ejecucion"]->idContratacion->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "cs_inicio_ejecucion") {
				if ($this->codigo_inicio_ejecucion->QueryStringValue == "") $this->codigo_inicio_ejecucion->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
if (!isset($cs_avances_list)) $cs_avances_list = new ccs_avances_list();

// Page init
$cs_avances_list->Page_Init();

// Page main
$cs_avances_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cs_avances_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($cs_avances->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fcs_avanceslist = new ew_Form("fcs_avanceslist", "list");
fcs_avanceslist.FormKeyCountName = '<?php echo $cs_avances_list->FormKeyCountName ?>';

// Form_CustomValidate event
fcs_avanceslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcs_avanceslist.ValidateRequired = true;
<?php } else { ?>
fcs_avanceslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var CurrentSearchForm = fcs_avanceslistsrch = new ew_Form("fcs_avanceslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($cs_avances->Export == "") { ?>
<div class="ewToolbar">
<?php if ($cs_avances->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($cs_avances_list->TotalRecs > 0 && $cs_avances_list->ExportOptions->Visible()) { ?>
<?php $cs_avances_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($cs_avances_list->SearchOptions->Visible()) { ?>
<?php $cs_avances_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($cs_avances_list->FilterOptions->Visible()) { ?>
<?php $cs_avances_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($cs_avances->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (($cs_avances->Export == "") || (EW_EXPORT_MASTER_RECORD && $cs_avances->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "cs_inicio_ejecucionlist.php";
if ($cs_avances_list->DbMasterFilter <> "" && $cs_avances->getCurrentMasterTable() == "cs_inicio_ejecucion") {
	if ($cs_avances_list->MasterRecordExists) {
		if ($cs_avances->getCurrentMasterTable() == $cs_avances->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<?php include_once "cs_inicio_ejecucionmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = $cs_avances_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($cs_avances_list->TotalRecs <= 0)
			$cs_avances_list->TotalRecs = $cs_avances->SelectRecordCount();
	} else {
		if (!$cs_avances_list->Recordset && ($cs_avances_list->Recordset = $cs_avances_list->LoadRecordset()))
			$cs_avances_list->TotalRecs = $cs_avances_list->Recordset->RecordCount();
	}
	$cs_avances_list->StartRec = 1;
	if ($cs_avances_list->DisplayRecs <= 0 || ($cs_avances->Export <> "" && $cs_avances->ExportAll)) // Display all records
		$cs_avances_list->DisplayRecs = $cs_avances_list->TotalRecs;
	if (!($cs_avances->Export <> "" && $cs_avances->ExportAll))
		$cs_avances_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$cs_avances_list->Recordset = $cs_avances_list->LoadRecordset($cs_avances_list->StartRec-1, $cs_avances_list->DisplayRecs);

	// Set no record found message
	if ($cs_avances->CurrentAction == "" && $cs_avances_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$cs_avances_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($cs_avances_list->SearchWhere == "0=101")
			$cs_avances_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$cs_avances_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$cs_avances_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($cs_avances->Export == "" && $cs_avances->CurrentAction == "") { ?>
<form name="fcs_avanceslistsrch" id="fcs_avanceslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($cs_avances_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fcs_avanceslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="cs_avances">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($cs_avances_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($cs_avances_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $cs_avances_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($cs_avances_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($cs_avances_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($cs_avances_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($cs_avances_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $cs_avances_list->ShowPageHeader(); ?>
<?php
$cs_avances_list->ShowMessage();
?>
<?php if ($cs_avances_list->TotalRecs > 0 || $cs_avances->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<form name="fcs_avanceslist" id="fcs_avanceslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cs_avances_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cs_avances_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cs_avances">
<div id="gmp_cs_avances" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($cs_avances_list->TotalRecs > 0) { ?>
<table id="tbl_cs_avanceslist" class="table ewTable">
<?php echo $cs_avances->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$cs_avances_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$cs_avances_list->RenderListOptions();

// Render list options (header, left)
$cs_avances_list->ListOptions->Render("header", "left");
?>
<?php if ($cs_avances->codigo->Visible) { // codigo ?>
	<?php if ($cs_avances->SortUrl($cs_avances->codigo) == "") { ?>
		<th data-name="codigo"><div id="elh_cs_avances_codigo" class="cs_avances_codigo"><div class="ewTableHeaderCaption"><?php echo $cs_avances->codigo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->codigo) ?>',1);"><div id="elh_cs_avances_codigo" class="cs_avances_codigo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->codigo->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->codigo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->codigo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->codigo_inicio_ejecucion->Visible) { // codigo_inicio_ejecucion ?>
	<?php if ($cs_avances->SortUrl($cs_avances->codigo_inicio_ejecucion) == "") { ?>
		<th data-name="codigo_inicio_ejecucion"><div id="elh_cs_avances_codigo_inicio_ejecucion" class="cs_avances_codigo_inicio_ejecucion"><div class="ewTableHeaderCaption"><?php echo $cs_avances->codigo_inicio_ejecucion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="codigo_inicio_ejecucion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->codigo_inicio_ejecucion) ?>',1);"><div id="elh_cs_avances_codigo_inicio_ejecucion" class="cs_avances_codigo_inicio_ejecucion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->codigo_inicio_ejecucion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->codigo_inicio_ejecucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->codigo_inicio_ejecucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->porcent_programado->Visible) { // porcent_programado ?>
	<?php if ($cs_avances->SortUrl($cs_avances->porcent_programado) == "") { ?>
		<th data-name="porcent_programado"><div id="elh_cs_avances_porcent_programado" class="cs_avances_porcent_programado"><div class="ewTableHeaderCaption"><?php echo $cs_avances->porcent_programado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="porcent_programado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->porcent_programado) ?>',1);"><div id="elh_cs_avances_porcent_programado" class="cs_avances_porcent_programado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->porcent_programado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->porcent_programado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->porcent_programado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->porcent_real->Visible) { // porcent_real ?>
	<?php if ($cs_avances->SortUrl($cs_avances->porcent_real) == "") { ?>
		<th data-name="porcent_real"><div id="elh_cs_avances_porcent_real" class="cs_avances_porcent_real"><div class="ewTableHeaderCaption"><?php echo $cs_avances->porcent_real->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="porcent_real"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->porcent_real) ?>',1);"><div id="elh_cs_avances_porcent_real" class="cs_avances_porcent_real">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->porcent_real->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->porcent_real->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->porcent_real->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->finan_programado->Visible) { // finan_programado ?>
	<?php if ($cs_avances->SortUrl($cs_avances->finan_programado) == "") { ?>
		<th data-name="finan_programado"><div id="elh_cs_avances_finan_programado" class="cs_avances_finan_programado"><div class="ewTableHeaderCaption"><?php echo $cs_avances->finan_programado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="finan_programado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->finan_programado) ?>',1);"><div id="elh_cs_avances_finan_programado" class="cs_avances_finan_programado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->finan_programado->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->finan_programado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->finan_programado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->finan_real->Visible) { // finan_real ?>
	<?php if ($cs_avances->SortUrl($cs_avances->finan_real) == "") { ?>
		<th data-name="finan_real"><div id="elh_cs_avances_finan_real" class="cs_avances_finan_real"><div class="ewTableHeaderCaption"><?php echo $cs_avances->finan_real->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="finan_real"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->finan_real) ?>',1);"><div id="elh_cs_avances_finan_real" class="cs_avances_finan_real">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->finan_real->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->finan_real->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->finan_real->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->fecha_registro->Visible) { // fecha_registro ?>
	<?php if ($cs_avances->SortUrl($cs_avances->fecha_registro) == "") { ?>
		<th data-name="fecha_registro"><div id="elh_cs_avances_fecha_registro" class="cs_avances_fecha_registro"><div class="ewTableHeaderCaption"><?php echo $cs_avances->fecha_registro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_registro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->fecha_registro) ?>',1);"><div id="elh_cs_avances_fecha_registro" class="cs_avances_fecha_registro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->fecha_registro->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->fecha_registro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->fecha_registro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->estado->Visible) { // estado ?>
	<?php if ($cs_avances->SortUrl($cs_avances->estado) == "") { ?>
		<th data-name="estado"><div id="elh_cs_avances_estado" class="cs_avances_estado"><div class="ewTableHeaderCaption"><?php echo $cs_avances->estado->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->estado) ?>',1);"><div id="elh_cs_avances_estado" class="cs_avances_estado">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->estado->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->estado->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->estado->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->user_registro->Visible) { // user_registro ?>
	<?php if ($cs_avances->SortUrl($cs_avances->user_registro) == "") { ?>
		<th data-name="user_registro"><div id="elh_cs_avances_user_registro" class="cs_avances_user_registro"><div class="ewTableHeaderCaption"><?php echo $cs_avances->user_registro->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="user_registro"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->user_registro) ?>',1);"><div id="elh_cs_avances_user_registro" class="cs_avances_user_registro">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->user_registro->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->user_registro->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->user_registro->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->desc_problemas->Visible) { // desc_problemas ?>
	<?php if ($cs_avances->SortUrl($cs_avances->desc_problemas) == "") { ?>
		<th data-name="desc_problemas"><div id="elh_cs_avances_desc_problemas" class="cs_avances_desc_problemas"><div class="ewTableHeaderCaption"><?php echo $cs_avances->desc_problemas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="desc_problemas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->desc_problemas) ?>',1);"><div id="elh_cs_avances_desc_problemas" class="cs_avances_desc_problemas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->desc_problemas->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->desc_problemas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->desc_problemas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->desc_temas->Visible) { // desc_temas ?>
	<?php if ($cs_avances->SortUrl($cs_avances->desc_temas) == "") { ?>
		<th data-name="desc_temas"><div id="elh_cs_avances_desc_temas" class="cs_avances_desc_temas"><div class="ewTableHeaderCaption"><?php echo $cs_avances->desc_temas->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="desc_temas"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->desc_temas) ?>',1);"><div id="elh_cs_avances_desc_temas" class="cs_avances_desc_temas">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->desc_temas->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->desc_temas->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->desc_temas->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->idEjecucion->Visible) { // idEjecucion ?>
	<?php if ($cs_avances->SortUrl($cs_avances->idEjecucion) == "") { ?>
		<th data-name="idEjecucion"><div id="elh_cs_avances_idEjecucion" class="cs_avances_idEjecucion"><div class="ewTableHeaderCaption"><?php echo $cs_avances->idEjecucion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idEjecucion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->idEjecucion) ?>',1);"><div id="elh_cs_avances_idEjecucion" class="cs_avances_idEjecucion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->idEjecucion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->idEjecucion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->idEjecucion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->fecha_avance->Visible) { // fecha_avance ?>
	<?php if ($cs_avances->SortUrl($cs_avances->fecha_avance) == "") { ?>
		<th data-name="fecha_avance"><div id="elh_cs_avances_fecha_avance" class="cs_avances_fecha_avance"><div class="ewTableHeaderCaption"><?php echo $cs_avances->fecha_avance->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="fecha_avance"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->fecha_avance) ?>',1);"><div id="elh_cs_avances_fecha_avance" class="cs_avances_fecha_avance">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->fecha_avance->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->fecha_avance->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->fecha_avance->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->idContratacion->Visible) { // idContratacion ?>
	<?php if ($cs_avances->SortUrl($cs_avances->idContratacion) == "") { ?>
		<th data-name="idContratacion"><div id="elh_cs_avances_idContratacion" class="cs_avances_idContratacion"><div class="ewTableHeaderCaption"><?php echo $cs_avances->idContratacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="idContratacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->idContratacion) ?>',1);"><div id="elh_cs_avances_idContratacion" class="cs_avances_idContratacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->idContratacion->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->idContratacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->idContratacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->estado_sol->Visible) { // estado_sol ?>
	<?php if ($cs_avances->SortUrl($cs_avances->estado_sol) == "") { ?>
		<th data-name="estado_sol"><div id="elh_cs_avances_estado_sol" class="cs_avances_estado_sol"><div class="ewTableHeaderCaption"><?php echo $cs_avances->estado_sol->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="estado_sol"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->estado_sol) ?>',1);"><div id="elh_cs_avances_estado_sol" class="cs_avances_estado_sol">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->estado_sol->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->estado_sol->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->estado_sol->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->adj_garantias->Visible) { // adj_garantias ?>
	<?php if ($cs_avances->SortUrl($cs_avances->adj_garantias) == "") { ?>
		<th data-name="adj_garantias"><div id="elh_cs_avances_adj_garantias" class="cs_avances_adj_garantias"><div class="ewTableHeaderCaption"><?php echo $cs_avances->adj_garantias->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="adj_garantias"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->adj_garantias) ?>',1);"><div id="elh_cs_avances_adj_garantias" class="cs_avances_adj_garantias">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->adj_garantias->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->adj_garantias->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->adj_garantias->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->adj_avances->Visible) { // adj_avances ?>
	<?php if ($cs_avances->SortUrl($cs_avances->adj_avances) == "") { ?>
		<th data-name="adj_avances"><div id="elh_cs_avances_adj_avances" class="cs_avances_adj_avances"><div class="ewTableHeaderCaption"><?php echo $cs_avances->adj_avances->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="adj_avances"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->adj_avances) ?>',1);"><div id="elh_cs_avances_adj_avances" class="cs_avances_adj_avances">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->adj_avances->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->adj_avances->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->adj_avances->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->adj_supervicion->Visible) { // adj_supervicion ?>
	<?php if ($cs_avances->SortUrl($cs_avances->adj_supervicion) == "") { ?>
		<th data-name="adj_supervicion"><div id="elh_cs_avances_adj_supervicion" class="cs_avances_adj_supervicion"><div class="ewTableHeaderCaption"><?php echo $cs_avances->adj_supervicion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="adj_supervicion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->adj_supervicion) ?>',1);"><div id="elh_cs_avances_adj_supervicion" class="cs_avances_adj_supervicion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->adj_supervicion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->adj_supervicion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->adj_supervicion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->adj_evaluacion->Visible) { // adj_evaluacion ?>
	<?php if ($cs_avances->SortUrl($cs_avances->adj_evaluacion) == "") { ?>
		<th data-name="adj_evaluacion"><div id="elh_cs_avances_adj_evaluacion" class="cs_avances_adj_evaluacion"><div class="ewTableHeaderCaption"><?php echo $cs_avances->adj_evaluacion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="adj_evaluacion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->adj_evaluacion) ?>',1);"><div id="elh_cs_avances_adj_evaluacion" class="cs_avances_adj_evaluacion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->adj_evaluacion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->adj_evaluacion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->adj_evaluacion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->adj_tecnica->Visible) { // adj_tecnica ?>
	<?php if ($cs_avances->SortUrl($cs_avances->adj_tecnica) == "") { ?>
		<th data-name="adj_tecnica"><div id="elh_cs_avances_adj_tecnica" class="cs_avances_adj_tecnica"><div class="ewTableHeaderCaption"><?php echo $cs_avances->adj_tecnica->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="adj_tecnica"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->adj_tecnica) ?>',1);"><div id="elh_cs_avances_adj_tecnica" class="cs_avances_adj_tecnica">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->adj_tecnica->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->adj_tecnica->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->adj_tecnica->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->adj_financiero->Visible) { // adj_financiero ?>
	<?php if ($cs_avances->SortUrl($cs_avances->adj_financiero) == "") { ?>
		<th data-name="adj_financiero"><div id="elh_cs_avances_adj_financiero" class="cs_avances_adj_financiero"><div class="ewTableHeaderCaption"><?php echo $cs_avances->adj_financiero->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="adj_financiero"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->adj_financiero) ?>',1);"><div id="elh_cs_avances_adj_financiero" class="cs_avances_adj_financiero">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->adj_financiero->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->adj_financiero->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->adj_financiero->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->adj_recepcion->Visible) { // adj_recepcion ?>
	<?php if ($cs_avances->SortUrl($cs_avances->adj_recepcion) == "") { ?>
		<th data-name="adj_recepcion"><div id="elh_cs_avances_adj_recepcion" class="cs_avances_adj_recepcion"><div class="ewTableHeaderCaption"><?php echo $cs_avances->adj_recepcion->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="adj_recepcion"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->adj_recepcion) ?>',1);"><div id="elh_cs_avances_adj_recepcion" class="cs_avances_adj_recepcion">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->adj_recepcion->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->adj_recepcion->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->adj_recepcion->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($cs_avances->adj_disconformidad->Visible) { // adj_disconformidad ?>
	<?php if ($cs_avances->SortUrl($cs_avances->adj_disconformidad) == "") { ?>
		<th data-name="adj_disconformidad"><div id="elh_cs_avances_adj_disconformidad" class="cs_avances_adj_disconformidad"><div class="ewTableHeaderCaption"><?php echo $cs_avances->adj_disconformidad->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="adj_disconformidad"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $cs_avances->SortUrl($cs_avances->adj_disconformidad) ?>',1);"><div id="elh_cs_avances_adj_disconformidad" class="cs_avances_adj_disconformidad">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $cs_avances->adj_disconformidad->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($cs_avances->adj_disconformidad->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($cs_avances->adj_disconformidad->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$cs_avances_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($cs_avances->ExportAll && $cs_avances->Export <> "") {
	$cs_avances_list->StopRec = $cs_avances_list->TotalRecs;
} else {

	// Set the last record to display
	if ($cs_avances_list->TotalRecs > $cs_avances_list->StartRec + $cs_avances_list->DisplayRecs - 1)
		$cs_avances_list->StopRec = $cs_avances_list->StartRec + $cs_avances_list->DisplayRecs - 1;
	else
		$cs_avances_list->StopRec = $cs_avances_list->TotalRecs;
}
$cs_avances_list->RecCnt = $cs_avances_list->StartRec - 1;
if ($cs_avances_list->Recordset && !$cs_avances_list->Recordset->EOF) {
	$cs_avances_list->Recordset->MoveFirst();
	$bSelectLimit = $cs_avances_list->UseSelectLimit;
	if (!$bSelectLimit && $cs_avances_list->StartRec > 1)
		$cs_avances_list->Recordset->Move($cs_avances_list->StartRec - 1);
} elseif (!$cs_avances->AllowAddDeleteRow && $cs_avances_list->StopRec == 0) {
	$cs_avances_list->StopRec = $cs_avances->GridAddRowCount;
}

// Initialize aggregate
$cs_avances->RowType = EW_ROWTYPE_AGGREGATEINIT;
$cs_avances->ResetAttrs();
$cs_avances_list->RenderRow();
while ($cs_avances_list->RecCnt < $cs_avances_list->StopRec) {
	$cs_avances_list->RecCnt++;
	if (intval($cs_avances_list->RecCnt) >= intval($cs_avances_list->StartRec)) {
		$cs_avances_list->RowCnt++;

		// Set up key count
		$cs_avances_list->KeyCount = $cs_avances_list->RowIndex;

		// Init row class and style
		$cs_avances->ResetAttrs();
		$cs_avances->CssClass = "";
		if ($cs_avances->CurrentAction == "gridadd") {
		} else {
			$cs_avances_list->LoadRowValues($cs_avances_list->Recordset); // Load row values
		}
		$cs_avances->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$cs_avances->RowAttrs = array_merge($cs_avances->RowAttrs, array('data-rowindex'=>$cs_avances_list->RowCnt, 'id'=>'r' . $cs_avances_list->RowCnt . '_cs_avances', 'data-rowtype'=>$cs_avances->RowType));

		// Render row
		$cs_avances_list->RenderRow();

		// Render list options
		$cs_avances_list->RenderListOptions();
?>
	<tr<?php echo $cs_avances->RowAttributes() ?>>
<?php

// Render list options (body, left)
$cs_avances_list->ListOptions->Render("body", "left", $cs_avances_list->RowCnt);
?>
	<?php if ($cs_avances->codigo->Visible) { // codigo ?>
		<td data-name="codigo"<?php echo $cs_avances->codigo->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_codigo" class="cs_avances_codigo">
<span<?php echo $cs_avances->codigo->ViewAttributes() ?>>
<?php echo $cs_avances->codigo->ListViewValue() ?></span>
</span>
<a id="<?php echo $cs_avances_list->PageObjName . "_row_" . $cs_avances_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($cs_avances->codigo_inicio_ejecucion->Visible) { // codigo_inicio_ejecucion ?>
		<td data-name="codigo_inicio_ejecucion"<?php echo $cs_avances->codigo_inicio_ejecucion->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_codigo_inicio_ejecucion" class="cs_avances_codigo_inicio_ejecucion">
<span<?php echo $cs_avances->codigo_inicio_ejecucion->ViewAttributes() ?>>
<?php echo $cs_avances->codigo_inicio_ejecucion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->porcent_programado->Visible) { // porcent_programado ?>
		<td data-name="porcent_programado"<?php echo $cs_avances->porcent_programado->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_porcent_programado" class="cs_avances_porcent_programado">
<span<?php echo $cs_avances->porcent_programado->ViewAttributes() ?>>
<?php echo $cs_avances->porcent_programado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->porcent_real->Visible) { // porcent_real ?>
		<td data-name="porcent_real"<?php echo $cs_avances->porcent_real->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_porcent_real" class="cs_avances_porcent_real">
<span<?php echo $cs_avances->porcent_real->ViewAttributes() ?>>
<?php echo $cs_avances->porcent_real->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->finan_programado->Visible) { // finan_programado ?>
		<td data-name="finan_programado"<?php echo $cs_avances->finan_programado->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_finan_programado" class="cs_avances_finan_programado">
<span<?php echo $cs_avances->finan_programado->ViewAttributes() ?>>
<?php echo $cs_avances->finan_programado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->finan_real->Visible) { // finan_real ?>
		<td data-name="finan_real"<?php echo $cs_avances->finan_real->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_finan_real" class="cs_avances_finan_real">
<span<?php echo $cs_avances->finan_real->ViewAttributes() ?>>
<?php echo $cs_avances->finan_real->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->fecha_registro->Visible) { // fecha_registro ?>
		<td data-name="fecha_registro"<?php echo $cs_avances->fecha_registro->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_fecha_registro" class="cs_avances_fecha_registro">
<span<?php echo $cs_avances->fecha_registro->ViewAttributes() ?>>
<?php echo $cs_avances->fecha_registro->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->estado->Visible) { // estado ?>
		<td data-name="estado"<?php echo $cs_avances->estado->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_estado" class="cs_avances_estado">
<span<?php echo $cs_avances->estado->ViewAttributes() ?>>
<?php echo $cs_avances->estado->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->user_registro->Visible) { // user_registro ?>
		<td data-name="user_registro"<?php echo $cs_avances->user_registro->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_user_registro" class="cs_avances_user_registro">
<span<?php echo $cs_avances->user_registro->ViewAttributes() ?>>
<?php echo $cs_avances->user_registro->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->desc_problemas->Visible) { // desc_problemas ?>
		<td data-name="desc_problemas"<?php echo $cs_avances->desc_problemas->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_desc_problemas" class="cs_avances_desc_problemas">
<span<?php echo $cs_avances->desc_problemas->ViewAttributes() ?>>
<?php echo $cs_avances->desc_problemas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->desc_temas->Visible) { // desc_temas ?>
		<td data-name="desc_temas"<?php echo $cs_avances->desc_temas->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_desc_temas" class="cs_avances_desc_temas">
<span<?php echo $cs_avances->desc_temas->ViewAttributes() ?>>
<?php echo $cs_avances->desc_temas->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->idEjecucion->Visible) { // idEjecucion ?>
		<td data-name="idEjecucion"<?php echo $cs_avances->idEjecucion->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_idEjecucion" class="cs_avances_idEjecucion">
<span<?php echo $cs_avances->idEjecucion->ViewAttributes() ?>>
<?php echo $cs_avances->idEjecucion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->fecha_avance->Visible) { // fecha_avance ?>
		<td data-name="fecha_avance"<?php echo $cs_avances->fecha_avance->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_fecha_avance" class="cs_avances_fecha_avance">
<span<?php echo $cs_avances->fecha_avance->ViewAttributes() ?>>
<?php echo $cs_avances->fecha_avance->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->idContratacion->Visible) { // idContratacion ?>
		<td data-name="idContratacion"<?php echo $cs_avances->idContratacion->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_idContratacion" class="cs_avances_idContratacion">
<span<?php echo $cs_avances->idContratacion->ViewAttributes() ?>>
<?php echo $cs_avances->idContratacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->estado_sol->Visible) { // estado_sol ?>
		<td data-name="estado_sol"<?php echo $cs_avances->estado_sol->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_estado_sol" class="cs_avances_estado_sol">
<span<?php echo $cs_avances->estado_sol->ViewAttributes() ?>>
<?php echo $cs_avances->estado_sol->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->adj_garantias->Visible) { // adj_garantias ?>
		<td data-name="adj_garantias"<?php echo $cs_avances->adj_garantias->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_adj_garantias" class="cs_avances_adj_garantias">
<span<?php echo $cs_avances->adj_garantias->ViewAttributes() ?>>
<?php echo $cs_avances->adj_garantias->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->adj_avances->Visible) { // adj_avances ?>
		<td data-name="adj_avances"<?php echo $cs_avances->adj_avances->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_adj_avances" class="cs_avances_adj_avances">
<span<?php echo $cs_avances->adj_avances->ViewAttributes() ?>>
<?php echo $cs_avances->adj_avances->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->adj_supervicion->Visible) { // adj_supervicion ?>
		<td data-name="adj_supervicion"<?php echo $cs_avances->adj_supervicion->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_adj_supervicion" class="cs_avances_adj_supervicion">
<span<?php echo $cs_avances->adj_supervicion->ViewAttributes() ?>>
<?php echo $cs_avances->adj_supervicion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->adj_evaluacion->Visible) { // adj_evaluacion ?>
		<td data-name="adj_evaluacion"<?php echo $cs_avances->adj_evaluacion->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_adj_evaluacion" class="cs_avances_adj_evaluacion">
<span<?php echo $cs_avances->adj_evaluacion->ViewAttributes() ?>>
<?php echo $cs_avances->adj_evaluacion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->adj_tecnica->Visible) { // adj_tecnica ?>
		<td data-name="adj_tecnica"<?php echo $cs_avances->adj_tecnica->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_adj_tecnica" class="cs_avances_adj_tecnica">
<span<?php echo $cs_avances->adj_tecnica->ViewAttributes() ?>>
<?php echo $cs_avances->adj_tecnica->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->adj_financiero->Visible) { // adj_financiero ?>
		<td data-name="adj_financiero"<?php echo $cs_avances->adj_financiero->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_adj_financiero" class="cs_avances_adj_financiero">
<span<?php echo $cs_avances->adj_financiero->ViewAttributes() ?>>
<?php echo $cs_avances->adj_financiero->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->adj_recepcion->Visible) { // adj_recepcion ?>
		<td data-name="adj_recepcion"<?php echo $cs_avances->adj_recepcion->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_adj_recepcion" class="cs_avances_adj_recepcion">
<span<?php echo $cs_avances->adj_recepcion->ViewAttributes() ?>>
<?php echo $cs_avances->adj_recepcion->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($cs_avances->adj_disconformidad->Visible) { // adj_disconformidad ?>
		<td data-name="adj_disconformidad"<?php echo $cs_avances->adj_disconformidad->CellAttributes() ?>>
<span id="el<?php echo $cs_avances_list->RowCnt ?>_cs_avances_adj_disconformidad" class="cs_avances_adj_disconformidad">
<span<?php echo $cs_avances->adj_disconformidad->ViewAttributes() ?>>
<?php echo $cs_avances->adj_disconformidad->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$cs_avances_list->ListOptions->Render("body", "right", $cs_avances_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($cs_avances->CurrentAction <> "gridadd")
		$cs_avances_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($cs_avances->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($cs_avances_list->Recordset)
	$cs_avances_list->Recordset->Close();
?>
<?php if ($cs_avances->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($cs_avances->CurrentAction <> "gridadd" && $cs_avances->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($cs_avances_list->Pager)) $cs_avances_list->Pager = new cPrevNextPager($cs_avances_list->StartRec, $cs_avances_list->DisplayRecs, $cs_avances_list->TotalRecs) ?>
<?php if ($cs_avances_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($cs_avances_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $cs_avances_list->PageUrl() ?>start=<?php echo $cs_avances_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($cs_avances_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $cs_avances_list->PageUrl() ?>start=<?php echo $cs_avances_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $cs_avances_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($cs_avances_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $cs_avances_list->PageUrl() ?>start=<?php echo $cs_avances_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($cs_avances_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $cs_avances_list->PageUrl() ?>start=<?php echo $cs_avances_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $cs_avances_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $cs_avances_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $cs_avances_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $cs_avances_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($cs_avances_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($cs_avances_list->TotalRecs == 0 && $cs_avances->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($cs_avances_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($cs_avances->Export == "") { ?>
<script type="text/javascript">
fcs_avanceslistsrch.Init();
fcs_avanceslistsrch.FilterList = <?php echo $cs_avances_list->GetFilterList() ?>;
fcs_avanceslist.Init();
</script>
<?php } ?>
<?php
$cs_avances_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($cs_avances->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$cs_avances_list->Page_Terminate();
?>
