<?php

abstract class Element
{
	protected $tagName;
	protected $id;
	protected $class;
	protected $innerHTML;
	protected $outerHTML;
	protected $closeTag;

	protected function __construct($childContext, $tagName, $id, $class, $innerHTML, $closeTag)
	{
		$this->tagName = strtolower($tagName);
		$this->id = $id;
		$this->class = $class;
		$this->innerHTML = $innerHTML;
		$this->closeTag = $closeTag;
		$this->createElement($childContext);
	}

	private function createElement($childContext)
	{
		$outerHTML = "<{$childContext->tagName} ";
		foreach($childContext as $propName => $propValue)
		{
			if($propValue !== null && $propName !== "tagName" && $propName !== "innerHTML" && $propName !== "outerHTML" && $propName !== "closeTag")
			{
				if($propName === "dataset" && !empty($propValue))
					$outerHTML .= "data-$propValue[0]=\"$propValue[1]\" ";
				else
					$outerHTML .= "$propName=\"$propValue\" ";
			}
				
		}
		$outerHTML .= ">";
		$childContext->outerHTML = $outerHTML.$childContext->innerHTML.$childContext->closeTag;
	}

	public function appendChild($child)
	{
		if($this->outerHTML && $this->closeTag)
		{
			$this->outerHTML = substr($this->outerHTML, 0, strlen($this->outerHTML) - strlen($this->closeTag));
			$this->outerHTML .= $child->outerHTML;
			$this->outerHTML .= $this->closeTag;
		}
		else
			throw new Exception("Cannot add element to '{$this->tagName}' element");
	}

	public function insertElement()
	{
		echo $this->outerHTML;
	}
}

class Div extends Element
{
	public function __construct($innerHTML = null, $id = null, $class = null)
	{
		parent::__construct($this, "div", $id, $class, $innerHTML, "</div>");
	}
}

class Form extends Element
{
	protected $action;
	protected $method;

	public function __construct($action, $method, $innerHTML = null, $id = null, $class = null)
	{
		$this->action = $action;
		$this->method = $method;
		parent::__construct($this, "form", $id, $class, $innerHTML, "</form>");
	}
}

class Input extends Element
{
	protected $name;
	protected $type;
	protected $value;
	protected $placeholder;
	protected $maxlength;

	public function __construct($name, $type, $value = null, $id = null, $class = null, $placeholder = null, $maxlength = null)
	{
		$this->name = $name;
		$this->type = $type;
		$this->value = $value;
		$this->placeholder = $placeholder;
		$this->maxlength = $maxlength;
		parent::__construct($this, "input", $id, $class, null, null);
	}
}

class Button extends Element
{
	protected $type;
	protected $dataset;

	public function __construct($type, $dataset = [], $innerHTML = null, $id = null, $class = null)
	{
		$this->type = $type;
		$this->dataset = $dataset;
		parent::__construct($this, "button", $id, $class, $innerHTML, "</button>");
	}
}

class Table extends Element
{
	public function __construct($id = null, $class = null)
	{
		parent::__construct($this, "table", $id, $class, null, "</table>");
	}
}

class THead extends Element
{
	public function __construct($id = null, $class = null)
	{
		parent::__construct($this, "thead", $id, $class, null, "</thead>");
	}
}

class TBody extends Element
{
	public function __construct($id = null, $class = null)
	{
		parent::__construct($this, "tbody", $id, $class, null, "</tbody>");
	}
}

class TFoot extends Element
{
	public function __construct($id = null, $class = null)
	{
		parent::__construct($this, "tfoot", $id, $class, null, "</tfoot>");
	}
}

class Tr extends Element
{
	public function __construct($id = null, $class = null)
	{
		parent::__construct($this, "tr", $id, $class, null, "</tr>");
	}
}

class Td extends Element
{
	public function __construct($id = null, $class = null, $innerHTML = null)
	{
		parent::__construct($this, "td", $id, $class, $innerHTML, "</td>");
	}
}

class Th extends Element
{
	public function __construct($id = null, $class = null, $innerHTML = null)
	{
		parent::__construct($this, "th", $id, $class, $innerHTML, "</th>");
	}
}