<?php 

namespace Automate\Exception;

use Exception;

abstract class CustomException extends Exception implements IException
{
    /**
     * @var string
     */
    protected $message = 'Unknown exception';     // Exception message

    /**
     * @var string
     */
    private   $string;                            // Unknown

    /**
     * @var int
     */
    protected $code    = 0;                       // User-defined exception code

    /**
     * @var string
     */
    protected $file;                              // Source filename of exception

    /**
     * @var int
     */
    protected $line;                              // Source line of exception

    /**
     * @var array
     */
    private   $trace;                             // Unknown

    public function __construct(string $message = null, int $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }
        parent::__construct($message, $code);
    }
    
    public function __toString() : string
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n"
                                . "{$this->getTraceAsString()}";
    }
}