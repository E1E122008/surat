<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

abstract class Controller
{
    /**
     * Status persetujuan Kepala Biro Hukum pada disposisi hanya boleh diubah oleh admin
     * (superadmin dan monitor hanya melihat).
     */
    protected function bolehUbahPersetujuan(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    /**
     * Hapus record beserta file lampirannya.
     * Mengembalikan true hanya jika baris benar-benar sudah tidak ada di database,
     * sehingga notifikasi "berhasil dihapus" tidak tampil untuk penghapusan yang gagal.
     * File lampiran baru dihapus setelah record berhasil dihapus.
     */
    protected function hapusRecord(?Model $record, string $kolomLampiran = 'lampiran'): bool
    {
        if (!$record || !$record->exists) {
            return false;
        }

        $lampiranData = json_decode($record->{$kolomLampiran} ?? '', true);

        $record->delete();

        if ($record->newQuery()->whereKey($record->getKey())->exists()) {
            return false;
        }

        if (is_array($lampiranData)) {
            foreach ($lampiranData as $lampiran) {
                if (isset($lampiran['path']) && Storage::disk('public')->exists($lampiran['path'])) {
                    Storage::disk('public')->delete($lampiran['path']);
                }
            }
        }

        return true;
    }
}
