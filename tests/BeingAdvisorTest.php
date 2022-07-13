<?php
namespace Tests;

use App\Models\Advisor;
use App\Models\Advisor_document;
use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Faker\Factory;
// use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
// use InteractsWithExceptionHandling;


class BeingAdvisorTest extends TestCase
{
    use InteractsWithExceptionHandling;

   
    function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->user = $user;
        $advisor_instance = Advisor::factory()->create([
            'user_id' => $user->id
        ]);
        $this->advisor_instance = $advisor_instance;
    }

    public function test_see_if_advisor_exists()
    {
        $this->seeInDatabase('advisors', ['user_id' => $this->user->id]);
    }

    public function test_user_can_get_advisor_profile()
    {
        $this->actingAs($this->user, 'api');
        $res = $this->json('GET', '/api/v1/advisor-profile')->seeJson([
            'user_id' => $this->user->id
        ]);
        // $res->assertResponseStatus(200);
    }
    public function test_advisor_can_upload_file()
    {
        // $this->withoutExceptionHandling();

        $faker = Factory::create();
        $this->actingAs($this->user, 'api');
        // $doc = Advisor_document::factory()->create([
        //     'advisor_id' => $this->advisor_instance->id
        // ]);
        $path = storage_path('app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . '62bb2c6c7e160_Screenshot_2022-05-12_14-26-09.png' );
        // $imageFile = new File(storage_path('app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . '6285457437346_Screenshot_2022-05-15_20-41-01.png'));
        // $imageFile = UploadedFile::fake()->image('file.png', 500, 500);
        $imageFile = new UploadedFile($path, 'file.png', 'image/png', null, true);
        $res = $this->json('POST', '/api/v1/upload-doc-file/' . strval($this->advisor_instance->id), [
            'doc_file' => $imageFile
        ]);
        $res->assertResponseStatus(201);
    }

    // public function test_advisor_can_download_file()
    // {
    //     $faker = Factory::create();
    //     $faker->image(public_path('uploads/'));
    //     $this->actingAs($this->user, 'api');
    //     $res = $this->json('POST', '/api/v1/download-doc-file/' . strval($this->advisor_instance->id), [
    //         'doc_file' => '',
    //     ]);
    //     $res->assertResponseStatus(200);
    // }

    // public function test_upload_works()
    // {
    //     $stub = __DIR__.'/stubs/test.png';
    //     $name = str_random(8).'.png';
    //     $path = sys_get_temp_dir().'/'.$name;

    //     copy($stub, $path);

    //     $file = new UploadedFile($path, $name, filesize($path), 'image/png', null, true);
    //     $response = $this->call('POST', '/upload', [], [], ['photo' => $file], ['Accept' => 'application/json']);

    //     $this->assertResponseOk();
    //     $content = json_decode($response->getContent());
    //     $this->assertObjectHasAttribute('name', $content);

    //     $uploaded = 'uploads'.DIRECTORY_SEPARATOR.$content->name;
    //     $this->assertFileExists(public_path($uploaded));

    //     @unlink($uploaded);
    // }
    
}
