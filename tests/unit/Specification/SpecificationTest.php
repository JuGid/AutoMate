<?php

use Automate\Handler\SpecVariableHandler;
use Automate\Specification\Specification;
use PHPUnit\Framework\TestCase;

class SpecificationTest extends TestCase {

    const SCENARIO = 'scenario-tests';
    const FILEPATH = __DIR__. '/../files/specs/'.self::SCENARIO.'/spec.csv';

    /**
     * @before
     */
    public function purgeSpecVariables() {
        SpecVariableHandler::removeAll();
    }

    public function testShouldCreateNewSpecificationAndGetProperties() {
        $specification = new Specification(self::FILEPATH);

        $this->assertSame(self::FILEPATH, $specification->getFilepath());
        $this->assertSame(['url'=>'http://youtube.fr','cookiename'=>'youtube'], $specification->current());
        $this->assertSame(3, $specification->getRowNumber());
        $this->assertSame('spec', $specification->getFilename());
        $this->assertSame('csv', $specification->getExtension());
        $this->assertSame(['url','cookiename'], $specification->getColumnsHeader());
        $this->assertSame(__DIR__. '/../files/specs/'.self::SCENARIO, $specification->getPath());
    }

    public function testShouldTestForeachData() {
        $specification = new Specification(self::FILEPATH);

        $dataArray = [
            [
                'url'=>'http://youtube.fr',
                'cookiename'=>'youtube'
            ],
            [
                'url'=>'http://google.fr',
                'cookiename'=>'google'
            ],
            [
                'url'=>'http://github.com',
                'cookiename'=>'github'
            ]
        ];

        for($i=0; $i < count($dataArray); $i++) {
            $this->assertSame($dataArray[$i], $specification->current());
            $specification->next();
        }
    }
}