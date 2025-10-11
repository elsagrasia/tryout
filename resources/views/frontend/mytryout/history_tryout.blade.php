@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

<div class="container py-4">
    <h4 class="mb-3">Riwayat Tryout</h4>

    @if($histories->isEmpty())
        <p>Belum ada riwayat tryout.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Tryout</th>
                    <th>Nilai</th>
                    <th>Benar</th>
                    <th>Salah</th>
                    {{-- <th>Tanggal</th> --}}
                    <th>Pembahasan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $history)
                    <tr>
                        <td>{{ $history->tryoutPackage->tryout_name ?? '-' }}</td>
                        <td>{{ $history->score }}</td>
                        <td>{{ $history->correct_count }}</td>
                        <td>{{ $history->wrong_count }}</td>
                        {{-- <td>{{ $history->finished_at ? $history->finished_at->format('d M Y H:i') : '-' }}</td> --}}
                        <td>
                            <a href="{{ route('tryout.explanation', $history->tryout_package_id) }}" class="btn btn-sm btn-info">
                                Pembahasan
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada riwayat tryout</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>

@endsection
