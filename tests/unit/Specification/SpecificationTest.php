<?php

use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;
use Automate\Specification\Specification;
use PHPUnit\Framework\TestCase;

class SpecificationTest extends TestCase {

    const SCENARIO = 'scenario-tests';
    const FILEPATH = __DIR__. '/../files/specs/'.self::SCENARIO.'/spec.csv';
    const FILEPATH_TO_RENAME = __DIR__. '/../files/specs/scenario-spec-processed/spec.csv';
    const FILEPATH_RENAMED = __DIR__. '/../files/specs/scenario-spec-processed/spec_PROCESSED.csv';
    /**
     * @before
     */
    public function purgeSpecVariables() {
        VariableRegistry::reset(Scope::SPEC);
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
            $this->assertSame($i, $specification->key());
            $this->assertTrue($specification->valid());
            $specification->next();
        }

        $specification->rewind();
        $this->assertSame($dataArray[0], $specification->current());
    }

    public function testShouldSetSpecAsProcessed() {
        $specification = new Specification(self::FILEPATH_TO_RENAME);

        $specification->setProcessed();

        $this->assertFalse(file_exists(self::FILEPATH_TO_RENAME));
        $this->assertTrue(file_exists(self::FILEPATH_RENAMED));

        rename(self::FILEPATH_RENAMED,self::FILEPATH_TO_RENAME);
    }

}