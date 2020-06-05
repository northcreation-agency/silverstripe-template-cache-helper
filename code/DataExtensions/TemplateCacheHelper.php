<?php
/**
 * Created by PhpStorm.
 * User: herbert
 * Date: 2018-02-28
 * Time: 23:03
 */
namespace NorthCreationAgency\TemplateCacheHelper;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\i18n\i18n;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use SilverStripe\SiteConfig\SiteConfig;

class TemplateCacheHelper extends DataExtension{
	static $template_page_cache_key; //cache per requests, so we dont calculate same thing over and over again
	public function TemplatePageCacheKey()
	{
		if (static::$template_page_cache_key) {
			return static::$template_page_cache_key;
		}
		$pageLatest = SiteTree::get()->max("LastEdited");
		$pageCount = SiteTree::get()->count();
		$elementalLatest = $elementalCount = 0;
		if (class_exists("\DNADesign\Elemental\Models\BaseElement")){
			$elementalLatest = \DNADesign\Elemental\Models\BaseElement::get()->max("LastEdited");
			$elementalCount = \DNADesign\Elemental\Models\BaseElement::get()->count();
		}
		$siteConfigLastEdited = SiteConfig::current_site_config()->LastEdited;
		$cacheKey =
			$_SERVER["HTTP_HOST"] . " | " . $this->owner->AbsoluteLink() . " | " .$this->owner->ID . " | " . $pageLatest . " | " . $pageCount . " | " .
			$elementalLatest . " | " .
			$elementalCount . " | " . $siteConfigLastEdited . " | " . $this->owner->IsInPreviewMode();
		$this->owner->extend("onTemplatePageCacheKey",$cacheKey);

		static::$template_page_cache_key = $cacheKey;
		return static::$template_page_cache_key;

	}



	static $template_sitewide_cache_key; //cache per requests, so we dont calculate same thing over and over again
	public function TemplateSitewideCacheKey()
	{
		if (static::$template_sitewide_cache_key) {
			return static::$template_sitewide_cache_key;
		}
		$pageLatest = SiteTree::get()->max("LastEdited");
		$pageCount = SiteTree::get()->count();
		$siteConfigLatest = SiteConfig::current_site_config()->LastEdited;
		$siteConfigID = SiteConfig::current_site_config()->ID;
		$cacheKey=$_SERVER["HTTP_HOST"] . " | " . $pageLatest . " | " . $pageCount . " | " . $siteConfigLatest . " | " . $siteConfigID." | ".$this->owner->CurrentLocale();
		$this->owner->extend("onTemplateSitewideCacheKey",$cacheKey);
		static::$template_sitewide_cache_key = $cacheKey;
		return static::$template_sitewide_cache_key;
	}
	public function IsInPreviewMode()
	{
		return key_exists("CMSPreview", $_REQUEST) ? true : false;
	}
    public function CurrentLocale()
    {
        if(DataObject::has_extension("Fluent")){
            return Fluent::current_locale();
        }
        return i18n::get_locale();

    }
}