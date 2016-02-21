<?php namespace Anomaly\Streams\Platform\Support;

use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class Presenter
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\Streams\Platform\Support
 */
class Presenter extends \Robbo\Presenter\Presenter
{

    use DispatchesJobs;

    /**
     * Protected names.
     *
     * @var array
     */
    protected $protected = [
        'delete',
        'save',
        'update'
    ];

    /**
     * Get the object.
     *
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Pass any unknown variable calls to present{$variable} or fall through to the injected object.
     *
     * @param  string $var
     * @return mixed
     */
    public function __get($var)
    {
        if (in_array($var, $this->protected)) {
            return null;
        }

        if ($method = $this->getPresenterMethodFromVariable($var)) {
            return $this->$method();
        }

        // Check the presenter for a getter.
        if (method_exists($this, camel_case('get_' . $var))) {
            return call_user_func_array([$this, camel_case('get_' . $var)], []);
        }

        // Check the presenter for a method.
        if (method_exists($this, camel_case($var))) {
            return call_user_func_array([$this->object, camel_case($var)], []);
        }

        // Check the object for a getter.
        if (method_exists($this->object, camel_case('get_' . $var))) {
            return call_user_func_array([$this->object, camel_case('get_' . $var)], []);
        }

        // Check the object for a getter.
        if (method_exists($this->object, camel_case('is_' . $var))) {
            return call_user_func_array([$this->object, camel_case('is_' . $var)], []);
        }

        // Check the object for a method.
        if (method_exists($this->object, camel_case($var))) {
            return call_user_func_array([$this->object, camel_case($var)], []);
        }

        try {
            // Lastly try generic property access.
            return $this->__getDecorator()->decorate(
                is_array($this->object) ? $this->object[$var] : $this->object->$var
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Fetch the presenter method name for the given variable.
     *
     * @param  string $variable
     * @return string|null
     */
    protected function getPresenterMethodFromVariable($variable)
    {
        $method = camel_case($variable);

        if (method_exists($this, $method)) {
            return $method;
        }
    }

    /**
     * Call unknown methods if safe.
     *
     * @param string $method
     * @param array  $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (in_array(snake_case($method), $this->protected)) {
            return null;
        }

        return parent::__call($method, $arguments); // TODO: Change the autogenerated stub
    }

    /**
     * Return the objects string method.
     *
     * @return string
     */
    function __toString()
    {
        if (method_exists($this->object, '__toString')) {
            return $this->object->__toString();
        }

        return json_encode($this->object);
    }
}
