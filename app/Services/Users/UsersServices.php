<?php

namespace App\Services\Users;


use Carbon\Carbon;
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

class UsersServices
{

    public function __construct(
        protected UserRepository $userRepository,
        protected RoleRepository $roleRepository,
        protected TokenServices $tokenServices,
        protected CallService $callService,
        protected UserTicketSoftService $userTicketSoftService,
        protected CheckCodesRepository $checkCodesRepository,
        protected GuestRepository $guestRepository
    )
    {
    }

    /**
     * @param string $login
     * @param string $password
     * @return array
     */

    public function login(string $login,string $password):array
    {
        $user = $this->userRepository->getByLoginAndPassword($login,$password);
        $role = $this->roleRepository->getById($user->role_id);
        $token = $this->tokenServices->create($role,$user->id);

        return ['id' => $user->id,'token' => $token];
    }

    /**
     * @param string $phone
     * @return bool
     */
    public function getAuthPhone(string $phone):bool {
        $phone = $this->formatPhone($phone);
        $code = $this->callService->run($phone->withEight);
        if ((empty($code)) || (getType($code) == 'array')) {
            return false;
        }
        $this->checkCodesRepository->createOrUpdate($phone->withSeven, $code);
        $guest = $this->checkExportUser($phone);
        if (!$guest) {
            $this->userTicketSoftService->getAddress($phone->withSeven);
            $guest = $this->checkExportUser($phone);
        }
        $guestCurrent = $this->userRepository->getUserByPhone($phone->withSeven);
        if (!$guestCurrent) {
            $this->guestRepository->create($guestCurrent);
        }
        return true;

    }

    /**
     * @param string $code
     * @return bool|array
     */
    public function checkCode(string $code):bool | array {
        $check = $this->checkCodesRepository->getCode($code);
        if ($check) {
            $guest = $this->userRepository->getUserByPhone($check->phone);
            $role = $this->roleRepository->getById($guest->role_id);
            $token = $this->tokenServices->create($role,$guest);
            return ['id' => $guest->id,'token' => $token];
        }

        return false;
    }


    public function updateProfile(array $optionUser):bool {
        $user = \Auth::user();

        return $user->update(
          [
              'name' => !empty($optionUser['fio']) && $user->name != $optionUser['fio'] ? $optionUser['fio'] : $user->name,
              'email' => !empty($optionUser['email']) && $user->email != $optionUser['email']  ? $optionUser['email'] : $user->email,
              'gender' => !empty($optionUser['gender']) && $user->gender != $optionUser['gender'] ? $optionUser['gender'] : $user->gender,
              'birthday' => !empty($optionUser['datebirth']) ? Carbon::parse($optionUser['datebirth'])->format('Y-m-d') : $user->birthday,

          ]  
        );
        
    }
    /**
     * @param FormatPhoneDto $phone
     * @return GuestExportDto|bool
     * достаем данные по гостю из ticketsofta
     */
    protected function checkExportUser(FormatPhoneDto $phone):GuestExportDto | bool {
        $user = $this->userTicketSoftService->getAddress($phone->toArray());
        if ($user) {
            $address = $this->userTicketSoftService->getUserFromCustomer($user->id);
            $card = $this->userTicketSoftService->getCard($address->id);
            return new GuestExportDto(
                $address->lastName,
                $user->email,
                $phone->withSeven,
                $address->id,
                0,
                $address->birthDate
            );
        }
        return false;
    }


    /**
     * @return PrivilegeDto
     */
    public function getPrivilege():PrivilegeDto {
        return new PrivilegeDto(
          5,
          0
        );
    }


    /**
     * @param string $phone
     * @return FormatPhoneDto|false
     */
    protected function formatPhone(string $phone):FormatPhoneDto | bool
    {
        if ((str_starts_with($phone, '8')) && (strlen($phone) == 11)) {
            $phone_search1 = '+7' . substr($phone, 1);
            $phone_search2 = '8' . substr($phone, 1);
            $phone_search3 = '7' . substr($phone, 1);
        } elseif ((str_starts_with($phone, '7')) && (strlen($phone) == 11)) {
            $phone_search1 = '+7' . substr($phone, 1);
            $phone_search2 = '8' . substr($phone, 1);
            $phone_search3 = '7' . substr($phone, 1);
        } elseif ((str_starts_with($phone, '+7')) && (strlen($phone) == 12)) {
            $phone_search1 = '+7' . substr($phone, 2);
            $phone_search2 = '8' . substr($phone, 2);
            $phone_search3 = '7' . substr($phone, 2);
        } else {
            return false;
        }
        return new FormatPhoneDto($phone_search1,$phone_search2,$phone_search3);
    }
}