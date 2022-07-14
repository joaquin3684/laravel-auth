<?php


namespace Hitocean\LaravelAuth\Tests\Actions\User\Auth\ResetPassword;


use Hitocean\LaravelAuth\Auth\Actions\DTOS\ResetPasswordDTO;
use Hitocean\LaravelAuth\Auth\Actions\ResetPasswordAction;
use Hitocean\LaravelAuth\Auth\FormRequests\ResetPasswordFormRequest;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase {
    /** @test */
    public function successfull_reset_password()
    {
        $data = [
            'email'                 => 'joaquin@vadiun.com',
            'password'              => 'pepemujica',
            'password_confirmation' => 'pepemujica',
            'token'                 => 'alkdsjfñasdkjlfñakjga'
        ];
        $dto  = new ResetPasswordDTO($data);

        ResetPasswordAction::partialMock()->shouldReceive('handle')->withArgs(fn ($arg) => $arg == $dto);

        $this->post('api/reset-password', $data)
             ->assertStatus(200);
    }

    /** @test */
    public function assert_controller_uses_correct_form_request()
    {
        $this->assertActionUsesFormRequest(ResetPasswordAction::class, ResetPasswordFormRequest::class);
    }

    /** @test */
    public function assert_form_request_rules_are_correct()
    {
        $form = new ResetPasswordFormRequest();
        $this->assertEquals(
            [
                'token'                 => 'required',
                'password_confirmation' => 'required|string',
                'password'              => 'required|confirmed',
                'email'                 => 'required|email',
            ],
            $form->rules()
        );
    }

    /** @test */
    public function assert_form_request_authorization_is_true()
    {
        $form = new ResetPasswordFormRequest();
        $this->assertTrue($form->authorize());
    }
}
