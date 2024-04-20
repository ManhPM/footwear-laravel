<?php

namespace App\Traits;

use Image;
use Illuminate\Support\Facades\Storage;

trait HandleImageTrait
{
    /**
     * @param $request
     * @return mixed
     */
    public function verify($request): mixed
    {
        return $request->has('image');
    }

    /**
     * @param $request
     * @return string|void
     */

    protected string $path = 'upload/';

    public function saveImage($request)
    {
        if ($this->verify($request)) {
            $file = $request->file('image');
            $name = time() . $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            Image::make($file->getRealPath())->resize(468, 249)->save($this->path . $name);
            return $name;
        }
    }

    /**
     * @paramfilesystems $request
     * @param $request
     * @param $currentImage
     * @return mixed|string|null
     */
    public function updateImage($request, $currentImage): mixed
    {
        if ($this->verify($request)) {
            $this->deleteImage($currentImage);
            return $this->saveImage($request);
        }
        return $currentImage;
    }

    /**
     * @param $imageName
     * @return void
     */
    public function deleteImage($imageName): void
    {
        if ($imageName && file_exists($this->path . $imageName)) {
            Storage::delete($this->path . $imageName);
        }
    }
}
