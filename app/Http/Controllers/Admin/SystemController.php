<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SystemController extends Controller
{
    /**
     * @var string View path
     */
    protected $viewPath = 'admin.system';

    /**
     * System info
     *
     * @return Application|Factory|View
     * @throws FileNotFoundException
     */
    public function systemInfo()
    {
        $path = $this->viewPath;

        $composerArray = hwaCore()->getComposerArray();
        $packages = hwaCore()->getPackagesAndDependencies($composerArray['require']);

        $systemEnv = hwaCore()->getSystemEnv();
        $serverEnv = hwaCore()->getServerEnv();

        $requiredPhpVersion = Arr::get($composerArray, 'require.php', '^7.3');
        $requiredPhpVersion = str_replace('^', '', $requiredPhpVersion);
        $requiredPhpVersion = str_replace('~', '', $requiredPhpVersion);

        $matchPHPRequirement = version_compare(phpversion(), $requiredPhpVersion) > 0;

        return view("{$path}.info")->with([
            'packages' => $packages,
            'systemEnv' => $systemEnv,
            'serverEnv' => $serverEnv,
            'matchPHPRequirement' => $matchPHPRequirement,
            'requiredPhpVersion' => $requiredPhpVersion,
        ]);
    }
}
