<?php

namespace Automate\Transformer;

use Automate\AutoMateEvents;
use Automate\Registry\Scope;
use Automate\Registry\VariableRegistry;
use PASVL\Validation\Problems\DataKeyMatchedNoPatternKey;
use PASVL\Validation\Problems\DataValueMatchedNoPattern;
use PHPUnit\Framework\TestCase;

/**
 * This class assure that the pattern is as good as we want
 */
class TransformersTest extends TestCase
{
    public function testShouldSetVariables()
    {
        VariableRegistry::set(Scope::SCENARIO, 'var', 'scenario');
        VariableRegistry::set(Scope::SPEC, 'var', 'scenario');
        VariableRegistry::set(Scope::WORLD, 'var', 'scenario');

        $scopes = [
            ['go'=>'{{ scenario.var }}'],
            ['go'=>'{{ spec.var }}'],
            ['go'=>'{{ world.var }}']
        ];

        $afterSetting = ['go'=>'scenario'];

        $transformer = new GoTransformer();
        foreach ($scopes as $scope) {
            $transformer->setStep($scope);
            $transformer->setVariables();
            $this->assertSame($afterSetting, $transformer->getStep());
        }
    }

    /**
     * I put this test first as it may change more then others
     */
    public function testConfigurationTransformer()
    {
        $transformer = new ConfigurationTransformer();
        $transformer->setStep(['configuration'=>[]]);
        $this->assertTrue($transformer->validate());

        $config = ['configuration'=>['wait'=>['for'=>'35', 'every'=>'500']]];
        $transformer->setStep($config);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Configuration changed', (string) $transformer);
        $this->assertSame(AutoMateEvents::STEP_TRANSFORM, $transformer->onEvent());
    }

    public function testGoTransformerAndGetProperties()
    {
        $this->expectException(DataValueMatchedNoPattern::class);

        $transformer = new GoTransformer();
        $transformer->setStep(['go'=>'http://github.fr']);
        $this->assertTrue($transformer->validate());
        $this->assertSame('Go at adresse http://github.fr', (string) $transformer);

        $transformer->setStep(['go'=>'foo']);
        $transformer->validate();
    }

    public function testAlertTransformerAndGetProperties()
    {
        $this->expectException(DataValueMatchedNoPattern::class);

        $possibilities = ["accept","dismiss","isPresent"];

        $transformer = new AlertTransformer();

        $pattern = ['alert' => ['type'=>'accept']];
        $transformer->setStep($pattern);
        $this->assertTrue($transformer->validate());

        $pattern = ['alert' => ['type'=>'sendKeys', 'value'=>'hi']];
        $transformer->setStep($pattern);
        $this->assertTrue($transformer->validate());

        $this->assertSame('On alert : sendKeys', (string) $transformer);

        $transformer->setStep(['alert'=>['type'=>'foo']]);
        $transformer->validate();
    }

    public function testClickTransformerAndGetProperties()
    {
        $this->expectException(DataKeyMatchedNoPatternKey::class);

        $findingPossibilities = ["css","xpath","id","class","name","tag","linktext", "pltext"];

        $transformer = new ClickTransformer();

        foreach ($findingPossibilities as $possibility) {
            $transformer->setStep(['click'=>[$possibility =>'selector']]);
            $this->assertTrue($transformer->validate());
        }
        
        $this->assertSame('Click on element pltext[selector]', (string) $transformer);

        $transformer->setStep(['click'=>['foo'=>'selector']]);
        $transformer->validate();
    }

    public function testCookieTransformerAndGetProperties()
    {
        $transformer = new CookieTransformer();
        $transformer->setStep(['cookie'=>['name'=>'foo', 'value'=>'bar']]);
        $this->assertTrue($transformer->validate());

        $this->assertSame('Create a cookie with foo:bar', (string) $transformer);
    }

    public function testCreateTransformerAndGetProperties()
    {
        $this->expectException(DataValueMatchedNoPattern::class);

        $possibilities = ["tab","window"];

        $transformer = new CreateTransformer();

        foreach ($possibilities as $possibility) {
            $transformer->setStep(['create'=>$possibility]);
            $this->assertTrue($transformer->validate());
        }

        $this->assertSame('Create a new window and switch to it', (string) $transformer);

        $transformer->setStep(['create'=>'tabs']);
        $transformer->validate();
    }

    public function testDeselectTransformerAndGetProperties()
    {
        $transformer = new DeselectTransformer();

        $transformer->setStep([
            'deselect'=> [
                'id' => 'selector',
                'type'=>'checkbox',
                'by'=> 'index',
                'value' => '0'
            ]
        ]);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Deselect in checkbox id[selector] by index with value 0', (string) $transformer);
    }

    public function testSelectTransformerAndGetProperties()
    {
        $transformer = new SelectTransformer();

        $transformer->setStep([
            'select'=> [
                'id' => 'selector',
                'type'=>'radio',
                'by'=> 'text',
                'value' => 'selectPoint'
            ]
        ]);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Select in radio id[selector] by text with value selectPoint', (string) $transformer);
    }

    public function testFillTransformerAndGetProperties()
    {
        $transformer = new FillTransformer();

        $transformer->setStep(['fill'=>['id' =>'selector', 'with'=>'phpunit']]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Fill element id[selector] with value phpunit', (string) $transformer);
    }

    public function testImpltmTransformerAndGetProperties()
    {
        $this->expectException(DataValueMatchedNoPattern::class);

        $transformer = new ImpltmTransformer();

        $transformer->setStep(['impltm'=>'60']);
       
        $this->assertTrue($transformer->validate());
        $this->assertSame('Set implicit timeout 60 s', (string) $transformer);

        $transformer->setStep(['impltm'=>'foo']);
        $this->assertTrue($transformer->validate());
    }

    public function testIsClickableTransformerAndGetProperties()
    {
        $transformer = new IsClickableTransformer();

        $transformer->setStep(['isClickable'=>['css' =>'selector']]);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Checking if css[selector] is clickable', (string) $transformer);
    }

    public function testIsNotSelectedTransformerAndGetProperties()
    {
        $transformer = new IsNotSelectedTransformer();

        $transformer->setStep(['isNotSelected'=>['css' =>'selector']]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Checking if element located by css[selector] is not selected', (string) $transformer);
    }

    public function testIsSelectedTransformerAndGetProperties()
    {
        $transformer = new IsSelectedTransformer();

        $transformer->setStep(['isSelected'=>['xpath' =>'selector']]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Checking if element located by xpath[selector] is selected', (string) $transformer);
    }

    public function testNumberOfWindowsTransformerAndGetProperties()
    {
        $transformer = new NumberOfWindowsTransformer();

        $transformer->setStep(['numberOfWindows'=>'2']);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Wait until number of windows is 2', (string) $transformer);
    }

    public function testPresenceOfAllTransformerAndGetProperties()
    {
        $transformer = new PresenceOfAllTransformer();

        $transformer->setStep(['presenceOfAll'=>['tag' =>'selector']]);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Checking presence of all elements located by tag[selector]', (string) $transformer);
    }

    public function testPresenceOfTransformerAndGetProperties()
    {
        $transformer = new PresenceOfTransformer();

        $transformer->setStep(['presenceOf'=>['pltext' =>'selector']]);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Checking presence of element located by pltext[selector]', (string) $transformer);
    }

    public function testReloadTransformerAndGetProperties()
    {
        $transformer = new ReloadTransformer();

        $transformer->setStep(['reload'=>'page']);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Reload the page', (string) $transformer);
    }

    public function testTextContainsTransformerAndGetProperties()
    {
        $transformer = new TextContainsTransformer();

        $transformer->setStep(['textContains'=>['value'=>'val', "class" =>'selector']]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Checking if text of element class[selector] contains val', (string) $transformer);
    }

    public function testTextIsTransformerAndGetProperties()
    {
        $transformer = new TextIsTransformer();

        $transformer->setStep(['textIs'=>['value'=>'val', 'linktext' =>'selector']]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Checking if text of element linktext[selector] is val', (string) $transformer);
    }

    public function testTextMatchesTransformerAndGetProperties()
    {
        $transformer = new TextMatchesTransformer();

        $transformer->setStep(['textMatches'=>['regexp'=>'val', 'name' =>'selector']]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Checking if text of element name[selector] matches val', (string) $transformer);
    }

    public function testTitleContainsTransformerAndGetProperties()
    {
        $transformer = new TitleContainsTransformer();

        $transformer->setStep(['titleContains'=>'page']);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Wait until title contains page', (string) $transformer);
    }

    public function testTitleIsTransformerAndGetProperties()
    {
        $transformer = new TitleIsTransformer();

        $transformer->setStep(['titleIs'=>'page']);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Wait until title is page', (string) $transformer);
    }

    public function testTitleMatchesTransformerAndGetProperties()
    {
        $transformer = new TitleMatchesTransformer();

        $transformer->setStep(['titleMatches'=>'page']);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Wait until title matches regexp page', (string) $transformer);
    }

    public function testUrlContainsTransformerAndGetProperties()
    {
        $transformer = new UrlContainsTransformer();

        $transformer->setStep(['urlContains'=>'page']);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Wait until url contains page', (string) $transformer);
    }

    public function testUrlIsTransformerAndGetProperties()
    {
        $transformer = new UrlIsTransformer();

        $transformer->setStep(['urlIs'=>'page']);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Wait until url is page', (string) $transformer);
    }

    public function testUrlMatchesTransformerAndGetProperties()
    {
        $transformer = new UrlMatchesTransformer();

        $transformer->setStep(['urlMatches'=>'page']);

        $this->assertTrue($transformer->validate());
        $this->assertSame('Wait until url matches regexp page', (string) $transformer);
    }

    public function testVilibilityOfAnyTransformerAndGetProperties()
    {
        $transformer = new VisibilityOfAnyTransformer();

        $transformer->setStep(['visibilityOfAny'=>['pltext'=>'selector']]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Checking visibility of any elements located by pltext[selector]', (string) $transformer);
    }

    public function testVilibilityOfTransformerAndGetProperties()
    {
        $transformer = new VisibilityOfTransformer();
        $transformer->setStep(['visibilityOf'=>['id'=>'selector']]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Checking visibility of element located by id[selector]', (string) $transformer);
    }

    public function testGetTransformerAndGetProperties()
    {
        $transformer = new GetTransformer();

        $validPattern = [
            'get'=> [
                'id' => 'selector',
                'what'=>'attribute',
                'attribute'=>'attr',
                'varname'=>'var'
            ]
        ];

        $transformer->setStep($validPattern);
        $this->assertTrue($transformer->validate());
        $this->assertSame('Get attribute for element id[selector] with value attr', (string) $transformer);

        $validPattern = [
            'get'=> [
                'class' => 'selector',
                'what'=>'text',
                'varname'=>'var'
            ]
        ];

        $transformer->setStep($validPattern);
        $this->assertTrue($transformer->validate());
        $this->assertSame('Get text for element class[selector] ', (string) $transformer);
    }

    public function testFrameTransformerAndGetProperties()
    {
        $transformer = new FrameTransformer();

        $validPattern = [
            'frame'=> [
                'pltext' => 'selector'
            ]
        ];

        $transformer->setStep($validPattern);
        $this->assertTrue($transformer->validate());
        $this->assertSame('Frame to string', (string) $transformer);

        $validPattern = [
            'frame'=> [
                'tag' => 'selector',
                'index'=>'0'
            ]
        ];

        $transformer->setStep($validPattern);
        $this->assertTrue($transformer->validate());
        $this->assertSame('Frame to string', (string) $transformer);

        $validPattern = [
            'frame'=> [
                'id' => 'selector',
                'index'=>'1',
                'default' => 'content'
            ]
        ];

        $transformer->setStep($validPattern);
        $this->assertTrue($transformer->validate());
        $this->assertSame('Frame to string', (string) $transformer);
    }

    public function testResizeTransformerAndGetProperties()
    {
        $transformer = new ResizeTransformer();

        $validPattern = [
            'resize'=> [
                'type' => 'maximize'
            ]
        ];

        $transformer->setStep($validPattern);
        $this->assertTrue($transformer->validate());
        $this->assertSame('Resize the page [maximize] ', (string) $transformer);

        $validPattern = [
            'resize'=> [
                'type'=>'size',
                'size'=>[
                    'width'=>'500',
                    'height'=>'600'
                ]
            ]
        ];

        $transformer->setStep($validPattern);
        $this->assertTrue($transformer->validate());
        $this->assertSame('Resize the page [size] with 500px per 600px', (string) $transformer);
    }

    public function testScreenshotTransformerAndGetProperties()
    {
        $transformer = new ScreenshotTransformer();

        $validPattern = [
            'screenshot'=> [
                'type' => 'all',
                'sname'=>'myScreenshot'
            ]
        ];
        
        $transformer->setStep($validPattern);
        $this->assertTrue($transformer->validate());
        $this->assertSame('Cheeeeeese [myScreenshot]', (string) $transformer);

        $validPattern = [
            'screenshot'=> [
                'type' => 'element',
                'sname'=>'myScreenshot',
                'id' => 'selector'
            ]
        ];

        $transformer->setStep($validPattern);
        $this->assertTrue($transformer->validate());
        $this->assertSame('Cheeeeeese [myScreenshot]', (string) $transformer);
    }

    public function testLoopTransformerAndGetProperties()
    {
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
        $this->assertSame('> Loop ends after 5 times', (string) $transformer);
    }

    public function testConditionTransformer()
    {
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

        $this->assertSame('Condition assessed 5 == 6 as false', (string) $transformer);
    }

    public function testExitTransformer()
    {
        $transformer = new ExitTransformer();
        $transformer->setStep(['exit'=>'For the example']);

        $this->assertTrue($transformer->validate());
        $this->assertSame('EXIT', (string) $transformer);
    }

    public function testUseTransformer()
    {
        $transformer = new UseTransformer();
        $transformer->setStep(['use'=>'main.name']);
        $this->assertTrue($transformer->validate());

        $transformer->setStep(['use'=>'sub.name']);
        $this->assertTrue($transformer->validate());
    }

    /**
     * This test the validate/__toString() and the detection of ';' in Js script
     */
    public function testScriptTransformer()
    {
        $transformer = new ScriptTransformer();
        
        $transformer->setStep(['script'=>'return jQuery.active === 0']);

        if (!substr(trim($transformer->getStep()['script']), -1) == ';') {
            $transformer->setStep(['script'=>$transformer->getStep()['script'].';']);
        }

        $this->assertTrue($transformer->validate());
        $this->assertSame('Executing script return jQuery.active === 0;', (string) $transformer);

        $transformer->setStep(['script'=>'return jQuery.active === 0;']);

        if (!substr(trim($transformer->getStep()['script']), -1) == ';') {
            $transformer->setStep(['script'=>$transformer->getStep()['script'].';']);
        }

        $this->assertTrue($transformer->validate());
        $this->assertSame('Executing script return jQuery.active === 0;', (string) $transformer);
    }

    public function testWajaxTransformer()
    {
        $transformer = new WajaxTransformer();
        
        $transformer->setStep(['wajax'=>'return jQuery.active === 0']);
        
        if (!substr(trim($transformer->getStep()['wajax']), -1) == ';') {
            $transformer->setStep(['wajax'=>$transformer->getStep()['wajax'].';']);
        }
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Waiting with script return jQuery.active === 0;', (string) $transformer);

        $transformer->setStep(['wajax'=>'return jQuery.active === 0;']);

        if (!substr(trim($transformer->getStep()['wajax']), -1) == ';') {
            $transformer->setStep(['wajax'=>$transformer->getStep()['wajax'].';']);
        }

        $this->assertTrue($transformer->validate());
        $this->assertSame('Waiting with script return jQuery.active === 0;', (string) $transformer);
    }

    public function testSubmitTransformerAndGetProperties()
    {
        $transformer = new SubmitTransformer();

        $transformer->setStep(['submit'=>['pltext'=>'selector', 'text'=>'jugid']]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Submit jugid for element pltext[selector]', (string) $transformer);
    }
    
    public function testKeyboardTransformerAndGetProperties()
    {
        $transformer = new KeyboardTransformer();
        $transformer->setStep(['keyboard' =>
            [
                'event'=>'pressKey',
                'keys'=> ['key1'=> 't', 'key2'=>'e']
            ]
        ]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Keyboard use on pressKey', (string) $transformer);
    }

    public function testMouseTransformerAndGetProperties()
    {
        $transformer = new MouseTransformer();
        $transformer->setStep(['mouse' =>
            [
                'id'=>'selector',
                'event'=> 'click'
            ]
        ]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Click use on id[selector]', (string) $transformer);
    }

    public function testCloseTransformerAndGetProperties()
    {
        $transformer = new CloseTransformer();
        $transformer->setStep(['close' => 'window']);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Close window', (string) $transformer);
    }

    public function testPrintTransformerAndGetProperties()
    {
        $transformer = new PrintTransformer();
        $transformer->setStep(['print' => 'my text']);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Text printed', (string) $transformer);
    }

    public function testFormTransformerAndGetProperties()
    {
        $transformer = new FormTransformer();
        $transformer->setStep(['form' =>
            [
                'id'=>'selector',
                'type'=> 'submit'
            ]
        ]);
        
        $this->assertTrue($transformer->validate());
        $this->assertSame('Form id[selector] submit', (string) $transformer);
    }
}
