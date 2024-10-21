<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    // Mendapatkan semua anime
    public function index()
    {
        $animes = Anime::all()->map(function ($anime) {
            $anime->poster_url = asset('storage/' . $anime->poster);
            return $anime;
        });

        return response()->json($animes);
    }

    // Menyimpan anime baru
    public function store(Request $request)
    {
        // Validasi data
        $validatedData = $request->validate([
            'title' => 'required',
            'poster' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'genre' => 'required|array', // Pastikan genre adalah array
            'genre.*' => 'string', // Setiap genre dalam array harus string
            'rating' => 'required|numeric|min:0|max:5',
            'episode' => 'required|integer',
            'studio' => 'required',
            'synopsis' => 'required',
        ]);

        // Coba untuk mendapatkan file poster dan simpan
        if ($request->hasFile('poster')) {
            // Menyimpan poster ke folder assets
            $posterPath = $request->file('poster')->store('assets', 'public');
        } else {
            return response()->json(['error' => 'Poster file is required'], 400);
        }

        // Mengonversi genre dari array menjadi string
        $genresString = implode(',', $validatedData['genre']); // Mengonversi array genre menjadi string

        // Simpan data ke database
        $anime = Anime::create([
            'title' => $validatedData['title'],
            'poster' => $posterPath,
            'genre' => $genresString, // Simpan genre dalam bentuk string
            'rating' => $validatedData['rating'],
            'episode' => $validatedData['episode'],
            'studio' => $validatedData['studio'],
            'synopsis' => $validatedData['synopsis'],
        ]);

        return response()->json($anime, 201);
    }



    // Mendapatkan detail anime
    public function show($id)
    {
        $anime = Anime::findOrFail($id);
        return response()->json($anime);
    }

    // Mengupdate anime
    public function update(Request $request, $id)
    {
        $anime = Anime::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'required|string',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'genre' => 'required|array',
            'rating' => 'required|numeric|min:0|max:5',
            'episode' => 'required|integer',
            'studio' => 'required|string',
            'synopsis' => 'required|string',
        ]);

        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('assets', 'public');
            $anime->poster = $posterPath;
        }

        $anime->title = $validatedData['title'];
        $anime->genre = $validatedData['genre']; // Menyimpan genre langsung sebagai array
        $anime->rating = $validatedData['rating'];
        $anime->episode = $validatedData['episode'];
        $anime->studio = $validatedData['studio'];
        $anime->synopsis = $validatedData['synopsis'];

        $anime->save();

        return response()->json($anime);
    }






    // Menghapus anime
    public function destroy($id)
    {
        // Temukan anime berdasarkan ID
        $anime = Anime::findOrFail($id);

        // Hapus file gambar dari folder assets
        $filePath = 'public/' . $anime->poster; // Pastikan path sesuai dengan yang disimpan
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        // Hapus anime dari database
        $anime->delete();

        return response()->json(null, 204);
    }


}

