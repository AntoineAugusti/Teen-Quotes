<?php

/*
 * This file is part of the Teen Quotes website.
 *
 * (c) Antoine Augusti <antoine.augusti@teen-quotes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeenQuotes\Tools\Validation;

use BadMethodCallException;
use InvalidArgumentException;
use Laracasts\Validation\FormValidationException;
use Laracasts\Validation\LaravelValidator;
use Str;

abstract class Validator extends LaravelValidator
{
    /**
     * Validate data against a set of rules.
     *
     * @param array  $data     The key-value data
     * @param string $rule     The name of the property for the rules
     * @param array  $messages
     *
     * @throws \Laracasts\Validation\FormValidationException
     *
     * @return bool
     */
    protected function validateForRule($data, $rule, $messages = [])
    {
        $this->validation = $this->make($data, $this->$rule, $messages);

        return $this->handleValidation();
    }

    /**
     * Get the failed rule of an attribute for the current validator.
     *
     * @param string $key The name of the attribute
     *
     * @return string
     */
    public function getFailedReasonFor($key)
    {
        $failed = $this->validation->failed();

        if (!array_key_exists($key, $failed)) {
            throw new InvalidArgumentException("Validator didn't failed for key: ".$key);
        }

        return Str::slug($this->getFailedReasonForKey($failed, $key));
    }

    /**
     * Magic call method to forward validate* methods.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @throws BadMethodCallException
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (Str::startsWith($name, 'validate')) {
            $property = 'rules'.str_replace('validate', '', $name);

            if (!property_exists($this, $property)) {
                throw new BadMethodCallException('Property '.$property.' does not exist on class '.get_class($this).'.');
            }

            // No custom validation messages were given
            if (count($arguments) == 1) {
                return $this->validateForRule($arguments[0], $property);
            }

            // Validate for a rule with custom validation messages
            return $this->validateForRule($arguments[0], $property, $arguments[1]);
        }

        // Forward other calls
        return call_user_func_array(
            [$this, $name],
            $arguments
        );
    }

    /**
     * Get the failed raison for a given key.
     *
     * @param array  $failed
     * @param string $key
     *
     * @return string The failed rule for this key
     */
    private function getFailedReasonForKey($failed, $key)
    {
        return array_keys($failed[$key])[0];
    }

    /**
     * Perform validation when the validator has been bound.
     *
     * @throws FormValidationException When the validation has failed
     *
     * @return bool
     */
    private function handleValidation()
    {
        if ($this->validation->fails()) {
            throw new FormValidationException(
                $this->validation->errors()->first($this->getFirstKeyFail()),
                $this->validation->errors()
            );
        }

        return true;
    }

    /**
     * Get the first failed key when validation has failed.
     *
     * @return string
     */
    private function getFirstKeyFail()
    {
        $keys = array_keys($this->validation->errors()->getMessages());

        return $keys[0];
    }
}
