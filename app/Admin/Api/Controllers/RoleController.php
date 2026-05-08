<?php

namespace App\Admin\Api\Controllers;

use Dcat\Admin\Models\Role;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('角色', '角色', 3)]
class RoleController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct(new Role);
    }

    /**
     * 获取列表
     */
    public function index(Request $request)
    {
        return parent::lists($request);
    }

    /**
     * @desc 数据校验证规则
     *
     * @param  string  $action  操作类型（store 创建数据，update 更新数据）
     */
    protected function getValidationRules(string $action): array
    {
        return [
            'store' => [
                [],
                [],
            ],
            'update' => [],
        ][$action] ?? [];
    }
}
