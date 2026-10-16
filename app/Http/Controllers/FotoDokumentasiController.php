<?php

namespace App\Http\Controllers;

use App\Models\FotoDokumentasi;
use App\Models\Pk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FotoDokumentasiController extends Controller
{
    public function show(FotoDokumentasi $foto)
    {
        if (!Storage::disk('public')->exists($foto->file_path_url)) {
            abort(404);
        }

        return Storage::disk('public')->response(
            $foto->file_path_url,
            basename($foto->file_path_url),
            [],
            'inline'
        );
    }

    public function store(Request $request, Pk $pk)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,jpg,png|max:10240', // Maksimal 10MB
        ]);

        $file = $request->file('foto');
        $path = $file->store('foto_dokumentasi', 'public');
        $fullPath = storage_path('app/public/' . $path);

        // Ekstraksi Metadata GPS EXIF
        list($latitude, $longitude, $timestamp) = $this->extractExifData($fullPath);

        $statusVerifikasi = ($latitude && $longitude) ? 'VALID' : 'PENDING';

        FotoDokumentasi::create([
            'pk_id' => $pk->id,
            'uploader_id' => Auth::id(),
            'file_path_url' => $path,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'timestamp_exif' => $timestamp,
            'status_verifikasi' => $statusVerifikasi,
        ]);

        $msg = $statusVerifikasi === 'VALID' 
            ? 'Foto ber-geotag berhasil diunggah dan terverifikasi!' 
            : 'Foto berhasil diunggah, tetapi koordinat GPS tidak ditemukan dalam metadata foto.';

        return redirect()->back()->with('success', $msg);
    }

    public function destroy(Request $request, FotoDokumentasi $foto)
    {
        if (
            $foto->uploader_id !== $request->user()->id
            && ! $request->user()->hasAnyRole(['Admin Penagihan', 'Supervisor'])
        ) {
            abort(403);
        }

        if (Storage::disk('public')->exists($foto->file_path_url)) {
            Storage::disk('public')->delete($foto->file_path_url);
        }
        $foto->delete();

        return redirect()->back()->with('success', 'Foto dokumentasi berhasil dihapus.');
    }

    private function extractExifData($filePath)
    {
        $lat = null;
        $lng = null;
        $timestamp = null;

        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($filePath);

            if ($exif) {
                if (isset($exif['DateTimeOriginal'])) {
                    $timestamp = date('Y-m-d H:i:s', strtotime($exif['DateTimeOriginal']));
                }

                if (isset($exif['GPSLatitude']) && isset($exif['GPSLongitude'])) {
                    $lat = $this->getGpsValue($exif['GPSLatitude'], $exif['GPSLatitudeRef'] ?? 'N');
                    $lng = $this->getGpsValue($exif['GPSLongitude'], $exif['GPSLongitudeRef'] ?? 'E');
                }
            }
        }

        return [$lat, $lng, $timestamp];
    }

    private function getGpsValue($coordinate, $ref)
    {
        $degrees = count($coordinate) > 0 ? $this->evalRational($coordinate[0]) : 0;
        $minutes = count($coordinate) > 1 ? $this->evalRational($coordinate[1]) : 0;
        $seconds = count($coordinate) > 2 ? $this->evalRational($coordinate[2]) : 0;

        $flip = ($ref === 'W' || $ref === 'S') ? -1 : 1;

        return $flip * ($degrees + ($minutes / 60) + ($seconds / 3600));
    }

    private function evalRational($val)
    {
        if (is_numeric($val)) return (float)$val;
        $parts = explode('/', $val);
        if (count($parts) === 2 && $parts[1] != 0) {
            return (float)$parts[0] / (float)$parts[1];
        }
        return 0;
    }
}
