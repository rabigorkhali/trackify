<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use App\Models\Event;
use App\Models\Testimonial;
use App\Models\Team;
use App\Models\ContactUs;
use Illuminate\Http\Request;
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
        // Project Statistics
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 1)->count();
        $recentProjects = Project::latest()->take(5)->get();
        
        // Ticket Statistics
        $totalTickets = Ticket::count();
        $ticketsByStatusData = Ticket::select('ticket_status_id', DB::raw('count(*) as count'))
            ->groupBy('ticket_status_id')
            ->get();
        
        $ticketsByStatus = [];
        foreach ($ticketsByStatusData as $item) {
            $status = TicketStatus::find($item->ticket_status_id);
            if ($status) {
                $ticketsByStatus[$status->name] = $item->count;
            }
        }
        
        $ticketsByPriority = Ticket::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority')
            ->toArray();
        
        $ticketsByType = Ticket::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type')
            ->toArray();
        
        $recentTickets = Ticket::with(['project', 'assignee', 'ticketStatus'])
            ->latest()
            ->take(5)
            ->get();
        
        // User Statistics
        $totalUsers = User::count();
        $activeUsers = User::where('status', 1)->count();
        
        // Content Statistics
        $totalPosts = Post::count();
        $totalPages = Page::count();
        $totalEvents = Event::count();
        $totalTestimonials = Testimonial::count();
        $totalTeams = Team::count();
        $totalContactUs = ContactUs::count();
        
        // Ticket trends (last 7 days)
        $ticketTrends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $ticketTrends[$date] = Ticket::whereDate('created_at', $date)->count();
        }
        
        // Projects with ticket counts
        $projectsWithStats = Project::withCount('tickets')
            ->orderBy('tickets_count', 'desc')
            ->take(10)
            ->get();
        
        return view('backend.system.dashboard', compact(
            'totalProjects',
            'activeProjects',
            'recentProjects',
            'totalTickets',
            'ticketsByStatus',
            'ticketsByPriority',
            'ticketsByType',
            'recentTickets',
            'totalUsers',
            'activeUsers',
            'totalPosts',
            'totalPages',
            'totalEvents',
            'totalTestimonials',
            'totalTeams',
            'totalContactUs',
            'ticketTrends',
            'projectsWithStats'
        ));
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
