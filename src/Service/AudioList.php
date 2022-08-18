<?php

declare(strict_types=1);

namespace App\Service;

class AudioList
{
    protected const ROOT_PATH = 'media/';

    public function getDefaultCollection(): ?string {
        $collections = $this->getCollections();

        if (empty($collections)) {
            return null;
        }

        return reset($collections)['dir'];
    }

    public function getCollections(): array
    {
        return $this->listDir(self::ROOT_PATH);
    }

    public function getFiles(string $collection): array
    {
        $path = self::ROOT_PATH . $collection . '/';
        $folders = $this->listDir($path);

        foreach ($folders as &$entry) {
            $entry['files'] = $this->listFiles($path . $entry['dir']);
        }

        return $folders;
    }

    protected function listDir(string $path): array
    {
        $directories = [];
        $files = scandir($path);

        foreach ($files as $file) {
            if(is_dir($path . '/' . $file) and $file !== "." && $file !== "..") {
                $cover = $this->getCover($path);
                $directories[md5($file)] =  ['dir' => $file, 'cover' => $cover];
            }
        }

        return $directories;
    }

    protected function listFiles(string $path, array $filter=['mp3', 'wav', 'ogg']): array
    {
        $files = [];
        $pathFiles = scandir($path);

        foreach ($pathFiles as $file) {
            if (!is_file($path . DIRECTORY_SEPARATOR . $file)) { continue; }

            $fileExt =  pathinfo($path . DIRECTORY_SEPARATOR . $file, PATHINFO_EXTENSION);

            if (!in_array($fileExt, $filter)) {
                continue;
            }

            $files[md5($path . $file)] = ['file' => $file, 'duration' => ''];
        }

        return $files;
    }

    protected function getCover(string $path):? string
    {
        if (file_exists($path . '/cover.png')) {
            return 'cover.png';
        }

        return null;
    }
}
