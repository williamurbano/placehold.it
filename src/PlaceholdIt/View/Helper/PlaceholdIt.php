<?php

namespace PlaceholdIt\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Validator;
use Zend\Filter;
use Zend\I18n;

/**
 * View Helper to generate placehold.it URLs
 *
 * @author William D. Urbano <williamurbano93@gmail.com> "http://williamurbano.com.br"
 * @license https://raw.githubusercontent.com/williamurbano/placehold.it/master/LICENSE
 * @package PlaceholdIt\View\Helper
 */
class PlaceholdIt extends AbstractHelper {

	/* Formats constants */
	const FORMAT_GIF  = 'gif';
	const FORMAT_JPEG = 'jpeg';
	const FORMAT_JPG  = 'jpg';
	const FORMAT_PNG  = 'png';

	/**
	 * Text for the image
	 *
	 * @var string
	 */
	protected $text = null;

	/**
	 * Image color scheme
	 *
	 * @var array|string
	 */
	protected $colors = null;

	/**
	 * Image format
	 *
	 * @var string
	 */
	protected $format = null;

	/**
	 * Image sizes
	 *
	 * @var array
	 */
	protected $size = null;

	/**
	 * Get placehold.it URL
	 *
	 * @param mixed  $size
	 * @param string $text
	 * @param string $colors
	 * @param string $format
	 * @return \PlaceholdIt\View\Helper\PlaceholdIt|string
	 */
	public function __invoke($size, $text = null, $colors = null, $format = null) {
		$this->setSize($size);

		if ($text != null) {
			$this->setText($text);
		}

		if ($colors != null) {
			$this->setColors($colors);
		}

		if ($format != null) {
			$this->setFormat($format);
		}

		return $this;
	}

	/**
	 * Call the generator when try print the URL
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->generate();
	}

	/**
	 * Genereate placehold.it URL
	 *
	 * @return mixed
	 * @author William D. Urbano <william.urbano@atualcard.com.br> "http://williamurbano.com.br"
	 */
	public function generate() {
		$patterns = array(
			"/(:size)/" => $this->getSize(true),
			"/(:colors)/" => $this->getColors(true),
			"/(:format)/" => $this->getFormat(),
			"/(:text)/" => $this->getText()
		);

		$urlPattern = "http://placehold.it/:size";

		if ($this->getColors() != null) {
			$urlPattern .= '/:colors';
		}

		if ($this->getFormat() != null) {
			$urlPattern .= '.:format';
		}

		if ($this->getText() != null) {
			$urlPattern .= '&text=:text';
		}

		$this->text   = null;
		$this->colors = null;
		$this->format = null;
		$this->size   = null;
		return preg_replace(array_keys($patterns), array_values($patterns), $urlPattern);
	}

	/**
	 * Set size
	 *
	 * @param mixed $size
	 * @return \PlaceholdIt\View\Helper\PlaceholdIt
	 */
	public function setSize($size) {
		if (is_array($size)) {
			$count = 0;
			foreach ($size as $k => $v) {
				$count++;
				if (is_string($v)) {
					$tmp      = (new Filter\Digits())->filter($v);
					$size[$k] = (new Filter\Int())->filter($tmp);
				}
			}
		} elseif (is_string($size)) {
			$tmp  = (new Filter\Digits())->filter($v);
			$size = array((new Filter\Int())->filter($tmp));
		} elseif (is_numeric($size)) {
			$tmp  = (new Filter\Digits())->filter($size);
			$tmp  = (new Filter\Int())->filter($tmp);
			$size = array($tmp, $tmp);
		} else {
			$size = array();
		}

		$this->size = $size;
		return $this;
	}

	/**
	 * Get size
	 *
	 * @param  string $string
	 * @return string|array
	 */
	public function getSize($string = false) {
		if ($string == true) {
			return implode('x', $this->size);
		} else {
			return $this->size;
		}
	}

	/**
	 * Set text
	 *
	 * @param string $text
	 * @throws Exception\InvalidArgumentException
	 * @return \PlaceholdIt\View\Helper\PlaceholdIt
	 */
	public function setText($text) {
		if (is_array($text)) {
			throw new Exception\InvalidArgumentException("The \"text\" parameter is not a string");
		} else {
			$this->text = (new Filter\UriNormalize())->filter($text);
		}

		return $this;
	}

	/**
	 * Get text
	 *
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Set colors
	 *
	 * @param unknown $colors
	 * @throws Exception\InvalidArgumentException
	 * @return \PlaceholdIt\View\Helper\PlaceholdIt
	 * @author William D. Urbano <william.urbano@atualcard.com.br> "http://williamurbano.com.br"
	 */
	public function setColors($colors) {
		$hexValidator = new Validator\Hex();
		if (is_array($colors)) {
			$count = 0;
			foreach ($colors as &$c) {
				$count++;
				$c = (new I18n\Filter\Alnum())->filter($c);
				if (!$hexValidator->isValid($c)) {
					foreach ($hexValidator->getMessages() as $message) {
						throw new Exception\InvalidArgumentException($message);
					}
				}
			}
		} elseif (is_string($colors)) {
			$colors = (new I18n\Filter\Alnum())->filter($colors);

			if (!$hexValidator->isValid($colors)) {
				foreach ($hexValidator->getMessages() as $message) {
					throw new Exception\InvalidArgumentException($message);
				}
			}

			$colors = array($colors);
		} elseif (is_numeric($colors)) {
			$colors = (new Filter\Digits())->filter($colors);

			if (!$hexValidator->isValid($colors)) {
				foreach ($hexValidator->getMessages() as $message) {
					throw new Exception\InvalidArgumentException($message);
				}
			}

			$colors = array($colors);
		} else {
			$colors = array();
		}

		$this->colors = $colors;

		return $this;
	}

	/**
	 * Get colors
	 *
	 * @param string $string
	 * @return array
	 */
	public function getColors($string = false) {
		return $string == true && $this->colors != null ? implode('/', $this->colors) : $this->colors;
	}

	/**
	 * Set format
	 *
	 * @param string $format
	 * @throws Exception\InvalidArgumentException
	 * @return \PlaceholdIt\View\Helper\PlaceholdIt
	 */
	public function setFormat($format) {
		$formats = array();

		$reflection = new \ReflectionClass($this);
		foreach ($reflection->getConstants() as $name => $value) {
			if (strpos($name, 'FORMAT_') !== -1) {
				$formats[] = $value;
			}
		}

		if (in_array((new Filter\StringToLower())->filter($format), $formats)) {
			$this->format = $format;
		} else {
			throw new Exception\InvalidArgumentException("The format \"%s\" is a invalid format. Try using %s", $format, implode(', ', $formats));
		}

		return $this;
	}

	/**
	 * Get format
	 *
	 * @return string
	 */
	public function getFormat() {
		return $this->format;
	}


}