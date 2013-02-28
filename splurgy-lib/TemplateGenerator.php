<?php
class TemplateGenerator {

    protected $templateName;
    protected $patterns;
    protected $replacements;
    protected $customPath;


    public function __construct($templateName=null, $customPath=null, $patterns=null, $replacements=null)
    {
        $this->templateName = $templateName;
        $this->patterns = $patterns;
        $this->replacements = $replacements;
        $this->customPath = $customPath;
    }

    public function setTemplateName($templateName) {
        $this->templateName = $templateName;
    }

    public function setPatterns($patterns) {
        $this->patterns = $patterns;
    }

    public function setReplacements($replacements) {
        $this->replacements = $replacements;
    }

    public function setPath($path) {
        $this->customPath = $path;
    }

    public function getTemplate() {
        $path = $this->customPath;

        if( is_null($this->templateName) ) {
            throw new TemplateErrorException("The template '$this->templateName.stp' doesn't exist");
        } else {
            $filename = $path. $this->templateName. ".stp";
        }

        if( file_exists($filename) ) {            
            $contents = file_get_contents($filename);
            $contents = str_replace($this->patterns, $this->replacements, $contents);
            return $contents;
        }

        throw new TemplateErrorException("The template '$this->templateName.stp' doesn't exist");
    }
}
?>
