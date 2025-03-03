<?php

namespace App\Services\HandBook;


use Carbon\Carbon;

use App\Repositories\HandBook\BannerRepository;
use App\Services\Paginator\PaginatorService;
use Illuminate\Support\Collection;
use App\Services\FileService\ImgService;
use Illuminate\Http\UploadedFile;

class BannerServices
{

    public function __construct(
        protected BannerRepository $bannerRepository,
        protected PaginatorService $paginatorService,
        protected ImgService $imgService,
    )
    {
    }


    /**
     * @param int $page
     * @return object
     */

    public function list(int $page):object {

        $halls = $this->bannerRepository->getAll();

        return $this->paginatorService->toPagination($halls,$page);
    }


    public function create(string $name,UploadedFile|null $imgFile,$dateStart,$dateStop) {
        $banner = $this->bannerRepository->createBanner($name,Carbon::parse($dateStart)->format('Y-m-d'),Carbon::parse($dateStop)->format('Y-m-d'));

        $this->imgService->create($banner->id,'banner',$imgFile);
        
    }


    public function get(int $id) {
        $banner = $this->bannerRepository->getById($id);
        $banner->img = $this->imgService->get($banner->id,'banner');
        $banner->fileUrl = config('services.app_url').'/img/'.$banner->img->type.'/'.$banner->img->name;
        return $banner;
        
        
    }
    
    public function getBanners() {
         $banners = $this->bannerRepository->getAll()->where('start','<',Carbon::now())->where('end','>',Carbon::now());
         return $banners->map(function ($banner) {
            $img = $this->imgService->get($banner->id,'banner');
            if (empty($img)) {
                $img = null;
            }
            else {
                $img = config('services.app_url').'/img/'.$img->type.'/'.$img->name;
            }
            return ['id' => $banner->id, 'img' => $img];
        })->where("img","!=",NULL);
    }

    /**
     * @param int $id
     * @param string $name
     * @param UploadedFile|null $imgFile
     * @param $dateStart
     * @param $dateStop
     * @return bool
     */
    public function update(int $id,string $name,UploadedFile|null $imgFile,$dateStart,$dateStop):bool {
        $banner = $this->bannerRepository->getById($id);
        $this->bannerRepository->updateById($id,$name,Carbon::parse($dateStart)->format('Y-m-d'),Carbon::parse($dateStop)->format('Y-m-d'));
        if ($imgFile) {
            $banner->img = $this->imgService->update($banner->id,'banner',$imgFile);
        }
        return false;
    }
}