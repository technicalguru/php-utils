<?php

namespace TgUtils\Templating;

/**
 * A snippet is a fixed, software-defined piece of text that can be inserted in
 * templates via the {{template-name}} variable.
 */
interface Snippet {

	/**
	 * Return the output of the template, using the given processor for objects and formatters.
	 */
	public function getOutput(Processor $processor);

}
