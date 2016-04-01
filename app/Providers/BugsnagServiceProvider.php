<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 25.03.16
 * Time: 14:59
 */

namespace App\Providers;

use Bugsnag\BugsnagLaravel\BugsnagLumenServiceProvider;

class BugsnagServiceProvider extends BugsnagLumenServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bugsnag', function ($app) {
            $config = isset($app['config']['services']['bugsnag']) ? $app['config']['services']['bugsnag'] : null;
            if (is_null($config)) {
                $config = $app['config']['bugsnag'] ?: $app['config']['bugsnag::config'];
            }

            $client = new \Bugsnag_Client($config['api_key']);
            $client->setStripPath(base_path());
            $client->setProjectRoot(base_path() . '/app');
            $client->setAutoNotify(false);
            $client->setBatchSending(true);
            $client->setReleaseStage($app->environment());
            $client->setNotifier(array(
                'name'    => 'Bugsnag Lumen',
                'version' => '1.6.4',
                'url'     => 'https://github.com/bugsnag/bugsnag-laravel'
            ));

            if (isset($config['notify_release_stages']) && is_array($config['notify_release_stages'])) {
                $client->setNotifyReleaseStages($config['notify_release_stages']);
            }

            if (isset($config['endpoint'])) {
                $client->setEndpoint($config['endpoint']);
            }

            if (isset($config['filters']) && is_array($config['filters'])) {
                $client->setFilters($config['filters']);
            }

            if (isset($config['proxy']) && is_array($config['proxy'])) {
                $client->setProxySettings($config['proxy']);
            }

            return $client;
        });
    }
}
