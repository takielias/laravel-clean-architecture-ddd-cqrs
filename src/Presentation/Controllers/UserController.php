<?php

declare(strict_types=1);

namespace Presentation\Controllers;

use Application\Bus\Contracts\CommandBusContract;
use Application\Bus\Contracts\QueryBusContract;
use Application\User\Commands\CreateUserCommand;
use Application\User\Contracts\UserServiceContract;
use Application\User\Data\UserData;
use Application\User\Data\UsersListData;
use Application\User\Queries\GetUserByEmailQuery;
use Illuminate\Http\Request;
use Presentation\Requests\UserStoreFormRequest;

final class UserController extends Controller
{
    public function __construct(
        protected CommandBusContract $commandBus,
        protected QueryBusContract $queryBus,
        protected UserServiceContract $userService,
    ) {}

    public function store(UserStoreFormRequest $request): UsersListData
    {

        $userData = UserData::from($request->validated());

        $email = $this->commandBus->dispatch(
            new CreateUserCommand($userData),
        );

        $user = $this->queryBus->ask(
            new GetUserByEmailQuery($email),
        );

        return UsersListData::from($user);
    }

    public function update(int $id, Request $request): UsersListData
    {
        $userData = UserData::from($request->all());

        $this->userService->update($id, $userData);

        $user = $this->queryBus->ask(new GetUserByEmailQuery($request->email));

        return UsersListData::from($user);
    }
}
