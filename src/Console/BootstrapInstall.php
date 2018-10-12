<?php

namespace Cdz\Bootstrap\Console;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class BootstrapInstall extends Command {
	
	 use DetectsApplicationNamespace;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'cdz-bootstrap:install
						{--views : Only scaffold the views}
						{--force : Overwrite existing views by default}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command to install the Bootstrap library (v3.3.7) and create a welcome page template.';
	
	/**
     * The views that need to be exported.
     *
     * @var array
     */
    protected $views = [
        'layouts/app.stub' => 'layouts/app.blade.php',
        'pages/home.stub' => 'pages/home.blade.php',
    ];
	
	/**
     * The translations that need to be exported.
     *
     * @var array
     */
    protected $translations = [
        'en/layout.stub' => 'en/layout.php',
        'fr/auth.stub' => 'fr/auth.php',
        'fr/layout.stub' => 'fr/layout.php',
        'fr/pagination.stub' => 'fr/pagination.php',
        'fr/passwords.stub' => 'fr/passwords.php',
        'fr/validation.stub' => 'fr/validation.php',
    ];
	
	/**
     * The assets that need to be exported.
     *
     * @var array
     */
    protected $assets = [
        'favicon.ico',
        'apple-touch-icon.png',
        'assets/app',
        'assets/libs'
    ];

	/**
     * Execute the command.
     */
    public function handle()
	{
		$this->createDirectories();
		
		$this->exportViews();
		
		$this->exportTranslations();
		
		$this->exportAssets();
		
		if (! $this->option('views')) {
            file_put_contents(
                app_path('Http/Controllers/HomeController.php'),
                $this->compileControllerStub()
            );

            file_put_contents(
                base_path('routes/web.php'),
                file_get_contents(__DIR__.'/stubs/install/routes.stub'),
                FILE_APPEND
            );
        }
		
		$this->info('Bootstrap template generated successfully.');
	}
	
	/**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
    	/*
		 * View directories
		 */ 
        if (! is_dir($directory = resource_path('views/layouts'))) {
            mkdir($directory, 0755, true);
        }

        if (! is_dir($directory = resource_path('views/pages'))) {
            mkdir($directory, 0755, true);
        }
		
		/*
		 * Translations directories
		 */ 
        if (! is_dir($directory = resource_path('lang/fr'))) {
            mkdir($directory, 0755, true);
        }
		
		/*
		 * Assets directories
		 */
		if (! is_dir($directory = public_path('assets'))) {
            mkdir($directory, 0755, true);
        }
		
		if (! is_dir($directory = public_path('assets/app'))) {
            mkdir($directory, 0755, true);
        }
		
		if (! is_dir($directory = public_path('assets/libs'))) {
            mkdir($directory, 0755, true);
        }
    }
	
	/**
     * Export the views.
     *
     * @return void
     */
    protected function exportViews()
    {
        foreach ($this->views as $key => $value) {
            if (file_exists($view = resource_path('views/'.$value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            copy(
                __DIR__.'/stubs/install/views/'.$key,
                $view
            );
        }
    }

	/**
     * Export the translations.
     *
     * @return void
     */
    protected function exportTranslations()
    {
        foreach ($this->translations as $key => $value) {
            if (file_exists($translation = resource_path('lang/'.$value)) && ! $this->option('force')) {
                if (! $this->confirm("The [{$value}] translation file already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            copy(
                __DIR__.'/stubs/install/lang/'.$key,
                $translation
            );
        }
    }
	
	/**
	 * Export assets
	 * 
	 * @return void
	 */
	 protected function exportAssets()
	 {
	 	foreach ($this->assets as $value) {
	 		$is_dir = is_dir(public_path($value));
			
	 		if (file_exists($asset = public_path($value)) && ! $this->option('force')) {
                if (! $this->confirm("The public/[{$value}] ".($is_dir ? 'directory' : 'file')." already exists. Do you want to replace it?")) {
                    continue;
                }
            }

			if ($is_dir) {
				$this->copyPath(__DIR__.'/stubs/install/public/'.$value, $asset);
			}
			else {
				copy(
	                __DIR__.'/stubs/install/public/'.$value,
	                $asset
	            );
			}
        }
	 }
	
	/**
     * Compiles the HomeController stub.
     *
     * @return string
     */
    protected function compileControllerStub()
    {
        return str_replace(
            '{{namespace}}',
            $this->getAppNamespace(),
            file_get_contents(__DIR__.'/stubs/install/controllers/HomeController.stub')
        );
    }
	
	/**
	 * Copy a path
	 * 
	 * @return void
	 */
	 protected function copyPath($source, $destination)
	 {
	 	$dir = opendir($source); 
		
		if (! is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
		
	    while(false !== ($file = readdir($dir))) { 
	        if (($file != '.') && ($file != '..')) { 
	            if (is_dir($source.'/'.$file)) { 
	                $this->copyPath($source.'/'.$file,$destination.'/'.$file); 
	            } 
	            else { 
	                copy($source.'/'.$file,$destination.'/'.$file); 
	            } 
	        } 
	    } 
	    closedir($dir); 
	 }
}