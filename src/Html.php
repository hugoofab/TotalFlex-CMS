<?php

namespace TotalFlex;

class Html {


	/**
	 * @var array[Context] The contexts this fields is allowed
	 */
	private $_contexts;
	protected $html = "";

	public function __construct ( $html ) {
		$this->html = $html;
	}

	public function toHtml ( ) {
		return $this->html;
	}

    /**
     * Sets the contexts this fields is allowed
     *
     * @param int The contexts this fields is allowed. See TotalFlex::Ctx* constants.
     * @return self
     */
    public function setContexts($contexts) {
        $this->_contexts = $contexts;
        return $this;
    }

    /**
     * Gets the contexts this fields is allowed
     *
     * @return int The contexts this fields is allowed
     */
    public function getContexts() {
        return $this->_contexts;
    }

    public function isInContext($context) {
        return (($context & $this->getContexts()) !== 0);
    }


}
