<?php

namespace App\Tests\Service;

use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploaderUnitTest extends TestCase
{
    public function testUpload()
    {
        $testDirectory = '/tmp/';
        $pathToTestFile = sprintf('%s/../../public/app_icon-72x72.png', __DIR__);
        $content = file_get_contents($pathToTestFile);
        $pathToTmpTestFile = sprintf('/tmp/%s.png', uniqid('', true));
        file_put_contents($pathToTmpTestFile, $content);

        $uploader = new FileUploader($testDirectory);
        $filename = $uploader->upload(new UploadedFile($pathToTmpTestFile, 'app_icon!!##.png', 'image/png', UPLOAD_ERR_OK, true));

        $contentOfUploadedFile = file_get_contents($testDirectory.$filename);
        $this->assertEquals($contentOfUploadedFile, $content);
    }
}
