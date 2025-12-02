<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Spatie\Sitemap\Sitemap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('backend.system.dashboard');
    }

    public function generateSitemap()
    {
        try {
            /* SITE MAP FOR POST */
            $sitemap = Sitemap::create();
            $sitemap->add(URL::to('/'), date('y-m-d'), '1.0', 'daily'); //static url
//            $sitemap->add(URL::to('page'), '2012-08-26T12:30:00+02:00', '0.9', 'monthly');
            $posts = Post::where('status', 1)->orderBy('created_at', 'desc')->get();
            foreach ($posts as $post) {
                $sitemap->add(URL::to('/') . '/blogs/' . $post->slug, $post->updated_at, 0.9, 'daily');
            }
            $sitemapXml = $sitemap->render('xml');
            $sitemapXml = preg_replace('/^.*?(?=<\?xml version)/s', '', $sitemapXml);
            $filePath = base_path('sitemap_post.xml');
            File::put($filePath, $sitemapXml);
            /* SITE MAP FOR POST */


            /* SITE MAP FOR PAGE */
            $sitemap = Sitemap::create();
            $sitemap->add(URL::to('/'), date('y-m-d'), '1.0', 'daily'); //static url
            $posts = Page::where('status', 1)->orderBy('created_at', 'desc')->get();
            foreach ($posts as $post) {
                $sitemap->add(URL::to('/') . '/' . $post->slug, $post->updated_at, 0.9, 'monthly');
            }
            $sitemapXml = $sitemap->render('xml');
            $sitemapXml = preg_replace('/^.*?(?=<\?xml version)/s', '', $sitemapXml);
            $filePath = base_path('sitemap_page.xml');
            File::put($filePath, $sitemapXml);
            /* SITE MAP FOR PAGE */

            /* SITE MAP FOR COURSE */
            $sitemap = Sitemap::create();
            $sitemap->add(URL::to('/'), date('y-m-d'), '1.0', 'daily'); //static url
            $posts = Course::where('status', 1)->orderBy('created_at', 'desc')->get();
            foreach ($posts as $post) {
                $sitemap->add(URL::to('/') . '/course/' . $post->slug, $post->updated_at, 0.9, 'monthly');
            }
            $sitemapXml = $sitemap->render('xml');
            $sitemapXml = preg_replace('/^.*?(?=<\?xml version)/s', '', $sitemapXml);
            $filePath = base_path('sitemap_course.xml');
            File::put($filePath, $sitemapXml);
            /* SITE MAP FOR COURSE */

            /* SITE MAP FOR SERVICE */
            $sitemap = Sitemap::create();
            $sitemap->add(URL::to('/'), date('y-m-d'), '1.0', 'daily'); //static url
            $posts = Service::where('status', 1)->orderBy('created_at', 'desc')->get();
            foreach ($posts as $post) {
                $sitemap->add(URL::to('/') . '/service/' . $post->slug, $post->updated_at, 0.9, 'monthly');
            }
            $sitemapXml = $sitemap->render('xml');
            $sitemapXml = preg_replace('/^.*?(?=<\?xml version)/s', '', $sitemapXml);
            $filePath = base_path('sitemap_service.xml');
            File::put($filePath, $sitemapXml);
            /* SITE MAP FOR SERVICE */

            /* SITE MAP FOR COURSE */
            $sitemap = Sitemap::create();
            $sitemap->add(URL::to('/sitemap_page.xml'));
            $sitemap->add(URL::to('/sitemap_post.xml'));
            $sitemap->add(URL::to('/sitemap_course.xml'));
            $sitemap->add(URL::to('/sitemap_service.xml'));
            $sitemapXml = $sitemap->render('xml');
            $sitemapXml = preg_replace('/^.*?(?=<\?xml version)/s', '', $sitemapXml);
            $filePath = base_path('sitemap.xml');
            File::put($filePath, $sitemapXml);
            /* SITE MAP FOR COURSE */
            return redirect()->back()->with('success', 'Sitemap generated successfully!');
        } catch (\Throwable $throwable) {
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }
    /**
     * Download database backup
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function backupDatabase()
    {
        // Get database configuration
        $database = Config::get('database.connections.mysql.database');
        $username = Config::get('database.connections.mysql.username');
        $password = Config::get('database.connections.mysql.password');
        $host = Config::get('database.connections.mysql.host', 'localhost');
        $port = Config::get('database.connections.mysql.port', 3306);

        // Generate filename with timestamp
        $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sql';

        // Return streaming response
        return response()->streamDownload(function () use ($database, $username, $password, $host, $port) {
            // Flush output buffer
            if (ob_get_level() > 0) {
                ob_end_flush();
            }
            
            // Build mysqldump command with proper escaping
            $command = sprintf(
                'mysqldump -h %s -P %s -u %s -p%s %s 2>/dev/null',
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($database)
            );
            
            // Execute and stream output
            $process = popen($command, 'r');
            if ($process) {
                while (!feof($process)) {
                    echo fread($process, 8192);
                    flush();
                }
                pclose($process);
            }
        }, $filename, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

    /**
     * Download project backup
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function backupProject()
    {
        try {
            // Set unlimited execution time for this operation
            set_time_limit(0);
            ini_set('memory_limit', '512M');

            // Create a timestamp for the file name
            $timestamp = date('Y-m-d-H-i-s');
            $fileName = 'public-backup-' . $timestamp . '.tar.gz';

            // Get the public directory path
            $publicPath = public_path();
            $publicDirName = basename($publicPath);

            // Return streaming response using tar command
            return response()->streamDownload(function () use ($publicPath, $publicDirName) {
                // Flush output buffer
                if (ob_get_level() > 0) {
                    ob_end_flush();
                }
                
                // Build tar command to create compressed archive and stream it
                // Change to parent directory and tar the public folder
                $parentDir = dirname($publicPath);
                $command = sprintf(
                    'cd %s && tar -czf - %s 2>/dev/null',
                    escapeshellarg($parentDir),
                    escapeshellarg($publicDirName)
                );
                
                // Execute and stream output
                $process = popen($command, 'r');
                if ($process) {
                    while (!feof($process)) {
                        echo fread($process, 8192);
                        flush();
                    }
                    pclose($process);
                } else {
                    die('Cannot create backup archive');
                }
            }, $fileName, [
                'Content-Type' => 'application/gzip',
            ]);
        } catch (\Exception $e) {
            \Log::error("Backup failed: " . $e->getMessage());
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }
}
