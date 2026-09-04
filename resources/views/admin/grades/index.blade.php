@extends('layouts.admin')
@section('title', 'Penilaian & Rapor')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 text-green-700 p-4 rounded-2xl border border-green-200 flex items-center gap-3 shadow-sm">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <span class="font-medium">{{ session('success') }}</span>
</div>
@endif

<div class="glass-panel rounded-3xl shadow-xl overflow-hidden mt-6">
    <div class="p-6 md:p-8 border-b border-gray-100 bg-white/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Rapor Bulanan Siswa</h2>
            <p class="text-gray-500 mt-1 text-sm">Berikan nilai kedisiplinan, kerjasama, dan keahlian siswa.</p>
        </div>
        
        <form action="{{ route('admin.grades.index') }}" method="GET" class="w-full md:w-auto flex items-center gap-2">
            <input type="month" name="month" value="{{ request('month', $month) }}" class="w-full md:w-auto px-4 py-2 rounded-xl border border-gray-200 shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 text-sm bg-gray-50" onchange="this.form.submit()" title="Pilih Bulan">
        </form>
    </div>

    <div class="p-0">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-widest border-b border-gray-100">
                        <th class="p-5 font-semibold">Nama Siswa</th>
                        <th class="p-5 font-semibold text-center">Disiplin</th>
                        <th class="p-5 font-semibold text-center">Kerjasama</th>
                        <th class="p-5 font-semibold text-center">Keahlian</th>
                        <th class="p-5 font-semibold text-center">Rata-rata</th>
                        <th class="p-5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($interns as $intern)
                        @php $grade = $intern->grades->first(); @endphp
                        <tr class="hover:bg-orange-50/30 transition-colors">
                            <td class="p-5 whitespace-nowrap">
                                <div class="font-medium text-gray-800">{{ $intern->name }}</div>
                                <div class="text-xs text-gray-400">{{ $intern->nim ?? 'NIM/NISN belum diisi' }}</div>
                            </td>
                            @if($grade)
                                <td class="p-5 text-center font-bold text-gray-700">{{ $grade->discipline_score }}</td>
                                <td class="p-5 text-center font-bold text-gray-700">{{ $grade->teamwork_score }}</td>
                                <td class="p-5 text-center font-bold text-gray-700">{{ $grade->skill_score }}</td>
                                <td class="p-5 text-center">
                                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-bold {{ $grade->average_score >= 80 ? 'bg-green-100 text-green-700' : ($grade->average_score >= 60 ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $grade->average_score }}
                                    </span>
                                </td>
                            @else
                                <td class="p-5 text-center text-gray-300">-</td>
                                <td class="p-5 text-center text-gray-300">-</td>
                                <td class="p-5 text-center text-gray-300">-</td>
                                <td class="p-5 text-center text-gray-300">-</td>
                            @endif
                            <td class="p-5 text-center">
                                <button type="button" onclick="openGradeModal({{ $intern->id }}, '{{ $intern->name }}', {{ $grade ? $grade->discipline_score : 'null' }}, {{ $grade ? $grade->teamwork_score : 'null' }}, {{ $grade ? $grade->skill_score : 'null' }}, '{{ $grade ? $grade->notes : '' }}')" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl text-sm font-semibold transition-colors shadow-sm">
                                    {{ $grade ? 'Edit Nilai' : 'Input Nilai' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center">
                                <div class="text-gray-500 font-medium">Belum ada data siswa.</div>
                            </td>
                        </tr>
                    @endempty
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Input Nilai -->
<div id="gradeModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-xl font-bold text-gray-900">Input Nilai Bulan <span class="text-orange-600">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</span></h3>
            <button type="button" onclick="document.getElementById('gradeModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('admin.grades.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="user_id" id="inputUserId">
            <input type="hidden" name="month" value="{{ $monthNum }}">
            <input type="hidden" name="year" value="{{ $year }}">
            
            <p class="text-sm text-gray-600 mb-4">Berikan nilai (0-100) untuk <span id="modalGradeInternName" class="font-bold text-gray-900"></span>.</p>
            
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Disiplin</label>
                    <input type="number" name="discipline_score" id="inputDiscipline" min="0" max="100" class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm text-center font-bold" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kerjasama</label>
                    <input type="number" name="teamwork_score" id="inputTeamwork" min="0" max="100" class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm text-center font-bold" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Keahlian</label>
                    <input type="number" name="skill_score" id="inputSkill" min="0" max="100" class="w-full px-3 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm text-center font-bold" required>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan/Feedback <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                <textarea name="notes" id="inputNotes" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm resize-none" placeholder="Tuliskan evaluasi perkembangan..."></textarea>
            </div>
            
            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-bold text-sm transition-colors shadow-md">
                    Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openGradeModal(userId, name, discipline, teamwork, skill, notes) {
        document.getElementById('inputUserId').value = userId;
        document.getElementById('modalGradeInternName').innerText = name;
        document.getElementById('inputDiscipline').value = discipline || '';
        document.getElementById('inputTeamwork').value = teamwork || '';
        document.getElementById('inputSkill').value = skill || '';
        document.getElementById('inputNotes').value = notes || '';
        document.getElementById('gradeModal').classList.remove('hidden');
    }
</script>

@endsection
