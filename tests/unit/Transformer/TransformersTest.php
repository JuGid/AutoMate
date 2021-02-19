<?php 

namespace Automate\Scenario\Transformer;

use PASVL\Parsing\Problems\NonEmptyPostfix;
use PASVL\Validation\Problems\DataKeyMatchedNoPatternKey;
use PASVL\Validation\Problems\DataValueMatchedNoPattern;
use PHPUnit\Framework\TestCase;

/**
 * This class assure that the pattern is as good as we want
 */
class TransformersTest extends TestCase {
    
    public function testGoTransformerAndGetProperties() {
        $this->expectException(DataValueMatchedNoPattern::class);

        $transformer = new GoTransformer();
        $transformer->setStep(['go'=>'http://github.fr']);
        $this->assertTrue($transformer->validate());
        $this->assertSame('Go at adresse http://github.fr', strval($transformer));

        $transformer->setStep(['go'=>'foo']);
        $transformer->validate();
    }

    public function testAlertTransformerAndGetProperties() {
        $this->expectException(DataValueMatchedNoPattern::class);

        $possibilities = ["accept","dismiss","isPresent"];

        $transformer = new AlertTransformer();

        foreach($possibilities as $possibility) {
            $transformer->setStep(['alert'=>$possibility]);
            $this->assertTrue($transformer->validate());
        }

        $this->assertSame('On alert : isPresent', strval($transformer));

        $transformer->setStep(['alert'=>'foo']);
        $transformer->validate();
    }

    public function testClickTransformerAndGetProperties() {
        $this->expectException(DataKeyMatchedNoPatternKey::class);

        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new ClickTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['click'=>[$possibility =>'selector']]);
            $this->assertTrue($transformer->validate());
        }
        
        $this->assertSame('Click on element pltext[selector]', strval($transformer));

        $transformer->setStep(['click'=>['foo'=>'selector']]);
        $transformer->validate();
    }

    public function testCookieTransformerAndGetProperties() {
        $transformer = new CookieTransformer();
        $transformer->setStep(['cookie'=>['name'=>'foo', 'value'=>'bar']]);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Create a cookie with foo:bar', strval($transformer));
    }

    public function testCreateTransformerAndGetProperties() {
        $this->expectException(DataValueMatchedNoPattern::class);

        $possibilities = ["tab","window"];

        $transformer = new CreateTransformer();

        foreach($possibilities as $possibility) {
            $transformer->setStep(['create'=>$possibility]);
            $this->assertTrue($transformer->validate());
        }

        $this->assertSame('Create a new window and switch to it', strval($transformer));

        $transformer->setStep(['create'=>'tabs']);
        $transformer->validate();
    }

    public function testDeselectTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];
        $byPossibilities = ["value","index","text","pltext"];
        $transformer = new DeselectTransformer();

        foreach($findingPossibilities as $fpossibility) {
            foreach($byPossibilities as $bpossibilitiy) {
                $transformer->setStep([
                    'deselect'=> [
                        $fpossibility => 'selector',
                        'by'=> $bpossibilitiy,
                        'value' => 'selectPoint'
                    ]
                ]);
                $this->assertTrue($transformer->validate());
            }
        }

        $this->assertSame('Deselect in select pltext[selector] by pltext with value selectPoint', strval($transformer));
    }

    public function testSelectTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];
        $byPossibilities = ["value","index","text","pltext"];
        $transformer = new SelectTransformer();

        foreach($findingPossibilities as $fpossibility) {
            foreach($byPossibilities as $bpossibilitiy) {
                $transformer->setStep([
                    'select'=> [
                        $fpossibility => 'selector',
                        'by'=> $bpossibilitiy,
                        'value' => 'selectPoint'
                    ]
                ]);
                $this->assertTrue($transformer->validate());
            }
        }

        $this->assertSame('Select in select pltext[selector] by pltext with value selectPoint', strval($transformer));
    }

    public function testFillTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new FillTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['fill'=>[$possibility =>'selector', 'with'=>'phpunit']]);
            $this->assertTrue($transformer->validate());
        }
        
        $this->assertSame('Fill element pltext[selector] with value phpunit', strval($transformer));
    }

    public function testImpltmTransformerAndGetProperties() {
        $this->expectException(DataValueMatchedNoPattern::class);

        $transformer = new ImpltmTransformer();

        $transformer->setStep(['impltm'=>'60']);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Set implicit timeout 60 s', strval($transformer));

        $transformer->setStep(['impltm'=>'foo']);
        $this->assertTrue($transformer->validate());
    }

    //GetTransformer
    //FrameTransformer
    //NEXT isClickable


}