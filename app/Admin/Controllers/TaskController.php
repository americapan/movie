<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Card;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class TaskController extends Controller
{
    public function index(Content $content)
    {
        $commands = $this->getCommands();

        Admin::script($this->buildScript());

        return $content
            ->header('定时任务管理')
            ->description('查看和手动执行所有 Artisan 命令')
            ->body(new Card('命令列表', $this->buildCommandTable($commands)));
    }

    public function execute(Request $request)
    {
        $commandName = $request->input('command');

        if (empty($commandName)) {
            return response()->json(['status' => 'error', 'msg' => '未指定命令']);
        }

        $params = $request->input('params', []);
        if (! is_array($params)) {
            $params = [$params];
        }

        try {
            $exitCode = Artisan::call($commandName, $params);
            $output = Artisan::output();

            $status = $exitCode === 0 ? 'success' : 'error';

            return response()->json([
                'status' => $status,
                'exit_code' => $exitCode,
                'output' => $output,
                'msg' => $exitCode === 0 ? '命令执行成功' : '命令执行完成(退出码: '.$exitCode.')',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'exit_code' => -1,
                'output' => $e->getMessage(),
                'msg' => '命令执行异常',
            ]);
        }
    }

    private function getCommands(): array
    {
        $appCommands = Artisan::all();
        $customCommands = [];

        foreach ($appCommands as $name => $command) {
            if (str_starts_with($name, 'schedule:') || $name === 'schedule') {
                continue;
            }
            if (str_contains($name, 'make:') || str_contains($name, 'migrate')
                || str_contains($name, 'db:') || str_contains($name, 'vendor:')
                || str_contains($name, 'package:') || $name === 'tinker'
                || str_contains($name, 'stub') || str_contains($name, 'optimize')
                || str_contains($name, 'event:') || str_contains($name, 'queue:')
                || str_contains($name, 'notification') || str_contains($name, 'listener')
            ) {
                continue;
            }

            $description = $command->getDescription() ?: $command->getProcessedHelp();
            $signature = $this->getSignatureInfo($command);

            $customCommands[$name] = [
                'name' => $name,
                'description' => $description,
                'arguments' => $signature['arguments'],
                'options' => $signature['options'],
                'is_scheduled' => $this->isScheduled($name),
            ];
        }

        ksort($customCommands);

        return $customCommands;
    }

    private function buildCommandTable(array $commands): string
    {
        $rows = '';
        $index = 0;

        foreach ($commands as $name => $info) {
            $index++;
            $badge = $info['is_scheduled']
                ? '<span class="label label-success" style="margin-left:5px;">定时</span>'
                : '';

            $paramsInput = '';
            if (! empty($info['arguments']) || ! empty($info['options'])) {
                $paramsInput = '<input type="text" class="form-control cmd-params" placeholder="可选参数" style="width:200px;display:inline-block;margin-left:5px;" />';
            }

            $rows .= <<<HTML
<tr>
    <td>{$index}</td>
    <td>
        <code style="font-size:13px;">php artisan {$name}</code>{$badge}
    </td>
    <td style="max-width:300px;">{$info['description']}</td>
    <td>{$paramsInput}</td>
    <td>
        <button class="btn btn-sm btn-primary btn-execute" data-command="{$name}">
            <i class="feather icon-play"></i> 执行
        </button>
    </td>
</tr>
HTML;
        }

        return <<<HTML
<style>
#output-box {
    background: #1e1e1e;
    color: #d4d4d4;
    font-family: Consolas, 'Courier New', monospace;
    font-size: 13px;
    padding: 15px;
    border-radius: 4px;
    max-height: 400px;
    overflow-y: auto;
    white-space: pre-wrap;
    word-break: break-all;
    display: none;
}
#output-box .success { color: #4ec9b0; }
#output-box .error { color: #f44747; }
#output-box .info { color: #569cd6; }
</style>

<div style="margin-bottom:12px;">
    <button class="btn btn-success" id="btn-run-all">
        <i class="feather icon-play-circle"></i> 一键运行全部
    </button>
    <span id="executing-status" style="display:none;margin-left:10px;color:#ff8c00;">
        <i class="fa fa-spinner fa-pulse"></i> 正在执行中...
    </span>
</div>

<div class="table-responsive">
<table class="table table-striped table-bordered">
<thead>
<tr>
    <th width="40">#</th>
    <th width="250">命令</th>
    <th>描述</th>
    <th width="220">参数</th>
    <th width="100">操作</th>
</tr>
</thead>
<tbody>
{$rows}
</tbody>
</table>
</div>

<div id="output-box"></div>
HTML;
    }

    private function buildScript(): string
    {
        return <<<'JS'
(function() {
    var executing = false;
    var commandList = [];

    document.querySelectorAll('.btn-execute').forEach(function(btn) {
        commandList.push(btn.dataset.command);
        btn.addEventListener('click', function() {
            if (executing) return;
            var cmd = btn.dataset.command;
            var row = btn.closest('tr');
            var paramsInput = row ? row.querySelector('.cmd-params') : null;
            var params = paramsInput && paramsInput.value.trim() ? paramsInput.value.trim().split(/\s+/) : [];
            runCommand(cmd, params, btn);
        });
    });

    document.getElementById('btn-run-all').addEventListener('click', function() {
        if (executing) return;
        runAllCommands();
    });

    function runAllCommands() {
        if (commandList.length === 0) return;
        executing = true;
        document.getElementById('executing-status').style.display = 'inline';
        var box = document.getElementById('output-box');
        box.style.display = 'block';
        box.innerHTML = '<div class="info">=== 开始批量执行命令 ===</div>\n';

        var i = 0;
        function next() {
            if (i >= commandList.length) {
                box.innerHTML += '\n<div class="success">=== 全部执行完毕 ===</div>';
                executing = false;
                document.getElementById('executing-status').style.display = 'none';
                box.scrollTop = box.scrollHeight;
                return;
            }
            var cmd = commandList[i];
            box.innerHTML += '\n<div class="info">$ php artisan ' + cmd + '</div>';
            fetch('/admin/task/execute', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({command: cmd, params: []})
            }).then(function(r) { return r.json(); }).then(function(data) {
                if (data.output) {
                    box.innerHTML += data.output.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
                }
                box.innerHTML += '<div class="' + (data.exit_code === 0 ? 'success' : 'error') + '">[Exit: ' + data.exit_code + '] ' + data.msg + '</div>';
                box.scrollTop = box.scrollHeight;
                i++;
                next();
            }).catch(function(e) {
                box.innerHTML += '<div class="error">请求失败: ' + e.message + '</div>';
                i++;
                next();
            });
        }
        next();
    }

    function runCommand(cmd, params, btn) {
        executing = true;
        document.getElementById('executing-status').style.display = 'inline';
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-pulse"></i> 执行中';

        var box = document.getElementById('output-box');
        box.style.display = 'block';
        box.innerHTML = '<div class="info">$ php artisan ' + cmd + (params.length ? ' ' + params.join(' ') : '') + '</div>\n';

        fetch('/admin/task/execute', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({command: cmd, params: params})
        }).then(function(r) { return r.json(); }).then(function(data) {
            if (data.output) {
                box.innerHTML += data.output.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
            }
            box.innerHTML += '<div class="' + (data.exit_code === 0 ? 'success' : 'error') + '">[Exit: ' + data.exit_code + '] ' + (data.msg || '') + '</div>';
            box.scrollTop = box.scrollHeight;
            executing = false;
            document.getElementById('executing-status').style.display = 'none';
            btn.disabled = false;
            btn.innerHTML = '<i class="feather icon-play"></i> 执行';
        }).catch(function(e) {
            box.innerHTML += '<div class="error">请求失败: ' + e.message + '</div>';
            executing = false;
            document.getElementById('executing-status').style.display = 'none';
            btn.disabled = false;
            btn.innerHTML = '<i class="feather icon-play"></i> 执行';
        });
    }
})();
JS;
    }

    private function getSignatureInfo(SymfonyCommand $command): array
    {
        $definition = $command->getDefinition();
        $arguments = [];
        $options = [];

        foreach ($definition->getArguments() as $argument) {
            if ($argument->getName() !== 'command') {
                $arguments[] = [
                    'name' => $argument->getName(),
                    'required' => $argument->isRequired(),
                ];
            }
        }

        foreach ($definition->getOptions() as $option) {
            $options[] = '--'.$option->getName();
        }

        return [
            'arguments' => $arguments,
            'options' => $options,
        ];
    }

    private function isScheduled(string $commandName): bool
    {
        $scheduledCommands = [
            'scrape:movies',
        ];

        return in_array($commandName, $scheduledCommands);
    }
}
