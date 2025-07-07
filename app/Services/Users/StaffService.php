<?php

namespace App\Services\Users;


use App\Dto\User\UpdateStaffDto;
use App\Services\Paginator\PaginatorService;
use Carbon\Carbon;
use App\Models\User;
use App\Dto\User\PrivilegeDto;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\RoleRepository;
use App\Services\Auth\TokenServices;
use App\Dto\User\FormatPhoneDto;
use App\Services\Auth\CallService;
use App\Services\Export\UserTicketSoftService;
use App\Dto\User\GuestExportDto;
use App\Repositories\Users\CheckCodesRepository;
use App\Repositories\Users\GuestRepository;
use App\Repositories\Users\UserPrivilegeRepository;

class StaffService
{

    public function __construct(
        protected UserRepository          $userRepository,
        protected RoleRepository          $roleRepository,
        protected TokenServices           $tokenServices,
        protected CallService             $callService,
        protected UserTicketSoftService   $userTicketSoftService,
        protected CheckCodesRepository    $checkCodesRepository,
        protected PaginatorService        $paginatorService,
        protected UserPrivilegeRepository $userPrivilegeRepository,
        protected GuestRepository         $guestRepository
    )
    {
    }

    /**
     * @param int $page
     * @param string|null $search
     * @return object
     */
    public function list(int $page, string|null $search): object
    {
        $users = $this->userRepository->getStaffBySearch($search);
        return $this->paginatorService->toPagination($users, $page);
    }


    /**
     * @param int $id
     * @param UpdateStaffDto $dto
     * @return bool
     */
    public function update(int $id, UpdateStaffDto $dto): bool {
        $user = $this->userRepository->getById($id);
        return $user->update($dto->toArray());

    }

    /**
     * @param UpdateStaffDto $dto
     * @return mixed
     */
    public function create(UpdateStaffDto $dto)  {
        return $this->userRepository->createStaff($dto);
    }
}