<?php
/**
 *
 * @author Géraud ISSERTES <gissertes@galilee.fr>
 * @copyright © 2017 Galilée (www.galilee.fr)
 */

namespace Galilee\PPM\SDK\Chili;

use Galilee\PPM\SDK\Chili\Config\Config;
use Galilee\PPM\SDK\Chili\Client\SoapCall;
use Galilee\PPM\SDK\Chili\Service;


class ChiliPublisher
{

    const RESOURCE_NAME_ADS = 'Ads';
    const RESOURCE_NAME_AD_SETTINGS = 'AdSettings';
    const RESOURCE_NAME_AD_SIZES = 'AdSizes';
    const RESOURCE_NAME_ARTICLES = 'Articles';
    const RESOURCE_NAME_ASSETS = 'Assets';
    const RESOURCE_NAME_BAR_CODE_TYPES = 'BarcodeTypes';
    const RESOURCE_NAME_COMPOSITE_PDF_EXPORTSETTINGS = 'CompositePdfExportSettings';
    const RESOURCE_NAME_DATA_SOURCES = 'DataSources';
    const RESOURCE_NAME_DOCUMENTS = 'Documents';
    const RESOURCE_NAME_DOCUMENT_CONSTRAINTS = 'DocumentConstraints';
    const RESOURCE_NAME_DOCUMENT_TEMPLATES = 'DocumentTemplates';
    const RESOURCE_NAME_EDITS = 'Edits';
    const RESOURCE_NAME_FOLDING_SETTINGS = 'FoldingSettings';
    const RESOURCE_NAME_FONTS = 'Fonts';
    const RESOURCE_NAME_HEALTH_CHECKS = 'HealthChecks';
    const RESOURCE_NAME_ICON_SETS = 'IconSets';
    const RESOURCE_NAME_IDML_EXPORT_SETTINGS = 'IdmlExportSettings';
    const RESOURCE_NAME_IMAGE_CONVERSION_PROFILES = 'ImageConversionProfiles';
    const RESOURCE_NAME_IMAGE_TRANSFORMATIONS = 'ImageTransformations';
    const RESOURCE_NAME_LANGUAGES = 'Languages';
    const RESOURCE_NAME_MOBILE_FEEDS = 'MobileFeeds';
    const RESOURCE_NAME_PDF_EXPORT_SETTINGS = 'PdfExportSettings';
    const RESOURCE_NAME_SPELL_CHECK_DICTS = 'SpellCheckDicts';
    const RESOURCE_NAME_SWITCH_SERVER_SETTINGS = 'SwitchServerSettings';
    const RESOURCE_NAME_USERS = 'Users';
    const RESOURCE_NAME_USER_GROUPS = 'UserGroups';
    const RESOURCE_NAME_VIDEOS = 'Videos';
    const RESOURCE_NAME_VIEW_PREFERENCES = 'ViewPreferences';
    const RESOURCE_NAME_WORK_SPACES = 'WorkSpaces';
    const RESOURCE_NAME_XINET_SETTINGS = 'XinetSettings';

    const RESOURCE_TYPE_LIST = 'List';
    const RESOURCE_TYPE_DIRECTORY_OBJECT = 'Directory_Object';
    const RESOURCE_TYPE_DIRECTORY_FILE = 'Directory_File';

    /**
     * thumbnail: a 100x100px, 72dpi, RGB png
     */
    const PREVIEW_TYPE_THUMBNAIL = 'thumbnail';

    /**
     * medium: a 400x400, 72dpi, RGB png
     */
    const PREVIEW_TYPE_MEDIUM = 'medium';

    /**
     * full: a 72dpi, RGB png at the size of the original file
     */
    const PREVIEW_TYPE_FULL = 'full';

    /**
     * swf, either the full png, or a compiled flash object (with preservation of vector information)
     */
    const PREVIEW_TYPE_SWF = 'swf';

    /**
     * pdfGeneration: an internal format, used for... PDF Generation
     * Alternatively, the ID of an Image Conversion Profile can be supplied
     */
    const PREVIEW_TYPE_PDF_GENERATION = 'pdfGeneration';

    /** @var Config|null  */
    protected $config = null;

    /** @var SoapCall */
    public $soapCall;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->soapCall = SoapCall::getInstance($config);
    }

    public function getDocument($id = '')
    {
        $serviceDocument = new Service\Document($this->soapCall);
        $serviceDocument->setProxyUrl($this->config->getProxyUrl());
        if ($id) {
            $serviceDocument->load($id);
        }
        return $serviceDocument;
    }

    public function getPdfExportSetting($id = '')
    {
        $serviceDocument = new Service\PdfExportSetting($this->soapCall);
        if ($id) {
            $serviceDocument->load($id);
        }
        return $serviceDocument;
    }
}
