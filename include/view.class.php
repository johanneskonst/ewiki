<?php

class View
{
    protected $dict = array();
    protected $template;

    function __set($name, $value) { $this->dict[$name] = $value; }
    function &__get($name) { return $this->dict[$name]; }
    function __isset($name) { return isset($this->dict[$name]); }
    function __unset($name) { unset($this->dict[$name]); }

    public function __construct($template=NULL)
    {
        $this->template = $template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    public function display($capture=false)
    {
        $include_path = get_include_path();
        $template_path = array(
                'templates/'.Config::TEMPLATE,
                'templates/common'
            );
        set_include_path(implode(PATH_SEPARATOR, $template_path));

	ob_start();
	$this->run();

        set_include_path($include_path);

	if ($capture)
	    return ob_get_clean();
	else
	    ob_end_flush();
    }

    protected function run()
    {
        foreach($this->dict as $key => $value)
            $$key = $value;
        require($this->template);
    }

    public function assign($vars)
    {
	foreach ($vars as $key => $value)
	    $this->$key = $value;
    }
}

