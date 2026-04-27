<?php

namespace Database\Seeders;

use App\Models\IncidentReport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IncidentReportSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@dms.com')->first();
        
        if (!$admin) return;

        $incidents = [
            [
                'title' => 'Kegagalan Server Database Regional',
                'description' => 'Terjadi lonjakan latency pada database utama di Regional 3 yang mempengaruhi performa aplikasi DMS.',
                'happened_at' => now()->subHours(5),
                'location' => 'Data Center R3',
                'work_unit' => 'IT Infrastructure',
                'priority' => 'critical',
                'status' => 'processing',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Kebocoran Data Akses (Percobaan)',
                'description' => 'Terdeteksi upaya pengalihan akses yang tidak sah pada folder rahasia manajemen.',
                'happened_at' => now()->subDays(1),
                'location' => 'Cyber Security Lab',
                'work_unit' => 'Cyber Security',
                'priority' => 'high',
                'status' => 'waiting_follow_up',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Masalah Login Single Sign-On',
                'description' => 'Beberapa karyawan melaporkan tidak bisa login menggunakan akun Telkom mereka.',
                'happened_at' => now()->subHours(2),
                'location' => 'Aplikasi Portal',
                'work_unit' => 'Digital Service',
                'priority' => 'medium',
                'status' => 'new',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Update Sertifikat SSL Kadaluarsa',
                'description' => 'Sertifikat SSL pada domain dev.telkom.co.id akan segera kadaluarsa dalam 24 jam.',
                'happened_at' => now()->subHours(12),
                'location' => 'Web Server B',
                'work_unit' => 'System Admin',
                'priority' => 'high',
                'status' => 'completed',
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Kerusakan Hardware Printer Cabang',
                'description' => 'Printer utama di kantor cabang Bandung mengalami kerusakan mekanik.',
                'happened_at' => now()->subDays(3),
                'location' => 'Kantor Cabang Bandung',
                'work_unit' => 'General Affairs',
                'priority' => 'low',
                'status' => 'closed',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($incidents as $incident) {
            $report = IncidentReport::create($incident);
            
            // Add initial log
            DB::table('incident_logs')->insert([
                'incident_report_id' => $report->id,
                'user_id' => $admin->id,
                'action' => 'created',
                'to_status' => $report->status,
                'comment' => 'Insiden dicatat secara otomatis melalui seeder.',
                'created_at' => $report->created_at,
                'updated_at' => $report->updated_at,
            ]);
        }
    }
}
