<?php namespace RtlWeb\Rtler\Classes;

use File;
use Config;
use Request;
use Lang;
use Illuminate\Routing\UrlGenerator as baseGenerator;

class UrlGenerator extends baseGenerator
{
    /**
     * Generate a URL to an application asset.
     *
     * @param  string $path
     * @param  bool|null $secure
     * @return string
     */
    public function asset($path, $secure = null)
    {
        if(!LanguageDetector::isRtl()) return parent::asset($path,$secure);
        if(!strpos($path,'/rtlweb/rtler/assets/css/rtl.css')) {
            if ($this->isValidUrl($path)) return $path;
            $backendUri = Config::get('cms.backendUri', 'backend');
            $requestUrl = Request::url();
            if (File::exists(
                base_path(dirname($path)) . '.rtl.' . File::extension($path)
            )
            ) {
                $path = dirname($path) . '.rtl.' . File::extension($path);
            } else if (File::extension($path) == 'css' && (strpos($requestUrl, $backendUri) || strpos($path, 'plugins/') || strpos($path, 'modules/'))) {
                $path = CssFlipper::flipCss($path);
            }
        }
        return parent::asset($path,$secure);
    }
}
