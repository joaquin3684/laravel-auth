<?php


namespace Hitocean\LaravelAuth\Tests\Actions\User\Auth\ResetPassword;


use Hitocean\LaravelAuth\Auth\Actions\ForgotPasswordAction;
use Hitocean\LaravelAuth\Auth\FormRequests\ForgotPasswordFormRequest;
use Tests\TestCase;

class ForgotPasswordControllerTest extends TestCase
{

    /** @test */
    public function assert_route_status_200()
    {
        $email = 'joaquin@vadiun.com';
        ForgotPasswordAction::partialMock()->shouldReceive('handle')->with($email);
        $this->post('api/forgot-password', ['email' => $email])
            ->assertStatus(200);
    }

    /** @test */
    public function assert_controller_uses_correct_form_request()
    {
        $this->assertActionUsesFormRequest(ForgotPasswordAction::class, ForgotPasswordFormRequest::class);
    }

    /** @test */
    public function assert_form_request_rules_are_correct()
    {
        $form = new ForgotPasswordFormRequest();
        $this->assertEquals([
            'email' => 'required|email'
        ], $form->rules());
    }

    /** @test */
    public function assert_form_request_authorization_is_true()
    {
        $form = new ForgotPasswordFormRequest();
        $this->assertTrue($form->authorize());
    }
}
