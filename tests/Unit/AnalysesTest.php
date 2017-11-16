<?php

namespace Postedin\Docode\Tests\Unit;

use Postedin\Docode\Analysis;
use Postedin\Docode\Exceptions\NotFoundException;
use Postedin\Docode\Tests\UnitTest;

class AnalysesTest extends UnitTest
{
    private $analysesData = [
        [
            'id' => '7c24cc94-225d-4ae1-8950-da4abfca6a27',
            'user' => 'Robbo',
            'repository' => null,
            'n_words' => 1,
            'suspect' => 'http://docode.cl/media/test.txt',
            'suspect_filename' => 'test.txt',
            'status' => 1,
            'result' => null,
            'request_date' => '2017-11-16T17:10:15.772975Z',
            'response_date' => null,
            'callback_url' => null,
            'public_url' => null
        ],
        [
            'id' => '7c54cc94-225d-4ae1-8950-dg4abfch6a27',
            'user' => 'Robbo',
            'repository' => null,
            'n_words' => 1,
            'suspect' => 'http://docode.cl/media/test.pdf',
            'suspect_filename' => 'test.pdf',
            'status' => 1,
            'result' => null,
            'request_date' => '2017-12-16T17:10:15.772975Z',
            'response_date' => null,
            'callback_url' => null,
            'public_url' => null
        ],
    ];

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
