<?php

namespace App\Util;

use App\Models\Admin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;
use Spatie\Permission\Models\Permission;
use Str;

class Routes
{
    private $admin;

    /**
     * Routes constructor.
     * @param Admin $admin
     */
    public function __construct(Admin $admin)
    {
        $this->setAdmin($admin);
    }

    /**
     * @return Collection
     * @throws InvalidArgumentException
     */
    public function nav(): Collection
    {
        $permissions = $this->getAdmin()->getAllPermissions()
            ->where('hidden', '=', 0)
            ->where('component', '!=', 'layout/Layout')
            ->where('component', '!=', 'rview')
            ->filter(function (Permission $permission): bool {
                return Str::startsWith($permission->path, '/');
            })
            ->values();
        $noCache = $this->getNoCache($permissions);
        $affix = $this->getAffix($permissions);
        return $permissions->map(function (Permission $permission) use ($noCache, $affix): Permission {
            $permission->no_cache = $noCache->get($permission->name, false);
            $permission->affix = $affix->get($permission->name, false);
            return $permission;
        });
    }

    public function cacheKey(): string
    {
        return "PERMISSION:NOCACHE:{$this->getAdmin()->id}";
    }

    /**
     * @param Collection $permissions
     * @return Collection
     * @throws InvalidArgumentException
     */
    private function getNoCache(Collection $permissions): Collection
    {
        $data = Cache::store('redis')->get($this->cacheKey(), collect());
        if ($data->count() === 0) {
            $data = $permissions->mapWithKeys(function (Permission $permission): array {
                return [$permission->name => false];
            });
            Cache::store('redis')->forever($this->cacheKey(), $data);
        }
        return $data;
    }

    public function affixKey(): string
    {
        return "PERMISSION:AFFIX:{$this->getAdmin()->id}";
    }

    /**
     * @param Collection $permissions
     * @return Collection
     * @throws InvalidArgumentException
     */
    private function getAffix(Collection $permissions): Collection
    {
        $data = Cache::store('redis')->get($this->affixKey(), collect());
        if ($data->count() === 0) {
            $data = $permissions->mapWithKeys(function (Permission $permission): array {
                return [$permission->name => false];
            });
            Cache::store('redis')->forever($this->affixKey(), $data);
        }
        return $data;
    }

    /**
     * @return Collection
     * @throws InvalidArgumentException
     */
    public function routes(): Collection
    {
        if ($this->getAdmin()->status === 1) {
            $permissions = $this->permissionCollect();
            $permissions = $this->sortByDesc($permissions);
            $permissions = $this->formatRoutes($permissions);
            $permissions = $this->formatRoutesChildren($permissions);
        } else {
            $permissions = collect();
        }
        return $permissions->merge([[
            'path' => '*',
            'redirect' => '/404',
            'hidden' => true
        ]]);
    }

    /**
     * @return Collection
     * @throws InvalidArgumentException
     */
    private function permissionCollect(): Collection
    {
        $permissions = $this->getAdmin()->getAllPermissions();
        $permissions = $permissions
            ->where('guard_name', '=', 'admin')
            ->toArray();
        $collect = [];
        $pivots = [];
        foreach ($permissions as $permission) {
            $pivots[$permission['id']][] = $permission['pivot'];
            $permission['pivots'] = $pivots[$permission['id']];
            $collect[$permission['id']] = $permission;
        }
        return collect($collect);
    }

    private function sortByDesc(Collection $permissions): Collection
    {
        $permissions = Arr::arraySort($permissions->toArray(), 'sort');
        return collect($permissions);
    }

    /**
     * @param Collection $permissions
     * @return Collection
     * @throws InvalidArgumentException
     */
    private function formatRoutes(Collection $permissions): Collection
    {
        $noCache = Cache::store('redis')->get($this->cacheKey(), collect());
        $affix = Cache::store('redis')->get($this->affixKey(), collect());
        return $permissions->map(function ($value) use ($noCache, $affix): array {
            $info = [];
            $info['id'] = $value['id'];
            $info['pid'] = $value['pid'];
            $info['path'] = $value['path'];
            $info['component'] = $value['component'];
            $info['name'] = $value['name']; // ???????????????????????????????????????????????????<keep-alive>????????????????????????
            $roles = [];
            if (isset($value['pivots'])) {
                foreach ($value['pivots'] as $pivot) {
                    if (isset($pivot['role_id'])) {
                        $roles[] = $pivot['role_id'];
                    } else {
                        $roles[] = $pivot['model_type'] . '\\' . $pivot['model_id'];
                    }
                }
            }
            $info['meta'] = [
                'title' => $value['name'], // ?????????????????????????????????????????????????????????
                'icon' => $value['icon'], // ????????????????????????????????? svg-class???????????? el-icon-x element-ui ??? icon
                // ?????????????????????????????????????????????????????????
                'roles' => $roles,
                'noCache' => $noCache->get($value['name'], false), // ???????????????true??????????????? <keep-alive> ??????(?????? false)
                'breadcrumb' => true, //  ???????????????false???????????????breadcrumb??????????????????(?????? true)
                'affix' => $affix->get($value['name'], false), // ???????????????true?????????????????????tags-view???(?????? false)
            ];
            // ????????? true ?????????????????????????????????????????? ???401???login???????????????????????????????????????/edit/1
            $info['hidden'] = (bool)$value['hidden'];
            // ????????? noRedirect ??????????????????????????????????????????????????????
            if ($value['component'] === 'layout/Layout' || $value['component'] === 'rview') {
                $info['redirect'] = 'noRedirect';
            }
            return $info;
        });
    }

    private function formatRoutesChildren(Collection $permissions): Collection
    {
        $permissions = Arr::getTree($permissions->toArray());

        foreach ($permissions as $key => $value) {
            if ($value['pid'] === 0 && $value['component'] !== 'layout/Layout' && $value['hidden'] === false) {
                $component = $value['component'];
                $permissions[$key]['component'] = 'layout/Layout';
                $permissions[$key]['redirect'] = 'noRedirect';
                $permissions[$key]['meta']['breadcrumb'] = false;
                $permissions[$key]['children'][] = [
                    'path' => 'index',
                    'component' => $component,
                    'name' => $value['name'],
                    'hidden' => $value['hidden'],
                    'meta' => [
                        'title' => $value['meta']['title'],
                        'icon' => $value['meta']['icon'],
                        'roles' => $value['meta']['roles'],
                        'noCache' => $value['meta']['noCache'],
                        'breadcrumb' => $value['meta']['breadcrumb'],
                        'affix' => $value['meta']['affix'],
                    ]
                ];
                unset($permissions[$key]['name']);
            }
        }

        return collect($permissions);
    }

    /**
     * @return Admin
     */
    public function getAdmin(): Admin
    {
        return $this->admin;
    }

    /**
     * @param Admin $admin
     * @return void
     */
    private function setAdmin(Admin $admin)
    {
        $this->admin = $admin;
    }
}
