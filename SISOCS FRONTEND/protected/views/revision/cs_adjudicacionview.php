<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "cs_adjudicacioninfo.php" ?>
<?php include_once "cruge_userinfo.php" ?>
<?php include_once "cs_calificacioninfo.php" ?>
<?php include_once "cs_contrataciongridcls.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$cs_adjudicacion_view = NULL; // Initialize page object first

class ccs_adjudicacion_view extends ccs_adjudicacion {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{95DDD5E1-EED3-4F75-9459-65662A38CD3B}";

	// Table name
	var $TableName = 'cs_adjudicacion';

	// Page object name
	var $PageObjName = 'cs_adjudicacion_view';

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

		// Table object (cs_adjudicacion)
		if (!isset($GLOBALS["cs_adjudicacion"]) || get_class($GLOBALS["cs_adjudicacion"]) == "ccs_adjudicacion") {
			$GLOBALS["cs_adjudicacion"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["cs_adjudicacion"];
		}
		$KeyUrl = "";
		if (@$_GET["idAdjudicacion"] <> "") {
			$this->RecKey["idAdjudicacion"] = $_GET["idAdjudicacion"];
			$KeyUrl .= "&amp;idAdjudicacion=" . urlencode($this->RecKey["idAdjudicacion"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (cruge_user)
		if (!isset($GLOBALS['cruge_user'])) $GLOBALS['cruge_user'] = new ccruge_user();

		// Table object (cs_calificacion)
		if (!isset($GLOBALS['cs_calificacion'])) $GLOBALS['cs_calificacion'] = new ccs_calificacion();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'cs_adjudicacion', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (cruge_user)
		if (!isset($UserTable)) {
			$UserTable = new ccruge_user();
			$UserTableConn = Conn($UserTable->DBID);
		}

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

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) $this->Page_Terminate(ew_GetUrl("login.php"));
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->idAdjudicacion->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		global $EW_EXPORT, $cs_adjudicacion;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($cs_adjudicacion);
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
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["idAdjudicacion"] <> "") {
				$this->idAdjudicacion->setQueryStringValue($_GET["idAdjudicacion"]);
				$this->RecKey["idAdjudicacion"] = $this->idAdjudicacion->QueryStringValue;
			} elseif (@$_POST["idAdjudicacion"] <> "") {
				$this->idAdjudicacion->setFormValue($_POST["idAdjudicacion"]);
				$this->RecKey["idAdjudicacion"] = $this->idAdjudicacion->FormValue;
			} else {
				$sReturnUrl = "cs_adjudicacionlist.php"; // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "cs_adjudicacionlist.php"; // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = "cs_adjudicacionlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_cs_contratacion"
		$item = &$option->Add("detail_cs_contratacion");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("cs_contratacion", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("cs_contratacionlist.php?" . EW_TABLE_SHOW_MASTER . "=cs_adjudicacion&fk_idAdjudicacion=" . urlencode(strval($this->idAdjudicacion->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["cs_contratacion_grid"] && $GLOBALS["cs_contratacion_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'cs_contratacion')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=cs_contratacion")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "cs_contratacion";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'cs_contratacion');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "cs_contratacion";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

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
		$this->idAdjudicacion->setDbValue($rs->fields('idAdjudicacion'));
		$this->idCalificacion->setDbValue($rs->fields('idCalificacion'));
		$this->numproceso->setDbValue($rs->fields('numproceso'));
		$this->nomprocesoproyecto->setDbValue($rs->fields('nomprocesoproyecto'));
		$this->nconsulnac->setDbValue($rs->fields('nconsulnac'));
		$this->nconsulinter->setDbValue($rs->fields('nconsulinter'));
		$this->costoesti->setDbValue($rs->fields('costoesti'));
		$this->estadoproceso->setDbValue($rs->fields('estadoproceso'));
		$this->actaaper->setDbValue($rs->fields('actaaper'));
		$this->informeacta->setDbValue($rs->fields('informeacta'));
		$this->resoladju->setDbValue($rs->fields('resoladju'));
		$this->estado->setDbValue($rs->fields('estado'));
		$this->otro->setDbValue($rs->fields('otro'));
		$this->numfirmasnac->setDbValue($rs->fields('numfirmasnac'));
		$this->numfimasinter->setDbValue($rs->fields('numfimasinter'));
		$this->numconsulcorta->setDbValue($rs->fields('numconsulcorta'));
		$this->fecharecibido->setDbValue($rs->fields('fecharecibido'));
		$this->fechacreacion->setDbValue($rs->fields('fechacreacion'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->idAdjudicacion->DbValue = $row['idAdjudicacion'];
		$this->idCalificacion->DbValue = $row['idCalificacion'];
		$this->numproceso->DbValue = $row['numproceso'];
		$this->nomprocesoproyecto->DbValue = $row['nomprocesoproyecto'];
		$this->nconsulnac->DbValue = $row['nconsulnac'];
		$this->nconsulinter->DbValue = $row['nconsulinter'];
		$this->costoesti->DbValue = $row['costoesti'];
		$this->estadoproceso->DbValue = $row['estadoproceso'];
		$this->actaaper->DbValue = $row['actaaper'];
		$this->informeacta->DbValue = $row['informeacta'];
		$this->resoladju->DbValue = $row['resoladju'];
		$this->estado->DbValue = $row['estado'];
		$this->otro->DbValue = $row['otro'];
		$this->numfirmasnac->DbValue = $row['numfirmasnac'];
		$this->numfimasinter->DbValue = $row['numfimasinter'];
		$this->numconsulcorta->DbValue = $row['numconsulcorta'];
		$this->fecharecibido->DbValue = $row['fecharecibido'];
		$this->fechacreacion->DbValue = $row['fechacreacion'];
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
		if ($this->costoesti->FormValue == $this->costoesti->CurrentValue && is_numeric(ew_StrToFloat($this->costoesti->CurrentValue)))
			$this->costoesti->CurrentValue = ew_StrToFloat($this->costoesti->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// idAdjudicacion
		// idCalificacion
		// numproceso
		// nomprocesoproyecto
		// nconsulnac
		// nconsulinter
		// costoesti
		// estadoproceso
		// actaaper
		// informeacta
		// resoladju
		// estado
		// otro
		// numfirmasnac
		// numfimasinter
		// numconsulcorta
		// fecharecibido
		// fechacreacion

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// idAdjudicacion
		$this->idAdjudicacion->ViewValue = $this->idAdjudicacion->CurrentValue;
		$this->idAdjudicacion->ViewCustomAttributes = "";

		// idCalificacion
		$this->idCalificacion->ViewValue = $this->idCalificacion->CurrentValue;
		$this->idCalificacion->ViewCustomAttributes = "";

		// numproceso
		$this->numproceso->ViewValue = $this->numproceso->CurrentValue;
		$this->numproceso->ViewCustomAttributes = "";

		// nomprocesoproyecto
		$this->nomprocesoproyecto->ViewValue = $this->nomprocesoproyecto->CurrentValue;
		$this->nomprocesoproyecto->ViewCustomAttributes = "";

		// nconsulnac
		$this->nconsulnac->ViewValue = $this->nconsulnac->CurrentValue;
		$this->nconsulnac->ViewCustomAttributes = "";

		// nconsulinter
		$this->nconsulinter->ViewValue = $this->nconsulinter->CurrentValue;
		$this->nconsulinter->ViewCustomAttributes = "";

		// costoesti
		$this->costoesti->ViewValue = $this->costoesti->CurrentValue;
		$this->costoesti->ViewCustomAttributes = "";

		// estadoproceso
		$this->estadoproceso->ViewValue = $this->estadoproceso->CurrentValue;
		$this->estadoproceso->ViewCustomAttributes = "";

		// actaaper
		$this->actaaper->ViewValue = $this->actaaper->CurrentValue;
		$this->actaaper->ViewCustomAttributes = "";

		// informeacta
		$this->informeacta->ViewValue = $this->informeacta->CurrentValue;
		$this->informeacta->ViewCustomAttributes = "";

		// resoladju
		$this->resoladju->ViewValue = $this->resoladju->CurrentValue;
		$this->resoladju->ViewCustomAttributes = "";

		// estado
		$this->estado->ViewValue = $this->estado->CurrentValue;
		$this->estado->ViewCustomAttributes = "";

		// otro
		$this->otro->ViewValue = $this->otro->CurrentValue;
		$this->otro->ViewCustomAttributes = "";

		// numfirmasnac
		$this->numfirmasnac->ViewValue = $this->numfirmasnac->CurrentValue;
		$this->numfirmasnac->ViewCustomAttributes = "";

		// numfimasinter
		$this->numfimasinter->ViewValue = $this->numfimasinter->CurrentValue;
		$this->numfimasinter->ViewCustomAttributes = "";

		// numconsulcorta
		$this->numconsulcorta->ViewValue = $this->numconsulcorta->CurrentValue;
		$this->numconsulcorta->ViewCustomAttributes = "";

		// fecharecibido
		$this->fecharecibido->ViewValue = $this->fecharecibido->CurrentValue;
		$this->fecharecibido->ViewCustomAttributes = "";

		// fechacreacion
		$this->fechacreacion->ViewValue = $this->fechacreacion->CurrentValue;
		$this->fechacreacion->ViewCustomAttributes = "";

			// idAdjudicacion
			$this->idAdjudicacion->LinkCustomAttributes = "";
			$this->idAdjudicacion->HrefValue = "";
			$this->idAdjudicacion->TooltipValue = "";

			// idCalificacion
			$this->idCalificacion->LinkCustomAttributes = "";
			$this->idCalificacion->HrefValue = "";
			$this->idCalificacion->TooltipValue = "";

			// numproceso
			$this->numproceso->LinkCustomAttributes = "";
			$this->numproceso->HrefValue = "";
			$this->numproceso->TooltipValue = "";

			// nomprocesoproyecto
			$this->nomprocesoproyecto->LinkCustomAttributes = "";
			$this->nomprocesoproyecto->HrefValue = "";
			$this->nomprocesoproyecto->TooltipValue = "";

			// nconsulnac
			$this->nconsulnac->LinkCustomAttributes = "";
			$this->nconsulnac->HrefValue = "";
			$this->nconsulnac->TooltipValue = "";

			// nconsulinter
			$this->nconsulinter->LinkCustomAttributes = "";
			$this->nconsulinter->HrefValue = "";
			$this->nconsulinter->TooltipValue = "";

			// costoesti
			$this->costoesti->LinkCustomAttributes = "";
			$this->costoesti->HrefValue = "";
			$this->costoesti->TooltipValue = "";

			// estadoproceso
			$this->estadoproceso->LinkCustomAttributes = "";
			$this->estadoproceso->HrefValue = "";
			$this->estadoproceso->TooltipValue = "";

			// actaaper
			$this->actaaper->LinkCustomAttributes = "";
			$this->actaaper->HrefValue = "";
			$this->actaaper->TooltipValue = "";

			// informeacta
			$this->informeacta->LinkCustomAttributes = "";
			$this->informeacta->HrefValue = "";
			$this->informeacta->TooltipValue = "";

			// resoladju
			$this->resoladju->LinkCustomAttributes = "";
			$this->resoladju->HrefValue = "";
			$this->resoladju->TooltipValue = "";

			// estado
			$this->estado->LinkCustomAttributes = "";
			$this->estado->HrefValue = "";
			$this->estado->TooltipValue = "";

			// otro
			$this->otro->LinkCustomAttributes = "";
			$this->otro->HrefValue = "";
			$this->otro->TooltipValue = "";

			// numfirmasnac
			$this->numfirmasnac->LinkCustomAttributes = "";
			$this->numfirmasnac->HrefValue = "";
			$this->numfirmasnac->TooltipValue = "";

			// numfimasinter
			$this->numfimasinter->LinkCustomAttributes = "";
			$this->numfimasinter->HrefValue = "";
			$this->numfimasinter->TooltipValue = "";

			// numconsulcorta
			$this->numconsulcorta->LinkCustomAttributes = "";
			$this->numconsulcorta->HrefValue = "";
			$this->numconsulcorta->TooltipValue = "";

			// fecharecibido
			$this->fecharecibido->LinkCustomAttributes = "";
			$this->fecharecibido->HrefValue = "";
			$this->fecharecibido->TooltipValue = "";

			// fechacreacion
			$this->fechacreacion->LinkCustomAttributes = "";
			$this->fechacreacion->HrefValue = "";
			$this->fechacreacion->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
			if ($sMasterTblVar == "cs_calificacion") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_idCalificacion"] <> "") {
					$GLOBALS["cs_calificacion"]->idCalificacion->setQueryStringValue($_GET["fk_idCalificacion"]);
					$this->idCalificacion->setQueryStringValue($GLOBALS["cs_calificacion"]->idCalificacion->QueryStringValue);
					$this->idCalificacion->setSessionValue($this->idCalificacion->QueryStringValue);
					if (!is_numeric($GLOBALS["cs_calificacion"]->idCalificacion->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);
			$this->setSessionWhere($this->GetDetailFilter());

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "cs_calificacion") {
				if ($this->idCalificacion->QueryStringValue == "") $this->idCalificacion->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("cs_contratacion", $DetailTblVar)) {
				if (!isset($GLOBALS["cs_contratacion_grid"]))
					$GLOBALS["cs_contratacion_grid"] = new ccs_contratacion_grid;
				if ($GLOBALS["cs_contratacion_grid"]->DetailView) {
					$GLOBALS["cs_contratacion_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["cs_contratacion_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["cs_contratacion_grid"]->setStartRecordNumber(1);
					$GLOBALS["cs_contratacion_grid"]->idAdjudicacion->FldIsDetailKey = TRUE;
					$GLOBALS["cs_contratacion_grid"]->idAdjudicacion->CurrentValue = $this->idAdjudicacion->CurrentValue;
					$GLOBALS["cs_contratacion_grid"]->idAdjudicacion->setSessionValue($GLOBALS["cs_contratacion_grid"]->idAdjudicacion->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "cs_adjudicacionlist.php", "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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
if (!isset($cs_adjudicacion_view)) $cs_adjudicacion_view = new ccs_adjudicacion_view();

// Page init
$cs_adjudicacion_view->Page_Init();

// Page main
$cs_adjudicacion_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$cs_adjudicacion_view->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fcs_adjudicacionview = new ew_Form("fcs_adjudicacionview", "view");

// Form_CustomValidate event
fcs_adjudicacionview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fcs_adjudicacionview.ValidateRequired = true;
<?php } else { ?>
fcs_adjudicacionview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php $cs_adjudicacion_view->ExportOptions->Render("body") ?>
<?php
	foreach ($cs_adjudicacion_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $cs_adjudicacion_view->ShowPageHeader(); ?>
<?php
$cs_adjudicacion_view->ShowMessage();
?>
<form name="fcs_adjudicacionview" id="fcs_adjudicacionview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($cs_adjudicacion_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $cs_adjudicacion_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="cs_adjudicacion">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($cs_adjudicacion->idAdjudicacion->Visible) { // idAdjudicacion ?>
	<tr id="r_idAdjudicacion">
		<td><span id="elh_cs_adjudicacion_idAdjudicacion"><?php echo $cs_adjudicacion->idAdjudicacion->FldCaption() ?></span></td>
		<td data-name="idAdjudicacion"<?php echo $cs_adjudicacion->idAdjudicacion->CellAttributes() ?>>
<span id="el_cs_adjudicacion_idAdjudicacion">
<span<?php echo $cs_adjudicacion->idAdjudicacion->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->idAdjudicacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->idCalificacion->Visible) { // idCalificacion ?>
	<tr id="r_idCalificacion">
		<td><span id="elh_cs_adjudicacion_idCalificacion"><?php echo $cs_adjudicacion->idCalificacion->FldCaption() ?></span></td>
		<td data-name="idCalificacion"<?php echo $cs_adjudicacion->idCalificacion->CellAttributes() ?>>
<span id="el_cs_adjudicacion_idCalificacion">
<span<?php echo $cs_adjudicacion->idCalificacion->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->idCalificacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->numproceso->Visible) { // numproceso ?>
	<tr id="r_numproceso">
		<td><span id="elh_cs_adjudicacion_numproceso"><?php echo $cs_adjudicacion->numproceso->FldCaption() ?></span></td>
		<td data-name="numproceso"<?php echo $cs_adjudicacion->numproceso->CellAttributes() ?>>
<span id="el_cs_adjudicacion_numproceso">
<span<?php echo $cs_adjudicacion->numproceso->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->numproceso->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->nomprocesoproyecto->Visible) { // nomprocesoproyecto ?>
	<tr id="r_nomprocesoproyecto">
		<td><span id="elh_cs_adjudicacion_nomprocesoproyecto"><?php echo $cs_adjudicacion->nomprocesoproyecto->FldCaption() ?></span></td>
		<td data-name="nomprocesoproyecto"<?php echo $cs_adjudicacion->nomprocesoproyecto->CellAttributes() ?>>
<span id="el_cs_adjudicacion_nomprocesoproyecto">
<span<?php echo $cs_adjudicacion->nomprocesoproyecto->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->nomprocesoproyecto->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->nconsulnac->Visible) { // nconsulnac ?>
	<tr id="r_nconsulnac">
		<td><span id="elh_cs_adjudicacion_nconsulnac"><?php echo $cs_adjudicacion->nconsulnac->FldCaption() ?></span></td>
		<td data-name="nconsulnac"<?php echo $cs_adjudicacion->nconsulnac->CellAttributes() ?>>
<span id="el_cs_adjudicacion_nconsulnac">
<span<?php echo $cs_adjudicacion->nconsulnac->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->nconsulnac->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->nconsulinter->Visible) { // nconsulinter ?>
	<tr id="r_nconsulinter">
		<td><span id="elh_cs_adjudicacion_nconsulinter"><?php echo $cs_adjudicacion->nconsulinter->FldCaption() ?></span></td>
		<td data-name="nconsulinter"<?php echo $cs_adjudicacion->nconsulinter->CellAttributes() ?>>
<span id="el_cs_adjudicacion_nconsulinter">
<span<?php echo $cs_adjudicacion->nconsulinter->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->nconsulinter->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->costoesti->Visible) { // costoesti ?>
	<tr id="r_costoesti">
		<td><span id="elh_cs_adjudicacion_costoesti"><?php echo $cs_adjudicacion->costoesti->FldCaption() ?></span></td>
		<td data-name="costoesti"<?php echo $cs_adjudicacion->costoesti->CellAttributes() ?>>
<span id="el_cs_adjudicacion_costoesti">
<span<?php echo $cs_adjudicacion->costoesti->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->costoesti->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->estadoproceso->Visible) { // estadoproceso ?>
	<tr id="r_estadoproceso">
		<td><span id="elh_cs_adjudicacion_estadoproceso"><?php echo $cs_adjudicacion->estadoproceso->FldCaption() ?></span></td>
		<td data-name="estadoproceso"<?php echo $cs_adjudicacion->estadoproceso->CellAttributes() ?>>
<span id="el_cs_adjudicacion_estadoproceso">
<span<?php echo $cs_adjudicacion->estadoproceso->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->estadoproceso->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->actaaper->Visible) { // actaaper ?>
	<tr id="r_actaaper">
		<td><span id="elh_cs_adjudicacion_actaaper"><?php echo $cs_adjudicacion->actaaper->FldCaption() ?></span></td>
		<td data-name="actaaper"<?php echo $cs_adjudicacion->actaaper->CellAttributes() ?>>
<span id="el_cs_adjudicacion_actaaper">
<span<?php echo $cs_adjudicacion->actaaper->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->actaaper->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->informeacta->Visible) { // informeacta ?>
	<tr id="r_informeacta">
		<td><span id="elh_cs_adjudicacion_informeacta"><?php echo $cs_adjudicacion->informeacta->FldCaption() ?></span></td>
		<td data-name="informeacta"<?php echo $cs_adjudicacion->informeacta->CellAttributes() ?>>
<span id="el_cs_adjudicacion_informeacta">
<span<?php echo $cs_adjudicacion->informeacta->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->informeacta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->resoladju->Visible) { // resoladju ?>
	<tr id="r_resoladju">
		<td><span id="elh_cs_adjudicacion_resoladju"><?php echo $cs_adjudicacion->resoladju->FldCaption() ?></span></td>
		<td data-name="resoladju"<?php echo $cs_adjudicacion->resoladju->CellAttributes() ?>>
<span id="el_cs_adjudicacion_resoladju">
<span<?php echo $cs_adjudicacion->resoladju->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->resoladju->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->estado->Visible) { // estado ?>
	<tr id="r_estado">
		<td><span id="elh_cs_adjudicacion_estado"><?php echo $cs_adjudicacion->estado->FldCaption() ?></span></td>
		<td data-name="estado"<?php echo $cs_adjudicacion->estado->CellAttributes() ?>>
<span id="el_cs_adjudicacion_estado">
<span<?php echo $cs_adjudicacion->estado->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->estado->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->otro->Visible) { // otro ?>
	<tr id="r_otro">
		<td><span id="elh_cs_adjudicacion_otro"><?php echo $cs_adjudicacion->otro->FldCaption() ?></span></td>
		<td data-name="otro"<?php echo $cs_adjudicacion->otro->CellAttributes() ?>>
<span id="el_cs_adjudicacion_otro">
<span<?php echo $cs_adjudicacion->otro->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->otro->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->numfirmasnac->Visible) { // numfirmasnac ?>
	<tr id="r_numfirmasnac">
		<td><span id="elh_cs_adjudicacion_numfirmasnac"><?php echo $cs_adjudicacion->numfirmasnac->FldCaption() ?></span></td>
		<td data-name="numfirmasnac"<?php echo $cs_adjudicacion->numfirmasnac->CellAttributes() ?>>
<span id="el_cs_adjudicacion_numfirmasnac">
<span<?php echo $cs_adjudicacion->numfirmasnac->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->numfirmasnac->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->numfimasinter->Visible) { // numfimasinter ?>
	<tr id="r_numfimasinter">
		<td><span id="elh_cs_adjudicacion_numfimasinter"><?php echo $cs_adjudicacion->numfimasinter->FldCaption() ?></span></td>
		<td data-name="numfimasinter"<?php echo $cs_adjudicacion->numfimasinter->CellAttributes() ?>>
<span id="el_cs_adjudicacion_numfimasinter">
<span<?php echo $cs_adjudicacion->numfimasinter->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->numfimasinter->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->numconsulcorta->Visible) { // numconsulcorta ?>
	<tr id="r_numconsulcorta">
		<td><span id="elh_cs_adjudicacion_numconsulcorta"><?php echo $cs_adjudicacion->numconsulcorta->FldCaption() ?></span></td>
		<td data-name="numconsulcorta"<?php echo $cs_adjudicacion->numconsulcorta->CellAttributes() ?>>
<span id="el_cs_adjudicacion_numconsulcorta">
<span<?php echo $cs_adjudicacion->numconsulcorta->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->numconsulcorta->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->fecharecibido->Visible) { // fecharecibido ?>
	<tr id="r_fecharecibido">
		<td><span id="elh_cs_adjudicacion_fecharecibido"><?php echo $cs_adjudicacion->fecharecibido->FldCaption() ?></span></td>
		<td data-name="fecharecibido"<?php echo $cs_adjudicacion->fecharecibido->CellAttributes() ?>>
<span id="el_cs_adjudicacion_fecharecibido">
<span<?php echo $cs_adjudicacion->fecharecibido->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->fecharecibido->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($cs_adjudicacion->fechacreacion->Visible) { // fechacreacion ?>
	<tr id="r_fechacreacion">
		<td><span id="elh_cs_adjudicacion_fechacreacion"><?php echo $cs_adjudicacion->fechacreacion->FldCaption() ?></span></td>
		<td data-name="fechacreacion"<?php echo $cs_adjudicacion->fechacreacion->CellAttributes() ?>>
<span id="el_cs_adjudicacion_fechacreacion">
<span<?php echo $cs_adjudicacion->fechacreacion->ViewAttributes() ?>>
<?php echo $cs_adjudicacion->fechacreacion->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php
	if (in_array("cs_contratacion", explode(",", $cs_adjudicacion->getCurrentDetailTable())) && $cs_contratacion->DetailView) {
?>
<?php if ($cs_adjudicacion->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("cs_contratacion", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "cs_contrataciongrid.php" ?>
<?php } ?>
</form>
<script type="text/javascript">
fcs_adjudicacionview.Init();
</script>
<?php
$cs_adjudicacion_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$cs_adjudicacion_view->Page_Terminate();
?>
