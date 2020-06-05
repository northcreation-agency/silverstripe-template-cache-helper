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
	static $default_cache_key;

	public function defaultCacheKey()
	{
		if (static::$default_cache_key) {
			return static::$default_cache_key;
		}
		$pageLatest = SiteTree::get()->max("LastEdited");
		$pageCount = SiteTree::get()->count();
		$elementalLatest = $elementalCount = 0;
		if (class_exists("BaseElement")){
			$elementalLatest = BaseElement::get()->max("LastEdited");
			$elementalCount = BaseElement::get()->count();
		}
		$siteConfigLastEdited = SiteConfig::current_site_config()->LastEdited;
		$cacheKey =
			$_SERVER["HTTP_HOST"] . " | " . $this->owner->AbsoluteLink() . " | " .$this->owner->ID . " | " . $pageLatest . " | " . $pageCount . " | " .
			$elementalLatest . " | " .
			$elementalCount . " | " . $siteConfigLastEdited . " | " . $this->owner->IsInPreviewMode();
		$this->owner->extend("onDefaultCacheKey",$cacheKey);
		//static::$default_chrome_cache_key = $cacheKey;

		static::$default_cache_key = $cacheKey;
		return static::$default_cache_key;

	}

	static $default_chrome_cache_key;

	public function defaultChromeCacheKey()
	{
		if (static::$default_chrome_cache_key) {
			return static::$default_chrome_cache_key;
		}
		$pageLatest = SiteTree::get()->max("LastEdited");
		$pageCount = SiteTree::get()->count();
		$siteConfigLatest = SiteConfig::current_site_config()->LastEdited;
		$siteConfigID = SiteConfig::current_site_config()->ID;
		$cacheKey=$_SERVER["HTTP_HOST"] . " | " . $pageLatest . " | " . $pageCount . " | " . $siteConfigLatest . " | " . $siteConfigID." | ".$this->owner->CurrentLocale();
		$this->owner->extend("onDefaultChromeCacheKey",$cacheKey);
		static::$default_chrome_cache_key = $cacheKey;
		return static::$default_chrome_cache_key;

		//$relatedAdsCollection=AdCollection::get()->where("FIND_IN_SET IN (".$this->TopAdCollectionID.",".$this->ContentAdCollectionID.",".$this->SecondInContentAdCollection.",".$this->BottomAdCollection." )")->max("LastEdited");
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