<?php

namespace Database\Factories;

use App\Models\Advisor_document;
use Illuminate\Database\Eloquent\Factories\Factory;

class Advisor_documentFactory extends Factory
{
    protected $model = Advisor_document::class;

    public function definition(): array
    {
    	return [
    	    'doc_file' => $this->faker->image(storage_path(DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'documents' ), 400, 300, null, false),
            

    	];
    }
}
