<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;


class ArticleController extends Controller
{
    public function index()
    {
        // Mengambil artikel, urutkan berdasarkan tanggal diterbitkan (published_date) secara menurun
        $articles = Article::orderBy('published_date', 'desc')->paginate(10);   
        return view('admin.article.index',compact('articles'));
    }

    public function create()
    {
        return view('admin.article.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'published_date' => 'required|date',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Konsistensi pada folder penyimpanan (menggunakan 'articles')
            $imagePath = $request->file('image')->store('articles', 'public');
            $validated['image'] = $imagePath;
        }

        Article::create($validated);

        return redirect()->route('article.index')->with('success', 'Artikel berhasil ditambahkan');
    }


    public function edit(Article $article)
    {
        return view('admin.article.edit', compact('article')); 
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'published_date' => 'required|date',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
        ]);

        // Jika ada gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }

            // Simpan gambar baru dengan konsistensi path 'articles'
            $imagePath = $request->file('image')->store('articles', 'public');
            $article->image = $imagePath;
        }

        // Update data artikel lainnya
        $article->title = $request->title;
        $article->author = $request->author;
        $article->published_date = $request->published_date;
        $article->content = $request->content;
        $article->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('article.index')->with('success', 'Artikel berhasil diperbarui');
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();
        return redirect()->route('article.index')->with('success', 'Artikel berhasil dihapus');
    }

    public function showArticles()
    {
        $articles = Article::orderBy('published_date', 'desc')->paginate(20); // Sesuaikan jumlah artikel per halaman
        return view('user.articles.index', compact('articles'));
    }

    public function showArticleDetail($id)
    {
        $article = Article::findOrFail($id);
        return view('user.articles.detail', compact('article'));
    }

    public function getLatestArticles()
    {
        // Mengambil 3 artikel terbaru
        $latestArticles = Article::orderBy('published_date', 'desc')->take(3)->get();
        
        return view('landing', compact('latestArticles'));
    }

    public function getLatestArticlesForHome()
    {
        // Ambil 3 artikel terbaru
        $latestArticles = Article::orderBy('published_date', 'desc')->take(3)->get();

        return view('user.home', compact('latestArticles'));
    }

    public function getLatestArticlesForAdmin()
    {
        // Ambil 3 artikel terbaru
        $latestArticles = Article::orderBy('published_date', 'desc')->take(3)->get();

        return view('admin.dashboard', compact('latestArticles'));
    }
}
