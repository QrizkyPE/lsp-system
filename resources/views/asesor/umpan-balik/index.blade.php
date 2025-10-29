@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h3 class="card-title">
						<i class="fas fa-comments me-2"></i>
						Umpan Balik dan Catatan Asesmen (Mahasiswa)
					</h3>
				</div>
				<div class="card-body">
					@if($umpanBalik->count() > 0)
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<thead class="table-dark">
									<tr>
										<th width="5%" class="text-center">No</th>
										<th>Skema Sertifikasi</th>
										<th width="15%">TUK</th>
										<th width="18%">Nama Asesi</th>
										<th width="20%">Tanggal Asesmen</th>
										<th width="12%" class="text-center">Aksi</th>
									</tr>
								</thead>
								<tbody>
									@foreach($umpanBalik as $index => $item)
										<tr>
											<td class="text-center">{{ $umpanBalik->firstItem() + $index }}</td>
											<td>{{ $item->judul }}</td>
											<td>
												@switch($item->tuk)
													@case('sewaktu')
														<span class="badge bg-info">Sewaktu</span>
														@break
													@case('tempat_kerja')
														<span class="badge bg-warning">Tempat Kerja</span>
														@break
													@case('mandiri')
														<span class="badge bg-success">Mandiri</span>
														@break
												@endswitch
											</td>
											<td>{{ $item->nama_asesi }}</td>
											<td>{{ $item->tanggal_mulai->format('d/m/Y') }} - {{ $item->tanggal_selesai->format('d/m/Y') }}</td>
											<td class="text-center">
												<a href="{{ route('asesor.umpan-balik.show', $item->id) }}" class="btn btn-info btn-sm" title="Detail">
													<i class="fas fa-eye"></i>
												</a>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<div class="d-flex justify-content-center">{{ $umpanBalik->links() }}</div>
					@else
						<div class="text-center py-5">
							<i class="fas fa-comments fa-3x text-muted mb-3"></i>
							<h5 class="text-muted">Belum ada umpan balik dari mahasiswa</h5>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
