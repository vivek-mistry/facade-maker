<?php

namespace Tests\Unit\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use VivekMistry\FacadeMaker\FacadeServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use VivekMistry\FacadeMaker\Commands\FacadeDeveloper as FacadeDeveloper;

class FaceMakerCommandTest extends BaseTestCase
{
    protected $filesystem;
    protected $facadePath;
    protected $facadeServicePath;
    protected $facadeName = 'FileUpload';
    protected $facadeServiceName = 'CommonFileUpload';

    protected function setUp(): void
    {
        parent::setUp();

        // Register the service provider that registers the command
        $this->app->register(FacadeServiceProvider::class);

        $this->filesystem = new Filesystem();
        $this->facadePath = app_path('Facades/' . $this->facadeName . '.php');
        $this->facadeServicePath = app_path('Facades/Services/' . $this->facadeServiceName . '.php');

        // Clean up before each test
        if ($this->filesystem->exists($this->facadePath)) {
            $this->filesystem->delete($this->facadePath);
        }
        if ($this->filesystem->exists($this->facadeServicePath)) {
            $this->filesystem->delete($this->facadeServicePath);
        }
    }

    // ... rest of the test class ...
    public function test_command_is_registered()
    {
        // Option 1: Check via Artisan (requires service provider to be registered)
        $this->assertArrayHasKey(
            'app:facade-maker',
            Artisan::all(),
            'Command should be registered'
        );
    }

    public function test_command_signature()
    {
        $command = new FacadeDeveloper(new Filesystem());
        $this->assertEquals('app:facade-maker {facadeName?} {facadeServiceClass?}', $command->getSignature());
    }

    public function test_command_properties()
    {
        $command = new FacadeDeveloper(new Filesystem());

        $reflection = new \ReflectionClass($command);
        $property = $reflection->getProperty('signature');
        $property->setAccessible(true);

        $this->assertEquals('app:facade-maker {facadeName?} {facadeServiceClass?}', $property->getValue($command));
    }

    public function test_command_execution()
    {
        // Get the actual path the command will use
        $expectedInterfacePath = app_path('Facades/FileUpload.php');
        $expectedRepoPath = app_path('Facades/Services/CommonFileUpload.php');

        // Clean up if files exist from previous tests
        if (file_exists($expectedInterfacePath)) {
            unlink($expectedInterfacePath);
        }
        if (file_exists($expectedRepoPath)) {
            unlink($expectedRepoPath);
        }

        $this->artisan('app:facade-maker', ['facadeName' => 'FileUpload', 'facadeServiceClass' => 'CommonFileUpload'])
            // ->expectsOutput("File : {$expectedInterfacePath} created")
            // ->expectsOutput("File : {$expectedRepoPath} created")
            ->assertExitCode(0);

        // Verify files were actually created
        $this->assertFileExists($expectedInterfacePath);
        $this->assertFileExists($expectedRepoPath);
    }
}
