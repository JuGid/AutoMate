<?php

namespace Automate\Specification;

use Automate\Configuration\Configuration;
use Automate\Exception\SpecificationException;
use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;
use PHPUnit\Framework\TestCase;

class SpecificationFinderTest extends TestCase
{

    /**
     * @before
     */
    public function loadConfig()
    {
        Configuration::load(__DIR__.'/../files/config-test.yaml');
        Configuration::$config_array['specs']['folder'] = __DIR__.'/../files/specs';
    }

    public function testShouldFindTheSpecificationInScenarioSpecFolder()
    {
        $finder = new SpecificationFinder();
        VariableRegistry::set(Scope::WORLD, 'scenario', 'scenario-tests');

        $specification = $finder->find();

        $this->assertInstanceOf(Specification::class, $specification);
        $this->assertSame(['url','cookiename'], $specification->getColumnsHeader());
    }

    public function testShouldFindTheSpecInSpecFolder()
    {
        $finder = new SpecificationFinder();
        VariableRegistry::set(Scope::WORLD, 'scenario', 'scenario-no-spec');

        $specification = $finder->find();

        $this->assertInstanceOf(Specification::class, $specification);
        $this->assertSame(['nospec','cookiename'], $specification->getColumnsHeader());
    }

    public function testShouldNotFindTheSpec()
    {
        $this->expectException(SpecificationException::class);
        $this->expectExceptionMessage('The specification has not been found.');

        $finder = new SpecificationFinder();
        
        VariableRegistry::set(Scope::WORLD, 'scenario', 'scenario-not-found');

        $specification = $finder->find();
    }

    public function testShouldWatchIfSpecIsProcessedAndCsv()
    {
        $finder = new SpecificationFinder();
        $file = Configuration::get('specs.folder').'/spec_PROCESSED.csv';
        $this->assertTrue($finder->isProcessed($file));
        $this->assertTrue($finder->isCsv($file));

        $file = Configuration::get('specs.folder').'/wrong-spec.cvs';
        $this->assertFalse($finder->isProcessed($file));
        $this->assertFalse($finder->isCsv($file));
    }
}
