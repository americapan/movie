<?php

namespace App\Admin\Api\Controllers;

use Dcat\Admin\Models\Setting;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('设置', '设置', 6)]
class SettingsController extends BaseApiController
{
    public function __construct()
    {
        parent::__construct(new Setting);
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
