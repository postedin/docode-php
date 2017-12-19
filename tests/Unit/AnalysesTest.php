<?php

namespace Postedin\Docode\Tests\Unit;

use Postedin\Docode;
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
            $this->assertInstanceOf(Docode\Analysis::class, $analyses[$i]);

            foreach ($data as $param => $value) {
                if ($param == 'n_words') {
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

        $this->assertInstanceOf(Docode\Analysis::class, $analysis);

        foreach ($this->analysesData[0] as $param => $value) {
            if ($param == 'n_words') {
                $param = 'wordCount';
            }

            $this->assertEquals($value, $analysis->{camel_case($param)});
        }

        $this->assertEquals($this->analysesData[0], $analysis->getRawData());

        try {
            $analysis->doesntExist;
        } catch (\Throwable $e) {
            return;
        }

        $this->fail('Expected propert doesn\'t exist error not being thrown.');
    }

    public function test_create_analysis()
    {
        $api = $this->docodeApi($this->mockResponseHandler([
            $this->mockResponse(201, $this->analysesData[0]),
        ]));

        $analysis = $api->createAnalysis('test-example.txt', 'content', 'http://postedin.test/callback');

        $this->assertInstanceOf(Docode\Analysis::class, $analysis);

        foreach ($this->analysesData[0] as $param => $value) {
            if ($param == 'n_words') {
                $param = 'wordCount';
            }

            $this->assertEquals($value, $analysis->{camel_case($param)});
        }
    }

    public function test_create_analysis_and_start()
    {
        $api = $this->docodeApi($this->mockResponseHandler([
            $this->mockResponse(201, $this->analysesData[0]),
            $this->mockResponse(201, $this->analysesData[0]),
        ]));

        $analysis = $api->createAnalysis('test-example.txt', 'content')->analyseWeb();

        $this->assertInstanceOf(Docode\Analysis::class, $analysis);

        foreach ($this->analysesData[0] as $param => $value) {
            if ($param == 'n_words') {
                $param = 'wordCount';
            }

            $this->assertEquals($value, $analysis->{camel_case($param)});
        }
    }

    public function test_full_analysis_response_transformed()
    {
        $data = json_decode(file_get_contents(__DIR__.'/fake-api/lorem-suspect.json'), true);

        $api = $this->docodeApi($this->mockResponseHandler([
            $this->mockResponse(200, $data),
        ]));

        $analysis = $api->getAnalysis('found one');

        $this->assertInstanceOf(Docode\Analysis::class, $analysis);

        foreach ($data as $param => $value) {
            if ($param == 'n_words') {
                $param = 'wordCount';
            }

            if ($param == 'result') {
                $this->assertInstanceOf(Docode\Result::class, $analysis->result);
                $this->assertEquals($value['suspectContainment'], $analysis->result->suspectContainment);
                $this->assertEquals($value['nWords'], $analysis->result->wordCount);

                $this->assertInstanceOf(Docode\Document::class, $analysis->result->suspect);
                $this->assertEquals($value['suspect']['title'], $analysis->result->suspect->title);
                $this->assertEquals($value['suspect']['text'], $analysis->result->suspect->text);

                $this->assertInternalType('array', $analysis->result->sources);

                foreach ($value['sources'] as $k => $source) {
                    $this->assertInstanceOf(Docode\Source::class, $analysis->result->sources[$k]);
                    $this->assertEquals($source['suspectContainment'], $analysis->result->sources[$k]->suspectContainment);
                    $this->assertEquals($source['url'], $analysis->result->sources[$k]->url);

                    $this->assertInstanceOf(Docode\Document::class, $analysis->result->sources[$k]->document);
                    $this->assertEquals($source['document']['title'], $analysis->result->sources[$k]->document->title);
                    $this->assertEquals($source['document']['text'], $analysis->result->sources[$k]->document->text);

                    $this->assertInternalType('array', $analysis->result->sources[$k]->matches);

                    foreach ($source['matches'] as $j => $match) {
                        $this->assertInstanceOf(Docode\Match::class, $analysis->result->sources[$k]->matches[$j]);

                        $this->assertEquals($match['suspectSpan']['start'], $analysis->result->sources[$k]->matches[$j]->suspectSpan->start);
                        $this->assertEquals($match['suspectSpan']['end'], $analysis->result->sources[$k]->matches[$j]->suspectSpan->end);
                        $this->assertEquals($match['sourceSpan']['start'], $analysis->result->sources[$k]->matches[$j]->sourceSpan->start);
                        $this->assertEquals($match['sourceSpan']['end'], $analysis->result->sources[$k]->matches[$j]->sourceSpan->end);
                    }
                }

                continue;
            }

            $this->assertEquals($value, $analysis->{camel_case($param)}, $param);
        }
    }
}
