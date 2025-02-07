<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\User;
use App\Request\Login\RegisterRequest;
use Hyperf\Di\Annotation\Inject;
use Psr\EventDispatcher\EventDispatcherInterface;
use App\Event\UserRegistered;
use App\Exception\BaseException;
use App\Request\Login\LoginRequest;
use App\Resource\Common\BaseResource;
use App\Resource\Login\LoginResource;
use App\Tools\JwtTool;

class LoginController
{
    #[Inject]
    private EventDispatcherInterface $eventDispatcher;

    /**
     * @var JwtTool
     */
    protected $jwtTool;

    public function __construct(JwtTool $jwtTool)
    {
        $this->jwtTool = $jwtTool;
    }

    public function register(RegisterRequest $request)
    {
        $input = $request->validated();

        $input['password'] = password_hash($input['password'], PASSWORD_BCRYPT);
        $user = User::create($input);

        $this->eventDispatcher->dispatch(new UserRegistered($user));

        return new BaseResource();
    }

    public function login(LoginRequest $request)
    {
        $input = $request->validated();

        $user = User::where('mobile', $input['mobile'])->first();

        if (!$user) {
            throw new BaseException(10001);
        }

        if (!password_verify($input['password'], $user->password)) {
            throw new BaseException(10002);
        }

        $token = $this->jwtTool->generateToken($user);

        $user->token = $token;

        return new LoginResource($user);
    }
}