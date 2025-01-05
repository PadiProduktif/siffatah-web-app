@extends('layouts.app')
@section('title', 'Restitusi')
@section('content')


    <div class="d-flex justify-content-end align-items-center mb-4">
        @if(auth()->user()->role === 'superadmin')
        <h4 class="me-auto">RESTITUSI KARYAWAN - SCREENING</h4>

        @endif
        <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDataBaru">+ Masukan Data Baru</a>
    </div>
    <button style="margin-bottom: 20px;" id="deleteSelected" class="btn btn-danger">Hapus Terpilih</button>
    <table id="klaimTable" class="display">
        <thead>
            <tr>
                <th class="align-middle">NO</th>
                <th class="align-middle">Badge</th>
                <th class="align-middle">Karyawan</th>
                <th class="align-middle">Deskripsi</th>
                <th class="align-middle">Nomor</th>
                <th class="align-middle" style="width: 5vw">Tanggal</th>
                <th class="align-middle">Nominal</th>
                <th class="align-middle">Urgensi</th>
                <th class="align-middle">Status</th>
                <th class="align-middle">Opt</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($restitusi as $key1 => $data1)
                <tr class="text-center align-top">
                    <td>{{ $key1+1 }}</td>
                    <td>{{ $data1->id_badge }}</td>
                    <td class="text-start">{{ $data1->nama_karyawan }}</td>
                    <td class="text-start">{{ $data1->keterangan_pengajuan }}, {{ $data1->deskripsi }}</td>
                    <td>{{ $data1->no_surat_rs }}</td>
                    <td>{{ format_date($data1->tanggal_pengobatan) }}</td>
                    <td class="text-end">{{ format_currency($data1->nominal) }}</td>
                    <td>{{ $data1->urgensi }}</td>
                    <td>
                        @if ($data1->status_pengajuan == 1)
                            <span class="badge bg-secondary">Verifikasi Screening</span>
                        @elseif ($data1->status_pengajuan == 2)
                            <span class="badge bg-warning">Verifikasi DR</span>
                        @elseif ($data1->status_pengajuan == 3)
                            <span class="badge bg-primary">Verifikasi VP</span>
                        @elseif ($data1->status_pengajuan == 0)
                            <span class="badge bg-danger">Reject Screening</span>
                        @elseif ($data1->status_pengajuan == 4)
                            <span class="badge bg-success">Approved</span>   
                        @else
                            <span class="badge bg-danger">Tidak Diketahui</span>
                        @endif
                    </td>
                    <td>
                        @if (auth()->user()->role === 'superadmin' || auth()->user()->role === 'tko')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a 
                                            href="#" 
                                            class="dropdown-item" 
                                            data-bs-toggle="modal" 
                                            {{-- data-bs-target="#modalEditBerkas-{{ $data1->id_pengajuan }}"> --}}
                                            data-bs-target="#modalUpdate-{{ $data1->id_pengajuan }}"
                                            data-id="{{ $data1->id_pengajuan }}" 
                                            data-id_badge="{{ $data1->id_badge }}" 
                                            data-nama_karyawan="{{ $data1->nik }}" 
                                            data-unit_kerja="{{ $data1->unit_kerja }}"
                                            data-asuransi="{{ $data1->deskripsi }}"
                                            data-rumah_sakit="{{ $data1->nominal }}"
                                            data-tanggal_wafat="{{ $data1->rumah_sakit }}"
                                            data-ahli_waris="{{ $data1->urgensi }}"
                                            data-hubungan="{{ $data1->no_surat_rs }}"
                                            data-no_polis="{{ $data1->tanggal_pengobatan }}"
                                            data-file_url="{{ $data1->keterangan_pengajuan }}"
                                            data-file_url="{{ $data1->url_file }}"
                                            >
                                            <i class="bi bi-file-text me-2"></i>Lihat Berkas & Approval

                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('{{ $data1->id_pengajuan }}')">
                                            <i class="bi bi-trash me-2"></i>Hapus
                                        </a>
                                        
                                        <!-- Form tersembunyi -->
                                        <form id="delete-form-{{ $data1->id_pengajuan }}" 
                                            action="/admin/restitusi_karyawan/delete/{{ $data1->id_pengajuan }}" 
                                            method="POST" 
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </li>

                                </ul>
                            </div>

                            <!-- <div class="d-flex">
                            @if (auth()->user()->role === 'superadmin' && $data1->status_pengajuan === 1)
                                <form action="{{ route('approval-screening', ['id' => $data1->id_pengajuan]) }}" method="POST" class="w-100">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-warning w-100">Approve</button>
                                </form>
                                <form action="{{ route('reject-screening', ['id' => $data1->id_pengajuan]) }}" method="POST" class="w-100">
                                    @csrf
                                    @method('PUT')
                                    <button style="margin-left: 10px;" type="submit" class="btn btn-sm btn-danger  w-100">Reject</button>
                                </form>
     
                            @endif

                            </div> -->
                        @else
                            <div class="d-flex">
                            @if (auth()->user()->role === 'dr_hph' && $data1->status_pengajuan === 1)
                                <form action="{{ route('approval-dr', ['id' => $data1->id_pengajuan]) }}" method="POST" class="w-100">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-warning w-100">Approve</button>
                                </form>
                            @elseif (auth()->user()->role === 'vp_osdm' && $data1->status_pengajuan === 2)
                                <form action="{{ route('approval-vp', ['id' => $data1->id_pengajuan]) }}" method="POST" class="w-100">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success w-100">Approve</button>
                                </form>
                            @endif
                                {{-- <button class="btn btn-sm btn-success w-100 mx-1">Approve</button>
                                <button class="btn btn-sm btn-danger w-100 mx-1">Disapprove</button> --}}
                                <!-- <button class="btn btn-sm btn-info mx-1">Berkas</button> -->
                            </div>
                        @endif

                        @if (auth()->user()->role === 'dr_hph')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a 
                                            href="#" 
                                            class="dropdown-item" 
                                            data-bs-toggle="modal" 
                                            {{-- data-bs-target="#modalEditBerkas-{{ $data1->id_pengajuan }}"> --}}
                                            data-bs-target="#modalUpdate-{{ $data1->id_pengajuan }}"
                                            data-id="{{ $data1->id_pengajuan }}" 
                                            data-id_badge="{{ $data1->id_badge }}" 
                                            data-nama_karyawan="{{ $data1->nik }}" 
                                            data-unit_kerja="{{ $data1->unit_kerja }}"
                                            data-asuransi="{{ $data1->deskripsi }}"
                                            data-rumah_sakit="{{ $data1->nominal }}"
                                            data-tanggal_wafat="{{ $data1->rumah_sakit }}"
                                            data-ahli_waris="{{ $data1->urgensi }}"
                                            data-hubungan="{{ $data1->no_surat_rs }}"
                                            data-no_polis="{{ $data1->tanggal_pengobatan }}"
                                            data-file_url="{{ $data1->keterangan_pengajuan }}"
                                            data-file_url="{{ $data1->url_file }}"
                                            >
                                            <i class="bi bi-file-text me-2"></i>Lihat Berkas & Approval

                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('{{ $data1->id_pengajuan }}')">
                                            <i class="bi bi-trash me-2"></i>Hapus
                                        </a>
                                        
                                        <!-- Form tersembunyi -->
                                        <form id="delete-form-{{ $data1->id_pengajuan }}" 
                                            action="/admin/restitusi_karyawan/delete/{{ $data1->id_pengajuan }}" 
                                            method="POST" 
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </li>

                                </ul>
                            </div>


                        @endif

                        @if (auth()->user()->role === 'vp_osdm')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a 
                                            href="#" 
                                            class="dropdown-item" 
                                            data-bs-toggle="modal" 
                                            {{-- data-bs-target="#modalEditBerkas-{{ $data1->id_pengajuan }}"> --}}
                                            data-bs-target="#modalUpdate-{{ $data1->id_pengajuan }}"
                                            data-id="{{ $data1->id_pengajuan }}" 
                                            data-id_badge="{{ $data1->id_badge }}" 
                                            data-nama_karyawan="{{ $data1->nik }}" 
                                            data-unit_kerja="{{ $data1->unit_kerja }}"
                                            data-asuransi="{{ $data1->deskripsi }}"
                                            data-rumah_sakit="{{ $data1->nominal }}"
                                            data-tanggal_wafat="{{ $data1->rumah_sakit }}"
                                            data-ahli_waris="{{ $data1->urgensi }}"
                                            data-hubungan="{{ $data1->no_surat_rs }}"
                                            data-no_polis="{{ $data1->tanggal_pengobatan }}"
                                            data-file_url="{{ $data1->keterangan_pengajuan }}"
                                            data-file_url="{{ $data1->url_file }}"
                                            >
                                            <i class="bi bi-file-text me-2"></i>Lihat Berkas & Approval

                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('{{ $data1->id_pengajuan }}')">
                                            <i class="bi bi-trash me-2"></i>Hapus
                                        </a>
                                        
                                        <!-- Form tersembunyi -->
                                        <form id="delete-form-{{ $data1->id_pengajuan }}" 
                                            action="/admin/restitusi_karyawan/delete/{{ $data1->id_pengajuan }}" 
                                            method="POST" 
                                            class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </li>

                                </ul>
                            </div>


                        @endif
                    </td>
                </tr>

                
                <div class="modal fade" id="modalUpdate-{{ $data1->id_pengajuan }}" tabindex="-1" aria-labelledby="addKaryawanModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addKaryawanModalLabel">Update Restitusi {{ $data1->no_surat_rs }}, a.n. {{ $data1->nama_karyawan }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="/admin/restitusi_karyawan/update/{{ $data1->id_pengajuan }}" method="POST">
                                @csrf
                                @php
                                    if (auth()->user()->role === 'dr_hph' || auth()->user()->role === 'vp_osdm') {
                                        $form = "disabled"; // Pastikan selalu array meskipun input tidak valid
                                        $hidden = "hidden";
                                    }else{
                                        $hidden = "";
                                        $form = "required";
                                    }
                                @endphp
                                <div class="modal-body">
                                    <div class="row text-start">

                                        <div class="col-md-3">
                                            <label for="tanggal_pengobatan" class="form-label">Tanggal pengobatan</label>
                                            <input type="date" class="form-control" id="tanggal_pengobatan" name="tanggal_pengobatan"
                                            value="{{ date('Y-m-d') }}"  {{ $form }}>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="no_surat_rs" class="form-label">No. Surat</label>
                                            <input type="text" class="form-control" id="no_surat_rs" name="no_surat_rs" value="{{ $data1->no_surat_rs }}" {{ $form }}>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="urgensi" class="form-label">Urgensi</label>
                                            <select class="form-control" id="urgensi" name="urgensi" {{ $form }}>
                                                <option value="Low" {{ $data1->urgensi == 'Low' ? 'selected' : '' }}>Low</option>
                                                <option value="Medium" {{ $data1->urgensi == 'Medium' ? 'selected' : '' }}>Medium</option>
                                                <option value="High" {{ $data1->urgensi == 'High' ? 'selected' : '' }}>High</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="gelar_belakang" class="form-label">Nominal</label>
                                            <input type="text" id="nominal" class="form-control" name="nominal" value="{{ $data1->nominal }}" {{ $form }}>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="rumah_sakit" class="form-label">Rumah sakit</label>
                                            <input type="text" class="form-control" id="rumah_sakit" name="rumah_sakit" value="{{ $data1->rumah_sakit }}" {{ $form }}>
                                        </div>

                                        <div class="col-12">
                                            <label for="deskripsi" class="form-label">Deskripsi</label>
                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" {{ $form }}>{{ $data1->deskripsi }}</textarea>
                                        </div>
                                        
                                        <div class="col-12" >
                                            <label for="keterangan_pengajuan" class="form-label">Keterangan pengajuan</label>
                                            <textarea class="form-control" id="keterangan_pengajuan" name="keterangan_pengajuan" rows="3" {{ $form }}>{{ $data1->keterangan_pengajuan }}</textarea>
                                        </div>

                                        @if ($data1->reject_notes != null || $data1->status_pengajuan === 0)
                                        <div class="col-12" >
                                            <label for="keterangan_pengajuan" class="form-label">Alasan Penolakan</label>
                                            <textarea class="form-control" id="keterangan_pengajuan" name="keterangan_pengajuan" rows="2" disabled>{{ $data1->reject_notes }}</textarea>
                                        </div>
                                            
                                        @endif
                                        <!-- <div class="col-12">
                                            <label for="keterangan_pengajuan" class="form-label">Keterangan pengajuan</label>
                                            <textarea class="form-control" id="keterangan_pengajuan" name="keterangan_pengajuan" rows="3" required>{{ $data1->keterangan_pengajuan }}</textarea>
                                        </div> -->
                                        
                                        <div class="col-md-12" style="margin-top: 20px;">
                                            <div id="editAttachmentDropzone-{{ $data1->id_pengajuan }}" class="dropzone" data-files='@json($data1->files)' {{ $hidden }}>
                                                <div class="dz-message">Drag & Drop your files here or click to upload</div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <h6>Daftar File Sebelumnya:</h6>
                                                <ul id="attachmentList-{{ $data1->id_pengajuan }}" class="list-group">
                                                    
                                                    @php
                                                        $files = json_decode($data1->url_file ?? '[]'); // Dekode JSON dengan fallback nilai default array kosong
                                                        if (!is_array($files)) {
                                                            $files = []; // Pastikan selalu array meskipun input tidak valid
                                                        }
                                                    @endphp

                                                    @foreach ($files as $file)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <img style="max-width:300px;" src="/uploads/Restitusi_Karyawan/{{ $file }}" alt="{{ $file }}" class="custom-thumbnail">
                                                            <a href="/uploads/Restitusi_Karyawan/{{ $file }}" target="_blank">{{ $file }}</a>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeExistingFile('{{ $file }}', '{{ $data1->id_pengajuan }}')" {{ $hidden }}>Hapus</button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <h6>Attachment File Dari Dokter:</h6>
                                                <ul id="attachmentListDR-{{ $data1->id_pengajuan }}" class="list-group">
                                                    
                                                    @php
                                                        $files = json_decode($data1->url_file_dr ?? '[]'); // Dekode JSON dengan fallback nilai default array kosong
                                                        if (!is_array($files)) {
                                                            $files = []; // Pastikan selalu array meskipun input tidak valid
                                                        }
                                                    @endphp

                                                    @foreach ($files as $file)
                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                            <img style="max-width:300px;" src="/uploads/Restitusi_Karyawan/{{ $file }}" alt="{{ $file }}" class="custom-thumbnail">
                                                            <a href="/uploads/Restitusi_Karyawan/{{ $file }}" target="_blank">{{ $file }}</a>
                                                            <!-- <button type="button" class="btn btn-danger btn-sm" onclick="removeExistingFile('{{ $file }}', '{{ $data1->id_pengajuan }}')" {{ $hidden }}>Hapus</button> -->
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <p class="text-danger">Mohon klik "Simpan" jika sudah menghapus attachment.</p>
                                    </div>
                                </div>
                                <input type="hidden" name="uploaded_files" id="uploadedFilesInput-{{ $data1->id_pengajuan }}" value="[]">
                                <input type="hidden" id="removedFilesInput-{{ $data1->id_pengajuan }}" name="removed_files">
                                @if (auth()->user()->role === 'superadmin' && $data1->status_pengajuan === 1)
                                <div class="modal-footer">
                                    
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                                @endif
                            </form>
                            <div class="modal-footer">
                            @if (auth()->user()->role === 'superadmin' && $data1->status_pengajuan === 1)
                                    
                                    
                                    <form action="javascript:void(0);" method="POST" class="w-100">
                                        @csrf
                                        <input type="hidden" name="id_pengajuan" value="{{ $data1->id_pengajuan }}">
                                        <button style="margin-left: 10px;" type="button" class="btn btn-sm btn-danger w-100" onclick="openRejectModal('{{ $data1->id_pengajuan }}')">Reject</button>
                                    </form>
                                    <form action="{{ route('approval-screening', ['id' => $data1->id_pengajuan]) }}" method="POST" class="w-100">
                                        @csrf
                                        @method('PUT')
                                        <!-- <button style="margin-left: 10px;" type="button" class="btn btn-sm btn-success w-100" onclick="openRejectModal('{{ $data1->id_pengajuan }}')">Reject</button> -->
                                        <button style="margin-left: 10px;" type="submit" class="btn btn-sm btn-success  w-100">Aprove</button>
                                    </form>
            
                            @endif
                            @if (auth()->user()->role === 'dr_hph' && $data1->status_pengajuan === 2)
                                    
                                    
                                    <form action="javascript:void(0);" method="POST" class="w-100">
                                        @csrf
                                        <input type="hidden" name="id_pengajuan" value="{{ $data1->id_pengajuan }}">
                                        <button style="margin-left: 10px;" type="button" class="btn btn-sm btn-danger w-100" onclick="openRejectDRModal('{{ $data1->id_pengajuan }}')">Reject</button>
                                    </form>
                                    <form action="javascript:void(0);" method="POST" class="w-100">
                                        @csrf
                                        <input type="hidden" name="id_pengajuan" value="{{ $data1->id_pengajuan }}">
                                        <button style="margin-left: 10px;" type="submit" class="btn btn-sm btn-success w-100" onclick="openApproveDRModal('{{ $data1->id_pengajuan }}')">Aprove</button>
                                    </form>
                                    <!-- <form action="{{ route('approval-dr', ['id' => $data1->id_pengajuan]) }}" method="POST" class="w-100">
                                        @csrf
                                        @method('PUT')
                                        
                                    </form> -->
            
                            @endif
                            @if (auth()->user()->role === 'vp_osdm' && $data1->status_pengajuan === 3)
                                    
                                    
                                    <form action="javascript:void(0);" method="POST" class="w-100">
                                        @csrf
                                        <input type="hidden" name="id_pengajuan" value="{{ $data1->id_pengajuan }}">
                                        <button style="margin-left: 10px;" type="button" class="btn btn-sm btn-danger w-100" onclick="openRejectVPModal('{{ $data1->id_pengajuan }}')">Reject</button>
                                    </form>
                                    <form action="{{ route('approval-vp', ['id' => $data1->id_pengajuan]) }}" method="POST" class="w-100">
                                        @csrf
                                        @method('PUT')
                                        <!-- <button style="margin-left: 10px;" type="button" class="btn btn-sm btn-success w-100" onclick="openRejectModal('{{ $data1->id_pengajuan }}')">Reject</button> -->
                                        <button style="margin-left: 10px;" type="submit" class="btn btn-sm btn-success  w-100">Aprove</button>
                                    </form>
            
                            @endif
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data karyawan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL REJECT SCREENING -->
<div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectReasonModalLabel">Alasan Reject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_pengajuan" id="rejectIdPengajuan">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="rejectReason" name="reject_notes" rows="3" ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Submit Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL REJECT DOKTER -->
<div class="modal fade" id="rejectReasonDRModal" tabindex="-1" aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectReasonModalLabel">Alasan Reject dari Dokter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectDRForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_pengajuan" id="rejectIdPengajuan">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="rejectReason" name="reject_notes" rows="3" ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Submit Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL REJECT VP -->
<div class="modal fade" id="rejectReasonVPModal" tabindex="-1" aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectReasonModalLabel">Alasan Reject dari VP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectVPForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_pengajuan" id="rejectIdPengajuan">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejectReason" class="form-label">Alasan Penolakan</label>
                        <textarea class="form-control" id="rejectReason" name="reject_notes" rows="3" ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Submit Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- MODAL ADD ATTACHMENT DOKTER -->
<div class="modal fade" id="approveDRModal" tabindex="-1" aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ApproveModalLabel">Approval Dokter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="approveDRForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_pengajuan" id="approveDRIdPengajuan">
                <input type="hidden" name="status_pengajuan" value="4">
                <div class="modal-body">
                    <div class="col-md-12">
                        <label for="dropzone" class="form-label">Attachment Approval</label>
                        <div id="editAttachmentDropzone2" class="dropzone">
                        <div class="dz-message">Drag & Drop files here or click to upload</div>
                        </div>
                                    <!-- Tampilkan file lama -->
                        <div id="editAttachmentList2">
                                        <!-- File lama akan dimuat melalui JavaScript -->
                        </div>
                    </div>
                </div>  
                <input type="hidden" name="uploaded_files" id="uploadedFilesInput2" value="[]">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL ADD --}}
<div class="modal fade" id="modalDataBaru" tabindex="-1" aria-labelledby="modalDataBaruLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKaryawanModalLabel">Tambah Data Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="dataForm" action="restitusi_karyawan/tambah" method="POST" enctype="multipart/form-data" >
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="id-badge" class="form-label">Karyawan</label>
                            <select id="id-badge" name="id_badge" class="form-control select2">
                                @foreach($karyawan as $data)
                                    <option value="{{ $data->id_badge }}">{{ $data->nama_karyawan }}</option>
                                @endforeach
                            </select>
                        </div>
                        

                        <div class="col-md-3">
                            <label for="tanggal_pengobatan" class="form-label">Tanggal pengobatan</label>
                            <input type="date" class="form-control" id="tanggal_pengobatan" name="tanggal_pengobatan"
                            value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="no_surat_rs" class="form-label">No. Surat</label>
                            <input type="text" class="form-control" id="no_surat_rs" name="no_surat_rs" required>
                        </div>
                        <div class="col-md-3">
                            <label for="urgensi" class="form-label">Urgensi</label>
                            <select class="form-control" id="urgensi" name="urgensi">
                                <option value="" selected disabled>Pilih urgensi</option>
                                <option value="Low" selected>Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="rumah_sakit" class="form-label">Rumah sakit</label>
                            <input type="text" class="form-control" id="rumah_sakit" name="rumah_sakit" required>
                        </div>
                        <div class="col-md-3">
                            <label for="nominal" class="form-label">Nominal</label>
                            <input type="number" class="form-control" id="nominal" name="nominal" step="any" required>
                        </div>

                        <div class="col-12">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="col-12">
                            <label for="keterangan_pengajuan" class="form-label">Keterangan pengajuan</label>
                            <textarea class="form-control" id="keterangan_pengajuan" name="keterangan_pengajuan" rows="3" required></textarea>
                        </div>
                        
                        <div class="col-md-12">
                            <div id="attachmentDropzone" class="dropzone">
                                <div class="dz-message">Drag & Drop your files here or click to upload</div>
                            </div>
                        </div>
                        <!-- Daftar file sebelumnya -->
                        
                    </div>
                </div>
                <input type="hidden" name="uploaded_files" id="uploadedFilesInput" value="[]">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- MODAL IMPORT --}}
<div class="modal fade" id="modalDataExcel" tabindex="-1" aria-labelledby="modalDataExcelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDataExcelLabel">Masukan Data Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            
            <form action="{{ route('pengajuan-klaim-pengobatan.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file_excel" class="form-label">Upload File Excel</label>
                    <input type="file" class="form-control" id="file_excel" name="file_excel" accept=".xlsx, .xls">
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')

    
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script> --}}

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
        
        // Setup - add a text input to each footer cell
        $('#klaimTable thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#klaimTable thead');

        var table = $('#klaimTable').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            pageLength: 10, // Menampilkan 10 data per halaman
            lengthMenu: [
                [10, 25, 50, -1], 
                [10, 25, 50, 'Semua']
            ],
            processing: true, // Menampilkan pesan saat memproses
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>", // Layout DataTable
            initComplete: function () {
                var api = this.api();

                // For each column
                api.columns().eq(0).each(function (colIdx) {
                    // Skip action column (misalnya kolom terakhir yang berisi tombol aksi)
                    if (colIdx == 9) return;

                    // Add input field
                    var cell = $('.filters th').eq(colIdx);
                    var title = $(cell).text();
                    $(cell).html('<input type="text" class="form-control form-control-sm" placeholder="Filter ' + title + '" />');

                    // Add filter functionality
                    $('input', $('.filters th').eq(colIdx))
                        .on('keyup change', function () {
                            if (api.column(colIdx).search() !== this.value) {
                                api
                                    .column(colIdx)
                                    .search(this.value)
                                    .draw();
                            }
                        });
                });
            },
            language: {
                search: "Pencarian:",
                lengthMenu: "Menampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ Total Data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 Total Data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok",
                processing: "Sedang memproses...",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    </script>

<script>
    // Inisialisasi Dropzone Edit
    let uploadedFilesDR = []; // Menyimpan nama file yang sudah diupload ke server
    let existingFiles1DR = []; // Menyimpan nama file yang sudah ada di database

    // Konfigurasi Dropzone
    let editAttachmentDropzone2 = new Dropzone("#editAttachmentDropzone2", {
        url: "/restitusi_karyawan/upload-temp", // Endpoint sementara untuk upload file
        paramName: "file",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        maxFilesize: 5, // 5MB
        acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx",
        addRemoveLinks: true,
        dictRemoveFile: "Hapus File",

        // Event saat file berhasil di-upload
        success: function (file, response) {
            if (response && response.fileName) {
                console.log("File uploaded successfully:", response);

                // Tambahkan file ke array uploadedFilesDR
                uploadedFilesDR.push(response.fileName);
                console.log("Uploaded Files Array:", uploadedFilesDR);

                // Simpan nama file di elemen Dropzone
                file.serverFileName = response.fileName;

                // Update input hidden
                updateHiddenInput2();
            } else {
                console.error("File upload failed or invalid response:", response);
            }
        },

        // Event saat file dihapus
        removedfile: function (file) {
            console.log("Removing file:", file);

            // Periksa apakah file adalah file baru atau file lama
            if (file.serverFileName) {
                // Jika file sudah di-upload (file baru), kirim request untuk hapus file
                $.ajax({
                    url: "/restitusi_karyawan/delete-temp",
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        fileName: file.serverFileName
                    },
                    success: function () {
                        console.log("File removed from server:", file.serverFileName);

                        // Hapus file dari array uploadedFilesDR
                        uploadedFilesDR = uploadedFilesDR.filter(f => f !== file.serverFileName);
                        console.log("Updated Uploaded Files Array:", uploadedFilesDR);

                        // Update input hidden
                        updateHiddenInput2();
                    },
                    error: function () {
                        console.error("Failed to remove file from server:", file.serverFileName);
                    }
                });
            } else if (file.name) {
                // Jika file adalah file lama
                console.log("Mark file for removal:", file.name);
                existingFiles1DR = existingFiles1DR.filter(f => f !== file.name);

                // Update input hidden untuk file lama yang dihapus
                $('#removedFilesInput').val(JSON.stringify(existingFiles1DR));
                console.log("Updated Removed Files Input:", existingFiles1DR);
            }

            // Hapus file dari tampilan Dropzone
            file.previewElement.remove();
        },

        init: function () {
            // Ambil data-file dari elemen Dropzone
            let existingFilesFromServer = $('#editAttachmentDropzone2').data('files') || '[]';

            // Pastikan data adalah string JSON valid
            try {
                existingFiles1DR = JSON.parse(existingFilesFromServer);
            } catch (error) {
                console.error("Invalid JSON in data-files:", error);
                existingFiles1DR = []; // Default ke array kosong jika error
            }

            // Tampilkan file lama di Dropzone
            if (Array.isArray(existingFiles1DR)) {
                existingFiles1DR.forEach(file => {
                    let mockFile = { name: file, size: 12345, serverFileName: file };
                    this.emit("addedfile", mockFile);
                    this.emit("thumbnail", mockFile, `/uploads/Restitusi_Karyawan/${file}`);
                    this.emit("complete", mockFile);
                });
            } else {
                console.warn("No valid files to display.");
            }
        }
    });

    // Fungsi untuk memperbarui input hidden
    function updateHiddenInput2() {
        $('#uploadedFilesInput2').val(JSON.stringify(uploadedFilesDR));
        console.log("Updated Uploaded Files Input:", $('#uploadedFilesInput2').val());
    }

    $('#approveDRForm').on('submit', function () {
    updateHiddenInput2(); // Memastikan nilai input hidden terupdate sebelum submit
    console.log("Form submitted with uploaded_files:", $('#uploadedFilesInput2').val());
});

console.log("Current Uploaded Files (uploadedFilesDR):", uploadedFilesDR);
console.log("Hidden Input Value (#uploadedFilesInput2):", $('#uploadedFilesInput2').val());
</script>

    <script>
        new Cleave('#nominal', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            prefix: 'Rp ',
            rawValueTrimPrefix: true
        });
    </script>

    <script>
        function toggle(source) {
            checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }
    </script>
    <script>
        function openRejectModal(idPengajuan) {
            // Set nilai ID pengajuan di input hidden modal
            document.getElementById('rejectIdPengajuan').value = idPengajuan;

            // Set action form sesuai dengan ID pengajuan
            const rejectForm = document.getElementById('rejectForm');
            rejectForm.action = `/restitusi-karyawan/reject-screening/${idPengajuan}`;

            // Tampilkan modal
            const rejectModal = new bootstrap.Modal(document.getElementById('rejectReasonModal'));
            rejectModal.show();
        }
        function openRejectDRModal(idPengajuan) {
            // Set nilai ID pengajuan di input hidden modal
            document.getElementById('rejectIdPengajuan').value = idPengajuan;

            // Set action form sesuai dengan ID pengajuan
            const rejectDRForm = document.getElementById('rejectDRForm');
            rejectDRForm.action = `/restitusi-karyawan/reject-dr/${idPengajuan}`;

            // Tampilkan modal
            const rejectModal = new bootstrap.Modal(document.getElementById('rejectReasonDRModal'));
            rejectModal.show();
        }
        function openApproveDRModal(idPengajuan) {
            // Set nilai ID pengajuan di input hidden modal
            document.getElementById('approveDRIdPengajuan').value = idPengajuan;

            // Set action form sesuai dengan ID pengajuan
            const approvalDRForm = document.getElementById('approveDRForm');
            approvalDRForm.action = `/restitusi-karyawan/approval-dr/${idPengajuan}`;

            // Tampilkan modal
            const rejectModal = new bootstrap.Modal(document.getElementById('approveDRModal'));
            rejectModal.show();
        }
        function openRejectVPModal(idPengajuan) {
            // Set nilai ID pengajuan di input hidden modal
            document.getElementById('rejectIdPengajuan').value = idPengajuan;

            // Set action form sesuai dengan ID pengajuan
            const rejectVPForm = document.getElementById('rejectVPForm');
            rejectVPForm.action = `/restitusi-karyawan/reject-vp/${idPengajuan}`;

            // Tampilkan modal
            const rejectModal = new bootstrap.Modal(document.getElementById('rejectReasonVPModal'));
            rejectModal.show();
        }


    </script>
    <script>
        $(document).ready(function () {
            // Inisialisasi DataTables
            // var table = $('#tableAdmin').DataTable({
            //     paging: true,
            //     searching: true,
            //     ordering: true,
            //     info: true,
            // });

            // Event untuk Select All Checkbox
            $('#selectAll').on('click', function () {
                var rows = table.rows({ search: 'applied' }).nodes();
                $('input[type="checkbox"].rowCheckbox', rows).prop('checked', this.checked);
            });

            // Event untuk mengontrol Select All Checkbox jika checkbox baris diubah
            // $('#tableAdmin tbody').on('change', 'input[type="checkbox"].rowCheckbox', function () {
            //     if (!this.checked) {
            //         var el = $('#selectAll').get(0);
            //         if (el && el.checked && ('indeterminate' in el)) {
            //             el.indeterminate = true;
            //         }
            //     }
            // });

            // Ambil Data Checkbox yang Dipilih
            $('#getSelected').on('click', function () {
                var selected = [];
                $('input[type="checkbox"].rowCheckbox:checked').each(function () {
                    selected.push($(this).closest('tr').find('td:nth-child(2)').text()); // Ambil ID Badge
                });

                alert('Selected ID Badges: ' + selected.join(', '));
            });
        });
    </script>

    <script>

        
        $(document).ready(function () {
        // Event listener untuk klik baris tabel
        // $('#tableAdmin tbody').on('click', 'tr', function (event) {
        //     // Cegah klik pada tombol Edit atau Hapus agar tidak memicu modal detail
        //     if ($(event.target).closest('.editBtn, .deleteBtn').length) return;

        //     const row = $(this); // Baris yang diklik
        //     const rowData = row.data(); // Ambil semua atribut data-* dari baris

        //     console.log("Debug Row Data:", rowData); // Debug data row di console

        //     // Set data ke dalam modal
        //     $('#detailIDBadge').text(rowData.id_badge || '-');
        //     $('#detailNamaKaryawan').text(rowData.nama_karyawan || '-');
        //     $('#detailUnitKerja').text(rowData.unit_kerja || '-');
        //     $('#detailNamaAsuransi').text(rowData.asuransi || '-');
        //     $('#detailRumahSakit').text(rowData.rumah_sakit || '-');
        //     $('#detailTanggalPengajuan').text(rowData.tanggal_pengajuan || '-');
        //     $('#detailNominal').text(rowData.nominal || '-');
        //     $('#detailDeskripsi').text(rowData.deskripsi || '-');

        //     // Parsing dan menampilkan file_url jika ada
        //     let attachmentHtml = 'Tidak ada file';

        //     if (rowData.file_url) {
        //         console.log("Raw file_url:", rowData.file_url); // Debugging
        //         let files = [];

        //         // Jika file_url sudah berupa array, langsung gunakan
        //         if (Array.isArray(rowData.file_url)) {
        //             files = rowData.file_url;
        //         } else {
        //             // Jika file_url berupa string, parse terlebih dahulu
        //             try {
        //                 files = JSON.parse(rowData.file_url);
        //             } catch (error) {
        //                 console.error("Error Parsing JSON:", error.message);
        //             }
        //         }

        //         // Tampilkan file sebagai gambar atau link
        //         if (files.length > 0) {
        //             attachmentHtml = files.map(file => {
        //                 const fileExtension = file.split('.').pop().toLowerCase();
        //                 const filePath = `/uploads/PengajuanKlaim/klaim_Pengobatan/${file}`;

        //                 // Periksa ekstensi file
        //                 if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
        //                     // Jika file adalah gambar, tampilkan sebagai img tag
        //                     return `<div><img src="${filePath}" alt="${file}" style="max-width: 150px; height: auto; margin: 5px;"></div>`;
        //                 } else {
        //                     // Jika file bukan gambar, tampilkan sebagai link
        //                     return `<div><a href="${filePath}" target="_blank">${file}</a></div>`;
        //                 }
        //             }).join('');
        //         }
        //     }

        //     // Tampilkan hasil pada modal
        //     $('#detailAttachment').html(attachmentHtml);

        //     // Tampilkan modal
        //     $('#modalDetail').modal('show');
        // });
    });

    </script>


    <script>
    $(document).ready(function () {
            // Fungsi untuk memformat angka menjadi format Rupiah
            function formatRupiah(number) {
                return 'Rp.' + Number(number).toLocaleString('id-ID');
            }

            // Inisialisasi DataTables
            // var table = $('#tableAdmin').DataTable();

            // Event delegation untuk tombol Edit
            // $('#tableAdmin').on('click', '.editBtn', function (event) {
            //     event.stopPropagation(); // Hentikan event klik dari propagasi ke elemen parent (baris)

            //     // Ambil data dari atribut tombol
            //     const id = $(this).data('id');
            //     const id_badge = $(this).data('id_badge') || '-';
            //     const nama_karyawan = $(this).data('nama_karyawan') || '-';
            //     const unit_kerja = $(this).data('unit_kerja') || '-';
            //     const nama_asuransi = $(this).data('asuransi') || '-';
            //     const rumah_sakit = $(this).data('rumah_sakit') || '-';
            //     const tanggal_pengajuan = $(this).data('tanggal_pengajuan') || '-';
            //     const nominal = $(this).data('nominal') || '0'; // Pastikan defaultnya angka '0' jika kosong
            //     const deskripsi = $(this).data('deskripsi') || '-';

            //     // Pastikan nominal adalah string, kemudian format ke Rupiah
            //     const formattedNominal = formatRupiah(String(nominal).replace('Rp.', '').replace(/,/g, ''));

            //     $('#editIdBadge').val(id_badge || '-');
            //     $('#editNamaKaryawan').val(nama_karyawan || '-');
            //     $('#editUnitKerja').val(unit_kerja || '-');
            //     $('#editNamaAsuransi').val(nama_asuransi || '-');
            //     $('#editRumahSakit').val(rumah_sakit || '-');
            //     $('#editTanggalPengajuan').val(tanggal_pengajuan || '-');
            //     $('#editNominal').val(formattedNominal || '-');
            //     $('#editDeskripsi').val(deskripsi || '-');
            //     // Set action form update
            //     $('#editForm').attr('action', '/admin/klaim_pengobatan/update/' + id);

            //     // Tampilkan modal edit
            //     $('#modalEditData').modal('show');
            // });
        });


    $(document).ready(function () {
        // Event delegation untuk tombol Hapus
        // $('#tableAdmin').on('click', '.deleteBtn', function () {
        //     const id = $(this).data('id'); // Ambil ID data

        //     // Tampilkan konfirmasi dengan SweetAlert2
        //     Swal.fire({
        //         title: 'Apakah Anda yakin?',
        //         text: 'Data akan dihapus dan tidak dapat dikembalikan!',
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#d33',
        //         cancelButtonColor: '#3085d6',
        //         confirmButtonText: 'Ya, hapus!',
        //         cancelButtonText: 'Batal'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             // Jika dikonfirmasi, kirim permintaan hapus ke server
        //             $.ajax({
        //             url: '/admin/klaim_pengobatan/delete/' + id,
        //             type: 'GET', // Ubah dari DELETE ke GET
        //             success: function (response) {
        //                 Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success');
        //                 location.reload(); // Reload halaman untuk memperbarui data
        //             },
        //             error: function () {
        //                 Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
        //             }
        //         });
        //         }
        //     });
        // });
    });




        $(document).ready(function () {
            // Pilih semua checkbox
            $('#selectAll').on('click', function () {
                $('.rowCheckbox').prop('checked', this.checked);
            });

            // Perbarui checkbox "Pilih Semua" jika ada perubahan pada baris
            // $('#tableAdmin').on('change', '.rowCheckbox', function () {
            //     if (!this.checked) {
            //         $('#selectAll').prop('checked', false);
            //     } else if ($('.rowCheckbox:checked').length === $('.rowCheckbox').length) {
            //         $('#selectAll').prop('checked', true);
            //     }
            // });

            // Hapus data terpilih
            $('#deleteSelected').on('click', function () {
                const selectedIds = $('.rowCheckbox:checked').map(function () {
                    return $(this).val();
                }).get(); // Ambil ID yang dipilih sebagai array

                if (selectedIds.length === 0) {
                    Swal.fire('Pilih Data!', 'Anda belum memilih data untuk dihapus.', 'warning');
                    return;
                }

                // Konfirmasi sebelum menghapus
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data yang dipilih akan dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kirim permintaan hapus melalui AJAX
                        $.ajax({
                            url: '/admin/klaim_pengobatan/delete-multiple', // Endpoint untuk hapus data
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                ids: selectedIds
                            },
                            success: function (response) {
                                Swal.fire('Berhasil!', 'Data berhasil dihapus.', 'success');
                                location.reload(); // Reload halaman untuk memperbarui tabel
                            },
                            error: function () {
                                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                            }
                        });
                    }
                });
            });
        });

        Dropzone.autoDiscover = false;

    // let uploadedFiles = []; // Array untuk menyimpan nama file yang diunggah
        let removedFiles = []; // Array untuk menyimpan file yang dihapus

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        if (document.querySelector('#attachmentDropzone')) {
            const attachmentDropzone = new Dropzone("#attachmentDropzone", {
                url: "/restitusi_karyawan/upload-temp", // Endpoint sementara untuk upload
                paramName: "file",
                headers: {
                    'X-CSRF-TOKEN': token
                },
                maxFiles: 5,
                maxFilesize: 5, // 5MB
                acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx",
                addRemoveLinks: true,
                dictRemoveFile: "Hapus File",
                dictDefaultMessage: "Drag & Drop your files here or click to upload",

                init: function () {
                    // Saat file berhasil diunggah
                    this.on("success", function (file, response) {
                        console.log("File upload response:", response);

                        if (response && response.fileName) {
                            file.uploadedFileName = response.fileName; // Simpan nama file di objek Dropzone file
                            uploadedFiles.push(response.fileName); // Tambahkan nama file ke array uploadedFiles

                            console.log("Uploaded file added:", response.fileName);
                            console.log("Uploaded Files Array:", uploadedFiles);

                            // Perbarui input hidden dengan file yang diunggah
                            document.getElementById("uploadedFilesInput").value = JSON.stringify(uploadedFiles);
                        } else {
                            console.error("Error: No fileName in response:", response);
                        }
                    });

                    // Saat file dihapus dari Dropzone
                    this.on("removedfile", function (file) {
                        console.log("File removed:", file);

                        // Pastikan uploadedFileName tersedia
                        if (file.uploadedFileName) {
                            console.log("Removing file from server:", file.uploadedFileName);

                            // Tambahkan file ke array removedFiles
                            removedFiles.push(file.uploadedFileName);

                            // Hapus file dari array uploadedFiles
                            uploadedFiles = uploadedFiles.filter(f => f !== file.uploadedFileName);

                            console.log("Updated Uploaded Files:", uploadedFiles);
                            console.log("Removed Files Array:", removedFiles);

                            // Perbarui input hidden untuk uploaded_files dan removed_files
                            document.getElementById("uploadedFilesInput").value = JSON.stringify(uploadedFiles);
                            document.getElementById("removedFilesInput").value = JSON.stringify(removedFiles);

                            // Kirim AJAX request untuk menghapus file di server
                            $.ajax({
                                url: "/restitusi_karyawan/delete-temp",
                                type: "POST",
                                data: {
                                    _token: token,
                                    fileName: file.uploadedFileName
                                },
                                success: function (response) {
                                    console.log("File successfully removed from server:", response);
                                },
                                error: function (error) {
                                    console.error("Failed to delete file on server:", error);
                                }
                            });
                        } else {
                            console.warn("File not uploaded to server, skipping removal.");
                        }
                    });
                }
            });
        }

        let oldFiles = []; // Array untuk menyimpan file lama


        // Inisialisasi Dropzone Edit



        let uploadedFiles = []; // Menyimpan nama file yang sudah diupload ke server
        let existingFiles1 = []; // Menyimpan nama file yang sudah ada di database
        // const dropzoneId = "";



        // Fungsi untuk memperbarui input hidden
        // function updateHiddenInput() {
        //     $('#uploadedFilesInput').val(JSON.stringify(uploadedFiles));
        //     console.log("Updated Uploaded Files Input:", $('#uploadedFilesInput').val());
        // }


        // document.querySelectorAll('.modal').forEach(modal => {
        //     modal.addEventListener('shown.bs.modal', function () {
        //         const modalId = modal.getAttribute('id');
        //         if (!modalId || !modalId.includes('-')) {
        //             console.error("Invalid modal ID:", modalId);
        //             return;
        //         }

        //         const parsedModalId = modalId.split('-')[1];
        //         const dropzoneId = `#editAttachmentDropzone-${parsedModalId}`;
        //         const dropzoneElement = document.querySelector(dropzoneId);

        //         if (!dropzoneElement) {
        //             console.error("Dropzone element not found:", dropzoneId);
        //             return;
        //         }

        //         if (!Dropzone.instances.find(dz => dz.element.id === dropzoneElement.id)) {
        //             console.log(`Initializing Dropzone for: ${dropzoneId}`);

        //             new Dropzone(dropzoneId, {
        //                 url: "/restitusi_karyawan/upload-temp",
        //                 paramName: "file",
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 maxFilesize: 5,
        //                 acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx",
        //                 addRemoveLinks: true,
        //                 dictRemoveFile: "Hapus File",
        //                 success: function (file, response) {
        //                         if (response && response.fileName) {
        //                             console.log("File uploaded successfully:", response);

        //                             // Hanya tambahkan file ke array jika berhasil diupload

        //                             uploadedFiles.push(response.fileName);
        //                             console.log("Uploaded Files Array:", uploadedFiles);

        //                             // Simpan nama file di elemen Dropzone
        //                             file.serverFileName = response.fileName;

        //                             // Update input hidden
        //                             updateHiddenInput();
        //                         } else {
        //                             console.error("File upload failed or invalid response:", response);
        //                         }
        //                     },
        //                     removedfile: function (file) {
        //                             console.log("Removing file:", file);

        //                             // Periksa apakah file adalah file baru atau file lama
        //                             if (file.serverFileName) {
        //                                 // Jika file sudah di-upload (file baru), kirim request untuk hapus file
        //                                 $.ajax({
        //                                     url: "/restitusi_karyawan/delete-temp",
        //                                     method: "POST",
        //                                     data: {
        //                                         _token: $('meta[name="csrf-token"]').attr('content'),
        //                                         fileName: file.serverFileName
        //                                     },
        //                                     success: function () {
        //                                         console.log("File removed from server:", file.serverFileName);

        //                                         // Hapus file dari array uploadedFiles
        //                                         uploadedFiles = uploadedFiles.filter(f => f !== file.serverFileName);
        //                                         console.log("Updated Uploaded Files Array:", uploadedFiles);

        //                                         // Update input hidden
        //                                         updateHiddenInput();
        //                                     },
        //                                     error: function () {
        //                                         console.error("Failed to remove file from server:", file.serverFileName);
        //                                     }
        //                                 });
        //                             } else if (file.name) {
        //                                 // Jika file adalah file lama
        //                                 console.log("Mark file for removal:", file.name);
        //                                 existingFiles1 = existingFiles1.filter(f => f !== file.name);

        //                                 // Update input hidden untuk file lama yang dihapus
        //                                 $('#removedFilesInput').val(JSON.stringify(existingFiles1));
        //                                 console.log("Updated Removed Files Input:", existingFiles1);
        //                             }

        //                             // Hapus file dari tampilan Dropzone
        //                             file.previewElement.remove();
        //                         },
        //                         init: function () {
        //                     // Ambil data-file lama berdasarkan modal
        //                             let existingFilesFromServer = $(`#editAttachmentDropzone-${modalId}`).data('files') || '[]';
        //                             try {
        //                                 let existingFiles = JSON.parse(existingFilesFromServer);
        //                                 if (Array.isArray(existingFiles)) {
        //                                     existingFiles.forEach(file => {
        //                                         let mockFile = { name: file, size: 12345, serverFileName: file };
        //                                         this.emit("addedfile", mockFile);
        //                                         this.emit("thumbnail", mockFile, `/uploads/PengajuanKlaim/klaim_Pengobatan/${file}`);
        //                                         this.emit("complete", mockFile);
        //                                     });
        //                                 }
        //                             } catch (error) {
        //                                 console.error("Invalid JSON in data-files:", error);
        //                             }
        //                         }
        //                     });
        //         }
        //     });
        // });

function updateHiddenInput(modalId) {
    const uploadedFiles = window[`uploadedFiles_${modalId}`] || [];
    const removedFiles = window[`removedFiles_${modalId}`] || [];
    document.getElementById(`uploadedFilesInput-${modalId}`).value = JSON.stringify(uploadedFiles);
    document.getElementById(`removedFilesInput-${modalId}`).value = JSON.stringify(removedFiles);
    console.log(`Updated hidden inputs for modal ${modalId}:`, {
        uploadedFiles,
        removedFiles
    });
}

document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('shown.bs.modal', function () {
        const modalId = modal.getAttribute('id').split('-')[1];
        const dropzoneId = `editAttachmentDropzone-${modalId}`;
        const dropzoneElement = document.querySelector(`#${dropzoneId}`);

        // Inisialisasi array dinamis untuk modal ini
        window[`uploadedFiles_${modalId}`] = window[`uploadedFiles_${modalId}`] || [];
        window[`removedFiles_${modalId}`] = window[`removedFiles_${modalId}`] || [];

        if (!dropzoneElement) {
            console.error(`Dropzone element not found: ${dropzoneId}`);
            return;
        }

        if (!Dropzone.instances.some(dz => dz.element.id === dropzoneId)) {
            console.log(`Initializing Dropzone for: ${dropzoneId}`);

            new Dropzone(`#${dropzoneId}`, {
                url: "/restitusi_karyawan/upload-temp",
                paramName: "file",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                maxFilesize: 5,
                acceptedFiles: "image/*,.pdf,.doc,.docx,.xls,.xlsx",
                addRemoveLinks: true,
                dictRemoveFile: "Hapus File",

                success: function (file, response) {
                    if (response && response.fileName) {
                        console.log(`File uploaded successfully in modal ${modalId}:`, response.fileName);
                        window[`uploadedFiles_${modalId}`].push(response.fileName);
                        updateHiddenInput(modalId);
                    } else {
                        console.error(`File upload failed for modal ${modalId}`);
                    }
                },

                removedfile: function (file) {
                    if (file.serverFileName) {
                        $.ajax({
                            url: "/restitusi_karyawan/delete-temp",
                            method: "POST",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                fileName: file.serverFileName
                            },
                            success: function () {
                                console.log(`File removed from server for modal ${modalId}:`, file.serverFileName);
                                window[`uploadedFiles_${modalId}`] = window[`uploadedFiles_${modalId}`].filter(f => f !== file.serverFileName);
                                updateHiddenInput(modalId);
                            },
                            error: function () {
                                console.error(`Failed to remove file from server for modal ${modalId}:`, file.serverFileName);
                            }
                        });
                    } else if (file.name) {
                        console.log(`Marking file for removal in modal ${modalId}:`, file.name);
                        window[`removedFiles_${modalId}`].push(file.name);
                        updateHiddenInput(modalId);
                    }
                    file.previewElement.remove();
                },

                init: function () {
                    console.log(`Initializing existing files for modal ${modalId}`);
                    const existingFilesFromServer = dropzoneElement.getAttribute('data-files') || '[]';
                    let existingFiles = [];
                    try {
                        existingFiles = JSON.parse(existingFilesFromServer);
                    } catch (error) {
                        console.error(`Invalid JSON in data-files for modal ${modalId}:`, error);
                    }

                    existingFiles.forEach(file => {
                        const mockFile = { name: file, size: 12345, serverFileName: file };
                        this.emit("addedfile", mockFile);
                        this.emit("thumbnail", mockFile, `/uploads/Restitusi_Karyawan/${file}`);
                        this.emit("complete", mockFile);
                    });
                }
            });
        }
    });
});


        console.log(`Initializing Dropzone for: ${dropzoneId}`);
        console.log(document.querySelector(dropzoneId));



        // Event untuk menampilkan file lama pada modal edit
        // $('#tableAdmin').on('click', '.editBtn', function () {
        //     const fileUrl = $(this).data('file_url'); // Ambil file lama dari data attribute

        //     let files = [];

        //     if (Array.isArray(fileUrl)) {
        //         files = fileUrl;
        //     } else if (typeof fileUrl === 'string') {
        //         try {
        //             files = JSON.parse(fileUrl.trim());
        //         } catch (error) {
        //             console.error("Error parsing file_url:", error.message);
        //         }
        //     }

        //     console.log("Processed files:", files); // Debugging

        //     $('#editAttachmentList').empty(); // Kosongkan list sebelumnya
        //     oldFiles = []; // Reset array file lama

        //     if (fileUrl) {
        //         try {
        //             let files = [];

        //         // Validasi fileUrl
        //         if (Array.isArray(fileUrl)) {
        //             files = fileUrl; // Jika sudah array, langsung gunakan
        //         } else if (typeof fileUrl === 'string') {
        //             try {
        //                 files = JSON.parse(fileUrl.trim()); // Parsing jika string JSON
        //             } catch (error) {
        //                 console.error("Error parsing file_url:", error.message);
        //                 files = []; // Default ke array kosong jika gagal
        //             }
        //         } else {
        //             console.warn("Unexpected fileUrl format:", fileUrl);
        //         }

        //             files.forEach((file, index) => {
        //                 const filePath = `/uploads/PengajuanKlaim/klaim_Pengobatan/${file}`;
        //                 const fileExtension = file.split('.').pop().toLowerCase();

        //                 // Tampilkan file lama sebagai gambar atau link
        //                 let fileHtml = '';
        //                 if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
        //                     fileHtml = `
        //                         <div class="uploaded-file" id="file-${index}">
        //                             <img src="${filePath}" alt="${file}">
        //                             <p>${file}</p>
        //                             <button type="button" class="btn btn-danger btn-sm remove-old-file" 
        //                                 data-file="${file}" data-index="${index}">Hapus</button>
        //                         </div>`;
        //                 } else {
        //                     fileHtml = `
        //                         <div id="file-${index}">
        //                             <a href="${filePath}" target="_blank">${file}</a>
        //                             <button type="button" class="btn btn-danger btn-sm remove-old-file" 
        //                                 data-file="${file}" data-index="${index}">Hapus</button>
        //                         </div>`;
        //                 }

        //                 $('#editAttachmentList').append(fileHtml);
        //                 oldFiles.push(file); // Simpan file lama di array
        //             });
        //         } catch (error) {
        //             console.error("Error parsing file_url:", error);
        //         }
        //     }

        // });


        function removeExistingFile(fileName, pengajuanId) {
            if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
                $.ajax({
                    url: '/restitusi_karyawan/delete-temp',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        fileName: fileName,
                        pengajuanId: pengajuanId
                    },
                    success: function (response) {
                        console.log("File berhasil dihapus:", response);
                        
                        // Tambahkan file yang dihapus ke array remove_files
                        let removedFilesInput = $(`#removedFilesInput-${pengajuanId}`);
                        let removedFiles = removedFilesInput.val() ? JSON.parse(removedFilesInput.val()) : [];
                        removedFiles.push(fileName);
                        removedFilesInput.val(JSON.stringify(removedFiles)); // Simpan kembali ke input hidden
                        
                        // Hapus elemen dari daftar
                        $(`#attachmentList-${pengajuanId} li:contains('${fileName}')`).remove();
                    },
                    error: function (error) {
                        console.error("Gagal menghapus file:", error);
                    }
                });
            }
        }
        
    </script>
@endpush