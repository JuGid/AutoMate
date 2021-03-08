<?php

namespace Automate\Exception;

/**
 * From PHP Documentation
 */
interface IException
{
    /* Protected methods inherited from Exception class */

    public function getMessage();                 // Exception message
    public function getCode();                    // User-defined Exception code
    public function getFile();                    // Source filename
    public function getLine();                    // Source line
    public function getTrace();                   // An array of the backtrace()
    public function getTraceAsString();           // Formated string of trace
    
    /* Overrideable methods inherited from Exception class */
    public function __toString() : string;                 // formated string for display
    public function __construct(string $message = null, int $code = 0);
}
