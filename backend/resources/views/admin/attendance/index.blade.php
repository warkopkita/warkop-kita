@extends('layouts.app')

@section('title', 'Rekap Absensi & GPS')
@section('page_title', 'Rekap Absensi Karyawan (Geofencing & Foto)')

@section('content')
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 12px;">
        <h3 class="card-title"><i class="fa-solid fa-location-dot text-amber"></i> Data Kehadiran Karyawan</h3>
        <form action="{{ route('admin.attendance.index') }}" method="GET" style="display: flex; gap: 10px;">
            <input type="date" name="date" value="{{ $date }}" style="padding: 8px 12px; background: #12100E; border: 1px solid var(--border-color); border-radius: 8px; color: var(--text-main); font-size: 13px;">
            <button type="submit" class="btn btn-secondary" style="padding: 8px 14px;">Pilih Tanggal</button>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Foto Selfie</th>
                    <th>Karyawan</th>
                    <th>Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Jarak GPS</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                    <tr>
                        <td>
                            <div style="width: 46px; height: 46px; border-radius: 10px; background: #12100E; border: 1px solid var(--border-color); overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                @if($att->clock_in_photo)
                                    <img src="{{ asset('storage/' . $att->clock_in_photo) }}" alt="Selfie" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fa-solid fa-camera text-muted"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--text-main);">{{ $att->user->name }}</div>
                            <div style="font-size: 11px; color: var(--primary); text-transform: capitalize;">{{ $att->user->role }}</div>
                        </td>
                        <td>{{ $att->shift->name ?? '-' }}</td>
                        <td style="font-weight: 600; color: #34D399;">{{ $att->clock_in ?? '-' }}</td>
                        <td style="font-weight: 600; color: #F87171;">{{ $att->clock_out ?? '-' }}</td>
                        <td>
                            @if($att->clock_in_distance_meters !== null)
                                <span style="font-size: 12px; font-weight: 600; color: {{ $att->clock_in_distance_meters <= 50 ? '#34D399' : '#F87171' }};">
                                    <i class="fa-solid fa-map-pin"></i> {{ round($att->clock_in_distance_meters) }} meter
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($att->status === 'present')
                                <span class="badge badge-success">Hadir Tepat Waktu</span>
                            @elseif($att->status === 'late')
                                <span class="badge badge-warning">Terlambat</span>
                            @else
                                <span class="badge badge-danger">{{ ucfirst($att->status) }}</span>
                            @endif
                        </td>
                        <td style="font-size: 12px; color: var(--text-muted);">{{ $att->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted);">
                            Tidak ada data absensi pada tanggal {{ $date }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
