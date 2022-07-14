<?php

namespace Hitocean\LaravelAuth\Tests\Actions\User\Auth\ResendEmailVerification;

use Hitocean\LaravelAuth\Auth\Actions\ResendVerificationEmailAction;
use Tests\TestCase;

class ResendEmailVerificationControllerTest extends TestCase
{
    /** @test */
    public function resend_email()
    {
        ResendVerificationEmailAction::partialMock()->shouldReceive('handle')->with("joaquinmazoud@gmail.com");

        $response = $this->post('api/verify/resend', ['email' => "joaquinmazoud@gmail.com"]);
        $response->assertStatus(200);
    }
}
