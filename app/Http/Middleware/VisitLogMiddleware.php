<?php

namespace App\Http\Middleware;

use App\Models\VisitLog;
use Closure;
use Illuminate\Http\Request;

class VisitLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $startTime) * 1000, 2);

        $ua = $request->userAgent();

        try {
            VisitLog::create([
                'ip_address' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_agent' => $ua,
                'referer' => $request->header('referer'),
                'language' => $request->header('accept-language'),
                'device_type' => $this->detectDeviceType($ua),
                'browser' => $this->detectBrowser($ua),
                'browser_version' => $this->detectBrowserVersion($ua),
                'os' => $this->detectOS($ua),
                'route_name' => $request->route() ? $request->route()->getName() : null,
                'query_params' => $request->query() ?: null,
                'session_id' => $request->session()->getId(),
                'status_code' => $response->status(),
                'request_duration' => $duration,
            ]);
        } catch (\Throwable $e) {
        }

        return $response;
    }

    private function detectDeviceType(?string $ua): string
    {
        if (empty($ua)) {
            return 'unknown';
        }

        if (preg_match('/(bot|crawler|spider|scraper)/i', $ua)) {
            return 'bot';
        }
        if (preg_match('/(tablet|ipad|playbook|silk)/i', $ua)) {
            return 'tablet';
        }
        if (preg_match('/(mobi|android|iphone|ipod|blackberry|opera mini|iemobile)/i', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function detectBrowser(?string $ua): string
    {
        if (empty($ua)) {
            return 'unknown';
        }

        if (preg_match('/Edg\//i', $ua)) {
            return 'Edge';
        }
        if (preg_match('/OPR\//i', $ua)) {
            return 'Opera';
        }
        if (preg_match('/Chrome\//i', $ua) && ! preg_match('/Edge|Edg|OPR/i', $ua)) {
            return 'Chrome';
        }
        if (preg_match('/Firefox\//i', $ua)) {
            return 'Firefox';
        }
        if (preg_match('/Safari\//i', $ua) && ! preg_match('/Chrome|Chromium/i', $ua)) {
            return 'Safari';
        }
        if (preg_match('/MSIE |Trident\//i', $ua)) {
            return 'IE';
        }

        return 'other';
    }

    private function detectBrowserVersion(?string $ua): string
    {
        if (empty($ua)) {
            return '';
        }

        $browsers = [
            'Edg' => 'Edg\/([\d.]+)',
            'OPR' => 'OPR\/([\d.]+)',
            'Chrome' => 'Chrome\/([\d.]+)',
            'Firefox' => 'Firefox\/([\d.]+)',
            'Safari' => 'Version\/([\d.]+)',
            'MSIE' => 'MSIE ([\d.]+)',
        ];

        foreach ($browsers as $name => $pattern) {
            if (preg_match('/'.$pattern.'/i', $ua, $matches)) {
                return $matches[1];
            }
        }

        return '';
    }

    private function detectOS(?string $ua): string
    {
        if (empty($ua)) {
            return 'unknown';
        }

        $osPatterns = [
            'Windows 11' => '/Windows NT 10\.0;.*Win64; x64(?!.*Touch)/i',
            'Windows 10' => '/Windows NT 10\.0/i',
            'Windows 8.1' => '/Windows NT 6\.3/i',
            'Windows 8' => '/Windows NT 6\.2/i',
            'Windows 7' => '/Windows NT 6\.1/i',
            'macOS' => '/Macintosh.*Mac OS X (\d+[._]\d+)/i',
            'Linux' => '/Linux/i',
            'Android' => '/Android (\d+[.\d]*)/i',
            'iOS' => '/iPhone OS (\d+[_\d]*)/i',
        ];

        foreach ($osPatterns as $os => $pattern) {
            if (preg_match($pattern, $ua)) {
                return $os;
            }
        }

        return 'other';
    }
}
