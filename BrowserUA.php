<?php
/**
 * UserAgent Theme Switcher class to process the plugin
 * @author Juan Benavides Romero <juan.benavides.romero@gmail.com>
 */
class BrowserUA {
	/**
	 * Browser code
	 * @var string
	 */
	private $code;


	/**
	 * Browser name
	 * @var string
	 */
	private $name;


	/**
	 * Theme name for this browser
	 * @var string
	 */
	private $theme;


	/**
	 * Regex for this browser
	 * @var string
	 */
	private $regex;


	/**
	 * Tag list for this browser
	 * @var array
	 */
	private $tags;


	/**
	 * Indicate by what are assigned the theme by code or by tag
	 * @var string
	 */
	private $themeBy;


	/**
	 * Constant that theme by code
	 */
	const THEMEBY_CODE = 'code';


	/**
	 * Constant that theme by tag
	 */
	const THEMEBY_TAG = 'tag';


	/**
	 * Default constructor. Can set by params all atributes
	 * @param string $code Browser code
	 * @param string $name Browser name
	 * @param string $icon Browser icon path
	 * @param string $theme Browser assigned theme
	 * @param string $regex Regex for this browser
	 * @param string|array $tags List of tags for this browser
	 */
	public function __construct($code = null, $name = null, $regex = null, $tags = null) {
		$this->code = $code;
		$this->name = $name;
		$this->regex = $regex;
		$this->setTags($tags);
	}//__construct


	/**
	 * Return the browser code
	 * @return int browser code
	 */
	public function getCode() {
		return $this->code;
	}//getCode


	/**
	 * Set the browser code
	 * @param string $code Browser code
	 */
	public function setCode($code) {
		$this->code = $code;
	}//setCode


	/**
	 * Return the browser name
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}//getName


	/**
	 * Set the browser name
	 * @param string $name Browser name
	 */
	public function setName($name) {
		$this->name = $name;
	}//setName


	/**
	 * Return the browser assigned theme
	 * @return string Browser assigned theme
	 */
	public function getTheme() {
		return $this->theme;
	}//getTheme


	/**
	 * Check if has theme asigned in this browser
	 * @return bool true if has theme false if not
	 */
	public function hasTheme() {
		if($this->theme == null) {
			return false;
		} else {
			return true;
		}
	}//hasTheme


	/**
	 * Set the browser assigned theme
	 * @param string $theme Browser assigned theme
	 */
	public function setTheme($theme) {
		$this->theme = $theme;
	}//setTheme


	/**
	 * Set the theme if code or tag set
	 * @param string $code Code or tag
	 * @param string $theme Theme to set
	 */
	public function setThemeByCodeTag($code, $theme) {
		if($code == $this->code) {
			$this->theme = $theme;
			$this->themeBy = BrowserUA::THEMEBY_CODE;
		} else {
			if(($this->theme == null || $this->theme == '') && $this->hasTag($code)) {
			$this->theme = $theme;
			$this->themeBy = BrowserUA::THEMEBY_TAG;
			}
		}
	}//setThemeByCodeTag


	/**
	 * Return the browser regex
	 * @return string Browser regex
	 */
	public function getRegex() {
		return $this->regex;
	}//getRegex


	/**
	 * Set the browser regex
	 * @param string $regex Browser regex
	 */
	public function setRegex($regex) {
		$this->regex = $regex;
	}//setRegex


	/**
	 * Return the list of tags
	 * @return array List of tags
	 */
	public function getTags() {
		return $this->tags;
	}//getTags


	public function getTagsAsString() {
		$countTag = count($this->tags);
		$tags = '';

		for($i = 0; $i < $countTag; $i++) {
			$tags .= $this->tags[$i].', ';
		}

		$tags = substr($tags, 0, -2);

		return $tags;
	}


	/**
	 * Return a tag by key
	 * @param int $key Tag key
	 * @return string Tag value
	 */
	public function getTag($key) {
		if(isset($this->tags[$key])) {
			return $this->tags[$key];
		} else {
			return null;
		}
	}//getTag


	/**
	 * Set the tags list. If is array, set this array in the tag list.
	 * If is a string, split by "," coma and add all tags to the tag list.
	 * @param array|string $tags Tag list
	 */
	public function setTags($tags) {
		if(is_array($tags)) {
			$this->tags = $tags;
		} else if(is_string($tags)) {
			$this->setTagsString($tags);
		}
	}//setTags


	/**
	 * Set the tag list by string separated by coma ",".
	 * @param string $tags Tag list separated by ",".
	 */
	public function setTagsString($tags) {
		$tagList = split(',', $tags);
		$tagCount = count($tagList);

		for($i = 0; $i < $tagCount; $i++) {
			$this->addTag($tagList[$i]);
		}
	}//setTagsString


	/**
	 * Add a tag to the tag list
	 * @param string $tag Tag text
	 */
	public function addTag($tag) {
		$this->tags[] = $tag;
	}//addTag


	/**
	 * Check if tag value exists
	 * @param string $tag Tag to search
	 * @return bool true if tag exist, false if not
	 */
	public function hasTag($tag) {
		$countTags = count($this->tags);

		for($i = 0; $i < $countTags; $i++) {
			if($this->tags[$i] == $tag) {
				return true;
			}
		}

		return false;
	}//hasTag


	/**
	 * Check if theme are asigned by code
	 * @return bool true if the theme are asigned by code
	 */
	public function isThemeByCode() {
		if($this->themeBy == BrowserUA::THEMEBY_CODE) {
			return true;
		}

		return false;
	}//isThemeByCode


	/**
	 * Check if theme are asigned by tag
	 * @return bool true if the theme are asigned by tag
	 */
	public function isThemeByTag() {
		if($this->themeBy == BrowserUA::THEMEBY_TAG) {
			return true;
		}

		return false;
	}//isThemeByTag



	public function isUserAgentBrowser($userAgent) {
		return @preg_match('/'.$this->regex.'/Usi', $userAgent);
	}
}//BrowserUA
?>
