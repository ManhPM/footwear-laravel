<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $user;
    protected $role;

    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    public function index()
    {
        return new UserResource(User::latest('id')->paginate(5));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $dataCreate = $request->all();
        $dataCreate['password'] = Hash::make($request->password);
        $dataCreate['guard_name'] = 'web';
        if (isset($dataCreate['image'])) {
            $dataCreate['image'] = $this->user->saveImage($request);
        }
        $user = $this->user->create($dataCreate);
        if (isset($dataCreate['image'])) {
            $user->images()->create(['url' => $dataCreate['image']]);
        }
        $user->roles()->attach($dataCreate['role_ids'] ?? []);

        return $this->sentSuccessResponse('', 'Tạo mới thành công', Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id)->load('roles');
        $dataUpdate = $request->except('password');
        if ($request->password) {
            $dataUpdate['password'] = Hash::make($request->password);
        }
        $user->update($dataUpdate);
        if (isset($dataUpdate['image'])) {
            $dataUpdate['image'] = $this->user->saveImage($request);
            $user->images()->delete();
            $user->images()->create(['url' => $dataUpdate['image']]);
        }
        $user->roles()->sync($dataUpdate['role_ids'] ?? []);

        return $this->sentSuccessResponse('', 'Cập nhật thành công', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->images()->delete();
        $imageName = $user->images->count() > 0 ? $user->images->first()->url : '';
        $this->user->deleteImage($imageName);
        $user->delete();
        return $this->sentSuccessResponse('', 'Xoá thành công', Response::HTTP_OK);
    }
}
