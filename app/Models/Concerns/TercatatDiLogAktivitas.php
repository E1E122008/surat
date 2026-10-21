<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;

/**
 * Catat otomatis aktivitas tambah, ubah, dan hapus data surat ke Riwayat Aktivitas.
 *
 * Model yang memakai trait ini mendefinisikan:
 *   protected string $labelLog = 'Surat Masuk';      // nama modul di riwayat
 *   protected string $kolomNomorLog = 'no_surat';    // opsional, default no_surat
 */
trait TercatatDiLogAktivitas
{
    public static function bootTercatatDiLogAktivitas(): void
    {
        static::created(fn ($model) => $model->catatAktivitas('document_create', 'Menambahkan'));

        static::updated(function ($model) {
            // Abaikan perubahan yang hanya menyentuh timestamp
            if (array_diff(array_keys($model->getChanges()), ['updated_at'])) {
                $model->catatAktivitas('document_update', 'Mengubah');
            }
        });

        static::deleted(fn ($model) => $model->catatAktivitas('document_delete', 'Menghapus'));
    }

    protected function catatAktivitas(string $aksi, string $kataKerja): void
    {
        $label = $this->labelLog ?? class_basename($this);
        $nomor = $this->{$this->kolomNomorLog ?? 'no_surat'} ?? '-';

        ActivityLog::log($aksi, "{$kataKerja} {$label} (No: {$nomor})");
    }
}
