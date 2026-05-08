<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

// 授权接口中间件
class AdminApiAuth extends BaseMiddleware
{
    /**
     * Handle an incominPg request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return response()->json([
                'code' => 402,
                'data' => [],
                'msg' => '缺少token',
            ], 402);
        }

        try {
            $admin = Auth::guard('adminapi')->authenticate($token);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'code' => 402,
                'msg' => 'token过期',
                'data' => [],
            ], 402);
        } catch (JWTException $e) {
            return response()->json([
                'code' => 402,
                'msg' => 'token无效',
                'data' => [],
            ], 402);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 402,
                'msg' => 'token无效',
                'data' => [],
            ], 402);
        }

        if (! $admin) {
            return response()->json([
                'code' => 402,
                'msg' => '用户不存在',
                'data' => [],
            ], 402);
        }

        return $next($request);
    }
}
