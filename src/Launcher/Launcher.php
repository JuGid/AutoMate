<?php 

namespace Automate\Launcher;

use Automate\AutoMate;
use RuntimeException;

class Launcher {

    /**
     * The array of all scenarios the launcher has to run.
     * @var array
     */
    private $scenariosToLaunch = [];

    /**
     * @var ResultAggregator
     */
    private $resultAggregator;

    /**
     * @var AutoMate
     */
    private $automate;

    public function __construct(string $configPath) {
        $this->automate = new AutoMate($configPath);
        $this->resultAggregator = new ResultAggregator();
    }

    public function add(string $scenario, bool $spec = false, string $browser = 'chrome') : self {
        $this->scenariosToLaunch[$scenario] = [$spec, $browser];
        return $this;
    }

    public function run() : bool {
        if (empty($this->scenariosToLaunch)) 
        {
            throw new RuntimeException('There is no scenarios to launch');
        }

        foreach ($this->scenariosToLaunch as $scenario=>$parameters) 
        {
            $result = $this->automate->run($scenario, $parameters[0], true, $parameters[1]);
            $this->resultAggregator->addResult($scenario, $result);
        }

        return $this->resultAggregator->hasError();
    }

    public function printReport() : void {
        $this->resultAggregator->printReports();
    }

    public function printReportInFile(string $file) : void 
    {
        $this->resultAggregator->printReportInFile($file);
    }
}