<?php

namespace Postedin\Docode\Tests\Unit;

use Postedin\Docode\Profile;
use Postedin\Docode\Tests\UnitTest;

class ProfileTest extends UnitTest
{
    public function test_get_profile()
    {
        $profileData = [
            'max_words' => 1000,
            'max_files' => 100,
            'words' => 234,
            'files' => 65,
            'phone' => '+56982410458',
            'avatar' => null,
            'regime' => 'DAILY',
        ];

        $api = $this->docodeApi($this->mockResponseHandler([
            $this->mockResponse(200, $profileData),
        ]));

        $profile = $api->getProfile();

        $this->assertInstanceOf(Profile::class, $profile);

        foreach ($profileData as $param => $value) {
            $this->assertEquals($profile->{camel_case($param)}, $value);
        }
    }
}
