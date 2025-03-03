<?php

namespace App\Services\FileService;


use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use App\Repositories\HandBook\FileRepository;
use Illuminate\Support\Facades\Storage;
class ImgService
{

    public function __construct(protected FileRepository $fileRepository)
    {
    }


    public function create(int $id,string $type, UploadedFile|null $imgFile): void
    {

        if ($imgFile == null) {


            return;
        }
        $nameFile = $id . '-' . rand(0, 9) . rand(0, 9) . rand(0, 9) . '.jpg';
        $filePath = 'img/'.$type.'/' . $nameFile;
        $imgFile = (string) \Image::make($imgFile)->encode('jpg', 75);
        Storage::disk('public')->put($filePath, $imgFile);
        $this->fileRepository->create($nameFile,$type,$id);
    }


    /**
     * @param int $id
     * @param string $type
     * @return mixed
     */
    public function get(int $id,string $type) {
        return $this->fileRepository->getById($id,$type);
    }


    public function update(int $id,string $type, UploadedFile|null $imgFile):void {
        $file = $this->fileRepository->getById($id,$type);
        if (!empty($file)) {
            $filePath = 'img/'.$type.'/'.$file->name;
            $this->fileRepository->delete($id,$type);
            Storage::disk('public')->delete($filePath);
        }


        $this->create($id,$type, $imgFile);

    }


}