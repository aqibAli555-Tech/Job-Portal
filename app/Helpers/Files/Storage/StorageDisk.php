<?php

namespace App\Helpers\Files\Storage;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class StorageDisk
{
    /**
     * Get the default disk resources
     *
     * @return Filesystem
     */
    public static function getDisk()
    {
        $defaultDisk = self::getDiskName();
        $disk = Storage::disk($defaultDisk);

        return $disk;
    }

    /**
     * Get the default disk name
     *
     * @return Repository|mixed
     */
    public static function getDiskName()
    {

        $defaultDisk = config('filesystems.default', 'public');
        // $defaultDisk = config('filesystems.cloud'); // Only for tests purpose!

        return $defaultDisk;
    }

    /**
     * Set the backup system disks
     */
    public static function setBackupDisks()
    {
        $disks = config('backup.backup.destination.disks');
        if (config('settings.backup.storage_disk') == '1') {
            $disks = [config('filesystems.cloud')];
        } else if (config('settings.backup.storage_disk') == '2') {
            $disks = array_merge($disks, [config('filesystems.cloud')]);
        }
        $disks = array_unique($disks);
        config()->set('backup.backup.destination.disks', $disks);
    }
}
