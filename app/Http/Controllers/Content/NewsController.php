<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Models\Content\Editorial;
use App\Models\Content\News;
use App\Models\Content\NewsPhoto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Image;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic;

class NewsController extends Controller
{
    /**
     * Show the index
     */
    public function index(Request $request)
    {
        $news = News::dateFromTo($request->start_date, $request->end_date)
            ->keywords($request->keyword)
            ->orderBy('created_at', 'desc')
            ->paginate(config('helpers.results_per_page'));

        return view('backend.news.index', [
            'news' => $news
        ]);
    }

    /**
     * The garbage
     */
    public function trash(Request $request)
    {
        $news = News::onlyTrashed()
            ->dateFromTo($request->start_date, $request->end_date)
            ->editorial($request->editorial)
            ->keywords($request->keywords)
            ->orderBy('created_at', 'desc')
            ->paginate(config('helpers.results_per_page'));

        return view('backend.news.trash', [
            'news' => $news,
            'editorials' => Editorial::all(),
        ]);
    }

    /**
     * Create form
     */
    public function create()
    {
        $editorials = Editorial::where('status', 1)->pluck('name','id');

        return view('backend.news.create', compact('editorials'));
    }

    /**
     * Edit form
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);
        $editorials = Editorial::where('status', 1)->pluck('name','id');


        return view('backend.news.edit', [
            'news' => $news,
            'editorials' => $editorials
        ]);
    }

    /**
     * Store the news on the database
     */
    public function store(NewsRequest $request)
    {
        $request['slug'] = $this->generateSlug($request->title);

        DB::transaction(function () use ($request) {
            $input = $request->all();

            $input['audio'] = $this->uploadAudio($request);
            $input['text'] = $this->uploadEditorImages($request);
            $input['comment_photo'] = $this->addQuoteImage($request);

            //save
            $news = News::create($input);
        });

        session()->flash('success', 'Notícia cadastrada com sucesso.');
        return redirect()->back();
    }

    /**
     * Update the news at the database
     */
    public function update($id, NewsRequest $request)
    {
        $news = News::findOrFail($id);

        DB::transaction(function () use ($request, $news) {
            $input = $request->all();

            if (!is_null($request->audio)) {
                $input['audio'] = $this->uploadAudio($request);
            }

            $input['text'] = $this->uploadEditorImages($request);

            if (!is_null($request->comment_photo)) {
                $input['comment_photo'] = $this->addQuoteImage($request, $news);
            }

            //save
            $news->update($input);
        });

        session()->flash('success', 'Notícia alterada com sucesso.');
        return redirect()->route('content.news.index');
    }

    /**
     * Upload the audio
     */
    private function uploadAudio($request)
    {
        if (!$request->audio) return null;

        $subfolder = date('Y-m');
        $path = '/files/news/audio/' . $subfolder . '/';

        $filename = str_slug($request->title . '-' . uniqid());
        $filename .= '.' . $request->file('audio')->getClientOriginalExtension();

        Storage::put(
            $path . $filename,
            file_get_contents($request->audio)
        );

        return $subfolder . '/' . $filename;
    }

    /**
     * Upload the images from the editor
     */
    private function uploadEditorImages($request)
    {
        $text = $request->text;

        if (!strstr($text, '<img')) return $text;

        //search the value inside <img >
        $img = [];
        $regex = "/<img(.*?)>/";
        preg_match_all($regex, $text, $img);
        $img = $img[1];

        //search the value inside src=" "
        $src = [];
        $regex = "/src=\"(.*?)\"/";
        preg_match_all($regex, $text, $src);
        $src = $src[1];

        //upload files
        $subfolder = date('Y-m');
        $path = '/img/news/' . $subfolder . '/';

        foreach ($src as $file) {
            if (!is_file(public_path() . $file)) continue;

            //treat the image (600 px width)
            $img = Image::make(public_path() . $file);
            $img->resize(600, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path() . $file, 80);

            //set the new filename
            $filename = str_slug($request->title . '-' . uniqid());
            $filename .= '.' . pathinfo($file, PATHINFO_EXTENSION);

            //put it on the storage (ftp)
            Storage::put(
                $path . $filename,
                file_get_contents(public_path() . $file)
            );

            //delete the temp directory
            $folder = public_path() . $file;
            $folder = substr($folder, 0, strrpos($folder, '/'));
            File::deleteDirectory($folder);

            //replace the url on the text
            $destination = app('portal')->url . $path . $filename;
            $text = str_replace($file, $destination, $text);
        }

        return $text;
    }

    /**
     * Add Quote
     */
    private function addQuoteImage($request, $news = null)
    {
        if (!$request->hasFile('comment_photo')) return null;

        $path = '/img/news/cite/';

        //delete the old image
        if (!is_null($news)) {
            Storage::delete($path . $news->comment_photo);
        }

        //treat the image (150 px width)
        $img = Image::make($request->comment_photo);
        $img->fit(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($request->comment_photo, 80);

        //set the new filename
        $subfolder = date('Y-m');
        $path .= $subfolder . '/';

        $filename = str_slug($request->comment_description . '-' . uniqid());
        $filename .= '.' . $request->file('comment_photo')->getClientOriginalExtension();

        //put it on the storage (ftp)
        Storage::put(
            $path . $filename,
            file_get_contents($request->comment_photo)
        );

        return $subfolder . '/' . $filename;
    }

    /**
     * Add photos to the news
     */
    public function photos($id)
    {
        $news = News::findOrFail($id);

        return view('backend.news.photos', [
            'news' => $news
        ]);
    }

    /**
     * Upload the photos
     */
    public function upload($id, Request $request)
    {
        $news = News::findOrFail($id);

        $subfolder = date('Y-m');
        $path = '/img/news/' . $subfolder . '/';

        $filename = str_slug($news->title . '-' . uniqid());
        $filename .= '.' . $request->file('file')->getClientOriginalExtension();


        //treat the image
        $img = Image::make($request->file('file'));
        $img->resize(1024, 768, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($request->file('file'), 80);

        //put the file on the ftp
        Storage::put(
            $path . $filename,
            file_get_contents($request->file('file')->getRealPath())
        );


        //create the thumb
        $img = Image::make($request->file('file'));
        $img->fit(240, 170, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($request->file('file'), 80);

        //put the file on the ftp
        Storage::put(
            '/thumb/' . $subfolder . '/' . $filename,
            file_get_contents($request->file('file')->getRealPath())
        );


        //store at the database
        $news->photos()->create([
            'file' => $subfolder . '/' . $filename
        ]);

        session()->flash('success', 'Fotos enviadas com sucesso.');
    }

    /**
     * Delete a photo
     */
    public function delPhoto($id)
    {
        $photo = NewsPhoto::findOrFail($id);

        $file = '/img/news/' . $photo->file;
        Storage::delete($file);

        $photo->delete();

        session()->flash('success', 'Foto excluída com sucesso.');
        return redirect()->back();
    }

    /**
     * Delete the news at database
     */
    public function destroy($id, Request $request)
    {
        //when selected some entries to delete
        if ($request->selected) {
            $entries = explode(',', $request->selected);

            DB::transaction(function () use ($entries, $request) {
                foreach ($entries as $entry) {
                    $news = News::findOrFail($entry);
                    $news->delete();
                }
            });

            $restore = "<a href='" . route('content.news.restore', 0) . "?entries=" . $request->selected . "'>Desfazer</a>";
        } else {
            $news = News::findOrFail($id);

            DB::transaction(function () use ($news) {
                $news->delete();
            });

            $restore = "<a href='" . route('content.news.restore', $id) . "'>Desfazer</a>";
        }

        session()->flash('success', "Notícia(s) excluída(s) com sucesso. $restore");
        return redirect()->back();
    }

    /**
     * Update the status.
     */
    public function status(Request $request)
    {
        $code = 418; //I'm a teapot!

        if ($request->id and preg_match('/(0|1)/', $request->status)) {
            $module = News::withTrashed()->findOrFail($request->id);
            $module->status = $request->status;
            if ($module->save()) $code = 200;
        }

        return $code;
    }

    /**
     * Define the featured news
     */
    public function featured(Request $request)
    {
        $checked = filter_var($request->checked, FILTER_VALIDATE_BOOLEAN);

        $news = News::withTrashed()->findOrFail($request->id);

        if ($checked) {
            $news->featured = 1;
        } else {
            $news->featured = 0;
        }
        $news->save();

        return 200;
    }

    /**
     * Define the cover of the news
     */
    public function cover(Request $request)
    {
        $photo = NewsPhoto::findOrFail($request->id);

        if (is_null($photo->subtitle) or is_null($photo->credits)) {
            return 418;
        }

        DB::transaction(function () use ($photo) {
            NewsPhoto::where('news_id', $photo->news_id)->update([
                'featured' => 0
            ]);

            $photo->featured = 1;
            $photo->save();
        });

        return 200;
    }

    /**
     * Define the caption and credits of the given photo
     */
    public function caption($id, Request $request)
    {
        $photo = NewsPhoto::findOrFail($id);

        $photo->subtitle = $request->caption;
        $photo->credits = $request->credits;
        $photo->save();

        session()->flash('success', "Informações adicionadas com sucesso.");
        return redirect()->back();
    }

    /**
     * Change the order of photos
     */
    public function order(Request $request)
    {
        $code = 418; //I'm a teapot!

        foreach ($request->item as $order => $id) {
            $photo = NewsPhoto::findOrFail($id);
            $photo->order = $order;
            if ($photo->save()) $code = 200;
        }

        return $code;
    }

    /**
     * Restore an item from the trash
     */
    public function restore($id, Request $request)
    {
        //when restoring a lot of entries
        if ($entries = $request->entries) {
            $entries = explode(',', $entries);

            DB::transaction(function () use ($entries) {
                foreach ($entries as $entry) {
                    $news = News::onlyTrashed()->findOrFail($entry);

                    DB::transaction(function () use ($news) {
                        $news->restore();
                    });
                }
            });
        } else {
            $news = News::onlyTrashed()->findOrFail($id);

            DB::transaction(function () use ($news) {
                $news->restore();
            });
        }

        session()->flash('success', 'Notícia restaurada com sucesso.');
        return redirect()->back();
    }

    /**
     * Set the slug
     */
    protected function generateSlug($slug)
    {
        $slug = str_slug($slug);

        $slugs = News::withTrashed()->where('slug', 'like', "$slug%")->get();
        if ($slugs->count() === 0) {
            return $slug;
        }

        $lastSlug = $slugs->sortBy('id')->last()->slug;
        $lastSlugNumber = intval(str_replace($slug . '-', '', $lastSlug));

        return $slug . '-' . ($lastSlugNumber + 1);
    }


    public function watermarkAdd($cod)
    {
        $photo = NewsPhoto::findOrFail($cod);

        // Verificar se possui marca dagua
        $company = Company::first();
        if ((!isset($company->watermark)) || ($company->watermark == '')) {
            return json_encode([
                'status' => 'error',
                'message' => "Erro! Você não possuí Marca d' Água cadastrada em 'Configurações'· "
            ]);
        }

        // Verifica se o arquivo existe
        $path = storage_path() . '/app/img/news/' . $photo->file;
        if (!\Illuminate\Support\Facades\File::exists($path))
            abort(404);

        try {
            $image = ImageManagerStatic::make($company->watermark);
            $image2 = ImageManagerStatic::make($path);
            $image2->insert($image, 'bottom-center', 10, 10);
            $image2->save();

        } catch (NotReadableException $exception) {
            return $exception->getMessage();
        }

        return json_encode([
            'status' => 'success',
            'message' => "Sucesso! Foi adicionado a Marca d' Água. "
        ]);

    }
}
