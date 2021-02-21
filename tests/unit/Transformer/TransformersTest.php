<?php 

namespace Automate\Scenario\Transformer;

use Automate\Handler\GlobalVariableHandler;
use Automate\Handler\ScenarioVariableHandler;
use Automate\Handler\SpecVariableHandler;
use PASVL\Validation\Problems\DataKeyMatchedNoPatternKey;
use PASVL\Validation\Problems\DataValueMatchedNoPattern;
use PHPUnit\Framework\TestCase;

/**
 * This class assure that the pattern is as good as we want
 */
class TransformersTest extends TestCase {
    
    public function testShouldSetVariables() {
        
        ScenarioVariableHandler::$variables['var'] = 'scenario';
        SpecVariableHandler::$variables['var'] = 'scenario';
        GlobalVariableHandler::$variables['var'] = 'scenario';

        $scopes = [
            ['go'=>'{{ scenario.var }}'],
            ['go'=>'{{ spec.var }}'],
            ['go'=>'{{ global.var }}']
        ];

        $afterSetting = ['go'=>'scenario'];

        $transformer = new GoTransformer();
        foreach($scopes as $scope) {
            $transformer->setStep($scope);
            $transformer->setVariables();
            $this->assertSame($afterSetting, $transformer->getStep());
        }
    }

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

    public function testIsClickableTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new IsClickableTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['isClickable'=>[$possibility =>'selector']]);
            $this->assertTrue($transformer->validate());
        }
        
        $this->assertSame('Checking if pltext[selector] is clickable', strval($transformer));
    }

    public function testIsNotSelectedTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new IsNotSelectedTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['isNotSelected'=>[$possibility =>'selector']]);
            $this->assertTrue($transformer->validate());
        }
        
        $this->assertSame('Checking if element located by pltext[selector] is not selected', strval($transformer));
    }

    public function testIsSelectedTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new IsSelectedTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['isSelected'=>[$possibility =>'selector']]);
            $this->assertTrue($transformer->validate());
        }
        
        $this->assertSame('Checking if element located by pltext[selector] is selected', strval($transformer));
    }

    public function testNumberOfWindowsTransformerAndGetProperties() {
        $transformer = new NumberOfWindowsTransformer();

        $transformer->setStep(['numberOfWindows'=>'2']);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Wait until number of windows is 2', strval($transformer));
    }

    public function testPresenceOfAllTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new PresenceOfAllTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['presenceOfAll'=>[$possibility =>'selector']]);
            $this->assertTrue($transformer->validate());
        }
        
        $this->assertSame('Checking presence of all elements located by pltext[selector]', strval($transformer));
    }

    public function testPresenceOfTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new PresenceOfTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['presenceOf'=>[$possibility =>'selector']]);
            $this->assertTrue($transformer->validate());
        }
        
        $this->assertSame('Checking presence of element located by pltext[selector]', strval($transformer));
    }

    public function testReloadTransformerAndGetProperties() {
        $transformer = new ReloadTransformer();

        $transformer->setStep(['reload'=>'page']);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Reload the page', strval($transformer));
    }

    public function testTextContainsTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new TextContainsTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['textContains'=>['value'=>'val', $possibility =>'selector']]);
            $this->assertTrue($transformer->validate());
        }

        $this->assertSame('Checking if text of element pltext[selector] contains val', strval($transformer));
    }

    public function testTextIsTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new TextIsTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['textIs'=>['value'=>'val', $possibility =>'selector']]);
            $this->assertTrue($transformer->validate());
        }

        $this->assertSame('Checking if text of element pltext[selector] is val', strval($transformer));
    }

    public function testTextMatchesTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new TextMatchesTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['textMatches'=>['regexp'=>'val', $possibility =>'selector']]);
            $this->assertTrue($transformer->validate());
        }

        $this->assertSame('Checking if text of element pltext[selector] matches val', strval($transformer));
    }

    public function testTitleContainsTransformerAndGetProperties() {
        $transformer = new TitleContainsTransformer();

        $transformer->setStep(['titleContains'=>'page']);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Wait until title contains page', strval($transformer));
    }

    public function testTitleIsTransformerAndGetProperties() {
        $transformer = new TitleIsTransformer();

        $transformer->setStep(['titleIs'=>'page']);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Wait until title is page', strval($transformer));
    }

    public function testTitleMatchesTransformerAndGetProperties() {
        $transformer = new TitleMatchesTransformer();

        $transformer->setStep(['titleMatches'=>'page']);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Wait until title matches regexp page', strval($transformer));
    }

    public function testUrlContainsTransformerAndGetProperties() {
        $transformer = new UrlContainsTransformer();

        $transformer->setStep(['urlContains'=>'page']);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Wait until url contains page', strval($transformer));
    }

    public function testUrlIsTransformerAndGetProperties() {
        $transformer = new UrlIsTransformer();

        $transformer->setStep(['urlIs'=>'page']);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Wait until url is page', strval($transformer));
    }

    public function testUrlMatchesTransformerAndGetProperties() {
        $transformer = new UrlMatchesTransformer();

        $transformer->setStep(['urlMatches'=>'page']);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Wait until url matches regexp page', strval($transformer));
    }

    public function testVilibilityOfAnyTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new VisibilityOfAnyTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['visibilityOfAny'=>[$possibility=>'selector']]);
            $this->assertTrue($transformer->validate());
        }

        $this->assertSame('Checking visibility of any elements located by pltext[selector]', strval($transformer));
    }

    public function testVilibilityOfTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new VisibilityOfTransformer();

        foreach($findingPossibilities as $possibility) {
            $transformer->setStep(['visibilityOf'=>[$possibility=>'selector']]);
            $this->assertTrue($transformer->validate());
        }

        $this->assertSame('Checking visibility of element located by pltext[selector]', strval($transformer));
    }

    public function testGetTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformerAttribute = new GetTransformer();
        $transformerNoAttribute = new GetTransformer();
        foreach($findingPossibilities as $possibility) {
            $validPattern = [
                'get'=> [
                    $possibility => 'selector',
                    'what'=>'attribute',
                    'attribute'=>'attribut',
                    'varname'=>'var'
                ]
            ];

            $secondValidPattern = [
                'get'=> [
                    $possibility => 'selector',
                    'what'=>'text',
                    'varname'=>'var'
                ]
            ];

            $transformerAttribute->setStep($validPattern);
            $this->assertTrue($transformerAttribute->validate());

            $transformerNoAttribute->setStep($secondValidPattern);
            $this->assertTrue($transformerNoAttribute->validate());
        }
        
        $this->assertSame('Get attribute for element pltext[selector] with value attribut', strval($transformerAttribute));
        $this->assertSame('Get text for element pltext[selector] ', strval($transformerNoAttribute));
        
    }

    public function testFrameTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new FrameTransformer();

        foreach($findingPossibilities as $possibility) {
            $validPattern = [
                'frame'=> [
                    $possibility => 'selector'
                ]
            ];

            $secondValidPattern = [
                'frame'=> [
                    $possibility => 'selector',
                    'index'=>'0'
                ]
            ];

            $thirdvalidPattern = [
                'frame'=> [
                    $possibility => 'selector',
                    'index'=>'1',
                    'default' => 'content'
                ]
            ];

            $transformer->setStep($validPattern);
            $this->assertTrue($transformer->validate());

            $transformer->setStep($secondValidPattern);
            $this->assertTrue($transformer->validate());

            $transformer->setStep($thirdvalidPattern);
            $this->assertTrue($transformer->validate());
        }

        $this->assertSame('Frame to string', strval($transformer));
    }

    public function testResizeTransformerAndGetProperties() {
        $screenPossibilities = ["maximize","fullscreen","size"];

        $transformerNoSize = new ResizeTransformer();
        $transformerSize = new ResizeTransformer();

        foreach($screenPossibilities as $possibility) {
            $validPattern = [
                'resize'=> [
                    'type' => $possibility
                ]
            ];

            $secondValidPattern = [
                'resize'=> [
                    'type'=>$possibility,
                    'size'=>[
                        'width'=>'500',
                        'height'=>'600'
                    ]
                ]
            ];

            $transformerNoSize->setStep($validPattern);
            $this->assertTrue($transformerNoSize->validate());

            $transformerSize->setStep($secondValidPattern);
            $this->assertTrue($transformerSize->validate());
        }

        // This assert is false in the process but works here and need to be
        // verified
        $this->assertSame('Resize the page [size] ',strval($transformerNoSize));

        $this->assertSame('Resize the page [size] with 500px per 600px',strval($transformerSize));
    }

    public function testScreenshotTransformerAndGetProperties() {
        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];
        $screenshotTypes = ["all","element"];

        $transformer = new ScreenshotTransformer();

        foreach($findingPossibilities as $possibility) {
            foreach($screenshotTypes as $type) {
                $validPattern = [
                    'screenshot'=> [
                        'type' => $type,
                        'sname'=>'myScreenshot'
                    ]
                ];
                
                $secondValidPattern = [
                    'screenshot'=> [
                        'type' => $type,
                        'sname'=>'myScreenshot',
                        $possibility => 'selector'
                    ]
                ];

                $transformer->setStep($validPattern);
                $this->assertTrue($transformer->validate());

                $transformer->setStep($secondValidPattern);
                $this->assertTrue($transformer->validate());
            }
        }

        $this->assertSame('Cheeeeeese [myScreenshot]', strval($transformer));
    }

    public function testLoopTransformerAndGetProperties() {
        $transformer = new LoopTransformer();
        $transformer->setStep([
            'loop'=> [
                'repeat'=> '5',
                'steps'=> [
                    ['go'=>'http://github.com'],
                    ['urlIs'=>'https://github.com']
                ]
            ]
        ]);
        $this->assertTrue($transformer->validate());
        $this->assertSame('> Loop ends after 5 times', strval($transformer));
    }

    public function testConditionTransformer() {
        $transformer = new ConditionTransformer();
        $transformer->setStep([
            'condition'=> [
                'eval'=>'5 == 6',
                'ifTrue'=> [
                    'steps'=>[
                        ['go'=>'http://github.com']
                    ]
                ],
                'ifFalse'=> [
                    'steps'=>[
                        ['go'=> 'http://youtube.com'],
                        ['reload'=>'page']
                    ]
                ]
            ]
        ]);
        
        $this->assertTrue($transformer->validate());
        
        $stepResult = $transformer->getStep();
        $stepResult['condition']['result'] = 'false';
        $transformer->setStep($stepResult);

        $this->assertSame('Condition assessed 5 == 6 as false', strval($transformer));
    }

    public function testExitTransformer() {
        $transformer = new ExitTransformer();
        $transformer->setStep(['exit'=>'For the example']);

        $this->assertTrue($transformer->validate());
        $this->assertSame('EXIT', strval($transformer));
    }
}