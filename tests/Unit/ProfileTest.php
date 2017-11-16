<?php

namespace Postedin\Docode\Tests\Unit;

use Postedin\Docode\Profile;
use Postedin\Docode\Tests\UnitTest;

class ProfileTest extends UnitTest
{
    public function test_get_profile()
    {
        $profileData = [
            'words' => 234,
            'files' => 65,
            'phone' => '+56982410458',
            'username' => 'Dragon',
            'regime' => [
                'words' => 1000,
                'files' => 20,
                'is_expired' => false,
            ],
        ];

        $api = $this->docodeApi($this->mockResponseHandler([
            $this->mockResponse(200, $profileData),
        ]));

        $profile = $api->getProfile();

        $this->assertInstanceOf(Profile::class, $profile);

        foreach ($profileData as $param => $value) {
            if (! is_array($value)) {
                $this->assertEquals($value, $profile->{camel_case($param)});
            }
        }

        $this->assertEquals($profileData['regime']['words'], $profile->regime->words);
        $this->assertEquals($profileData['regime']['files'], $profile->regime->files);
        $this->assertEquals($profileData['regime']['is_expired'], $profile->regime->isExpired);
    }
}
