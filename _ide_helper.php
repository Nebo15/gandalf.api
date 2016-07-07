<?php
/**
 * A helper file for Laravel 5, to provide autocomplete information to your IDE
 * Generated for Laravel Lumen (5.1.1) (Laravel Components 5.1.*) on 2015-08-18.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 * @see https://github.com/barryvdh/laravel-ide-helper
 */

namespace {
    exit("This file should not be included, only analyzed by your IDE");

    class App extends \Illuminate\Support\Facades\App{

        /**
         * Get the version number of the application.
         *
         * @return string
         * @static
         */
        public static function version(){
            return \Laravel\Lumen\Application::version();
        }

        /**
         * Get or check the current application environment.
         *
         * @param mixed
         * @return string
         * @static
         */
        public static function environment(){
            return \Laravel\Lumen\Application::environment();
        }

        /**
         * Determine if the application is currently down for maintenance.
         *
         * @return bool
         * @static
         */
        public static function isDownForMaintenance(){
            return \Laravel\Lumen\Application::isDownForMaintenance();
        }

        /**
         * Register all of the configured providers.
         *
         * @return void
         * @static
         */
        public static function registerConfiguredProviders(){
            \Laravel\Lumen\Application::registerConfiguredProviders();
        }

        /**
         * Get the path to the cached "compiled.php" file.
         *
         * @return string
         * @static
         */
        public static function getCachedCompilePath(){
            return \Laravel\Lumen\Application::getCachedCompilePath();
        }

        /**
         * Get the path to the cached services.json file.
         *
         * @return string
         * @static
         */
        public static function getCachedServicesPath(){
            return \Laravel\Lumen\Application::getCachedServicesPath();
        }

        /**
         * Register a service provider with the application.
         *
         * @param \Illuminate\Support\ServiceProvider|string $provider
         * @param array $options
         * @param bool $force
         * @return \Illuminate\Support\ServiceProvider
         * @static
         */
        public static function register($provider, $options = array(), $force = false){
            return \Laravel\Lumen\Application::register($provider, $options, $force);
        }

        /**
         * Register a deferred provider and service.
         *
         * @param string $provider
         * @param string $service
         * @return void
         * @static
         */
        public static function registerDeferredProvider($provider, $service = null){
            \Laravel\Lumen\Application::registerDeferredProvider($provider, $service);
        }

        /**
         * Boot the application's service providers.
         *
         * @return void
         * @static
         */
        public static function boot(){
            \Laravel\Lumen\Application::boot();
        }

        /**
         * Register a new boot listener.
         *
         * @param mixed $callback
         * @return void
         * @static
         */
        public static function booting($callback){
            \Laravel\Lumen\Application::booting($callback);
        }

        /**
         * Register a new "booted" listener.
         *
         * @param mixed $callback
         * @return void
         * @static
         */
        public static function booted($callback){
            \Laravel\Lumen\Application::booted($callback);
        }

        /**
         * Throw an HttpException with the given data.
         *
         * @param int $code
         * @param string $message
         * @param array $headers
         * @return void
         * @throws \Symfony\Component\HttpKernel\Exception\HttpException
         * @static
         */
        public static function abort($code, $message = '', $headers = array()){
            \Laravel\Lumen\Application::abort($code, $message, $headers);
        }

        /**
         * Resolve the given type from the container.
         *
         * @param string $abstract
         * @param array $parameters
         * @return mixed
         * @static
         */
        public static function make($abstract, $parameters = array()){
            return \Laravel\Lumen\Application::make($abstract, $parameters);
        }

        /**
         * Load a configuration file into the application.
         *
         * @return void
         * @static
         */
        public static function configure($name){
            \Laravel\Lumen\Application::configure($name);
        }

        /**
         * Register the facades for the application.
         *
         * @return void
         * @static
         */
        public static function withFacades(){
            \Laravel\Lumen\Application::withFacades();
        }

        /**
         * Load the Eloquent library for the application.
         *
         * @return void
         * @static
         */
        public static function withEloquent(){
            \Laravel\Lumen\Application::withEloquent();
        }

        /**
         * Register a set of routes with a set of shared attributes.
         *
         * @param array $attributes
         * @param \Closure $callback
         * @return void
         * @static
         */
        public static function group($attributes, $callback){
            \Laravel\Lumen\Application::group($attributes, $callback);
        }

        /**
         * Register a route with the application.
         *
         * @param string $uri
         * @param mixed $action
         * @return $this
         * @static
         */
        public static function get($uri, $action){
            return \Laravel\Lumen\Application::get($uri, $action);
        }

        /**
         * Register a route with the application.
         *
         * @param string $uri
         * @param mixed $action
         * @return $this
         * @static
         */
        public static function post($uri, $action){
            return \Laravel\Lumen\Application::post($uri, $action);
        }

        /**
         * Register a route with the application.
         *
         * @param string $uri
         * @param mixed $action
         * @return $this
         * @static
         */
        public static function put($uri, $action){
            return \Laravel\Lumen\Application::put($uri, $action);
        }

        /**
         * Register a route with the application.
         *
         * @param string $uri
         * @param mixed $action
         * @return $this
         * @static
         */
        public static function patch($uri, $action){
            return \Laravel\Lumen\Application::patch($uri, $action);
        }

        /**
         * Register a route with the application.
         *
         * @param string $uri
         * @param mixed $action
         * @return $this
         * @static
         */
        public static function delete($uri, $action){
            return \Laravel\Lumen\Application::delete($uri, $action);
        }

        /**
         * Register a route with the application.
         *
         * @param string $uri
         * @param mixed $action
         * @return $this
         * @static
         */
        public static function options($uri, $action){
            return \Laravel\Lumen\Application::options($uri, $action);
        }

        /**
         * Add a route to the collection.
         *
         * @param string $method
         * @param string $uri
         * @param mixed $action
         * @static
         */
        public static function addRoute($method, $uri, $action){
            return \Laravel\Lumen\Application::addRoute($method, $uri, $action);
        }

        /**
         * Add new middleware to the application.
         *
         * @param array $middleware
         * @return $this
         * @static
         */
        public static function middleware($middleware){
            return \Laravel\Lumen\Application::middleware($middleware);
        }

        /**
         * Define the route middleware for the application.
         *
         * @param array $middleware
         * @return $this
         * @static
         */
        public static function routeMiddleware($middleware){
            return \Laravel\Lumen\Application::routeMiddleware($middleware);
        }

        /**
         * {@inheritdoc}
         *
         * @static
         */
        public static function handle($request, $type = 1, $catch = true){
            return \Laravel\Lumen\Application::handle($request, $type, $catch);
        }

        /**
         * Run the application and send the response.
         *
         * @param \Laravel\Lumen\SymfonyRequest|null $request
         * @return void
         * @static
         */
        public static function run($request = null){
            \Laravel\Lumen\Application::run($request);
        }

        /**
         * Dispatch the incoming request.
         *
         * @param \Laravel\Lumen\SymfonyRequest|null $request
         * @return \Laravel\Lumen\Response
         * @static
         */
        public static function dispatch($request = null){
            return \Laravel\Lumen\Application::dispatch($request);
        }

        /**
         * Set the FastRoute dispatcher instance.
         *
         * @param \FastRoute\Dispatcher $dispatcher
         * @return void
         * @static
         */
        public static function setDispatcher($dispatcher){
            \Laravel\Lumen\Application::setDispatcher($dispatcher);
        }

        /**
         * Prepare the response for sending.
         *
         * @param mixed $response
         * @return \Laravel\Lumen\Response
         * @static
         */
        public static function prepareResponse($response){
            return \Laravel\Lumen\Application::prepareResponse($response);
        }

        /**
         * Get the current HTTP path info.
         *
         * @return string
         * @static
         */
        public static function getPathInfo(){
            return \Laravel\Lumen\Application::getPathInfo();
        }

        /**
         * Get the base path for the application.
         *
         * @param string $path
         * @return string
         * @static
         */
        public static function basePath($path = null){
            return \Laravel\Lumen\Application::basePath($path);
        }

        /**
         * Get the storage path for the application.
         *
         * @param string $path
         * @return string
         * @static
         */
        public static function storagePath($path = null){
            return \Laravel\Lumen\Application::storagePath($path);
        }

        /**
         * Set a custom storage path for the application.
         *
         * @param string $path
         * @return $this
         * @static
         */
        public static function useStoragePath($path){
            return \Laravel\Lumen\Application::useStoragePath($path);
        }

        /**
         * Get the database path for the application.
         *
         * @return string
         * @static
         */
        public static function databasePath(){
            return \Laravel\Lumen\Application::databasePath();
        }

        /**
         * Set a custom configuration path for the application.
         *
         * @param string $path
         * @return $this
         * @static
         */
        public static function useConfigPath($path){
            return \Laravel\Lumen\Application::useConfigPath($path);
        }

        /**
         * Get the resource path for the application.
         *
         * @param string $path
         * @return string
         * @static
         */
        public static function resourcePath($path = null){
            return \Laravel\Lumen\Application::resourcePath($path);
        }

        /**
         * Set a custom resource path for the application.
         *
         * @param string $path
         * @return $this
         * @static
         */
        public static function useResourcePath($path){
            return \Laravel\Lumen\Application::useResourcePath($path);
        }

        /**
         * Determine if the application is running in the console.
         *
         * @return string
         * @static
         */
        public static function runningInConsole(){
            return \Laravel\Lumen\Application::runningInConsole();
        }

        /**
         * Prepare the application to execute a console command.
         *
         * @return void
         * @static
         */
        public static function prepareForConsoleCommand(){
            \Laravel\Lumen\Application::prepareForConsoleCommand();
        }

        /**
         * Get the raw routes for the application.
         *
         * @return array
         * @static
         */
        public static function getRoutes(){
            return \Laravel\Lumen\Application::getRoutes();
        }

        /**
         * Set the cached routes.
         *
         * @param array $routes
         * @return void
         * @static
         */
        public static function setRoutes($routes){
            \Laravel\Lumen\Application::setRoutes($routes);
        }

        /**
         * Get the HTML from the Lumen welcome screen.
         *
         * @return string
         * @static
         */
        public static function welcome(){
            return \Laravel\Lumen\Application::welcome();
        }

        /**
         * Define a contextual binding.
         *
         * @param string $concrete
         * @return \Illuminate\Contracts\Container\ContextualBindingBuilder
         * @static
         */
        public static function when($concrete){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::when($concrete);
        }

        /**
         * Determine if the given abstract type has been bound.
         *
         * @param string $abstract
         * @return bool
         * @static
         */
        public static function bound($abstract){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::bound($abstract);
        }

        /**
         * Determine if the given abstract type has been resolved.
         *
         * @param string $abstract
         * @return bool
         * @static
         */
        public static function resolved($abstract){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::resolved($abstract);
        }

        /**
         * Determine if a given string is an alias.
         *
         * @param string $name
         * @return bool
         * @static
         */
        public static function isAlias($name){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::isAlias($name);
        }

        /**
         * Register a binding with the container.
         *
         * @param string|array $abstract
         * @param \Closure|string|null $concrete
         * @param bool $shared
         * @return void
         * @static
         */
        public static function bind($abstract, $concrete = null, $shared = false){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::bind($abstract, $concrete, $shared);
        }

        /**
         * Add a contextual binding to the container.
         *
         * @param string $concrete
         * @param string $abstract
         * @param \Closure|string $implementation
         * @static
         */
        public static function addContextualBinding($concrete, $abstract, $implementation){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::addContextualBinding($concrete, $abstract, $implementation);
        }

        /**
         * Register a binding if it hasn't already been registered.
         *
         * @param string $abstract
         * @param \Closure|string|null $concrete
         * @param bool $shared
         * @return void
         * @static
         */
        public static function bindIf($abstract, $concrete = null, $shared = false){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::bindIf($abstract, $concrete, $shared);
        }

        /**
         * Register a shared binding in the container.
         *
         * @param string $abstract
         * @param \Closure|string|null $concrete
         * @return void
         * @static
         */
        public static function singleton($abstract, $concrete = null){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::singleton($abstract, $concrete);
        }

        /**
         * Wrap a Closure such that it is shared.
         *
         * @param \Closure $closure
         * @return \Closure
         * @static
         */
        public static function share($closure){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::share($closure);
        }

        /**
         * Bind a shared Closure into the container.
         *
         * @param string $abstract
         * @param \Closure $closure
         * @return void
         * @deprecated since version 5.1. Use singleton instead.
         * @static
         */
        public static function bindShared($abstract, $closure){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::bindShared($abstract, $closure);
        }

        /**
         * "Extend" an abstract type in the container.
         *
         * @param string $abstract
         * @param \Closure $closure
         * @return void
         * @throws \InvalidArgumentException
         * @static
         */
        public static function extend($abstract, $closure){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::extend($abstract, $closure);
        }

        /**
         * Register an existing instance as shared in the container.
         *
         * @param string $abstract
         * @param mixed $instance
         * @return void
         * @static
         */
        public static function instance($abstract, $instance){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::instance($abstract, $instance);
        }

        /**
         * Assign a set of tags to a given binding.
         *
         * @param array|string $abstracts
         * @param array|mixed $tags
         * @return void
         * @static
         */
        public static function tag($abstracts, $tags){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::tag($abstracts, $tags);
        }

        /**
         * Resolve all of the bindings for a given tag.
         *
         * @param string $tag
         * @return array
         * @static
         */
        public static function tagged($tag){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::tagged($tag);
        }

        /**
         * Alias a type to a different name.
         *
         * @param string $abstract
         * @param string $alias
         * @return void
         * @static
         */
        public static function alias($abstract, $alias){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::alias($abstract, $alias);
        }

        /**
         * Bind a new callback to an abstract's rebind event.
         *
         * @param string $abstract
         * @param \Closure $callback
         * @return mixed
         * @static
         */
        public static function rebinding($abstract, $callback){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::rebinding($abstract, $callback);
        }

        /**
         * Refresh an instance on the given target and method.
         *
         * @param string $abstract
         * @param mixed $target
         * @param string $method
         * @return mixed
         * @static
         */
        public static function refresh($abstract, $target, $method){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::refresh($abstract, $target, $method);
        }

        /**
         * Wrap the given closure such that its dependencies will be injected when executed.
         *
         * @param \Closure $callback
         * @param array $parameters
         * @return \Closure
         * @static
         */
        public static function wrap($callback, $parameters = array()){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::wrap($callback, $parameters);
        }

        /**
         * Call the given Closure / class@method and inject its dependencies.
         *
         * @param callable|string $callback
         * @param array $parameters
         * @param string|null $defaultMethod
         * @return mixed
         * @static
         */
        public static function call($callback, $parameters = array(), $defaultMethod = null){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::call($callback, $parameters, $defaultMethod);
        }

        /**
         * Instantiate a concrete instance of the given type.
         *
         * @param string $concrete
         * @param array $parameters
         * @return mixed
         * @throws \Illuminate\Contracts\Container\BindingResolutionException
         * @static
         */
        public static function build($concrete, $parameters = array()){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::build($concrete, $parameters);
        }

        /**
         * Register a new resolving callback.
         *
         * @param string $abstract
         * @param \Closure|null $callback
         * @return void
         * @static
         */
        public static function resolving($abstract, $callback = null){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::resolving($abstract, $callback);
        }

        /**
         * Register a new after resolving callback for all types.
         *
         * @param string $abstract
         * @param \Closure|null $callback
         * @return void
         * @static
         */
        public static function afterResolving($abstract, $callback = null){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::afterResolving($abstract, $callback);
        }

        /**
         * Determine if a given type is shared.
         *
         * @param string $abstract
         * @return bool
         * @static
         */
        public static function isShared($abstract){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::isShared($abstract);
        }

        /**
         * Get the container's bindings.
         *
         * @return array
         * @static
         */
        public static function getBindings(){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::getBindings();
        }

        /**
         * Remove a resolved instance from the instance cache.
         *
         * @param string $abstract
         * @return void
         * @static
         */
        public static function forgetInstance($abstract){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::forgetInstance($abstract);
        }

        /**
         * Clear all of the instances from the container.
         *
         * @return void
         * @static
         */
        public static function forgetInstances(){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::forgetInstances();
        }

        /**
         * Flush the container of all bindings and resolved instances.
         *
         * @return void
         * @static
         */
        public static function flush(){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::flush();
        }

        /**
         * Set the globally available instance of the container.
         *
         * @return static
         * @static
         */
        public static function getInstance(){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::getInstance();
        }

        /**
         * Set the shared instance of the container.
         *
         * @param \Illuminate\Contracts\Container\Container $container
         * @return void
         * @static
         */
        public static function setInstance($container){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::setInstance($container);
        }

        /**
         * Determine if a given offset exists.
         *
         * @param string $key
         * @return bool
         * @static
         */
        public static function offsetExists($key){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::offsetExists($key);
        }

        /**
         * Get the value at a given offset.
         *
         * @param string $key
         * @return mixed
         * @static
         */
        public static function offsetGet($key){
            //Method inherited from \Illuminate\Container\Container
            return \Laravel\Lumen\Application::offsetGet($key);
        }

        /**
         * Set the value at a given offset.
         *
         * @param string $key
         * @param mixed $value
         * @return void
         * @static
         */
        public static function offsetSet($key, $value){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::offsetSet($key, $value);
        }

        /**
         * Unset the value at a given offset.
         *
         * @param string $key
         * @return void
         * @static
         */
        public static function offsetUnset($key){
            //Method inherited from \Illuminate\Container\Container
            \Laravel\Lumen\Application::offsetUnset($key);
        }

    }


    class Auth extends \Illuminate\Support\Facades\Auth{

        /**
         * Create an instance of the database driver.
         *
         * @return \Illuminate\Auth\Guard
         * @static
         */
        public static function createDatabaseDriver(){
            return \Illuminate\Auth\AuthManager::createDatabaseDriver();
        }

        /**
         * Create an instance of the Eloquent driver.
         *
         * @return \Illuminate\Auth\Guard
         * @static
         */
        public static function createEloquentDriver(){
            return \Illuminate\Auth\AuthManager::createEloquentDriver();
        }

        /**
         * Get the default authentication driver name.
         *
         * @return string
         * @static
         */
        public static function getDefaultDriver(){
            return \Illuminate\Auth\AuthManager::getDefaultDriver();
        }

        /**
         * Set the default authentication driver name.
         *
         * @param string $name
         * @return void
         * @static
         */
        public static function setDefaultDriver($name){
            \Illuminate\Auth\AuthManager::setDefaultDriver($name);
        }

        /**
         * Get a driver instance.
         *
         * @param string $driver
         * @return mixed
         * @static
         */
        public static function driver($driver = null){
            //Method inherited from \Illuminate\Support\Manager
            return \Illuminate\Auth\AuthManager::driver($driver);
        }

        /**
         * Register a custom driver creator Closure.
         *
         * @param string $driver
         * @param \Closure $callback
         * @return $this
         * @static
         */
        public static function extend($driver, $callback){
            //Method inherited from \Illuminate\Support\Manager
            return \Illuminate\Auth\AuthManager::extend($driver, $callback);
        }

        /**
         * Get all of the created "drivers".
         *
         * @return array
         * @static
         */
        public static function getDrivers(){
            //Method inherited from \Illuminate\Support\Manager
            return \Illuminate\Auth\AuthManager::getDrivers();
        }

        /**
         * Determine if the current user is authenticated.
         *
         * @return bool
         * @static
         */
        public static function check(){
            return \Illuminate\Auth\Guard::check();
        }

        /**
         * Determine if the current user is a guest.
         *
         * @return bool
         * @static
         */
        public static function guest(){
            return \Illuminate\Auth\Guard::guest();
        }

        /**
         * Get the currently authenticated user.
         *
         * @return \App\User|null
         * @static
         */
        public static function user(){
            return \Illuminate\Auth\Guard::user();
        }

        /**
         * Get the ID for the currently authenticated user.
         *
         * @return int|null
         * @static
         */
        public static function id(){
            return \Illuminate\Auth\Guard::id();
        }

        /**
         * Log a user into the application without sessions or cookies.
         *
         * @param array $credentials
         * @return bool
         * @static
         */
        public static function once($credentials = array()){
            return \Illuminate\Auth\Guard::once($credentials);
        }

        /**
         * Validate a user's credentials.
         *
         * @param array $credentials
         * @return bool
         * @static
         */
        public static function validate($credentials = array()){
            return \Illuminate\Auth\Guard::validate($credentials);
        }

        /**
         * Attempt to authenticate using HTTP Basic Auth.
         *
         * @param string $field
         * @return \Symfony\Component\HttpFoundation\Response|null
         * @static
         */
        public static function basic($field = 'email'){
            return \Illuminate\Auth\Guard::basic($field);
        }

        /**
         * Perform a stateless HTTP Basic login attempt.
         *
         * @param string $field
         * @return \Symfony\Component\HttpFoundation\Response|null
         * @static
         */
        public static function onceBasic($field = 'email'){
            return \Illuminate\Auth\Guard::onceBasic($field);
        }

        /**
         * Attempt to authenticate a user using the given credentials.
         *
         * @param array $credentials
         * @param bool $remember
         * @param bool $login
         * @return bool
         * @static
         */
        public static function attempt($credentials = array(), $remember = false, $login = true){
            return \Illuminate\Auth\Guard::attempt($credentials, $remember, $login);
        }

        /**
         * Register an authentication attempt event listener.
         *
         * @param mixed $callback
         * @return void
         * @static
         */
        public static function attempting($callback){
            \Illuminate\Auth\Guard::attempting($callback);
        }

        /**
         * Log a user into the application.
         *
         * @param \Illuminate\Contracts\Auth\Authenticatable $user
         * @param bool $remember
         * @return void
         * @static
         */
        public static function login($user, $remember = false){
            \Illuminate\Auth\Guard::login($user, $remember);
        }

        /**
         * Log the given user ID into the application.
         *
         * @param mixed $id
         * @param bool $remember
         * @return \App\User
         * @static
         */
        public static function loginUsingId($id, $remember = false){
            return \Illuminate\Auth\Guard::loginUsingId($id, $remember);
        }

        /**
         * Log the given user ID into the application without sessions or cookies.
         *
         * @param mixed $id
         * @return bool
         * @static
         */
        public static function onceUsingId($id){
            return \Illuminate\Auth\Guard::onceUsingId($id);
        }

        /**
         * Log the user out of the application.
         *
         * @return void
         * @static
         */
        public static function logout(){
            \Illuminate\Auth\Guard::logout();
        }

        /**
         * Get the cookie creator instance used by the guard.
         *
         * @return \Illuminate\Contracts\Cookie\QueueingFactory
         * @throws \RuntimeException
         * @static
         */
        public static function getCookieJar(){
            return \Illuminate\Auth\Guard::getCookieJar();
        }

        /**
         * Set the cookie creator instance used by the guard.
         *
         * @param \Illuminate\Contracts\Cookie\QueueingFactory $cookie
         * @return void
         * @static
         */
        public static function setCookieJar($cookie){
            \Illuminate\Auth\Guard::setCookieJar($cookie);
        }

        /**
         * Get the event dispatcher instance.
         *
         * @return \Illuminate\Contracts\Events\Dispatcher
         * @static
         */
        public static function getDispatcher(){
            return \Illuminate\Auth\Guard::getDispatcher();
        }

        /**
         * Set the event dispatcher instance.
         *
         * @param \Illuminate\Contracts\Events\Dispatcher $events
         * @return void
         * @static
         */
        public static function setDispatcher($events){
            \Illuminate\Auth\Guard::setDispatcher($events);
        }

        /**
         * Get the session store used by the guard.
         *
         * @return \Illuminate\Session\Store
         * @static
         */
        public static function getSession(){
            return \Illuminate\Auth\Guard::getSession();
        }

        /**
         * Get the user provider used by the guard.
         *
         * @return \Illuminate\Contracts\Auth\UserProvider
         * @static
         */
        public static function getProvider(){
            return \Illuminate\Auth\Guard::getProvider();
        }

        /**
         * Set the user provider used by the guard.
         *
         * @param \Illuminate\Contracts\Auth\UserProvider $provider
         * @return void
         * @static
         */
        public static function setProvider($provider){
            \Illuminate\Auth\Guard::setProvider($provider);
        }

        /**
         * Return the currently cached user.
         *
         * @return \App\User|null
         * @static
         */
        public static function getUser(){
            return \Illuminate\Auth\Guard::getUser();
        }

        /**
         * Set the current user.
         *
         * @param \Illuminate\Contracts\Auth\Authenticatable $user
         * @return void
         * @static
         */
        public static function setUser($user){
            \Illuminate\Auth\Guard::setUser($user);
        }

        /**
         * Get the current request instance.
         *
         * @return \Symfony\Component\HttpFoundation\Request
         * @static
         */
        public static function getRequest(){
            return \Illuminate\Auth\Guard::getRequest();
        }

        /**
         * Set the current request instance.
         *
         * @param \Symfony\Component\HttpFoundation\Request $request
         * @return $this
         * @static
         */
        public static function setRequest($request){
            return \Illuminate\Auth\Guard::setRequest($request);
        }

        /**
         * Get the last user we attempted to authenticate.
         *
         * @return \App\User
         * @static
         */
        public static function getLastAttempted(){
            return \Illuminate\Auth\Guard::getLastAttempted();
        }

        /**
         * Get a unique identifier for the auth session value.
         *
         * @return string
         * @static
         */
        public static function getName(){
            return \Illuminate\Auth\Guard::getName();
        }

        /**
         * Get the name of the cookie used to store the "recaller".
         *
         * @return string
         * @static
         */
        public static function getRecallerName(){
            return \Illuminate\Auth\Guard::getRecallerName();
        }

        /**
         * Determine if the user was authenticated via "remember me" cookie.
         *
         * @return bool
         * @static
         */
        public static function viaRemember(){
            return \Illuminate\Auth\Guard::viaRemember();
        }

    }


    class Bus extends \Illuminate\Support\Facades\Bus{

        /**
         * Marshal a command and dispatch it to its appropriate handler.
         *
         * @param mixed $command
         * @param array $array
         * @return mixed
         * @static
         */
        public static function dispatchFromArray($command, $array){
            return \Illuminate\Bus\Dispatcher::dispatchFromArray($command, $array);
        }

        /**
         * Marshal a command and dispatch it to its appropriate handler.
         *
         * @param mixed $command
         * @param \ArrayAccess $source
         * @param array $extras
         * @return mixed
         * @static
         */
        public static function dispatchFrom($command, $source, $extras = array()){
            return \Illuminate\Bus\Dispatcher::dispatchFrom($command, $source, $extras);
        }

        /**
         * Dispatch a command to its appropriate handler.
         *
         * @param mixed $command
         * @param \Closure|null $afterResolving
         * @return mixed
         * @static
         */
        public static function dispatch($command, $afterResolving = null){
            return \Illuminate\Bus\Dispatcher::dispatch($command, $afterResolving);
        }

        /**
         * Dispatch a command to its appropriate handler in the current process.
         *
         * @param mixed $command
         * @param \Closure|null $afterResolving
         * @return mixed
         * @static
         */
        public static function dispatchNow($command, $afterResolving = null){
            return \Illuminate\Bus\Dispatcher::dispatchNow($command, $afterResolving);
        }

        /**
         * Dispatch a command to its appropriate handler behind a queue.
         *
         * @param mixed $command
         * @return mixed
         * @throws \RuntimeException
         * @static
         */
        public static function dispatchToQueue($command){
            return \Illuminate\Bus\Dispatcher::dispatchToQueue($command);
        }

        /**
         * Get the handler instance for the given command.
         *
         * @param mixed $command
         * @return mixed
         * @static
         */
        public static function resolveHandler($command){
            return \Illuminate\Bus\Dispatcher::resolveHandler($command);
        }

        /**
         * Get the handler class for the given command.
         *
         * @param mixed $command
         * @return string
         * @static
         */
        public static function getHandlerClass($command){
            return \Illuminate\Bus\Dispatcher::getHandlerClass($command);
        }

        /**
         * Get the handler method for the given command.
         *
         * @param mixed $command
         * @return string
         * @static
         */
        public static function getHandlerMethod($command){
            return \Illuminate\Bus\Dispatcher::getHandlerMethod($command);
        }

        /**
         * Register command-to-handler mappings.
         *
         * @param array $commands
         * @return void
         * @static
         */
        public static function maps($commands){
            \Illuminate\Bus\Dispatcher::maps($commands);
        }

        /**
         * Register a fallback mapper callback.
         *
         * @param \Closure $mapper
         * @return void
         * @static
         */
        public static function mapUsing($mapper){
            \Illuminate\Bus\Dispatcher::mapUsing($mapper);
        }

        /**
         * Map the command to a handler within a given root namespace.
         *
         * @param mixed $command
         * @param string $commandNamespace
         * @param string $handlerNamespace
         * @return string
         * @static
         */
        public static function simpleMapping($command, $commandNamespace, $handlerNamespace){
            return \Illuminate\Bus\Dispatcher::simpleMapping($command, $commandNamespace, $handlerNamespace);
        }

        /**
         * Set the pipes through which commands should be piped before dispatching.
         *
         * @param array $pipes
         * @return $this
         * @static
         */
        public static function pipeThrough($pipes){
            return \Illuminate\Bus\Dispatcher::pipeThrough($pipes);
        }

    }


    class DB extends \Illuminate\Support\Facades\DB{

        /**
         * Get a database connection instance.
         *
         * @param string $name
         * @return \Illuminate\Database\Connection
         * @static
         */
        public static function connection($name = null){
            return \Illuminate\Database\DatabaseManager::connection($name);
        }

        /**
         * Disconnect from the given database and remove from local cache.
         *
         * @param string $name
         * @return void
         * @static
         */
        public static function purge($name = null){
            \Illuminate\Database\DatabaseManager::purge($name);
        }

        /**
         * Disconnect from the given database.
         *
         * @param string $name
         * @return void
         * @static
         */
        public static function disconnect($name = null){
            \Illuminate\Database\DatabaseManager::disconnect($name);
        }

        /**
         * Reconnect to the given database.
         *
         * @param string $name
         * @return \Illuminate\Database\Connection
         * @static
         */
        public static function reconnect($name = null){
            return \Illuminate\Database\DatabaseManager::reconnect($name);
        }

        /**
         * Get the default connection name.
         *
         * @return string
         * @static
         */
        public static function getDefaultConnection(){
            return \Illuminate\Database\DatabaseManager::getDefaultConnection();
        }

        /**
         * Set the default connection name.
         *
         * @param string $name
         * @return void
         * @static
         */
        public static function setDefaultConnection($name){
            \Illuminate\Database\DatabaseManager::setDefaultConnection($name);
        }

        /**
         * Register an extension connection resolver.
         *
         * @param string $name
         * @param callable $resolver
         * @return void
         * @static
         */
        public static function extend($name, $resolver){
            \Illuminate\Database\DatabaseManager::extend($name, $resolver);
        }

        /**
         * Return all of the created connections.
         *
         * @return array
         * @static
         */
        public static function getConnections(){
            return \Illuminate\Database\DatabaseManager::getConnections();
        }

        /**
         * Get a schema builder instance for the connection.
         *
         * @return \Illuminate\Database\Schema\MySqlBuilder
         * @static
         */
        public static function getSchemaBuilder(){
            return \Illuminate\Database\MySqlConnection::getSchemaBuilder();
        }

        /**
         * Set the query grammar to the default implementation.
         *
         * @return void
         * @static
         */
        public static function useDefaultQueryGrammar(){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::useDefaultQueryGrammar();
        }

        /**
         * Set the schema grammar to the default implementation.
         *
         * @return void
         * @static
         */
        public static function useDefaultSchemaGrammar(){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::useDefaultSchemaGrammar();
        }

        /**
         * Set the query post processor to the default implementation.
         *
         * @return void
         * @static
         */
        public static function useDefaultPostProcessor(){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::useDefaultPostProcessor();
        }

        /**
         * Begin a fluent query against a database table.
         *
         * @param string $table
         * @return \Illuminate\Database\Query\Builder
         * @static
         */
        public static function table($table){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::table($table);
        }

        /**
         * Get a new raw query expression.
         *
         * @param mixed $value
         * @return \Illuminate\Database\Query\Expression
         * @static
         */
        public static function raw($value){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::raw($value);
        }

        /**
         * Run a select statement and return a single result.
         *
         * @param string $query
         * @param array $bindings
         * @return mixed
         * @static
         */
        public static function selectOne($query, $bindings = array()){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::selectOne($query, $bindings);
        }

        /**
         * Run a select statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @return array
         * @static
         */
        public static function selectFromWriteConnection($query, $bindings = array()){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::selectFromWriteConnection($query, $bindings);
        }

        /**
         * Run a select statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @param bool $useReadPdo
         * @return array
         * @static
         */
        public static function select($query, $bindings = array(), $useReadPdo = true){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::select($query, $bindings, $useReadPdo);
        }

        /**
         * Run an insert statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @return bool
         * @static
         */
        public static function insert($query, $bindings = array()){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::insert($query, $bindings);
        }

        /**
         * Run an update statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @return int
         * @static
         */
        public static function update($query, $bindings = array()){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::update($query, $bindings);
        }

        /**
         * Run a delete statement against the database.
         *
         * @param string $query
         * @param array $bindings
         * @return int
         * @static
         */
        public static function delete($query, $bindings = array()){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::delete($query, $bindings);
        }

        /**
         * Execute an SQL statement and return the boolean result.
         *
         * @param string $query
         * @param array $bindings
         * @return bool
         * @static
         */
        public static function statement($query, $bindings = array()){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::statement($query, $bindings);
        }

        /**
         * Run an SQL statement and get the number of rows affected.
         *
         * @param string $query
         * @param array $bindings
         * @return int
         * @static
         */
        public static function affectingStatement($query, $bindings = array()){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::affectingStatement($query, $bindings);
        }

        /**
         * Run a raw, unprepared query against the PDO connection.
         *
         * @param string $query
         * @return bool
         * @static
         */
        public static function unprepared($query){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::unprepared($query);
        }

        /**
         * Prepare the query bindings for execution.
         *
         * @param array $bindings
         * @return array
         * @static
         */
        public static function prepareBindings($bindings){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::prepareBindings($bindings);
        }

        /**
         * Execute a Closure within a transaction.
         *
         * @param \Closure $callback
         * @return mixed
         * @throws \Throwable
         * @static
         */
        public static function transaction($callback){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::transaction($callback);
        }

        /**
         * Start a new database transaction.
         *
         * @return void
         * @static
         */
        public static function beginTransaction(){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::beginTransaction();
        }

        /**
         * Commit the active database transaction.
         *
         * @return void
         * @static
         */
        public static function commit(){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::commit();
        }

        /**
         * Rollback the active database transaction.
         *
         * @return void
         * @static
         */
        public static function rollBack(){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::rollBack();
        }

        /**
         * Get the number of active transactions.
         *
         * @return int
         * @static
         */
        public static function transactionLevel(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::transactionLevel();
        }

        /**
         * Execute the given callback in "dry run" mode.
         *
         * @param \Closure $callback
         * @return array
         * @static
         */
        public static function pretend($callback){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::pretend($callback);
        }

        /**
         * Log a query in the connection's query log.
         *
         * @param string $query
         * @param array $bindings
         * @param float|null $time
         * @return void
         * @static
         */
        public static function logQuery($query, $bindings, $time = null){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::logQuery($query, $bindings, $time);
        }

        /**
         * Register a database query listener with the connection.
         *
         * @param \Closure $callback
         * @return void
         * @static
         */
        public static function listen($callback){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::listen($callback);
        }

        /**
         * Get a Doctrine Schema Column instance.
         *
         * @param string $table
         * @param string $column
         * @return \Doctrine\DBAL\Schema\Column
         * @static
         */
        public static function getDoctrineColumn($table, $column){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getDoctrineColumn($table, $column);
        }

        /**
         * Get the Doctrine DBAL schema manager for the connection.
         *
         * @return \Doctrine\DBAL\Schema\AbstractSchemaManager
         * @static
         */
        public static function getDoctrineSchemaManager(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getDoctrineSchemaManager();
        }

        /**
         * Get the Doctrine DBAL database connection instance.
         *
         * @return \Doctrine\DBAL\Connection
         * @static
         */
        public static function getDoctrineConnection(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getDoctrineConnection();
        }

        /**
         * Get the current PDO connection.
         *
         * @return \PDO
         * @static
         */
        public static function getPdo(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getPdo();
        }

        /**
         * Get the current PDO connection used for reading.
         *
         * @return \PDO
         * @static
         */
        public static function getReadPdo(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getReadPdo();
        }

        /**
         * Set the PDO connection.
         *
         * @param \PDO|null $pdo
         * @return $this
         * @static
         */
        public static function setPdo($pdo){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::setPdo($pdo);
        }

        /**
         * Set the PDO connection used for reading.
         *
         * @param \PDO|null $pdo
         * @return $this
         * @static
         */
        public static function setReadPdo($pdo){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::setReadPdo($pdo);
        }

        /**
         * Set the reconnect instance on the connection.
         *
         * @param callable $reconnector
         * @return $this
         * @static
         */
        public static function setReconnector($reconnector){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::setReconnector($reconnector);
        }

        /**
         * Get the database connection name.
         *
         * @return string|null
         * @static
         */
        public static function getName(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getName();
        }

        /**
         * Get an option from the configuration options.
         *
         * @param string $option
         * @return mixed
         * @static
         */
        public static function getConfig($option){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getConfig($option);
        }

        /**
         * Get the PDO driver name.
         *
         * @return string
         * @static
         */
        public static function getDriverName(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getDriverName();
        }

        /**
         * Get the query grammar used by the connection.
         *
         * @return \Illuminate\Database\Query\Grammars\Grammar
         * @static
         */
        public static function getQueryGrammar(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getQueryGrammar();
        }

        /**
         * Set the query grammar used by the connection.
         *
         * @param \Illuminate\Database\Query\Grammars\Grammar $grammar
         * @return void
         * @static
         */
        public static function setQueryGrammar($grammar){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::setQueryGrammar($grammar);
        }

        /**
         * Get the schema grammar used by the connection.
         *
         * @return \Illuminate\Database\Schema\Grammars\Grammar
         * @static
         */
        public static function getSchemaGrammar(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getSchemaGrammar();
        }

        /**
         * Set the schema grammar used by the connection.
         *
         * @param \Illuminate\Database\Schema\Grammars\Grammar $grammar
         * @return void
         * @static
         */
        public static function setSchemaGrammar($grammar){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::setSchemaGrammar($grammar);
        }

        /**
         * Get the query post processor used by the connection.
         *
         * @return \Illuminate\Database\Query\Processors\Processor
         * @static
         */
        public static function getPostProcessor(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getPostProcessor();
        }

        /**
         * Set the query post processor used by the connection.
         *
         * @param \Illuminate\Database\Query\Processors\Processor $processor
         * @return void
         * @static
         */
        public static function setPostProcessor($processor){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::setPostProcessor($processor);
        }

        /**
         * Get the event dispatcher used by the connection.
         *
         * @return \Illuminate\Contracts\Events\Dispatcher
         * @static
         */
        public static function getEventDispatcher(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getEventDispatcher();
        }

        /**
         * Set the event dispatcher instance on the connection.
         *
         * @param \Illuminate\Contracts\Events\Dispatcher $events
         * @return void
         * @static
         */
        public static function setEventDispatcher($events){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::setEventDispatcher($events);
        }

        /**
         * Determine if the connection in a "dry run".
         *
         * @return bool
         * @static
         */
        public static function pretending(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::pretending();
        }

        /**
         * Get the default fetch mode for the connection.
         *
         * @return int
         * @static
         */
        public static function getFetchMode(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getFetchMode();
        }

        /**
         * Set the default fetch mode for the connection.
         *
         * @param int $fetchMode
         * @return int
         * @static
         */
        public static function setFetchMode($fetchMode){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::setFetchMode($fetchMode);
        }

        /**
         * Get the connection query log.
         *
         * @return array
         * @static
         */
        public static function getQueryLog(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getQueryLog();
        }

        /**
         * Clear the query log.
         *
         * @return void
         * @static
         */
        public static function flushQueryLog(){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::flushQueryLog();
        }

        /**
         * Enable the query log on the connection.
         *
         * @return void
         * @static
         */
        public static function enableQueryLog(){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::enableQueryLog();
        }

        /**
         * Disable the query log on the connection.
         *
         * @return void
         * @static
         */
        public static function disableQueryLog(){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::disableQueryLog();
        }

        /**
         * Determine whether we're logging queries.
         *
         * @return bool
         * @static
         */
        public static function logging(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::logging();
        }

        /**
         * Get the name of the connected database.
         *
         * @return string
         * @static
         */
        public static function getDatabaseName(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getDatabaseName();
        }

        /**
         * Set the name of the connected database.
         *
         * @param string $database
         * @return string
         * @static
         */
        public static function setDatabaseName($database){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::setDatabaseName($database);
        }

        /**
         * Get the table prefix for the connection.
         *
         * @return string
         * @static
         */
        public static function getTablePrefix(){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::getTablePrefix();
        }

        /**
         * Set the table prefix in use by the connection.
         *
         * @param string $prefix
         * @return void
         * @static
         */
        public static function setTablePrefix($prefix){
            //Method inherited from \Illuminate\Database\Connection
            \Illuminate\Database\MySqlConnection::setTablePrefix($prefix);
        }

        /**
         * Set the table prefix and return the grammar.
         *
         * @param \Illuminate\Database\Grammar $grammar
         * @return \Illuminate\Database\Grammar
         * @static
         */
        public static function withTablePrefix($grammar){
            //Method inherited from \Illuminate\Database\Connection
            return \Illuminate\Database\MySqlConnection::withTablePrefix($grammar);
        }

    }


    class Cache extends \Illuminate\Support\Facades\Cache{

        /**
         * Get a cache store instance by name.
         *
         * @param string|null $name
         * @return mixed
         * @static
         */
        public static function store($name = null){
            return \Illuminate\Cache\CacheManager::store($name);
        }

        /**
         * Get a cache driver instance.
         *
         * @param string $driver
         * @return mixed
         * @static
         */
        public static function driver($driver = null){
            return \Illuminate\Cache\CacheManager::driver($driver);
        }

        /**
         * Create a new cache repository with the given implementation.
         *
         * @param \Illuminate\Contracts\Cache\Store $store
         * @return \Illuminate\Cache\Repository
         * @static
         */
        public static function repository($store){
            return \Illuminate\Cache\CacheManager::repository($store);
        }

        /**
         * Get the default cache driver name.
         *
         * @return string
         * @static
         */
        public static function getDefaultDriver(){
            return \Illuminate\Cache\CacheManager::getDefaultDriver();
        }

        /**
         * Set the default cache driver name.
         *
         * @param string $name
         * @return void
         * @static
         */
        public static function setDefaultDriver($name){
            \Illuminate\Cache\CacheManager::setDefaultDriver($name);
        }

        /**
         * Register a custom driver creator Closure.
         *
         * @param string $driver
         * @param \Closure $callback
         * @return $this
         * @static
         */
        public static function extend($driver, $callback){
            return \Illuminate\Cache\CacheManager::extend($driver, $callback);
        }

        /**
         * Set the event dispatcher instance.
         *
         * @param \Illuminate\Contracts\Events\Dispatcher $events
         * @return void
         * @static
         */
        public static function setEventDispatcher($events){
            \Illuminate\Cache\Repository::setEventDispatcher($events);
        }

        /**
         * Determine if an item exists in the cache.
         *
         * @param string $key
         * @return bool
         * @static
         */
        public static function has($key){
            return \Illuminate\Cache\Repository::has($key);
        }

        /**
         * Retrieve an item from the cache by key.
         *
         * @param string $key
         * @param mixed $default
         * @return mixed
         * @static
         */
        public static function get($key, $default = null){
            return \Illuminate\Cache\Repository::get($key, $default);
        }

        /**
         * Retrieve an item from the cache and delete it.
         *
         * @param string $key
         * @param mixed $default
         * @return mixed
         * @static
         */
        public static function pull($key, $default = null){
            return \Illuminate\Cache\Repository::pull($key, $default);
        }

        /**
         * Store an item in the cache.
         *
         * @param string $key
         * @param mixed $value
         * @param \DateTime|int $minutes
         * @return void
         * @static
         */
        public static function put($key, $value, $minutes){
            \Illuminate\Cache\Repository::put($key, $value, $minutes);
        }

        /**
         * Store an item in the cache if the key does not exist.
         *
         * @param string $key
         * @param mixed $value
         * @param \DateTime|int $minutes
         * @return bool
         * @static
         */
        public static function add($key, $value, $minutes){
            return \Illuminate\Cache\Repository::add($key, $value, $minutes);
        }

        /**
         * Store an item in the cache indefinitely.
         *
         * @param string $key
         * @param mixed $value
         * @return void
         * @static
         */
        public static function forever($key, $value){
            \Illuminate\Cache\Repository::forever($key, $value);
        }

        /**
         * Get an item from the cache, or store the default value.
         *
         * @param string $key
         * @param \DateTime|int $minutes
         * @param \Closure $callback
         * @return mixed
         * @static
         */
        public static function remember($key, $minutes, $callback){
            return \Illuminate\Cache\Repository::remember($key, $minutes, $callback);
        }

        /**
         * Get an item from the cache, or store the default value forever.
         *
         * @param string $key
         * @param \Closure $callback
         * @return mixed
         * @static
         */
        public static function sear($key, $callback){
            return \Illuminate\Cache\Repository::sear($key, $callback);
        }

        /**
         * Get an item from the cache, or store the default value forever.
         *
         * @param string $key
         * @param \Closure $callback
         * @return mixed
         * @static
         */
        public static function rememberForever($key, $callback){
            return \Illuminate\Cache\Repository::rememberForever($key, $callback);
        }

        /**
         * Remove an item from the cache.
         *
         * @param string $key
         * @return bool
         * @static
         */
        public static function forget($key){
            return \Illuminate\Cache\Repository::forget($key);
        }

        /**
         * Get the default cache time.
         *
         * @return int
         * @static
         */
        public static function getDefaultCacheTime(){
            return \Illuminate\Cache\Repository::getDefaultCacheTime();
        }

        /**
         * Set the default cache time in minutes.
         *
         * @param int $minutes
         * @return void
         * @static
         */
        public static function setDefaultCacheTime($minutes){
            \Illuminate\Cache\Repository::setDefaultCacheTime($minutes);
        }

        /**
         * Get the cache store implementation.
         *
         * @return \Illuminate\Contracts\Cache\Store
         * @static
         */
        public static function getStore(){
            return \Illuminate\Cache\Repository::getStore();
        }

        /**
         * Determine if a cached value exists.
         *
         * @param string $key
         * @return bool
         * @static
         */
        public static function offsetExists($key){
            return \Illuminate\Cache\Repository::offsetExists($key);
        }

        /**
         * Retrieve an item from the cache by key.
         *
         * @param string $key
         * @return mixed
         * @static
         */
        public static function offsetGet($key){
            return \Illuminate\Cache\Repository::offsetGet($key);
        }

        /**
         * Store an item in the cache for the default time.
         *
         * @param string $key
         * @param mixed $value
         * @return void
         * @static
         */
        public static function offsetSet($key, $value){
            \Illuminate\Cache\Repository::offsetSet($key, $value);
        }

        /**
         * Remove an item from the cache.
         *
         * @param string $key
         * @return void
         * @static
         */
        public static function offsetUnset($key){
            \Illuminate\Cache\Repository::offsetUnset($key);
        }

        /**
         * Register a custom macro.
         *
         * @param string $name
         * @param callable $macro
         * @return void
         * @static
         */
        public static function macro($name, $macro){
            \Illuminate\Cache\Repository::macro($name, $macro);
        }

        /**
         * Checks if macro is registered.
         *
         * @param string $name
         * @return bool
         * @static
         */
        public static function hasMacro($name){
            return \Illuminate\Cache\Repository::hasMacro($name);
        }

        /**
         * Dynamically handle calls to the class.
         *
         * @param string $method
         * @param array $parameters
         * @return mixed
         * @throws \BadMethodCallException
         * @static
         */
        public static function macroCall($method, $parameters){
            return \Illuminate\Cache\Repository::macroCall($method, $parameters);
        }

        /**
         * Increment the value of an item in the cache.
         *
         * @param string $key
         * @param mixed $value
         * @return int
         * @static
         */
        public static function increment($key, $value = 1){
            return \Illuminate\Cache\FileStore::increment($key, $value);
        }

        /**
         * Decrement the value of an item in the cache.
         *
         * @param string $key
         * @param mixed $value
         * @return int
         * @static
         */
        public static function decrement($key, $value = 1){
            return \Illuminate\Cache\FileStore::decrement($key, $value);
        }

        /**
         * Remove all items from the cache.
         *
         * @return void
         * @static
         */
        public static function flush(){
            \Illuminate\Cache\FileStore::flush();
        }

        /**
         * Get the Filesystem instance.
         *
         * @return \Illuminate\Filesystem\Filesystem
         * @static
         */
        public static function getFilesystem(){
            return \Illuminate\Cache\FileStore::getFilesystem();
        }

        /**
         * Get the working directory of the cache.
         *
         * @return string
         * @static
         */
        public static function getDirectory(){
            return \Illuminate\Cache\FileStore::getDirectory();
        }

        /**
         * Get the cache key prefix.
         *
         * @return string
         * @static
         */
        public static function getPrefix(){
            return \Illuminate\Cache\FileStore::getPrefix();
        }

    }


    class Cookie extends \Illuminate\Support\Facades\Cookie{

        /**
         * Create a new cookie instance.
         *
         * @param string $name
         * @param string $value
         * @param int $minutes
         * @param string $path
         * @param string $domain
         * @param bool $secure
         * @param bool $httpOnly
         * @return \Symfony\Component\HttpFoundation\Cookie
         * @static
         */
        public static function make($name, $value, $minutes = 0, $path = null, $domain = null, $secure = false, $httpOnly = true){
            return \Illuminate\Cookie\CookieJar::make($name, $value, $minutes, $path, $domain, $secure, $httpOnly);
        }

        /**
         * Create a cookie that lasts "forever" (five years).
         *
         * @param string $name
         * @param string $value
         * @param string $path
         * @param string $domain
         * @param bool $secure
         * @param bool $httpOnly
         * @return \Symfony\Component\HttpFoundation\Cookie
         * @static
         */
        public static function forever($name, $value, $path = null, $domain = null, $secure = false, $httpOnly = true){
            return \Illuminate\Cookie\CookieJar::forever($name, $value, $path, $domain, $secure, $httpOnly);
        }

        /**
         * Expire the given cookie.
         *
         * @param string $name
         * @param string $path
         * @param string $domain
         * @return \Symfony\Component\HttpFoundation\Cookie
         * @static
         */
        public static function forget($name, $path = null, $domain = null){
            return \Illuminate\Cookie\CookieJar::forget($name, $path, $domain);
        }

        /**
         * Determine if a cookie has been queued.
         *
         * @param string $key
         * @return bool
         * @static
         */
        public static function hasQueued($key){
            return \Illuminate\Cookie\CookieJar::hasQueued($key);
        }

        /**
         * Get a queued cookie instance.
         *
         * @param string $key
         * @param mixed $default
         * @return \Symfony\Component\HttpFoundation\Cookie
         * @static
         */
        public static function queued($key, $default = null){
            return \Illuminate\Cookie\CookieJar::queued($key, $default);
        }

        /**
         * Queue a cookie to send with the next response.
         *
         * @param mixed
         * @return void
         * @static
         */
        public static function queue(){
            \Illuminate\Cookie\CookieJar::queue();
        }

        /**
         * Remove a cookie from the queue.
         *
         * @param string $name
         * @static
         */
        public static function unqueue($name){
            return \Illuminate\Cookie\CookieJar::unqueue($name);
        }

        /**
         * Set the default path and domain for the jar.
         *
         * @param string $path
         * @param string $domain
         * @return $this
         * @static
         */
        public static function setDefaultPathAndDomain($path, $domain){
            return \Illuminate\Cookie\CookieJar::setDefaultPathAndDomain($path, $domain);
        }

        /**
         * Get the cookies which have been queued for the next request.
         *
         * @return array
         * @static
         */
        public static function getQueuedCookies(){
            return \Illuminate\Cookie\CookieJar::getQueuedCookies();
        }

    }


    class Crypt extends \Illuminate\Support\Facades\Crypt{

        /**
         * Determine if the given key and cipher combination is valid.
         *
         * @param string $key
         * @param string $cipher
         * @return bool
         * @static
         */
        public static function supported($key, $cipher){
            return \Illuminate\Encryption\Encrypter::supported($key, $cipher);
        }

        /**
         * Encrypt the given value.
         *
         * @param string $value
         * @return string
         * @static
         */
        public static function encrypt($value){
            return \Illuminate\Encryption\Encrypter::encrypt($value);
        }

        /**
         * Decrypt the given value.
         *
         * @param string $payload
         * @return string
         * @static
         */
        public static function decrypt($payload){
            return \Illuminate\Encryption\Encrypter::decrypt($payload);
        }

    }


    class Event extends \Illuminate\Support\Facades\Event{

        /**
         * Register an event listener with the dispatcher.
         *
         * @param string|array $events
         * @param mixed $listener
         * @param int $priority
         * @return void
         * @static
         */
        public static function listen($events, $listener, $priority = 0){
            \Illuminate\Events\Dispatcher::listen($events, $listener, $priority);
        }

        /**
         * Determine if a given event has listeners.
         *
         * @param string $eventName
         * @return bool
         * @static
         */
        public static function hasListeners($eventName){
            return \Illuminate\Events\Dispatcher::hasListeners($eventName);
        }

        /**
         * Register an event and payload to be fired later.
         *
         * @param string $event
         * @param array $payload
         * @return void
         * @static
         */
        public static function push($event, $payload = array()){
            \Illuminate\Events\Dispatcher::push($event, $payload);
        }

        /**
         * Register an event subscriber with the dispatcher.
         *
         * @param object|string $subscriber
         * @return void
         * @static
         */
        public static function subscribe($subscriber){
            \Illuminate\Events\Dispatcher::subscribe($subscriber);
        }

        /**
         * Fire an event until the first non-null response is returned.
         *
         * @param string|object $event
         * @param array $payload
         * @return mixed
         * @static
         */
        public static function until($event, $payload = array()){
            return \Illuminate\Events\Dispatcher::until($event, $payload);
        }

        /**
         * Flush a set of pushed events.
         *
         * @param string $event
         * @return void
         * @static
         */
        public static function flush($event){
            \Illuminate\Events\Dispatcher::flush($event);
        }

        /**
         * Get the event that is currently firing.
         *
         * @return string
         * @static
         */
        public static function firing(){
            return \Illuminate\Events\Dispatcher::firing();
        }

        /**
         * Fire an event and call the listeners.
         *
         * @param string|object $event
         * @param mixed $payload
         * @param bool $halt
         * @return array|null
         * @static
         */
        public static function fire($event, $payload = array(), $halt = false){
            return \Illuminate\Events\Dispatcher::fire($event, $payload, $halt);
        }

        /**
         * Get all of the listeners for a given event name.
         *
         * @param string $eventName
         * @return array
         * @static
         */
        public static function getListeners($eventName){
            return \Illuminate\Events\Dispatcher::getListeners($eventName);
        }

        /**
         * Register an event listener with the dispatcher.
         *
         * @param mixed $listener
         * @return mixed
         * @static
         */
        public static function makeListener($listener){
            return \Illuminate\Events\Dispatcher::makeListener($listener);
        }

        /**
         * Create a class based listener using the IoC container.
         *
         * @param mixed $listener
         * @return \Closure
         * @static
         */
        public static function createClassListener($listener){
            return \Illuminate\Events\Dispatcher::createClassListener($listener);
        }

        /**
         * Remove a set of listeners from the dispatcher.
         *
         * @param string $event
         * @return void
         * @static
         */
        public static function forget($event){
            \Illuminate\Events\Dispatcher::forget($event);
        }

        /**
         * Forget all of the pushed listeners.
         *
         * @return void
         * @static
         */
        public static function forgetPushed(){
            \Illuminate\Events\Dispatcher::forgetPushed();
        }

        /**
         * Set the queue resolver implementation.
         *
         * @param callable $resolver
         * @return $this
         * @static
         */
        public static function setQueueResolver($resolver){
            return \Illuminate\Events\Dispatcher::setQueueResolver($resolver);
        }

    }


    class Hash extends \Illuminate\Support\Facades\Hash{

        /**
         * Hash the given value.
         *
         * @param string $value
         * @param array $options
         * @return string
         * @throws \RuntimeException
         * @static
         */
        public static function make($value, $options = array()){
            return \Illuminate\Hashing\BcryptHasher::make($value, $options);
        }

        /**
         * Check the given plain value against a hash.
         *
         * @param string $value
         * @param string $hashedValue
         * @param array $options
         * @return bool
         * @static
         */
        public static function check($value, $hashedValue, $options = array()){
            return \Illuminate\Hashing\BcryptHasher::check($value, $hashedValue, $options);
        }

        /**
         * Check if the given hash has been hashed using the given options.
         *
         * @param string $hashedValue
         * @param array $options
         * @return bool
         * @static
         */
        public static function needsRehash($hashedValue, $options = array()){
            return \Illuminate\Hashing\BcryptHasher::needsRehash($hashedValue, $options);
        }

        /**
         * Set the default password work factor.
         *
         * @param int $rounds
         * @return $this
         * @static
         */
        public static function setRounds($rounds){
            return \Illuminate\Hashing\BcryptHasher::setRounds($rounds);
        }

    }


    class Log extends \Illuminate\Support\Facades\Log{

        /**
         * Adds a log record at the DEBUG level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function debug($message, $context = array()){
            return \Monolog\Logger::debug($message, $context);
        }

        /**
         * Adds a log record at the INFO level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function info($message, $context = array()){
            return \Monolog\Logger::info($message, $context);
        }

        /**
         * Adds a log record at the NOTICE level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function notice($message, $context = array()){
            return \Monolog\Logger::notice($message, $context);
        }

        /**
         * Adds a log record at the WARNING level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function warning($message, $context = array()){
            return \Monolog\Logger::warning($message, $context);
        }

        /**
         * Adds a log record at the ERROR level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function error($message, $context = array()){
            return \Monolog\Logger::error($message, $context);
        }

        /**
         * Adds a log record at the CRITICAL level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function critical($message, $context = array()){
            return \Monolog\Logger::critical($message, $context);
        }

        /**
         * Adds a log record at the ALERT level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function alert($message, $context = array()){
            return \Monolog\Logger::alert($message, $context);
        }

        /**
         * Adds a log record at the EMERGENCY level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function emergency($message, $context = array()){
            return \Monolog\Logger::emergency($message, $context);
        }

        /**
         *
         *
         * @return string
         * @static
         */
        public static function getName(){
            return \Monolog\Logger::getName();
        }

        /**
         * Pushes a handler on to the stack.
         *
         * @param \Monolog\HandlerInterface $handler
         * @return $this
         * @static
         */
        public static function pushHandler($handler){
            return \Monolog\Logger::pushHandler($handler);
        }

        /**
         * Pops a handler from the stack
         *
         * @return \Monolog\HandlerInterface
         * @static
         */
        public static function popHandler(){
            return \Monolog\Logger::popHandler();
        }

        /**
         * Set handlers, replacing all existing ones.
         *
         * If a map is passed, keys will be ignored.
         *
         * @param \Monolog\HandlerInterface[] $handlers
         * @return $this
         * @static
         */
        public static function setHandlers($handlers){
            return \Monolog\Logger::setHandlers($handlers);
        }

        /**
         *
         *
         * @return \Monolog\HandlerInterface[]
         * @static
         */
        public static function getHandlers(){
            return \Monolog\Logger::getHandlers();
        }

        /**
         * Adds a processor on to the stack.
         *
         * @param callable $callback
         * @return $this
         * @static
         */
        public static function pushProcessor($callback){
            return \Monolog\Logger::pushProcessor($callback);
        }

        /**
         * Removes the processor on top of the stack and returns it.
         *
         * @return callable
         * @static
         */
        public static function popProcessor(){
            return \Monolog\Logger::popProcessor();
        }

        /**
         *
         *
         * @return callable[]
         * @static
         */
        public static function getProcessors(){
            return \Monolog\Logger::getProcessors();
        }

        /**
         * Adds a log record.
         *
         * @param integer $level The logging level
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function addRecord($level, $message, $context = array()){
            return \Monolog\Logger::addRecord($level, $message, $context);
        }

        /**
         * Adds a log record at the DEBUG level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function addDebug($message, $context = array()){
            return \Monolog\Logger::addDebug($message, $context);
        }

        /**
         * Adds a log record at the INFO level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function addInfo($message, $context = array()){
            return \Monolog\Logger::addInfo($message, $context);
        }

        /**
         * Adds a log record at the NOTICE level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function addNotice($message, $context = array()){
            return \Monolog\Logger::addNotice($message, $context);
        }

        /**
         * Adds a log record at the WARNING level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function addWarning($message, $context = array()){
            return \Monolog\Logger::addWarning($message, $context);
        }

        /**
         * Adds a log record at the ERROR level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function addError($message, $context = array()){
            return \Monolog\Logger::addError($message, $context);
        }

        /**
         * Adds a log record at the CRITICAL level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function addCritical($message, $context = array()){
            return \Monolog\Logger::addCritical($message, $context);
        }

        /**
         * Adds a log record at the ALERT level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function addAlert($message, $context = array()){
            return \Monolog\Logger::addAlert($message, $context);
        }

        /**
         * Adds a log record at the EMERGENCY level.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function addEmergency($message, $context = array()){
            return \Monolog\Logger::addEmergency($message, $context);
        }

        /**
         * Gets all supported logging levels.
         *
         * @return array Assoc array with human-readable level names => level codes.
         * @static
         */
        public static function getLevels(){
            return \Monolog\Logger::getLevels();
        }

        /**
         * Gets the name of the logging level.
         *
         * @param integer $level
         * @return string
         * @static
         */
        public static function getLevelName($level){
            return \Monolog\Logger::getLevelName($level);
        }

        /**
         * Converts PSR-3 levels to Monolog ones if necessary
         *
         * @param string|int  Level number (monolog) or name (PSR-3)
         * @return int
         * @static
         */
        public static function toMonologLevel($level){
            return \Monolog\Logger::toMonologLevel($level);
        }

        /**
         * Checks whether the Logger has a handler that listens on the given level
         *
         * @param integer $level
         * @return Boolean
         * @static
         */
        public static function isHandling($level){
            return \Monolog\Logger::isHandling($level);
        }

        /**
         * Adds a log record at an arbitrary level.
         *
         * This method allows for compatibility with common interfaces.
         *
         * @param mixed $level The log level
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function log($level, $message, $context = array()){
            return \Monolog\Logger::log($level, $message, $context);
        }

        /**
         * Adds a log record at the WARNING level.
         *
         * This method allows for compatibility with common interfaces.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function warn($message, $context = array()){
            return \Monolog\Logger::warn($message, $context);
        }

        /**
         * Adds a log record at the ERROR level.
         *
         * This method allows for compatibility with common interfaces.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function err($message, $context = array()){
            return \Monolog\Logger::err($message, $context);
        }

        /**
         * Adds a log record at the CRITICAL level.
         *
         * This method allows for compatibility with common interfaces.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function crit($message, $context = array()){
            return \Monolog\Logger::crit($message, $context);
        }

        /**
         * Adds a log record at the EMERGENCY level.
         *
         * This method allows for compatibility with common interfaces.
         *
         * @param string $message The log message
         * @param array $context The log context
         * @return Boolean Whether the record has been processed
         * @static
         */
        public static function emerg($message, $context = array()){
            return \Monolog\Logger::emerg($message, $context);
        }

        /**
         * Set the timezone to be used for the timestamp of log records.
         *
         * This is stored globally for all Logger instances
         *
         * @param \DateTimeZone $tz Timezone object
         * @static
         */
        public static function setTimezone($tz){
            return \Monolog\Logger::setTimezone($tz);
        }

    }


    class Mail extends \Illuminate\Support\Facades\Mail{

        /**
         * Set the global from address and name.
         *
         * @param string $address
         * @param string|null $name
         * @return void
         * @static
         */
        public static function alwaysFrom($address, $name = null){
            \Illuminate\Mail\Mailer::alwaysFrom($address, $name);
        }

        /**
         * Set the global to address and name.
         *
         * @param string $address
         * @param string|null $name
         * @return void
         * @static
         */
        public static function alwaysTo($address, $name = null){
            \Illuminate\Mail\Mailer::alwaysTo($address, $name);
        }

        /**
         * Send a new message when only a raw text part.
         *
         * @param string $text
         * @param mixed $callback
         * @return int
         * @static
         */
        public static function raw($text, $callback){
            return \Illuminate\Mail\Mailer::raw($text, $callback);
        }

        /**
         * Send a new message when only a plain part.
         *
         * @param string $view
         * @param array $data
         * @param mixed $callback
         * @return int
         * @static
         */
        public static function plain($view, $data, $callback){
            return \Illuminate\Mail\Mailer::plain($view, $data, $callback);
        }

        /**
         * Send a new message using a view.
         *
         * @param string|array $view
         * @param array $data
         * @param \Closure|string $callback
         * @return mixed
         * @static
         */
        public static function send($view, $data, $callback){
            return \Illuminate\Mail\Mailer::send($view, $data, $callback);
        }

        /**
         * Queue a new e-mail message for sending.
         *
         * @param string|array $view
         * @param array $data
         * @param \Closure|string $callback
         * @param string|null $queue
         * @return mixed
         * @static
         */
        public static function queue($view, $data, $callback, $queue = null){
            return \Illuminate\Mail\Mailer::queue($view, $data, $callback, $queue);
        }

        /**
         * Queue a new e-mail message for sending on the given queue.
         *
         * @param string $queue
         * @param string|array $view
         * @param array $data
         * @param \Closure|string $callback
         * @return mixed
         * @static
         */
        public static function queueOn($queue, $view, $data, $callback){
            return \Illuminate\Mail\Mailer::queueOn($queue, $view, $data, $callback);
        }

        /**
         * Queue a new e-mail message for sending after (n) seconds.
         *
         * @param int $delay
         * @param string|array $view
         * @param array $data
         * @param \Closure|string $callback
         * @param string|null $queue
         * @return mixed
         * @static
         */
        public static function later($delay, $view, $data, $callback, $queue = null){
            return \Illuminate\Mail\Mailer::later($delay, $view, $data, $callback, $queue);
        }

        /**
         * Queue a new e-mail message for sending after (n) seconds on the given queue.
         *
         * @param string $queue
         * @param int $delay
         * @param string|array $view
         * @param array $data
         * @param \Closure|string $callback
         * @return mixed
         * @static
         */
        public static function laterOn($queue, $delay, $view, $data, $callback){
            return \Illuminate\Mail\Mailer::laterOn($queue, $delay, $view, $data, $callback);
        }

        /**
         * Handle a queued e-mail message job.
         *
         * @param \Illuminate\Contracts\Queue\Job $job
         * @param array $data
         * @return void
         * @static
         */
        public static function handleQueuedMessage($job, $data){
            \Illuminate\Mail\Mailer::handleQueuedMessage($job, $data);
        }

        /**
         * Tell the mailer to not really send messages.
         *
         * @param bool $value
         * @return void
         * @static
         */
        public static function pretend($value = true){
            \Illuminate\Mail\Mailer::pretend($value);
        }

        /**
         * Check if the mailer is pretending to send messages.
         *
         * @return bool
         * @static
         */
        public static function isPretending(){
            return \Illuminate\Mail\Mailer::isPretending();
        }

        /**
         * Get the view factory instance.
         *
         * @return \Illuminate\Contracts\View\Factory
         * @static
         */
        public static function getViewFactory(){
            return \Illuminate\Mail\Mailer::getViewFactory();
        }

        /**
         * Get the Swift Mailer instance.
         *
         * @return \Swift_Mailer
         * @static
         */
        public static function getSwiftMailer(){
            return \Illuminate\Mail\Mailer::getSwiftMailer();
        }

        /**
         * Get the array of failed recipients.
         *
         * @return array
         * @static
         */
        public static function failures(){
            return \Illuminate\Mail\Mailer::failures();
        }

        /**
         * Set the Swift Mailer instance.
         *
         * @param \Swift_Mailer $swift
         * @return void
         * @static
         */
        public static function setSwiftMailer($swift){
            \Illuminate\Mail\Mailer::setSwiftMailer($swift);
        }

        /**
         * Set the log writer instance.
         *
         * @param \Psr\Log\LoggerInterface $logger
         * @return $this
         * @static
         */
        public static function setLogger($logger){
            return \Illuminate\Mail\Mailer::setLogger($logger);
        }

        /**
         * Set the queue manager instance.
         *
         * @param \Illuminate\Contracts\Queue\Queue $queue
         * @return $this
         * @static
         */
        public static function setQueue($queue){
            return \Illuminate\Mail\Mailer::setQueue($queue);
        }

        /**
         * Set the IoC container instance.
         *
         * @param \Illuminate\Contracts\Container\Container $container
         * @return void
         * @static
         */
        public static function setContainer($container){
            \Illuminate\Mail\Mailer::setContainer($container);
        }

    }


    class Queue extends \Illuminate\Support\Facades\Queue{

        /**
         * Register an event listener for the daemon queue loop.
         *
         * @param mixed $callback
         * @return void
         * @static
         */
        public static function looping($callback){
            \Illuminate\Queue\QueueManager::looping($callback);
        }

        /**
         * Register an event listener for the failed job event.
         *
         * @param mixed $callback
         * @return void
         * @static
         */
        public static function failing($callback){
            \Illuminate\Queue\QueueManager::failing($callback);
        }

        /**
         * Register an event listener for the daemon queue stopping.
         *
         * @param mixed $callback
         * @return void
         * @static
         */
        public static function stopping($callback){
            \Illuminate\Queue\QueueManager::stopping($callback);
        }

        /**
         * Determine if the driver is connected.
         *
         * @param string $name
         * @return bool
         * @static
         */
        public static function connected($name = null){
            return \Illuminate\Queue\QueueManager::connected($name);
        }

        /**
         * Resolve a queue connection instance.
         *
         * @param string $name
         * @return \Illuminate\Contracts\Queue\Queue
         * @static
         */
        public static function connection($name = null){
            return \Illuminate\Queue\QueueManager::connection($name);
        }

        /**
         * Add a queue connection resolver.
         *
         * @param string $driver
         * @param \Closure $resolver
         * @return void
         * @static
         */
        public static function extend($driver, $resolver){
            \Illuminate\Queue\QueueManager::extend($driver, $resolver);
        }

        /**
         * Add a queue connection resolver.
         *
         * @param string $driver
         * @param \Closure $resolver
         * @return void
         * @static
         */
        public static function addConnector($driver, $resolver){
            \Illuminate\Queue\QueueManager::addConnector($driver, $resolver);
        }

        /**
         * Get the name of the default queue connection.
         *
         * @return string
         * @static
         */
        public static function getDefaultDriver(){
            return \Illuminate\Queue\QueueManager::getDefaultDriver();
        }

        /**
         * Set the name of the default queue connection.
         *
         * @param string $name
         * @return void
         * @static
         */
        public static function setDefaultDriver($name){
            \Illuminate\Queue\QueueManager::setDefaultDriver($name);
        }

        /**
         * Get the full name for the given connection.
         *
         * @param string $connection
         * @return string
         * @static
         */
        public static function getName($connection = null){
            return \Illuminate\Queue\QueueManager::getName($connection);
        }

        /**
         * Determine if the application is in maintenance mode.
         *
         * @return bool
         * @static
         */
        public static function isDownForMaintenance(){
            return \Illuminate\Queue\QueueManager::isDownForMaintenance();
        }

        /**
         * Push a new job onto the queue.
         *
         * @param string $job
         * @param mixed $data
         * @param string $queue
         * @return mixed
         * @throws \Throwable
         * @static
         */
        public static function push($job, $data = '', $queue = null){
            return \Illuminate\Queue\SyncQueue::push($job, $data, $queue);
        }

        /**
         * Push a raw payload onto the queue.
         *
         * @param string $payload
         * @param string $queue
         * @param array $options
         * @return mixed
         * @static
         */
        public static function pushRaw($payload, $queue = null, $options = array()){
            return \Illuminate\Queue\SyncQueue::pushRaw($payload, $queue, $options);
        }

        /**
         * Push a new job onto the queue after a delay.
         *
         * @param \DateTime|int $delay
         * @param string $job
         * @param mixed $data
         * @param string $queue
         * @return mixed
         * @static
         */
        public static function later($delay, $job, $data = '', $queue = null){
            return \Illuminate\Queue\SyncQueue::later($delay, $job, $data, $queue);
        }

        /**
         * Pop the next job off of the queue.
         *
         * @param string $queue
         * @return \Illuminate\Contracts\Queue\Job|null
         * @static
         */
        public static function pop($queue = null){
            return \Illuminate\Queue\SyncQueue::pop($queue);
        }

        /**
         * Push a new job onto the queue.
         *
         * @param string $queue
         * @param string $job
         * @param mixed $data
         * @return mixed
         * @static
         */
        public static function pushOn($queue, $job, $data = ''){
            //Method inherited from \Illuminate\Queue\Queue
            return \Illuminate\Queue\SyncQueue::pushOn($queue, $job, $data);
        }

        /**
         * Push a new job onto the queue after a delay.
         *
         * @param string $queue
         * @param \DateTime|int $delay
         * @param string $job
         * @param mixed $data
         * @return mixed
         * @static
         */
        public static function laterOn($queue, $delay, $job, $data = ''){
            //Method inherited from \Illuminate\Queue\Queue
            return \Illuminate\Queue\SyncQueue::laterOn($queue, $delay, $job, $data);
        }

        /**
         * Marshal a push queue request and fire the job.
         *
         * @throws \RuntimeException
         * @deprecated since version 5.1.
         * @static
         */
        public static function marshal(){
            //Method inherited from \Illuminate\Queue\Queue
            return \Illuminate\Queue\SyncQueue::marshal();
        }

        /**
         * Push an array of jobs onto the queue.
         *
         * @param array $jobs
         * @param mixed $data
         * @param string $queue
         * @return mixed
         * @static
         */
        public static function bulk($jobs, $data = '', $queue = null){
            //Method inherited from \Illuminate\Queue\Queue
            return \Illuminate\Queue\SyncQueue::bulk($jobs, $data, $queue);
        }

        /**
         * Set the IoC container instance.
         *
         * @param \Illuminate\Container\Container $container
         * @return void
         * @static
         */
        public static function setContainer($container){
            //Method inherited from \Illuminate\Queue\Queue
            \Illuminate\Queue\SyncQueue::setContainer($container);
        }

        /**
         * Set the encrypter instance.
         *
         * @param \Illuminate\Contracts\Encryption\Encrypter $crypt
         * @return void
         * @static
         */
        public static function setEncrypter($crypt){
            //Method inherited from \Illuminate\Queue\Queue
            \Illuminate\Queue\SyncQueue::setEncrypter($crypt);
        }

    }


    class Request extends \Illuminate\Support\Facades\Request{

        /**
         * Create a new Illuminate HTTP request from server variables.
         *
         * @return static
         * @static
         */
        public static function capture(){
            return \Illuminate\Http\Request::capture();
        }

        /**
         * Return the Request instance.
         *
         * @return $this
         * @static
         */
        public static function instance(){
            return \Illuminate\Http\Request::instance();
        }

        /**
         * Get the request method.
         *
         * @return string
         * @static
         */
        public static function method(){
            return \Illuminate\Http\Request::method();
        }

        /**
         * Get the root URL for the application.
         *
         * @return string
         * @static
         */
        public static function root(){
            return \Illuminate\Http\Request::root();
        }

        /**
         * Get the URL (no query string) for the request.
         *
         * @return string
         * @static
         */
        public static function url(){
            return \Illuminate\Http\Request::url();
        }

        /**
         * Get the full URL for the request.
         *
         * @return string
         * @static
         */
        public static function fullUrl(){
            return \Illuminate\Http\Request::fullUrl();
        }

        /**
         * Get the current path info for the request.
         *
         * @return string
         * @static
         */
        public static function path(){
            return \Illuminate\Http\Request::path();
        }

        /**
         * Get the current encoded path info for the request.
         *
         * @return string
         * @static
         */
        public static function decodedPath(){
            return \Illuminate\Http\Request::decodedPath();
        }

        /**
         * Get a segment from the URI (1 based index).
         *
         * @param int $index
         * @param mixed $default
         * @return string
         * @static
         */
        public static function segment($index, $default = null){
            return \Illuminate\Http\Request::segment($index, $default);
        }

        /**
         * Get all of the segments for the request path.
         *
         * @return array
         * @static
         */
        public static function segments(){
            return \Illuminate\Http\Request::segments();
        }

        /**
         * Determine if the current request URI matches a pattern.
         *
         * @param mixed  string
         * @return bool
         * @static
         */
        public static function is(){
            return \Illuminate\Http\Request::is();
        }

        /**
         * Determine if the request is the result of an AJAX call.
         *
         * @return bool
         * @static
         */
        public static function ajax(){
            return \Illuminate\Http\Request::ajax();
        }

        /**
         * Determine if the request is the result of an PJAX call.
         *
         * @return bool
         * @static
         */
        public static function pjax(){
            return \Illuminate\Http\Request::pjax();
        }

        /**
         * Determine if the request is over HTTPS.
         *
         * @return bool
         * @static
         */
        public static function secure(){
            return \Illuminate\Http\Request::secure();
        }

        /**
         * Returns the client IP address.
         *
         * @return string
         * @static
         */
        public static function ip(){
            return \Illuminate\Http\Request::ip();
        }

        /**
         * Returns the client IP addresses.
         *
         * @return array
         * @static
         */
        public static function ips(){
            return \Illuminate\Http\Request::ips();
        }

        /**
         * Determine if the request contains a given input item key.
         *
         * @param string|array $key
         * @return bool
         * @static
         */
        public static function exists($key){
            return \Illuminate\Http\Request::exists($key);
        }

        /**
         * Determine if the request contains a non-empty value for an input item.
         *
         * @param string|array $key
         * @return bool
         * @static
         */
        public static function has($key){
            return \Illuminate\Http\Request::has($key);
        }

        /**
         * Get all of the input and files for the request.
         *
         * @return array
         * @static
         */
        public static function all(){
            return \Illuminate\Http\Request::all();
        }

        /**
         * Retrieve an input item from the request.
         *
         * @param string $key
         * @param mixed $default
         * @return string|array
         * @static
         */
        public static function input($key = null, $default = null){
            return \Illuminate\Http\Request::input($key, $default);
        }

        /**
         * Get a subset of the items from the input data.
         *
         * @param array $keys
         * @return array
         * @static
         */
        public static function only($keys){
            return \Illuminate\Http\Request::only($keys);
        }

        /**
         * Get all of the input except for a specified array of items.
         *
         * @param array $keys
         * @return array
         * @static
         */
        public static function except($keys){
            return \Illuminate\Http\Request::except($keys);
        }

        /**
         * Retrieve a query string item from the request.
         *
         * @param string $key
         * @param mixed $default
         * @return string|array
         * @static
         */
        public static function query($key = null, $default = null){
            return \Illuminate\Http\Request::query($key, $default);
        }

        /**
         * Determine if a cookie is set on the request.
         *
         * @param string $key
         * @return bool
         * @static
         */
        public static function hasCookie($key){
            return \Illuminate\Http\Request::hasCookie($key);
        }

        /**
         * Retrieve a cookie from the request.
         *
         * @param string $key
         * @param mixed $default
         * @return string|array
         * @static
         */
        public static function cookie($key = null, $default = null){
            return \Illuminate\Http\Request::cookie($key, $default);
        }

        /**
         * Retrieve a file from the request.
         *
         * @param string $key
         * @param mixed $default
         * @return \Symfony\Component\HttpFoundation\File\UploadedFile|array
         * @static
         */
        public static function file($key = null, $default = null){
            return \Illuminate\Http\Request::file($key, $default);
        }

        /**
         * Determine if the uploaded data contains a file.
         *
         * @param string $key
         * @return bool
         * @static
         */
        public static function hasFile($key){
            return \Illuminate\Http\Request::hasFile($key);
        }

        /**
         * Retrieve a header from the request.
         *
         * @param string $key
         * @param mixed $default
         * @return string|array
         * @static
         */
        public static function header($key = null, $default = null){
            return \Illuminate\Http\Request::header($key, $default);
        }

        /**
         * Retrieve a server variable from the request.
         *
         * @param string $key
         * @param mixed $default
         * @return string|array
         * @static
         */
        public static function server($key = null, $default = null){
            return \Illuminate\Http\Request::server($key, $default);
        }

        /**
         * Retrieve an old input item.
         *
         * @param string $key
         * @param mixed $default
         * @return mixed
         * @static
         */
        public static function old($key = null, $default = null){
            return \Illuminate\Http\Request::old($key, $default);
        }

        /**
         * Flash the input for the current request to the session.
         *
         * @param string $filter
         * @param array $keys
         * @return void
         * @static
         */
        public static function flash($filter = null, $keys = array()){
            \Illuminate\Http\Request::flash($filter, $keys);
        }

        /**
         * Flash only some of the input to the session.
         *
         * @param mixed  string
         * @return void
         * @static
         */
        public static function flashOnly($keys){
            \Illuminate\Http\Request::flashOnly($keys);
        }

        /**
         * Flash only some of the input to the session.
         *
         * @param mixed  string
         * @return void
         * @static
         */
        public static function flashExcept($keys){
            \Illuminate\Http\Request::flashExcept($keys);
        }

        /**
         * Flush all of the old input from the session.
         *
         * @return void
         * @static
         */
        public static function flush(){
            \Illuminate\Http\Request::flush();
        }

        /**
         * Merge new input into the current request's input array.
         *
         * @param array $input
         * @return void
         * @static
         */
        public static function merge($input){
            \Illuminate\Http\Request::merge($input);
        }

        /**
         * Replace the input for the current request.
         *
         * @param array $input
         * @return void
         * @static
         */
        public static function replace($input){
            \Illuminate\Http\Request::replace($input);
        }

        /**
         * Get the JSON payload for the request.
         *
         * @param string $key
         * @param mixed $default
         * @return mixed
         * @static
         */
        public static function json($key = null, $default = null){
            return \Illuminate\Http\Request::json($key, $default);
        }

        /**
         * Determine if the given content types match.
         *
         * @return bool
         * @static
         */
        public static function matchesType($actual, $type){
            return \Illuminate\Http\Request::matchesType($actual, $type);
        }

        /**
         * Determine if the request is sending JSON.
         *
         * @return bool
         * @static
         */
        public static function isJson(){
            return \Illuminate\Http\Request::isJson();
        }

        /**
         * Determine if the current request is asking for JSON in return.
         *
         * @return bool
         * @static
         */
        public static function wantsJson(){
            return \Illuminate\Http\Request::wantsJson();
        }

        /**
         * Determines whether the current requests accepts a given content type.
         *
         * @param string|array $contentTypes
         * @return bool
         * @static
         */
        public static function accepts($contentTypes){
            return \Illuminate\Http\Request::accepts($contentTypes);
        }

        /**
         * Return the most suitable content type from the given array based on content negotiation.
         *
         * @param string|array $contentTypes
         * @return string|null
         * @static
         */
        public static function prefers($contentTypes){
            return \Illuminate\Http\Request::prefers($contentTypes);
        }

        /**
         * Determines whether a request accepts JSON.
         *
         * @return bool
         * @static
         */
        public static function acceptsJson(){
            return \Illuminate\Http\Request::acceptsJson();
        }

        /**
         * Determines whether a request accepts HTML.
         *
         * @return bool
         * @static
         */
        public static function acceptsHtml(){
            return \Illuminate\Http\Request::acceptsHtml();
        }

        /**
         * Get the data format expected in the response.
         *
         * @param string $default
         * @return string
         * @static
         */
        public static function format($default = 'html'){
            return \Illuminate\Http\Request::format($default);
        }

        /**
         * Create an Illuminate request from a Symfony instance.
         *
         * @param \Symfony\Component\HttpFoundation\Request $request
         * @return \Illuminate\Http\Request
         * @static
         */
        public static function createFromBase($request){
            return \Illuminate\Http\Request::createFromBase($request);
        }

        /**
         * Clones a request and overrides some of its parameters.
         *
         * @param array $query The GET parameters
         * @param array $request The POST parameters
         * @param array $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
         * @param array $cookies The COOKIE parameters
         * @param array $files The FILES parameters
         * @param array $server The SERVER parameters
         * @return \Symfony\Component\HttpFoundation\Request The duplicated request
         * @api
         * @static
         */
        public static function duplicate($query = null, $request = null, $attributes = null, $cookies = null, $files = null, $server = null){
            return \Illuminate\Http\Request::duplicate($query, $request, $attributes, $cookies, $files, $server);
        }

        /**
         * Get the session associated with the request.
         *
         * @return \Illuminate\Session\Store
         * @throws \RuntimeException
         * @static
         */
        public static function session(){
            return \Illuminate\Http\Request::session();
        }

        /**
         * Get the user making the request.
         *
         * @return mixed
         * @static
         */
        public static function user(){
            return \Illuminate\Http\Request::user();
        }

        /**
         * Get the route handling the request.
         *
         * @param string|null $param
         * @return object|string
         * @static
         */
        public static function route($param = null){
            return \Illuminate\Http\Request::route($param);
        }

        /**
         * Get the user resolver callback.
         *
         * @return \Closure
         * @static
         */
        public static function getUserResolver(){
            return \Illuminate\Http\Request::getUserResolver();
        }

        /**
         * Set the user resolver callback.
         *
         * @param \Closure $callback
         * @return $this
         * @static
         */
        public static function setUserResolver($callback){
            return \Illuminate\Http\Request::setUserResolver($callback);
        }

        /**
         * Get the route resolver callback.
         *
         * @return \Closure
         * @static
         */
        public static function getRouteResolver(){
            return \Illuminate\Http\Request::getRouteResolver();
        }

        /**
         * Set the route resolver callback.
         *
         * @param \Closure $callback
         * @return $this
         * @static
         */
        public static function setRouteResolver($callback){
            return \Illuminate\Http\Request::setRouteResolver($callback);
        }

        /**
         * Determine if the given offset exists.
         *
         * @param string $offset
         * @return bool
         * @static
         */
        public static function offsetExists($offset){
            return \Illuminate\Http\Request::offsetExists($offset);
        }

        /**
         * Get the value at the given offset.
         *
         * @param string $offset
         * @return mixed
         * @static
         */
        public static function offsetGet($offset){
            return \Illuminate\Http\Request::offsetGet($offset);
        }

        /**
         * Set the value at the given offset.
         *
         * @param string $offset
         * @param mixed $value
         * @return void
         * @static
         */
        public static function offsetSet($offset, $value){
            \Illuminate\Http\Request::offsetSet($offset, $value);
        }

        /**
         * Remove the value at the given offset.
         *
         * @param string $offset
         * @return void
         * @static
         */
        public static function offsetUnset($offset){
            \Illuminate\Http\Request::offsetUnset($offset);
        }

        /**
         * Sets the parameters for this request.
         *
         * This method also re-initializes all properties.
         *
         * @param array $query The GET parameters
         * @param array $request The POST parameters
         * @param array $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
         * @param array $cookies The COOKIE parameters
         * @param array $files The FILES parameters
         * @param array $server The SERVER parameters
         * @param string|resource $content The raw body data
         * @api
         * @static
         */
        public static function initialize($query = array(), $request = array(), $attributes = array(), $cookies = array(), $files = array(), $server = array(), $content = null){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::initialize($query, $request, $attributes, $cookies, $files, $server, $content);
        }

        /**
         * Creates a new request with values from PHP's super globals.
         *
         * @return \Symfony\Component\HttpFoundation\Request A new request
         * @api
         * @static
         */
        public static function createFromGlobals(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::createFromGlobals();
        }

        /**
         * Creates a Request based on a given URI and configuration.
         *
         * The information contained in the URI always take precedence
         * over the other information (server and parameters).
         *
         * @param string $uri The URI
         * @param string $method The HTTP method
         * @param array $parameters The query (GET) or request (POST) parameters
         * @param array $cookies The request cookies ($_COOKIE)
         * @param array $files The request files ($_FILES)
         * @param array $server The server parameters ($_SERVER)
         * @param string $content The raw body data
         * @return \Symfony\Component\HttpFoundation\Request A Request instance
         * @api
         * @static
         */
        public static function create($uri, $method = 'GET', $parameters = array(), $cookies = array(), $files = array(), $server = array(), $content = null){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::create($uri, $method, $parameters, $cookies, $files, $server, $content);
        }

        /**
         * Sets a callable able to create a Request instance.
         *
         * This is mainly useful when you need to override the Request class
         * to keep BC with an existing system. It should not be used for any
         * other purpose.
         *
         * @param callable|null $callable A PHP callable
         * @static
         */
        public static function setFactory($callable){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::setFactory($callable);
        }

        /**
         * Overrides the PHP global variables according to this request instance.
         *
         * It overrides $_GET, $_POST, $_REQUEST, $_SERVER, $_COOKIE.
         * $_FILES is never overridden, see rfc1867
         *
         * @api
         * @static
         */
        public static function overrideGlobals(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::overrideGlobals();
        }

        /**
         * Sets a list of trusted proxies.
         *
         * You should only list the reverse proxies that you manage directly.
         *
         * @param array $proxies A list of trusted proxies
         * @api
         * @static
         */
        public static function setTrustedProxies($proxies){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::setTrustedProxies($proxies);
        }

        /**
         * Gets the list of trusted proxies.
         *
         * @return array An array of trusted proxies.
         * @static
         */
        public static function getTrustedProxies(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getTrustedProxies();
        }

        /**
         * Sets a list of trusted host patterns.
         *
         * You should only list the hosts you manage using regexs.
         *
         * @param array $hostPatterns A list of trusted host patterns
         * @static
         */
        public static function setTrustedHosts($hostPatterns){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::setTrustedHosts($hostPatterns);
        }

        /**
         * Gets the list of trusted host patterns.
         *
         * @return array An array of trusted host patterns.
         * @static
         */
        public static function getTrustedHosts(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getTrustedHosts();
        }

        /**
         * Sets the name for trusted headers.
         *
         * The following header keys are supported:
         *
         *  * Request::HEADER_CLIENT_IP:    defaults to X-Forwarded-For   (see getClientIp())
         *  * Request::HEADER_CLIENT_HOST:  defaults to X-Forwarded-Host  (see getHost())
         *  * Request::HEADER_CLIENT_PORT:  defaults to X-Forwarded-Port  (see getPort())
         *  * Request::HEADER_CLIENT_PROTO: defaults to X-Forwarded-Proto (see getScheme() and isSecure())
         *
         * Setting an empty value allows to disable the trusted header for the given key.
         *
         * @param string $key The header key
         * @param string $value The header name
         * @throws \InvalidArgumentException
         * @static
         */
        public static function setTrustedHeaderName($key, $value){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::setTrustedHeaderName($key, $value);
        }

        /**
         * Gets the trusted proxy header name.
         *
         * @param string $key The header key
         * @return string The header name
         * @throws \InvalidArgumentException
         * @static
         */
        public static function getTrustedHeaderName($key){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getTrustedHeaderName($key);
        }

        /**
         * Normalizes a query string.
         *
         * It builds a normalized query string, where keys/value pairs are alphabetized,
         * have consistent escaping and unneeded delimiters are removed.
         *
         * @param string $qs Query string
         * @return string A normalized query string for the Request
         * @static
         */
        public static function normalizeQueryString($qs){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::normalizeQueryString($qs);
        }

        /**
         * Enables support for the _method request parameter to determine the intended HTTP method.
         *
         * Be warned that enabling this feature might lead to CSRF issues in your code.
         * Check that you are using CSRF tokens when required.
         * If the HTTP method parameter override is enabled, an html-form with method "POST" can be altered
         * and used to send a "PUT" or "DELETE" request via the _method request parameter.
         * If these methods are not protected against CSRF, this presents a possible vulnerability.
         *
         * The HTTP method can only be overridden when the real HTTP method is POST.
         *
         * @static
         */
        public static function enableHttpMethodParameterOverride(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::enableHttpMethodParameterOverride();
        }

        /**
         * Checks whether support for the _method request parameter is enabled.
         *
         * @return bool True when the _method request parameter is enabled, false otherwise
         * @static
         */
        public static function getHttpMethodParameterOverride(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getHttpMethodParameterOverride();
        }

        /**
         * Gets a "parameter" value.
         *
         * This method is mainly useful for libraries that want to provide some flexibility.
         *
         * Order of precedence: GET, PATH, POST
         *
         * Avoid using this method in controllers:
         *
         *  * slow
         *  * prefer to get from a "named" source
         *
         * It is better to explicitly get request parameters from the appropriate
         * public property instead (query, attributes, request).
         *
         * @param string $key the key
         * @param mixed $default the default value
         * @param bool $deep is parameter deep in multidimensional array
         * @return mixed
         * @static
         */
        public static function get($key, $default = null, $deep = false){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::get($key, $default, $deep);
        }

        /**
         * Gets the Session.
         *
         * @return \Symfony\Component\HttpFoundation\SessionInterface|null The session
         * @api
         * @static
         */
        public static function getSession(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getSession();
        }

        /**
         * Whether the request contains a Session which was started in one of the
         * previous requests.
         *
         * @return bool
         * @api
         * @static
         */
        public static function hasPreviousSession(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::hasPreviousSession();
        }

        /**
         * Whether the request contains a Session object.
         *
         * This method does not give any information about the state of the session object,
         * like whether the session is started or not. It is just a way to check if this Request
         * is associated with a Session instance.
         *
         * @return bool true when the Request contains a Session object, false otherwise
         * @api
         * @static
         */
        public static function hasSession(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::hasSession();
        }

        /**
         * Sets the Session.
         *
         * @param \Symfony\Component\HttpFoundation\SessionInterface $session The Session
         * @api
         * @static
         */
        public static function setSession($session){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::setSession($session);
        }

        /**
         * Returns the client IP addresses.
         *
         * In the returned array the most trusted IP address is first, and the
         * least trusted one last. The "real" client IP address is the last one,
         * but this is also the least trusted one. Trusted proxies are stripped.
         *
         * Use this method carefully; you should use getClientIp() instead.
         *
         * @return array The client IP addresses
         * @see getClientIp()
         * @static
         */
        public static function getClientIps(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getClientIps();
        }

        /**
         * Returns the client IP address.
         *
         * This method can read the client IP address from the "X-Forwarded-For" header
         * when trusted proxies were set via "setTrustedProxies()". The "X-Forwarded-For"
         * header value is a comma+space separated list of IP addresses, the left-most
         * being the original client, and each successive proxy that passed the request
         * adding the IP address where it received the request from.
         *
         * If your reverse proxy uses a different header name than "X-Forwarded-For",
         * ("Client-Ip" for instance), configure it via "setTrustedHeaderName()" with
         * the "client-ip" key.
         *
         * @return string The client IP address
         * @see getClientIps()
         * @see http://en.wikipedia.org/wiki/X-Forwarded-For
         * @api
         * @static
         */
        public static function getClientIp(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getClientIp();
        }

        /**
         * Returns current script name.
         *
         * @return string
         * @api
         * @static
         */
        public static function getScriptName(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getScriptName();
        }

        /**
         * Returns the path being requested relative to the executed script.
         *
         * The path info always starts with a /.
         *
         * Suppose this request is instantiated from /mysite on localhost:
         *
         *  * http://localhost/mysite              returns an empty string
         *  * http://localhost/mysite/about        returns '/about'
         *  * http://localhost/mysite/enco%20ded   returns '/enco%20ded'
         *  * http://localhost/mysite/about?var=1  returns '/about'
         *
         * @return string The raw path (i.e. not urldecoded)
         * @api
         * @static
         */
        public static function getPathInfo(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getPathInfo();
        }

        /**
         * Returns the root path from which this request is executed.
         *
         * Suppose that an index.php file instantiates this request object:
         *
         *  * http://localhost/index.php         returns an empty string
         *  * http://localhost/index.php/page    returns an empty string
         *  * http://localhost/web/index.php     returns '/web'
         *  * http://localhost/we%20b/index.php  returns '/we%20b'
         *
         * @return string The raw path (i.e. not urldecoded)
         * @api
         * @static
         */
        public static function getBasePath(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getBasePath();
        }

        /**
         * Returns the root URL from which this request is executed.
         *
         * The base URL never ends with a /.
         *
         * This is similar to getBasePath(), except that it also includes the
         * script filename (e.g. index.php) if one exists.
         *
         * @return string The raw URL (i.e. not urldecoded)
         * @api
         * @static
         */
        public static function getBaseUrl(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getBaseUrl();
        }

        /**
         * Gets the request's scheme.
         *
         * @return string
         * @api
         * @static
         */
        public static function getScheme(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getScheme();
        }

        /**
         * Returns the port on which the request is made.
         *
         * This method can read the client port from the "X-Forwarded-Port" header
         * when trusted proxies were set via "setTrustedProxies()".
         *
         * The "X-Forwarded-Port" header must contain the client port.
         *
         * If your reverse proxy uses a different header name than "X-Forwarded-Port",
         * configure it via "setTrustedHeaderName()" with the "client-port" key.
         *
         * @return string
         * @api
         * @static
         */
        public static function getPort(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getPort();
        }

        /**
         * Returns the user.
         *
         * @return string|null
         * @static
         */
        public static function getUser(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getUser();
        }

        /**
         * Returns the password.
         *
         * @return string|null
         * @static
         */
        public static function getPassword(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getPassword();
        }

        /**
         * Gets the user info.
         *
         * @return string A user name and, optionally, scheme-specific information about how to gain authorization to access the server
         * @static
         */
        public static function getUserInfo(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getUserInfo();
        }

        /**
         * Returns the HTTP host being requested.
         *
         * The port name will be appended to the host if it's non-standard.
         *
         * @return string
         * @api
         * @static
         */
        public static function getHttpHost(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getHttpHost();
        }

        /**
         * Returns the requested URI (path and query string).
         *
         * @return string The raw URI (i.e. not URI decoded)
         * @api
         * @static
         */
        public static function getRequestUri(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getRequestUri();
        }

        /**
         * Gets the scheme and HTTP host.
         *
         * If the URL was called with basic authentication, the user
         * and the password are not added to the generated string.
         *
         * @return string The scheme and HTTP host
         * @static
         */
        public static function getSchemeAndHttpHost(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getSchemeAndHttpHost();
        }

        /**
         * Generates a normalized URI (URL) for the Request.
         *
         * @return string A normalized URI (URL) for the Request
         * @see getQueryString()
         * @api
         * @static
         */
        public static function getUri(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getUri();
        }

        /**
         * Generates a normalized URI for the given path.
         *
         * @param string $path A path to use instead of the current one
         * @return string The normalized URI for the path
         * @api
         * @static
         */
        public static function getUriForPath($path){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getUriForPath($path);
        }

        /**
         * Returns the path as relative reference from the current Request path.
         *
         * Only the URIs path component (no schema, host etc.) is relevant and must be given.
         * Both paths must be absolute and not contain relative parts.
         * Relative URLs from one resource to another are useful when generating self-contained downloadable document archives.
         * Furthermore, they can be used to reduce the link size in documents.
         *
         * Example target paths, given a base path of "/a/b/c/d":
         * - "/a/b/c/d"     -> ""
         * - "/a/b/c/"      -> "./"
         * - "/a/b/"        -> "../"
         * - "/a/b/c/other" -> "other"
         * - "/a/x/y"       -> "../../x/y"
         *
         * @param string $path The target path
         * @return string The relative target path
         * @static
         */
        public static function getRelativeUriForPath($path){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getRelativeUriForPath($path);
        }

        /**
         * Generates the normalized query string for the Request.
         *
         * It builds a normalized query string, where keys/value pairs are alphabetized
         * and have consistent escaping.
         *
         * @return string|null A normalized query string for the Request
         * @api
         * @static
         */
        public static function getQueryString(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getQueryString();
        }

        /**
         * Checks whether the request is secure or not.
         *
         * This method can read the client port from the "X-Forwarded-Proto" header
         * when trusted proxies were set via "setTrustedProxies()".
         *
         * The "X-Forwarded-Proto" header must contain the protocol: "https" or "http".
         *
         * If your reverse proxy uses a different header name than "X-Forwarded-Proto"
         * ("SSL_HTTPS" for instance), configure it via "setTrustedHeaderName()" with
         * the "client-proto" key.
         *
         * @return bool
         * @api
         * @static
         */
        public static function isSecure(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::isSecure();
        }

        /**
         * Returns the host name.
         *
         * This method can read the client port from the "X-Forwarded-Host" header
         * when trusted proxies were set via "setTrustedProxies()".
         *
         * The "X-Forwarded-Host" header must contain the client host name.
         *
         * If your reverse proxy uses a different header name than "X-Forwarded-Host",
         * configure it via "setTrustedHeaderName()" with the "client-host" key.
         *
         * @return string
         * @throws \UnexpectedValueException when the host name is invalid
         * @api
         * @static
         */
        public static function getHost(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getHost();
        }

        /**
         * Sets the request method.
         *
         * @param string $method
         * @api
         * @static
         */
        public static function setMethod($method){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::setMethod($method);
        }

        /**
         * Gets the request "intended" method.
         *
         * If the X-HTTP-Method-Override header is set, and if the method is a POST,
         * then it is used to determine the "real" intended HTTP method.
         *
         * The _method request parameter can also be used to determine the HTTP method,
         * but only if enableHttpMethodParameterOverride() has been called.
         *
         * The method is always an uppercased string.
         *
         * @return string The request method
         * @api
         * @see getRealMethod()
         * @static
         */
        public static function getMethod(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getMethod();
        }

        /**
         * Gets the "real" request method.
         *
         * @return string The request method
         * @see getMethod()
         * @static
         */
        public static function getRealMethod(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getRealMethod();
        }

        /**
         * Gets the mime type associated with the format.
         *
         * @param string $format The format
         * @return string The associated mime type (null if not found)
         * @api
         * @static
         */
        public static function getMimeType($format){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getMimeType($format);
        }

        /**
         * Gets the format associated with the mime type.
         *
         * @param string $mimeType The associated mime type
         * @return string|null The format (null if not found)
         * @api
         * @static
         */
        public static function getFormat($mimeType){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getFormat($mimeType);
        }

        /**
         * Associates a format with mime types.
         *
         * @param string $format The format
         * @param string|array $mimeTypes The associated mime types (the preferred one must be the first as it will be used as the content type)
         * @api
         * @static
         */
        public static function setFormat($format, $mimeTypes){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::setFormat($format, $mimeTypes);
        }

        /**
         * Gets the request format.
         *
         * Here is the process to determine the format:
         *
         *  * format defined by the user (with setRequestFormat())
         *  * _format request parameter
         *  * $default
         *
         * @param string $default The default format
         * @return string The request format
         * @api
         * @static
         */
        public static function getRequestFormat($default = 'html'){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getRequestFormat($default);
        }

        /**
         * Sets the request format.
         *
         * @param string $format The request format.
         * @api
         * @static
         */
        public static function setRequestFormat($format){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::setRequestFormat($format);
        }

        /**
         * Gets the format associated with the request.
         *
         * @return string|null The format (null if no content type is present)
         * @api
         * @static
         */
        public static function getContentType(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getContentType();
        }

        /**
         * Sets the default locale.
         *
         * @param string $locale
         * @api
         * @static
         */
        public static function setDefaultLocale($locale){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::setDefaultLocale($locale);
        }

        /**
         * Get the default locale.
         *
         * @return string
         * @static
         */
        public static function getDefaultLocale(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getDefaultLocale();
        }

        /**
         * Sets the locale.
         *
         * @param string $locale
         * @api
         * @static
         */
        public static function setLocale($locale){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::setLocale($locale);
        }

        /**
         * Get the locale.
         *
         * @return string
         * @static
         */
        public static function getLocale(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getLocale();
        }

        /**
         * Checks if the request method is of specified type.
         *
         * @param string $method Uppercase request method (GET, POST etc).
         * @return bool
         * @static
         */
        public static function isMethod($method){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::isMethod($method);
        }

        /**
         * Checks whether the method is safe or not.
         *
         * @return bool
         * @api
         * @static
         */
        public static function isMethodSafe(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::isMethodSafe();
        }

        /**
         * Returns the request body content.
         *
         * @param bool $asResource If true, a resource will be returned
         * @return string|resource The request body content or a resource to read the body stream.
         * @throws \LogicException
         * @static
         */
        public static function getContent($asResource = false){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getContent($asResource);
        }

        /**
         * Gets the Etags.
         *
         * @return array The entity tags
         * @static
         */
        public static function getETags(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getETags();
        }

        /**
         *
         *
         * @return bool
         * @static
         */
        public static function isNoCache(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::isNoCache();
        }

        /**
         * Returns the preferred language.
         *
         * @param array $locales An array of ordered available locales
         * @return string|null The preferred locale
         * @api
         * @static
         */
        public static function getPreferredLanguage($locales = null){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getPreferredLanguage($locales);
        }

        /**
         * Gets a list of languages acceptable by the client browser.
         *
         * @return array Languages ordered in the user browser preferences
         * @api
         * @static
         */
        public static function getLanguages(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getLanguages();
        }

        /**
         * Gets a list of charsets acceptable by the client browser.
         *
         * @return array List of charsets in preferable order
         * @api
         * @static
         */
        public static function getCharsets(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getCharsets();
        }

        /**
         * Gets a list of encodings acceptable by the client browser.
         *
         * @return array List of encodings in preferable order
         * @static
         */
        public static function getEncodings(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getEncodings();
        }

        /**
         * Gets a list of content types acceptable by the client browser.
         *
         * @return array List of content types in preferable order
         * @api
         * @static
         */
        public static function getAcceptableContentTypes(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::getAcceptableContentTypes();
        }

        /**
         * Returns true if the request is a XMLHttpRequest.
         *
         * It works if your JavaScript library sets an X-Requested-With HTTP header.
         * It is known to work with common JavaScript frameworks:
         *
         * @link http://en.wikipedia.org/wiki/List_of_Ajax_frameworks#JavaScript
         * @return bool true if the request is an XMLHttpRequest, false otherwise
         * @api
         * @static
         */
        public static function isXmlHttpRequest(){
            //Method inherited from \Symfony\Component\HttpFoundation\Request
            return \Illuminate\Http\Request::isXmlHttpRequest();
        }

    }


    class Schema extends \Illuminate\Support\Facades\Schema{

        /**
         * Determine if the given table exists.
         *
         * @param string $table
         * @return bool
         * @static
         */
        public static function hasTable($table){
            return \Illuminate\Database\Schema\MySqlBuilder::hasTable($table);
        }

        /**
         * Get the column listing for a given table.
         *
         * @param string $table
         * @return array
         * @static
         */
        public static function getColumnListing($table){
            return \Illuminate\Database\Schema\MySqlBuilder::getColumnListing($table);
        }

        /**
         * Determine if the given table has a given column.
         *
         * @param string $table
         * @param string $column
         * @return bool
         * @static
         */
        public static function hasColumn($table, $column){
            //Method inherited from \Illuminate\Database\Schema\Builder
            return \Illuminate\Database\Schema\MySqlBuilder::hasColumn($table, $column);
        }

        /**
         * Determine if the given table has given columns.
         *
         * @param string $table
         * @param array $columns
         * @return bool
         * @static
         */
        public static function hasColumns($table, $columns){
            //Method inherited from \Illuminate\Database\Schema\Builder
            return \Illuminate\Database\Schema\MySqlBuilder::hasColumns($table, $columns);
        }

        /**
         * Modify a table on the schema.
         *
         * @param string $table
         * @param \Closure $callback
         * @return \Illuminate\Database\Schema\Blueprint
         * @static
         */
        public static function table($table, $callback){
            //Method inherited from \Illuminate\Database\Schema\Builder
            return \Illuminate\Database\Schema\MySqlBuilder::table($table, $callback);
        }

        /**
         * Create a new table on the schema.
         *
         * @param string $table
         * @param \Closure $callback
         * @return \Illuminate\Database\Schema\Blueprint
         * @static
         */
        public static function create($table, $callback){
            //Method inherited from \Illuminate\Database\Schema\Builder
            return \Illuminate\Database\Schema\MySqlBuilder::create($table, $callback);
        }

        /**
         * Drop a table from the schema.
         *
         * @param string $table
         * @return \Illuminate\Database\Schema\Blueprint
         * @static
         */
        public static function drop($table){
            //Method inherited from \Illuminate\Database\Schema\Builder
            return \Illuminate\Database\Schema\MySqlBuilder::drop($table);
        }

        /**
         * Drop a table from the schema if it exists.
         *
         * @param string $table
         * @return \Illuminate\Database\Schema\Blueprint
         * @static
         */
        public static function dropIfExists($table){
            //Method inherited from \Illuminate\Database\Schema\Builder
            return \Illuminate\Database\Schema\MySqlBuilder::dropIfExists($table);
        }

        /**
         * Rename a table on the schema.
         *
         * @param string $from
         * @param string $to
         * @return \Illuminate\Database\Schema\Blueprint
         * @static
         */
        public static function rename($from, $to){
            //Method inherited from \Illuminate\Database\Schema\Builder
            return \Illuminate\Database\Schema\MySqlBuilder::rename($from, $to);
        }

        /**
         * Get the database connection instance.
         *
         * @return \Illuminate\Database\Connection
         * @static
         */
        public static function getConnection(){
            //Method inherited from \Illuminate\Database\Schema\Builder
            return \Illuminate\Database\Schema\MySqlBuilder::getConnection();
        }

        /**
         * Set the database connection instance.
         *
         * @param \Illuminate\Database\Connection $connection
         * @return $this
         * @static
         */
        public static function setConnection($connection){
            //Method inherited from \Illuminate\Database\Schema\Builder
            return \Illuminate\Database\Schema\MySqlBuilder::setConnection($connection);
        }

        /**
         * Set the Schema Blueprint resolver callback.
         *
         * @param \Closure $resolver
         * @return void
         * @static
         */
        public static function blueprintResolver($resolver){
            //Method inherited from \Illuminate\Database\Schema\Builder
            \Illuminate\Database\Schema\MySqlBuilder::blueprintResolver($resolver);
        }

    }


    class Session extends \Illuminate\Support\Facades\Session{

        /**
         * Get the session configuration.
         *
         * @return array
         * @static
         */
        public static function getSessionConfig(){
            return \Illuminate\Session\SessionManager::getSessionConfig();
        }

        /**
         * Get the default session driver name.
         *
         * @return string
         * @static
         */
        public static function getDefaultDriver(){
            return \Illuminate\Session\SessionManager::getDefaultDriver();
        }

        /**
         * Set the default session driver name.
         *
         * @param string $name
         * @return void
         * @static
         */
        public static function setDefaultDriver($name){
            \Illuminate\Session\SessionManager::setDefaultDriver($name);
        }

        /**
         * Get a driver instance.
         *
         * @param string $driver
         * @return mixed
         * @static
         */
        public static function driver($driver = null){
            //Method inherited from \Illuminate\Support\Manager
            return \Illuminate\Session\SessionManager::driver($driver);
        }

        /**
         * Register a custom driver creator Closure.
         *
         * @param string $driver
         * @param \Closure $callback
         * @return $this
         * @static
         */
        public static function extend($driver, $callback){
            //Method inherited from \Illuminate\Support\Manager
            return \Illuminate\Session\SessionManager::extend($driver, $callback);
        }

        /**
         * Get all of the created "drivers".
         *
         * @return array
         * @static
         */
        public static function getDrivers(){
            //Method inherited from \Illuminate\Support\Manager
            return \Illuminate\Session\SessionManager::getDrivers();
        }

        /**
         * Starts the session storage.
         *
         * @return bool True if session started.
         * @throws \RuntimeException If session fails to start.
         * @api
         * @static
         */
        public static function start(){
            return \Illuminate\Session\Store::start();
        }

        /**
         * Returns the session ID.
         *
         * @return string The session ID.
         * @api
         * @static
         */
        public static function getId(){
            return \Illuminate\Session\Store::getId();
        }

        /**
         * Sets the session ID.
         *
         * @param string $id
         * @api
         * @static
         */
        public static function setId($id){
            return \Illuminate\Session\Store::setId($id);
        }

        /**
         * Determine if this is a valid session ID.
         *
         * @param string $id
         * @return bool
         * @static
         */
        public static function isValidId($id){
            return \Illuminate\Session\Store::isValidId($id);
        }

        /**
         * Returns the session name.
         *
         * @return mixed The session name.
         * @api
         * @static
         */
        public static function getName(){
            return \Illuminate\Session\Store::getName();
        }

        /**
         * Sets the session name.
         *
         * @param string $name
         * @api
         * @static
         */
        public static function setName($name){
            return \Illuminate\Session\Store::setName($name);
        }

        /**
         * Invalidates the current session.
         *
         * Clears all session attributes and flashes and regenerates the
         * session and deletes the old session from persistence.
         *
         * @param int $lifetime Sets the cookie lifetime for the session cookie. A null value
         *                      will leave the system settings unchanged, 0 sets the cookie
         *                      to expire with browser session. Time is in seconds, and is
         *                      not a Unix timestamp.
         * @return bool True if session invalidated, false if error.
         * @api
         * @static
         */
        public static function invalidate($lifetime = null){
            return \Illuminate\Session\Store::invalidate($lifetime);
        }

        /**
         * Migrates the current session to a new session id while maintaining all
         * session attributes.
         *
         * @param bool $destroy Whether to delete the old session or leave it to garbage collection.
         * @param int $lifetime Sets the cookie lifetime for the session cookie. A null value
         *                       will leave the system settings unchanged, 0 sets the cookie
         *                       to expire with browser session. Time is in seconds, and is
         *                       not a Unix timestamp.
         * @return bool True if session migrated, false if error.
         * @api
         * @static
         */
        public static function migrate($destroy = false, $lifetime = null){
            return \Illuminate\Session\Store::migrate($destroy, $lifetime);
        }

        /**
         * Generate a new session identifier.
         *
         * @param bool $destroy
         * @return bool
         * @static
         */
        public static function regenerate($destroy = false){
            return \Illuminate\Session\Store::regenerate($destroy);
        }

        /**
         * Force the session to be saved and closed.
         *
         * This method is generally not required for real sessions as
         * the session will be automatically saved at the end of
         * code execution.
         *
         * @static
         */
        public static function save(){
            return \Illuminate\Session\Store::save();
        }

        /**
         * Age the flash data for the session.
         *
         * @return void
         * @static
         */
        public static function ageFlashData(){
            \Illuminate\Session\Store::ageFlashData();
        }

        /**
         * Checks if an attribute is defined.
         *
         * @param string $name The attribute name
         * @return bool true if the attribute is defined, false otherwise
         * @api
         * @static
         */
        public static function has($name){
            return \Illuminate\Session\Store::has($name);
        }

        /**
         * Returns an attribute.
         *
         * @param string $name The attribute name
         * @param mixed $default The default value if not found.
         * @return mixed
         * @api
         * @static
         */
        public static function get($name, $default = null){
            return \Illuminate\Session\Store::get($name, $default);
        }

        /**
         * Get the value of a given key and then forget it.
         *
         * @param string $key
         * @param string $default
         * @return mixed
         * @static
         */
        public static function pull($key, $default = null){
            return \Illuminate\Session\Store::pull($key, $default);
        }

        /**
         * Determine if the session contains old input.
         *
         * @param string $key
         * @return bool
         * @static
         */
        public static function hasOldInput($key = null){
            return \Illuminate\Session\Store::hasOldInput($key);
        }

        /**
         * Get the requested item from the flashed input array.
         *
         * @param string $key
         * @param mixed $default
         * @return mixed
         * @static
         */
        public static function getOldInput($key = null, $default = null){
            return \Illuminate\Session\Store::getOldInput($key, $default);
        }

        /**
         * Sets an attribute.
         *
         * @param string $name
         * @param mixed $value
         * @api
         * @static
         */
        public static function set($name, $value){
            return \Illuminate\Session\Store::set($name, $value);
        }

        /**
         * Put a key / value pair or array of key / value pairs in the session.
         *
         * @param string|array $key
         * @param mixed|null $value
         * @return void
         * @static
         */
        public static function put($key, $value = null){
            \Illuminate\Session\Store::put($key, $value);
        }

        /**
         * Push a value onto a session array.
         *
         * @param string $key
         * @param mixed $value
         * @return void
         * @static
         */
        public static function push($key, $value){
            \Illuminate\Session\Store::push($key, $value);
        }

        /**
         * Flash a key / value pair to the session.
         *
         * @param string $key
         * @param mixed $value
         * @return void
         * @static
         */
        public static function flash($key, $value){
            \Illuminate\Session\Store::flash($key, $value);
        }

        /**
         * Flash an input array to the session.
         *
         * @param array $value
         * @return void
         * @static
         */
        public static function flashInput($value){
            \Illuminate\Session\Store::flashInput($value);
        }

        /**
         * Reflash all of the session flash data.
         *
         * @return void
         * @static
         */
        public static function reflash(){
            \Illuminate\Session\Store::reflash();
        }

        /**
         * Reflash a subset of the current flash data.
         *
         * @param array|mixed $keys
         * @return void
         * @static
         */
        public static function keep($keys = null){
            \Illuminate\Session\Store::keep($keys);
        }

        /**
         * Returns attributes.
         *
         * @return array Attributes
         * @api
         * @static
         */
        public static function all(){
            return \Illuminate\Session\Store::all();
        }

        /**
         * Sets attributes.
         *
         * @param array $attributes Attributes
         * @static
         */
        public static function replace($attributes){
            return \Illuminate\Session\Store::replace($attributes);
        }

        /**
         * Removes an attribute.
         *
         * @param string $name
         * @return mixed The removed value or null when it does not exist
         * @api
         * @static
         */
        public static function remove($name){
            return \Illuminate\Session\Store::remove($name);
        }

        /**
         * Remove an item from the session.
         *
         * @param string $key
         * @return void
         * @static
         */
        public static function forget($key){
            \Illuminate\Session\Store::forget($key);
        }

        /**
         * Clears all attributes.
         *
         * @api
         * @static
         */
        public static function clear(){
            return \Illuminate\Session\Store::clear();
        }

        /**
         * Remove all of the items from the session.
         *
         * @return void
         * @static
         */
        public static function flush(){
            \Illuminate\Session\Store::flush();
        }

        /**
         * Checks if the session was started.
         *
         * @return bool
         * @static
         */
        public static function isStarted(){
            return \Illuminate\Session\Store::isStarted();
        }

        /**
         * Registers a SessionBagInterface with the session.
         *
         * @param \Symfony\Component\HttpFoundation\Session\SessionBagInterface $bag
         * @static
         */
        public static function registerBag($bag){
            return \Illuminate\Session\Store::registerBag($bag);
        }

        /**
         * Gets a bag instance by name.
         *
         * @param string $name
         * @return \Symfony\Component\HttpFoundation\Session\SessionBagInterface
         * @static
         */
        public static function getBag($name){
            return \Illuminate\Session\Store::getBag($name);
        }

        /**
         * Gets session meta.
         *
         * @return \Symfony\Component\HttpFoundation\Session\MetadataBag
         * @static
         */
        public static function getMetadataBag(){
            return \Illuminate\Session\Store::getMetadataBag();
        }

        /**
         * Get the raw bag data array for a given bag.
         *
         * @param string $name
         * @return array
         * @static
         */
        public static function getBagData($name){
            return \Illuminate\Session\Store::getBagData($name);
        }

        /**
         * Get the CSRF token value.
         *
         * @return string
         * @static
         */
        public static function token(){
            return \Illuminate\Session\Store::token();
        }

        /**
         * Get the CSRF token value.
         *
         * @return string
         * @static
         */
        public static function getToken(){
            return \Illuminate\Session\Store::getToken();
        }

        /**
         * Regenerate the CSRF token value.
         *
         * @return void
         * @static
         */
        public static function regenerateToken(){
            \Illuminate\Session\Store::regenerateToken();
        }

        /**
         * Get the previous URL from the session.
         *
         * @return string|null
         * @static
         */
        public static function previousUrl(){
            return \Illuminate\Session\Store::previousUrl();
        }

        /**
         * Set the "previous" URL in the session.
         *
         * @param string $url
         * @return void
         * @static
         */
        public static function setPreviousUrl($url){
            \Illuminate\Session\Store::setPreviousUrl($url);
        }

        /**
         * Set the existence of the session on the handler if applicable.
         *
         * @param bool $value
         * @return void
         * @static
         */
        public static function setExists($value){
            \Illuminate\Session\Store::setExists($value);
        }

        /**
         * Get the underlying session handler implementation.
         *
         * @return \SessionHandlerInterface
         * @static
         */
        public static function getHandler(){
            return \Illuminate\Session\Store::getHandler();
        }

        /**
         * Determine if the session handler needs a request.
         *
         * @return bool
         * @static
         */
        public static function handlerNeedsRequest(){
            return \Illuminate\Session\Store::handlerNeedsRequest();
        }

        /**
         * Set the request on the handler instance.
         *
         * @param \Symfony\Component\HttpFoundation\Request $request
         * @return void
         * @static
         */
        public static function setRequestOnHandler($request){
            \Illuminate\Session\Store::setRequestOnHandler($request);
        }

    }


    class Storage extends \Illuminate\Support\Facades\Storage{

        /**
         * Get a filesystem instance.
         *
         * @param string $name
         * @return \Illuminate\Contracts\Filesystem\Filesystem
         * @static
         */
        public static function drive($name = null){
            return \Illuminate\Filesystem\FilesystemManager::drive($name);
        }

        /**
         * Get a filesystem instance.
         *
         * @param string $name
         * @return \Illuminate\Contracts\Filesystem\Filesystem
         * @static
         */
        public static function disk($name = null){
            return \Illuminate\Filesystem\FilesystemManager::disk($name);
        }

        /**
         * Create an instance of the local driver.
         *
         * @param array $config
         * @return \Illuminate\Contracts\Filesystem\Filesystem
         * @static
         */
        public static function createLocalDriver($config){
            return \Illuminate\Filesystem\FilesystemManager::createLocalDriver($config);
        }

        /**
         * Create an instance of the ftp driver.
         *
         * @param array $config
         * @return \Illuminate\Contracts\Filesystem\Filesystem
         * @static
         */
        public static function createFtpDriver($config){
            return \Illuminate\Filesystem\FilesystemManager::createFtpDriver($config);
        }

        /**
         * Create an instance of the Amazon S3 driver.
         *
         * @param array $config
         * @return \Illuminate\Contracts\Filesystem\Cloud
         * @static
         */
        public static function createS3Driver($config){
            return \Illuminate\Filesystem\FilesystemManager::createS3Driver($config);
        }

        /**
         * Create an instance of the Rackspace driver.
         *
         * @param array $config
         * @return \Illuminate\Contracts\Filesystem\Cloud
         * @static
         */
        public static function createRackspaceDriver($config){
            return \Illuminate\Filesystem\FilesystemManager::createRackspaceDriver($config);
        }

        /**
         * Get the default driver name.
         *
         * @return string
         * @static
         */
        public static function getDefaultDriver(){
            return \Illuminate\Filesystem\FilesystemManager::getDefaultDriver();
        }

        /**
         * Register a custom driver creator Closure.
         *
         * @param string $driver
         * @param \Closure $callback
         * @return $this
         * @static
         */
        public static function extend($driver, $callback){
            return \Illuminate\Filesystem\FilesystemManager::extend($driver, $callback);
        }

    }


}
