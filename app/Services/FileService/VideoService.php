<?php

namespace App\Services\FileService;


use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use App\Repositories\HandBook\FileRepository;
use Illuminate\Support\Facades\Storage;
class VideoService
{

    public function __construct(protected FileRepository $fileRepository)
    {
    }


    /**
     * @param int $id
     * @param string $type
     * @param UploadedFile|null $videoFile
     * @return void
     */
    public function create(int $id,string $type, UploadedFile|null $videoFile): void
    {

        if ($videoFile == null) {
            return;
        }

        $nameFile = $id . '-' .$videoFile->getClientOriginalName();
        $filePath = 'public/img/'.$type;
        $videoFile->storeAs($filePath, $nameFile);
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


    public function update(int $id,string $type, UploadedFile|null $videoFile):void {
        $file = $this->fileRepository->getById($id,$type);
        if (!empty($file)) {
            $filePath = 'img/'.$type.'/'.$file->name;
            $this->fileRepository->delete($id,$type);
            Storage::disk('public')->delete($filePath);
        }


        $this->create($id,$type, $videoFile);

    }


}