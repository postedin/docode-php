<?php

namespace Postedin\Docode\Tests\Unit;

use Postedin\Docode\Analysis;
use Postedin\Docode\Exceptions\NotFoundException;
use Postedin\Docode\Tests\UnitTest;

class AnalysesTest extends UnitTest
{
    private $analysesData;

    public function setUp()
    {
        $this->analysesData = $this->parseHjsonFile(__DIR__.'/fake-api/analyses.hjson');

        parent::setUp();
    }

    public function test_get_analyses()
    {
        $api = $this->docodeApi($this->mockResponseHandler([
            $this->mockResponse(200, $this->analysesData),
        ]));

        $analyses = $api->getAnalyses();

        foreach ($this->analysesData as $i => $data) {
            $this->assertInstanceOf(Analysis::class, $analyses[$i]);

            foreach ($data as $param => $value) {
                if ($param === 'n_words') {
                    $param = 'wordCount';
                }

                $this->assertEquals($value, $analyses[$i]->{camel_case($param)});
            }
        }
    }

    public function test_get_incorrect_analysis()
    {
        $api = $this->docodeApi($this->mockResponseHandler([
            $this->mockResponse(404, ['detail' => 'No encontrado']),
        ]));

        $this->expectException(NotFoundException::class);

        $api->getAnalysis('not found one');
    }

    public function test_get_analysis()
    {
        $api = $this->docodeApi($this->mockResponseHandler([
            $this->mockResponse(200, $this->analysesData[0]),
        ]));

        $analysis = $api->getAnalysis('found one');

        $this->assertInstanceOf(Analysis::class, $analysis);

        foreach ($this->analysesData[0] as $param => $value) {
            if ($param === 'n_words') {
                $param = 'wordCount';
            }

            $this->assertEquals($value, $analysis->{camel_case($param)});
        }
    }
}
